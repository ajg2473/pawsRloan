<?php
/**
 * Created by PhpStorm.
 * User: root
 * Date: 10/3/18
 * Time: 6:07 PM
 */

namespace Bolzen\Src\Error;


use Bolzen\Core\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class ErrorController extends Controller
{
    public function accessDenied(Request $request, array $err = array())
    {
        return $this->render($request, $err);
    }
}