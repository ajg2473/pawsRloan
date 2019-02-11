<?php
/**
 * Created by PhpStorm.
 * User: root
 * Date: 8/18/18
 * Time: 1:40 PM
 */

namespace Bolzen\Core\User;

use Bolzen\Core\Database\DatabaseInterface;
use Bolzen\Core\Session\SessionInterface;

class User implements UserInterface
{
    private $usersTable;
    private $database;
    private $session;
    private $anonymousUser = "anonymous";

    public function __construct(SessionInterface $session, DatabaseInterface $database)
    {
        $this->database = $database;
        $this->session = $session->getSession();
        $this->usersTable = "users";
    }

    /**
     * Return the roles of the supplied user. If the supplied user is empty,
     * then it is assumed that the requested roles should be that of the current
     * logged user
     * @param string $user - the user's role to get. The default is set to empty and will
     *                       return the roles of the current logged user
     * @return array - the supplied user's roles
     */
    public function getRoles(string $user = ""): array
    {
        //if empty, take the current logged in user
        if (empty($user)) {
            $user = $this->getUsername();
        }

        $sql = "SELECT name as role FROM roles INNER JOIN
                userRoles ON userRoles.roleID = roles.roleID WHERE
                userRoles.username = ?";

        $bindings = array($user);
        $roles = $this->database->genericSqlQueryBuilder($sql, $bindings);

        //if record is found in the database then return
        //all roles for this user otherwise return an array with the role anonymous
        return $roles->rowCount() > 0 ? $roles->fetchAll() : array(array('role'=>$this->anonymousUser));
    }

    /**
     * Get the username of the current logged user
     * @return string - the username of the current logged user
     */
    public function getUsername(): string
    {
        return $this->session->get('username', $this->anonymousUser);
    }

    /**
     * Return a list of all users from the users table
     * @return array - a list of all users
     */
    public function getUsers(): array
    {
        $users = $this->database->select($this->usersTable);

        // no records found
        if (!$users) {
            return array();
        }
        return $users->fetchAll();
    }

    /**
     * Return a specific user(s) by applying filtering using the where clause
     * @param string $where - the where clause eg id=?
     * @param array $bindings - the bindings for the where clause
     * @param string $columns - the columns to return from the user's table
     * @return array - return an array of the target user(s)
     */
    public function getUsersByFilter(string $where, array $bindings, string $columns = "*"): array
    {
        $users = $this->database->select($this->usersTable, $where, $bindings, $columns);

        // no records found
        if (!$users) {
            return array();
        }
        return $users->fetchAll();
    }

    /**
     * Add a specific user to the users table
     * @param string $username - the username to add
     * @param string $password - the password of the user you are current adding
     * @param bool $verified - a boolean statement on whether the user's account is verified
     * @return bool - true if the user was successful added and false otherwise.
     */
    public function add(string $username, string $password = "", bool $verified = false): bool
    {
        $columns = "username,password,verified";
        $values = "?,?,?";

        //hashing the password
        $password = $username.$password;
        $password = password_hash($password, PASSWORD_DEFAULT);

        $bindings = array($username,$password,$verified);

        return $this->database->insert($this->usersTable, $columns, $values, $bindings);
    }

    /**
     * Remove a user from the system by their username
     * @param string $username - the username to remove
     * @return bool - true if the user was successful removed, false otherwise
     */
    public function remove(string $username): bool
    {
        $where = "username = ?";
        $bindings = array($username);
        return $this->database->delete($this->usersTable, $where, $bindings);
    }

    /**
     * Return a boolean expression on whether the current logged user is anonymous
     * @return bool - true if is anonymous false otherwise
     */
    public function isAnonymous(): bool
    {
        return $this->getUsername() === $this->anonymousUser;
    }

    /**
     * Change the password of a user
     * @param string $username - the username of whose password to change
     * @param string $password - the new password
     * @return bool - true if the password was successful changed, false otherwise
     */
    public function changePassword(string $username, string $password): bool
    {
        $set = "password = ?";
        $where = "username = ?";

        //hashing the password
        $password = $username.$password;
        $password = password_hash($password, PASSWORD_DEFAULT);

        $bindings = array($username,$password);
        return $this->database->update($this->usersTable, $set, $where, $bindings);
    }
}
