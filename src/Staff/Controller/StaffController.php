<?php
/**
 * Created by PhpStorm.
 * User: root
 * Date: 11/27/18
 * Time: 6:58 PM
 */

namespace Bolzen\Src\Staff\Controller;


use Bolzen\Core\Controller\Controller;
use Bolzen\Src\Account\Model\AccountModel;
use Bolzen\Src\Category\Model\CategoryModel;
use Bolzen\Src\Inventory\Model\InventoryModel;
use Bolzen\Src\Loan\Model\LoanModel;
use Bolzen\Src\UniversityID\Model\UniversityID;
use Symfony\Component\HttpFoundation\Request;
use Bolzen\Src\Staff\Model\StaffModel;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class StaffController
 * @package Bolzen\Src\Staff\Controller
 */
class StaffController extends Controller
{
    private $categoryModel;
    private $staffModel;
    private $data;
    private $loanModel;
    private $universityIdModel;
    private $inventoryModel;
    private $accountModel;

    /**
     * StaffController constructor.
     */
    public function __construct()
    {
        parent::__construct();
        $this->categoryModel = new CategoryModel();
        $this->staffModel = new StaffModel();
        $this->data = array();
        $this->loanModel = new LoanModel();
        $this->universityIdModel = new UniversityID();
        $this->inventoryModel = new InventoryModel();
        $this->accountModel = new AccountModel();
    }

    /**
     * @param Request $request pulls all information from database and show on the website
     * @return Response with array of each row from database
     * @throws \Twig_Error_Loader
     * @throws \Twig_Error_Runtime
     * @throws \Twig_Error_Syntax
     */
    public function index(Request $request)
    {
        if (!$this->accountModel->isStaff()) {
            $this->accountModel->redirectToAccessDenied();
        }


        $this->listOut();
        $this->loadOverViewItems();
        $this->loadInventoryStats();
        $this->loadHistory();
        return $this->render($request, $this->data);
    }

    /**
     * @param Request $request retrieve database for check in
     * @return Response show list of array for each row of database
     * check in the item
     */
    public function checkIn(Request $request)
    {
        $response = array("status"=>400,"msg"=>"We were not able to check in your item");
        $itemId = $request->get('item', '');

        if (!$this->loanModel->return($itemId)) {
            if ($this->loanModel->hasError()) {
                $response["msg"] = $this->loanModel->getError()[0];
            }
        } else {
            $response["status"] = 200;
            $response["msg"] = "Successful checked in";
        }

        return new Response(json_encode($response));
    }

    /**
     * load inventory status
     */
    public function loadInventoryStats()
    {
        $this->data["stats"] = $this->inventoryModel->inventoryStats();
    }

    /**
     * load history
     */
    public function listHistory()
    {
        $this->data['history'] = $this->staffModel->listHistory();
    }

    /**
     * @param Request $request retrieve database for available item
     * @return Response show list of array for each row of database
     * @throws \Throwable
     * @throws \Twig_Error_Loader
     * @throws \Twig_Error_Runtime
     * @throws \Twig_Error_Syntax
     * show all available items
     */
    public function availableItem(Request $request)
    {
        $categoryId = $request->get('category', '');
        //$categoryId = "b8ab3ff957e3e02179b4";
        $response = array("status"=>400,"msg"=>"unable to fetch items");

        $info = $this->loanModel->showAvailableItems($categoryId);

        if ($this->loanModel->hasError()) {
            $response["msg"] = $this->loanModel->getError()[0];
        } else {
            $response["status"] = 200;
            $template = $this->twig->loadTemplate('Partial/Staff/available.php');
            $response["msg"] = $template->render(array("available"=>$info));
        }
        return new Response(json_encode($response));
    }

    /**
     * @param Request $request retrieve database for check out items
     * @return Response show list of array for each row of database
     * check items
     */
    public function checkOutMassive(Request $request)
    {
        $response = array("status"=>400, "msg"=>"an error occurred, try again");
        $universityId = $request->get('university', '');
        $universityId =  $this->universityIdModel->getHashedUniversityID($universityId);

        $categoryId = $request->get('category', '');
        $dueDates = $request->get('dues', array());
        $items = $request->get('items', array());

        if (!$this->loanModel->loan($universityId, $categoryId, $items, $dueDates)) {
            if ($this->loanModel->hasError()) {
                $response["msg"] = $this->loanModel->getError()[0];
            }
        } else {
            $response["status"] = 200;
            $response["msg"] = "item(s) were successful loaned";
        }

        return new Response(json_encode($response));
    }

    /**
     * @param Request $request
     * swipe card verification
     */
    public function processSwipe(Request $request)
    {
        $universityID = $request->get('universityid', '');
    }

    /**
     * load all history
     */
    public function loadHistory()
    {
        $this->data['histories'] = $this->staffModel->listHistory();
    }

    /**
     * list all check out items
     */
    public function listOut()
    {
        $this->data["checkout"] = $this->staffModel->listCheckOut();
    }

    /**
     * list all overdue items
     */
    public function loadOverViewItems()
    {
        $this->data["overdue"]= $this->staffModel->getOverDueItems();
    }

    /**
     * @return Response retrieve database for the load check in information
     * @throws \Throwable
     * @throws \Twig_Error_Loader
     * @throws \Twig_Error_Runtime
     * @throws \Twig_Error_Syntax
     * load all check in items
     */
    public function loadCheckIn()
    {
        $this->data["checkIn"]= $this->staffModel->listCheckIn();
        $template = $this->twig->loadTemplate('Staff/checkin.php');
        return new Response($template->render($this->data));
    }

}