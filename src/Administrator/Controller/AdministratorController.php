<?php
/**
 * Created by PhpStorm.
 * User: Superchang
 * Date: 11/29/18
 * Time: 13:46
 */

namespace Bolzen\Src\Administrator\Controller;


use Bolzen\Core\Controller\Controller;
use Bolzen\Src\Account\Model\AccountModel;
use Bolzen\Src\Manager\Model\ManagerModel;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class AdministratorController
 * @package Bolzen\Src\Administrator\Controller
 */
class AdministratorController extends Controller
{
    private $data;
    private $managerModel;
    private $accountModel;

    /**
     * AdministratorController constructor.
     */
    public function __construct()
    {
        parent::__construct();
        $this->data = array();
        $this->managerModel = new ManagerModel();
        $this->accountModel = new AccountModel();
    }

    /**
     * @param Request $request Administrator that enters username and add username as a manager
     * @return Response is ajax to do something about adding username to manager
     * @throws \Throwable
     * @throws \Twig_Error_Loader
     * @throws \Twig_Error_Runtime
     * @throws \Twig_Error_Syntax
     * add new manager to table
     */
    public function addManager(Request $request)
    {
        $username = $request->get('username', '');

        $response = array("status"=>400,"msg"=>"An error prevented us from adding a new manager");


        if (!$this->managerModel->addManager($username)) {
            if ($this->managerModel->hasError()) {
                $response["msg"] = $this->managerModel->getError()[0];
            }
        } else {
            $response["status"] = 200;


            //update the ui
            $template = $this->twig->loadTemplate("Partial/Administrator/managers.php");
            $this->loadManager();

            $response["msg"] = $template->render($this->data);
        }

        return new Response(json_encode($response));
    }

    /**
     * @param Request $request a website and direct to php
     * @return Response this data to php
     * @throws \Twig_Error_Loader
     * @throws \Twig_Error_Runtime
     * @throws \Twig_Error_Syntax
     * verification of admin
     */
    public function index(Request $request)
    {
        if (!$this->accountModel->isAdmin()) {
            $this->accountModel->redirectToAccessDenied();
        }
        $this->data["administrator"] = "yes";
        $this->loadManager();
        return $this->render($request, $this->data);
    }

    /**
     * show all managers
     */
    public function loadManager()
    {
        $this->data["managers"]= $this->managerModel->all();
    }

    /**
     * @param Request $request Administrator enters username to delete manager username
     * @return Response for ajax to do something about deleting a username
     * @throws \Throwable
     * @throws \Twig_Error_Loader
     * @throws \Twig_Error_Runtime
     * @throws \Twig_Error_Syntax
     * delete existing manager
     */
    public function deleteManager(Request $request)
    {
        $username = $request->get('username', '');



        $response = array("status"=>400,"msg"=>"An error prevented us from deleting a manager");

        if (!$this->managerModel->delete($username)) {
            if ($this->managerModel->hasError()) {
                $response["msg"] = $this->managerModel->getError()[0];
            }
        } else {
            $response["status"] = 200;


            //update the ui
            $template = $this->twig->loadTemplate("Partial/Administrator/managers.php");
            $this->loadManager();

            $response["msg"] = $template->render($this->data);
        }

        return new Response(json_encode($response));
    }


}
