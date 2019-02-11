<?php
/**
 * Created by PhpStorm.
 * User: root
 * Date: 8/18/18
 * Time: 12:55 PM
 */

namespace Bolzen\Core\User;

interface UserInterface
{
    /**
     * Return the roles of the supplied user. If the supplied user is empty,
     * then it is assumed that the requested roles should be that of the current
     * logged user
     * @param string $user - the user's role to get. The default is set to empty and will
     *                       return the roles of the current logged user
     * @return array - the supplied user's roles
     */
    public function getRoles(string $user = ""):array;

    /**
     * Get the username of the current logged user
     * @return string - the username of the current logged user
     */
    public function getUsername():string;

    /**
     * Return a list of all users from the users table
     * @return array - a list of all users
     */
    public function getUsers():array;

    /**
     * Return a specific user(s) by applying filtering using the where clause
     * @param string $where - the where clause eg id=?
     * @param array $bindings - the bindings for the where clause
     * @param string $columns - the columns to return from the user's table
     * @return array - return an array of the target user(s)
     */
    public function getUsersByFilter(string $where, array $bindings, string $columns = "*"):array;

    /**
     * Add a specific user to the users table
     * @param string $username - the username to add
     * @param string $password - the password of the user you are current adding
     * @param bool $verified - a boolean statement on whether the user's account is verified
     * @return bool - true if the user was successful added and false otherwise.
     */
    public function add(string $username, string $password = "", bool $verified = false):bool;

    /**
     * Remove a user from the system by their username
     * @param string $username - the username to remove
     * @return bool - true if the user was successful removed, false otherwise
     */
    public function remove(string $username):bool;

    /**
     * Return a boolean expression on whether the current logged user is anonymous
     * @return bool - true if is anonymous false otherwise
     */
    public function isAnonymous():bool;

    /**
     * Change the password of a user
     * @param string $username - the username of whose password to change
     * @param string $password - the new password
     * @return bool - true if the password was successful changed, false otherwise
     */
    public function changePassword(string $username, string $password):bool;
}