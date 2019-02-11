<?php
/**
 * Created by PhpStorm.
 * User: root
 * Date: 12/10/18
 * Time: 1:01 PM
 */

namespace Bolzen\Src\Account\Controller;


use Bolzen\Core\Controller\Controller;
use Bolzen\Src\Account\Model\AccountModel;
use Bolzen\Src\UniversityID\Model\UniversityID;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class LoginController
 * @package Bolzen\Src\Account\Controller
 */
class LoginController extends Controller
{
    private $accountModel;

    /**
     * LoginController constructor.
     */
    public function __construct()
    {
        parent::__construct();
        $this->accountModel = new AccountModel();
    }

    public function account(Request $request)
    {
        $account = $request->get('account', '');
        $this->accountModel->rerouteWithToken($account);
    }

    /**
     * @param Request $request username
     * @return Response
     * @throws \Twig_Error_Loader
     * @throws \Twig_Error_Runtime
     * @throws \Twig_Error_Syntax
     * request for user to log in
     */
    public function login(Request $request)
    {
        return $this->render($request);
    }

    /**
     * @param Request $request username for university ID
     * @return Response
     * @throws \Twig_Error_Loader
     * @throws \Twig_Error_Runtime
     * @throws \Twig_Error_Syntax
     * request for university ID
     */
    public function uid(Request $request)
    {
        $this->accountModel->skipFirstTimeUniversityIDPrompt();
        return $this->render($request);
    }

    /**
     * @param Request $request username
     * @return Response to ajax for login with username
     * @throws \Throwable
     * @throws \Twig_Error_Loader
     * @throws \Twig_Error_Runtime
     * @throws \Twig_Error_Syntax
     * verifying user's provided username and password
     */
    public function authenicate(Request $request)
    {
        $response = array("status"=>400, "msg"=>"something went wrong, try again");
        $username = $request->get('username', '');
        $password = $request->get('password', '');

        $username = "ksc2650";
        $password = "password";

        if (!$this->accountModel->login($username, $password)) {
            $response["msg"] = $this->accountModel->getError()[0];
        } else {
            $links = $this->accountModel->links($username);
            $response["status"] = 200;
            $template = $this->twig->loadTemplate('login/login.php');
            $response["msg"] = $template->render(array("links"=>$links));
        }

        return new Response(json_encode($response));
    }
}