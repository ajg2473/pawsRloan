<?php
/**
 * Created by PhpStorm.
 * User: root
 * Date: 8/21/18
 * Time: 10:05 PM
 */

namespace Bolzen\Src\Home\Controller;


use Bolzen\Core\Controller\Controller;
use Bolzen\Src\Account\Model\AccountModel;
use Bolzen\Src\Device\Device;
use Symfony\Component\HttpFoundation\Request;

/**
 * Class HomeController
 * @package Bolzen\Src\Home\Controller
 */
class HomeController extends Controller
{
    private $accountModel;

    /**
     * HomeController constructor.
     */
    public function __construct()
    {
        parent::__construct();
        $this->accountModel = new AccountModel();
    }

    /**
     * @param Request $request
     */
    public function index(Request $request)
    {
        $this->accountModel->rerouteWithToken("borrower");
    }
}