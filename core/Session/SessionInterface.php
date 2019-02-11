<?php
/**
 * Created by PhpStorm.
 * User: root
 * Date: 8/18/18
 * Time: 1:44 PM
 */

namespace Bolzen\Core\Session;

use Symfony\Component\HttpFoundation\Session\Session;

interface SessionInterface
{
    /**
     * Return the current session
     * @return Session - the current active session
     */
    public function getSession():Session;
}
