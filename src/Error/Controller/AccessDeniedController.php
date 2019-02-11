<?php
/**
 * Created by PhpStorm.
 * User: root
 * Date: 10/19/18
 * Time: 12:23 PM
 */

namespace Bolzen\Src\Error\Controller;

use Bolzen\Core\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class AccessDeniedController extends Controller
{

    public function denied(Request $request, array $err = array())
    {
        return $this->render($request, $err);
    }

    public function invalidSession(Request $request, array $err = array())
    {
        return $this->render($request, $err);
    }

}