<?php
/**
 * Created by PhpStorm.
 * User: root
 * Date: 8/18/18
 * Time: 12:51 PM
 */

namespace Bolzen\Core\AccessControl;

use Bolzen\Core\Config\ConfigInterface;
use Bolzen\Core\Database\DatabaseInterface;
use Bolzen\Core\Session\SessionInterface;
use Bolzen\Core\User\UserInterface;
use Symfony\Component\HttpFoundation\Request;

class AccessControl implements AccessControlInterface
{
    private $user;
    private $session;
    private $database;
    private $user_roles_table;
    private $roles_table;
    private $maxOpensslRandomPseudoByteLength;
    private $config;

    /**
     * AccessControl constructor.
     * @param UserInterface $user
     * @param SessionInterface $session
     * @param DatabaseInterface $database
     * @param ConfigInterface $config
     */
    public function __construct(
        UserInterface $user,
        SessionInterface $session,
        DatabaseInterface $database,
        ConfigInterface $config
    ) {
        $this->user = $user;
        $this->session = $session->getSession();
        $this->database = $database;
        $this->maxOpensslRandomPseudoByteLength = 10;
        $this->roles_table = "roles";
        $this->user_roles_table = "userRoles";
        $this->config = $config;
    }

    /**
     * This method authenicate a user
     * @param string $username - the username of the user to authenicate
     * @param string $password - the user's passwword
     * @return bool - true if the user was successful authenticate. False otherwise
     */
    public function authenticate(string $username, string $password): bool
    {
        $where = "username = ?";
        $bindings = array($username);

        $retrieveInfo = $this->user->getUsersByFilter($where, $bindings);

        //user doesn't exist
        if (empty($retrieveInfo)) {
            return false;
        }

        $hash = $retrieveInfo['password'];

        if (password_verify($password, $hash)) {
            $request = Request::createFromGlobals();
            $ip = $request->getClientIp();
            $agent = $request->headers->get('User-Agent');
            $role = $this->user->getRoles($username);
            $this->createAccessControlSession($username, $ip, $agent, $role);

            return true;
        }

        return false;
    }


    /**
     * Create a authorized session for this user
     * @param string $username - the username
     * @param string $ip - the ip of the user
     * @param string $agent - the browser user-agent
     * @param array $role - list of roles for this user
     */
    public function createAccessControlSession(
        string $username,
        string $ip,
        string $agent,
        array $role
    ):void {
        $this->session->set('username', $username);
        $this->session->set('ip', $ip);
        $this->session->set('agent', $agent);
        $this->session->set('role', $role);
        $this->session->set('token', $this->newToken());
    }

    /**
     * Return a newly create token using openssl_random_pseudo_bytes
     * @return string - secured random token
     */
    public function newToken(): string
    {
        return bin2hex(openssl_random_pseudo_bytes($this->maxOpensslRandomPseudoByteLength));
    }

    /**
     * Return a boolean expression on whether a csrf token is valid
     * @param string $token - the token to check if is valid
     * @return bool - true if valid. False otherwise
     */
    public function isValidCSRFToken(string $token): bool
    {
        return $this->getCSRFToken() === $token;
    }

    /**
     * return the current CSRF Token
     * @return string - the current CSRF token
     */
    public function getCSRFToken(): string
    {
        return $this->session->get('token', $this->newToken());
    }

    /**
     * Redirect if the user is not logged in
     * @param string $path - the path to redirect the user to
     */
    public function redirectToLoginPageIfNotLogged(string $path): void
    {
        if ($this->user->isAnonymous()) {
            $this->redirect($path);
        }
    }

    /**
     * Redirect the user to desired path
     * @param $path - the path to redirect the user to
     */
    public function redirect($path): void
    {
        //stripping the / from the front
        if (substr_count($path, "/") > 1) {
            $path = ltrim($path, "/");
        }
        if (substr_count($path, "/")<=0) {
            $path = "/".$path;
        }

        $location = $this->config->getBaseUrl().$path;
        header("Location:".$location);
        exit;
    }

    /**
     * Check if the user has a certain role
     * @param string $role - the role to check for
     * @param string $username - the username to check against. If leave empty,
     *                it assumed that the username is that of the logged user
     * @return bool - true if the user has the role. False otherwise
     */
    public function hasRole(string $role, string $username = ""): bool
    {
        //request to use the current logged user?
        if (empty($username)) {
            $username = $this->user->getUsername();
        }


        $roles = $this->user->getRoles($username);

        foreach ($roles as $currentRole) {
            $currentRole = $currentRole['role'];


            if (strcasecmp($role, $currentRole)==0) {
                return true;
            }
        }

        return false;
    }

    /**
     * Assign a role to a user
     * @param string $username - the username to assign the role
     * @param string $role - the role to assign the user
     * @return bool
     */
    public function assignRole(string $username, string $role):bool
    {
        if (empty($username) || empty($role)) {
            return false;
        }

        //no duplication allow
        if ($this->hasRole($role, $username)) {
            return false;
        }

        //attempt to getch the role id
        $id = $this->getRoleID($role);

        if (empty($id)) {
            return false;
        }

        $id = $id['roleID'];

        //now we can assign the user the desired role with the id
        $columns = "username,roleID";
        $values = "?,?";
        $bindings = array($username,$id);
        return $this->database->insert($this->user_roles_table, $columns, $values, $bindings);
    }

    /**
     * Perform a series of check to see whether a session is valid
     * @param string $token - the csrf token
     * @return bool - true if valid. False otherwise
     */
    public function isValidSession(string $token): bool
    {
        if (!$this->isValidCSRFToken($token)) {
            return false;
        }

        $request = Request::createFromGlobals();

        $username = $this->user->getUsername();
        $roles = $this->user->getRoles();
        $agent = $request->headers->get('User-Agent', '');
        $clientIpAddress = $request->getClientIp();

        //username doesnt match
        if ($this->user->isAnonymous() || ($this->session->get('username')!==$username)) {
            return false;
        }

        //user agent doesnt match
        if ($this->session->get('agent')!==$agent) {
            return false;
        }

        //roles doesnt match
        if ($this->session->get('roles')!==$roles) {
            return false;
        }

        //client ip doesnt match
        if ($this->session->get('ip') !==$clientIpAddress) {
            return false;
        }

        //otherwise we are good
        return true;
    }

    /**
     * Redirect the user to a desired path if the session proven to be invalid
     * @param string $path
     * @param string $token
     */
    public function redirectIfInvalidSession(string $path, string $token): void
    {
        if (!$this->isValidSession($token)) {
            $this->redirect($path);
        }
    }

    /**
     * This function takes a role and attempt to return the role id
     * @param string $role - the role whose id to fetch
     * @return array - array with the role id if it exist otherwise an empty array
     */
    public function getRoleID(string $role): array
    {
        $where = "name = ?";
        $bindings = array($role);
        $columns = "roleID";
        $roles = $this->database->select($this->roles_table, $where, $bindings, $columns);

        if (!$role) {
            return array();
        }

        return $roles->fetch();
    }

    /**
     * Strip the user of a specific role
     * @param string $username - the username to remove the role from
     * @param string $role - the role to remove
     * @return bool - true if the role was removed. False otherwise
     */
    public function stripRole(string $username, string $role): bool
    {
        //get the role if
        $roleID = $this->getRoleID($role);

        if (empty($roleID)) {
            return false;
        }

        $roleID = $roleID['roleID'];

        $where = "username = ? AND roleID = ?";
        $bindings = array($username, $roleID);
        return $this->database->delete($this->user_roles_table, $where, $bindings);
    }
}
