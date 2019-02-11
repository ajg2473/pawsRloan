<?php
/**
 * Created by PhpStorm.
 * User: root
 * Date: 8/18/18
 * Time: 1:52 PM
 */

namespace Bolzen\Core\AccessControl;

interface AccessControlInterface
{

    /**
     * This method authenicate a user
     * @param string $username - the username of the user to authenicate
     * @param string $password - the user's passwword
     * @return bool - true if the user was successful authenticate. False otherwise
     */
    public function authenticate(string $username, string $password):bool;

    /**
     * Create a authorized session for this user
     * @param string $username - the username
     * @param string $ip - the ip of the user
     * @param string $agent - the browser user-agent
     * @param array $role - list of roles for this user
     */
    public function createAccessControlSession(string $username, string $ip, string $agent, array $role):void;

    /**
     * Return a newly create token using openssl_random_pseudo_bytes
     * @return string - secured random token
     */
    public function newToken():string;

    /**
     * return the current CSRF Token
     * @return string - the current CSRF token
     */
    public function getCSRFToken():string;

    /**
     * Return a boolean expression on whether a csrf token is valid
     * @param string $token - the token to check if is valid
     * @return bool - true if valid. False otherwise
     */
    public function isValidCSRFToken(string $token):bool;

    /**
     * Redirect if the user is not logged in
     * @param string $path - the path to redirect the user to
     */
    public function redirectToLoginPageIfNotLogged(string $path):void;

    /**
     * Redirect the user to desired path
     * @param $path - the path to redirect the user to
     */
    public function redirect($path):void;

    /**
     * Check if the user has a certain role
     * @param string $role - the role to check for
     * @param string $username - the username to check against. If leave empty,
     *                it assumed that the username is that of the logged user
     * @return bool - true if the user has the role. False otherwise
     */
    public function hasRole(string $role, string $username = ""):bool;

    /**
     * Assign a role to a user
     * @param string $username - the username to assign the role
     * @param string $role - the role to assign the user
     * @return bool - true if the role was successful added, false otherwise
     */
    public function assignRole(string $username, string $role):bool;

    /**
     * Perform a series of check to see whether a session is valid
     * @param string $token - the csrf token
     * @return bool - true if valid. False otherwise
     */
    public function isValidSession(string $token):bool;

    /**
     * Redirect the user to a desired path if the session proven to be invalid
     * @param string $path
     * @param string $token
     */
    public function redirectIfInvalidSession(string $path, string $token):void;

    /**
     * This function takes a role and attempt to return the role id
     * @param string $role - the role whose id to fetch
     * @return array - array with the role id if it exist otherwise an empty array
     */
    public function getRoleID(string $role):array;

    /**
     * Strip the user of a specific role
     * @param string $username - the username to remove the role from
     * @param string $role - the role to remove
     * @return bool - true if the role was removed. False otherwise
     */
    public function stripRole(string $username, string $role):bool;
}