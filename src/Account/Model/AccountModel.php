<?php
/**
 * Created by PhpStorm.
 * User: root
 * Date: 10/19/18
 * Time: 12:00 PM
 */

namespace Bolzen\Src\Account\Model;

use Bolzen\Core\Model\Model;
use Bolzen\Src\Service\Role\Role;
use Bolzen\Src\Shibboleth\Shibboleth;
use Bolzen\Src\UniversityID\Model\UniversityID;
use Symfony\Component\HttpFoundation\Request;

/**
 * Class AccountModel
 * @package Bolzen\Src\Account\Model
 */
class AccountModel extends Model
{

    private $shibboleth;
    private $table;
    private $universityModel;

    /**
     * AccountModel constructor.
     */
    public function __construct()
    {
        parent::__construct();
        $this->errors = array();
        $this->table = "users";
        $this->shibboleth = new \Bolzen\Src\Service\Shibboleth\Shibboleth();
        $this->universityModel = new UniversityID();

        $this->createUserIfNotExist();

        //authenicate the user
        $this->createAuthenicatedSession();

        $this->promptUniversityID();

        //session protection
        $this->invalidSessionProtector();
    }

    /**
     * SQL to get university ID for verification
     */
    public function promptUniversityID()
    {
        $request = Request::createFromGlobals();
        $path = $this->accessControl->getCSRFToken()."/universityid";
        $uri = $request->server->get('REQUEST_URI', '');

        if (strpos($uri, $path)===false) {
            $sql = "SELECT universityId FROM users WHERE username = ? and universityId is NULL";
            $binding = array($this->shibboleth->getUsername());

            if ($this->database->genericSqlQueryBuilder($sql, $binding)->rowCount() !== 0) {
                $this->accessControl->redirect($path);
            }
        }
    }

    /**
     * SQL to check username from database and skip to the website
     */
    public function skipFirstTimeUniversityIDPrompt()
    {
        $sql = "SELECT universityId FROM users WHERE username = ? and universityId is NULL";
        $binding = array($this->shibboleth->getUsername());
        $path = "";

        if ($this->database->genericSqlQueryBuilder($sql, $binding)->rowCount() === 0) {
            $this->accessControl->redirect($path);
        }
    }

    /**
     * add new user and insert to table
     */
    public function createUserIfNotExist()
    {
        $where = "username = ?";
        $bindings = array($this->shibboleth->getUsername());
        if ($this->database->select($this->table, $where, $bindings)===null) {
            $columns = "username,fName,lName";
            $values = "?,?,?";
            $bindings = array($this->shibboleth->getUsername(), $this->shibboleth->getFirstName(),
                $this->shibboleth->getLastName());
            if (!$this->database->insert($this->table, $columns, $values, $bindings)) {
                echo "An error occurred while creating your account";
                exit();
            }

            if (!$this->accessControl->assignRole($this->getUsername(), Role::$RITUSER)) {
                echo "An error occurred while setting up your account permission";
                exit();
            }

            if (!$this->save()) {
                echo "An error exist while finalize your account";
                exit();
            }
        }
    }


    /**
     * This page direct user to the invalid session if they are using an invalid session
     */
    public function invalidSessionProtector()
    {
        $allowed = "paws/index";
        $request = Request::createFromGlobals();
        $uri = $request->server->get('QUERY_STRING', '');

        if (!empty($uri)) {
            //do nto include the main index
            if (strpos($uri, $allowed) ===false) {
                //attempt to get the token
                $token = $request->get('token', '');

                //get the token by filtering it out from the URI
                if (empty($token)) {
                    $query = $request->query->all();

                    if (count($query) > 0) {
                        $query = array_keys($query);
                        $query = explode("/", $query[0]);
                        $token = $query[0];
                    }
                }

                if (empty($token) || !$this->accessControl->isValidCSRFToken($token)) {
                    $this->accessControl->redirect('invalidSession');
                }
            }
        }
    }


    /**
     * verify access and be able to authenticate user with shibboleth
     */
    private function loadShibbolethAndAuthenication()
    {
        if ($this->shibboleth===null) {
            $this->shibboleth = new \Bolzen\Src\Service\Shibboleth\Shibboleth();
        }
        $this->createAuthenicatedSession();
    }

    /**
     * Display user's username, roles and ip
     */
    private function createAuthenicatedSession()
    {
        if ($this->user->isAnonymous()) {
            $username = $this->shibboleth->getUsername();
            $roles = $this->user->getRoles($username);
            $request = Request::createFromGlobals();
            $agent = $request->get('User-Agent', '');
            $ip = $request->getClientIp();
            $this->accessControl->createAccessControlSession($username, $ip, $agent, $roles);
        }
    }

    /**
     * @param string $username
     * @return bool if username is exists
     * boolean set if username is empty or not on pop-up textfield
     */
    public function hasAccount(string $username):bool
    {
        if (empty($username)) {
            $this->setError("username cannot be empty");
            return false;
        }

        $where = "username = ?";
        $binding = array($username);
        return $this->database->select($this->table, $where, $binding) instanceof \PDOStatement;
    }

    /**
     * @param $accountType and redirect to the url
     */
    public function rerouteWithToken(string $accountType)
    {
        $token = $this->accessControl->getCSRFToken();
        $url = $token."/".$accountType."/index";

        $this->accessControl->redirect($url);
    }

    /**
     * @return string
     * get user's username
     */
    public function getUsername()
    {
        $this->loadShibbolethAndAuthenication();
        return $this->shibboleth->getUsername();
    }

    /**
     * @return string
     * get the email typed by user
     */
    public function getEmail()
    {
        $this->loadShibbolethAndAuthenication();
        return $this->shibboleth->getEmail();
    }


    /**
     * redirect user to access denied page
     */
    public function redirectToAccessDenied()
    {
        $this->accessControl->redirect('accessDenied');
    }

    /**
     * @param string $username
     * @return bool if username is exist to Admin
     * if user logged in happens to admin, display the role
     */
    public function isAdmin(string $username = ""):bool
    {
        return $this->accessControl->hasRole(Role::$ADMIN, $username);
    }

    /**
     * @param string $username
     * @return bool if username is exist to staff
     * if user logged in happens to be staff, display the role
     */
    public function isStaff(string $username = ""):bool
    {
        return $this->accessControl->hasRole(Role::$STAFF, $username);
    }

    /**
     * @param string $username
     * @return bool if username is exists to manager
     * if user logged in happens to be manager, display the role
     */
    public function isManager(string $username = ""):bool
    {

        return $this->accessControl->hasRole(Role::$MANAGER, $username);
    }

    /**
     * @param string $username
     * @return bool if user logged in happens to be any RIT user, display the role
     */
    public function isRITUser(string $username = ""):bool
    {
        return $this->accessControl->hasRole(Role::$RITUSER, $username);
    }

}
