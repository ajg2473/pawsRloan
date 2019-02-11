<?php
/**
 * Created by PhpStorm.
 * User: root
 * Date: 10/16/18
 * Time: 10:46 AM
 */

namespace Bolzen\Src\Service\Shibboleth;

use Symfony\Component\HttpFoundation\Request;

/**
 * Class Shibboleth
 * @package Bolzen\Src\Service\Shibboleth
 *
 * Shibboleth is assigned for username, fullname, and gmail to login in.
 */
class Shibboleth
{
    private const USERNAME = "uid";
    private const FIRSTNAME = "givenName";
    private const LASTNAME = "sn";
    private const MAIL = "mail";
    private $sessionInitiatorURL = '/Shibboleth.sso/Login/?target=';

    /**
     * Shibboleth constructor.
     */
    public function __construct()
    {
        $this->promptLogin();
    }

    /**
     * promptLogin method
     * If it is active, to call the URI for login
     */
    public function promptLogin()
    {

        if (!$this->isActive()) {
            $res = Request::createFromGlobals();
            $host = $res->server->get('HTTP_HOST');
            $redirectTo = urlencode($res->server->get('SCRIPT_URI'));

            if (strpos($redirectTo, 'https') === false) {
                echo 'true';

                str_replace("http", "https", $redirectTo);
            }

            $url = "https://".$host.$this->sessionInitiatorURL.$redirectTo;
            header("Location:$url");
            exit();
        }
    }

    /**
     * @return bool
     * isActive method - if it is active
     */
    public function isActive():bool
    {
        if (isset($_SERVER['AUTH_TYPE']) && $_SERVER['AUTH_TYPE'] == 'shibboleth' &&
            isset($_SERVER['Shib_Session_ID']) && !empty($_SERVER['Shib_Session_ID']) &&
            isset($_SERVER['uid']) && !empty($_SERVER['uid']) &&
            isset($_SERVER['givenName']) && !empty($_SERVER['givenName']) &&
            isset($_SERVER['sn']) && !empty($_SERVER['sn']) &&
            isset($_SERVER['mail']) && !empty($_SERVER['mail'])) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * @return string
     * getUsername method
     */
    public function getUsername():string
    {
        return isset($_SERVER[self::USERNAME]) ? $_SERVER[self::USERNAME] : "";
    }

    /**
     * @return string
     * getFirstName method
     */
    public function getFirstName():string
    {
        return isset($_SERVER[self::FIRSTNAME]) ? $_SERVER[self::FIRSTNAME] : "";
    }

    /**
     * @return string
     * getLastName method
     */
    public function getLastName():string
    {
        return isset($_SERVER[self::LASTNAME]) ? $_SERVER[self::LASTNAME] : "";
    }

    /**
     * @return string
     * getEmail method
     */
    public function getEmail():string
    {
        return isset($_SERVER[self::MAIL]) ? $_SERVER[self::MAIL] : "";
    }
}