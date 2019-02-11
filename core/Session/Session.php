<?php
/**
 * Created by PhpStorm.
 * User: root
 * Date: 8/18/18
 * Time: 1:43 PM
 */

namespace Bolzen\Core\Session;

class Session implements SessionInterface
{
    private $session;

    /**
     * Session constructor - initalize the session and start it if it is
     * not already started
     */
    public function __construct()
    {
        $this->session = new \Symfony\Component\HttpFoundation\Session\Session();
        if (!$this->session->isStarted()) {
            $this->session->start();
        }
    }

    /**
     * Return the current session
     * @return \Symfony\Component\HttpFoundation\Session\Session - the current active session
     */
    public function getSession(): \Symfony\Component\HttpFoundation\Session\Session
    {
        return $this->session;
    }
}