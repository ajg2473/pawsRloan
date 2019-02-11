<?php
/**
 * Created by PhpStorm.
 * User: root
 * Date: 11/29/18
 * Time: 10:47 AM
 */

namespace Bolzen\Src\Manager\Controller;

use Bolzen\Core\Controller\Controller;
use Bolzen\Src\Account\Model\AccountModel;
use Bolzen\Src\Category\Model\CategoryModel;
use Bolzen\Src\Fee\Model\FeeModel;
use Bolzen\Src\Field\FieldModel;
use Bolzen\Src\BlockList\Model\BlockList;
use Bolzen\Src\Inventory\Model\InventoryModel;
use Bolzen\Src\Policy\Model\PolicyModel;
use Bolzen\Src\Service\Attribute\Parser;
use Bolzen\Src\Staff\Model\StaffModel;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class ManagerController
 * @package Bolzen\Src\Manager\Controller
 *
 * ManagerController is different functions based on descriptive terms on the navigational tab.
 */
class ManagerController extends Controller
{
    private $data;
    private $categoryModel;
    private $feeModel;
    private $staffModel;
    private $fieldModel;
    private $inventoryModel;
    private $policyModel;
    private $accountModel;

    /**
     * ManagerController constructor.
     */
    public function __construct()
    {
        parent::__construct();
        $this->data = array();
        $this->categoryModel = new CategoryModel();
        $this->feeModel = new FeeModel();
        $this->staffModel = new StaffModel();
        $this->fieldModel = new FieldModel();
        $this->inventoryModel = new InventoryModel();
        $this->policyModel = new PolicyModel();
        $this->accountModel = new AccountModel();
    }

    /**
     * @param Request $request a load from specific database calling
     * @return Response a data from list
     * @throws \Twig_Error_Loader
     * @throws \Twig_Error_Runtime
     * @throws \Twig_Error_Syntax
     *
     * index method - verification of manager
     */
    public function index(Request $request)
    {
        if (!$this->accountModel->isManager()) {
            $this->accountModel->redirectToAccessDenied();
        }
        $this->data["manager"] = "yes";
        $this->loadCategory();
        $this->loadFees();
        $this->loadStaff();
        $this->loadInventoryStats();
        $this->loadPolicy();
        return $this->render($request, $this->data);
    }

    /**
     * call sql function to load list of policy
     */
    public function loadPolicy()
    {
        $this->data["policies"] = $this->policyModel->loadPolicy();
    }

    /**
     * @param Request $request for information that is updated or inserted into database
     * @return Response to ajax and perform an action on website
     *
     * */
    public function addPolicy(Request $request)
    {
        $policy = $request->get('policy', '');
        $categoryId = $request->get('category', '');
        $name = $this->categoryModel->getCategoryName($categoryId);

        $response = array("status"=>400, "msg"=>"We were not able to add the policy");

        if (count($this->policyModel->getPolicy($categoryId)) > 0) {
            $response["msg"] = "An unknown error prevented us from update the policy";
            if (!empty($policy)) {
                $policy = htmlentities($policy);
            }

            if (!$this->policyModel->updatePolicy($categoryId, $name, $policy)) {
                if ($this->policyModel->hasError()) {
                    $response["msg"] = $this->policyModel->getError()[0];
                }
            } else {
                $response["status"] = 200;
                $response["msg"] = "$name was successful updated";
            }
        } else {
            $name = $this->categoryModel->getCategoryName($categoryId);

            if (!empty($policy)) {
                $policy = htmlentities($policy);
            }

            if (!$this->policyModel->addPolicy($categoryId, $name, $policy)) {
                if ($this->policyModel->hasError()) {
                    $response["msg"] = $this->policyModel->getError()[0];
                }
            } else {
                $response["status"] = 201;
                $response["msg"] = "Policy was successful added";
            }
        }

        return new Response(json_encode($response));
    }

    /**
     * load categories from inventory
     */
    public function loadCategory()
    {
        $category = $this->categoryModel->all();

        $this->data["categories"] = $category;
    }

    /**
     * @return Response load a category from database on website
     * @throws \Throwable
     * @throws \Twig_Error_Loader
     * @throws \Twig_Error_Runtime
     * @throws \Twig_Error_Syntax
     * load fees from overdue items
     *
     * load fee form from manager
     */
    public function loadFeeForm()
    {
        $this->loadCategory();

        $template = $this->twig->loadTemplate('Partial/Manager/feeForm.php');
        $this->loadCategory();
        return new Response($template->render($this->data));
    }

    /**
     * load staff from manager
     */
    public function loadStaff()
    {
        $this->data["staffs"] = $this->staffModel->all();
    }

    /**
     * @param Request $request add username as a staff to the website
     * @return Response to ajax and performs an action for the website
     * @throws \Throwable
     * @throws \Twig_Error_Loader
     * @throws \Twig_Error_Runtime
     * @throws \Twig_Error_Syntax
     * insert staff by manager
     */
    public function addStaff(Request $request)
    {
        $username = $request->get('username', '');
        $response = array("status"=>400,"msg"=>"An error prevented us from add the manager");

        //successful added?
        if (!$this->staffModel->addStaff($username)) {
            if ($this->staffModel->hasError()) {
                $response["msg"] = $this->staffModel->getError()[0];
            }
        } else {
            $response["status"] = 200;

            $template = $this->twig->loadTemplate('Partial/Manager/staffList.php');
            $this->loadStaff();
            $response["msg"] = $template->render($this->data);
        }

        return new Response(json_encode($response));
    }

    /**
     * @param Request $request called to get category, rate and fee from database
     * @return Response to ajax to perform an action for the website
     * @throws \Throwable
     * @throws \Twig_Error_Loader
     * @throws \Twig_Error_Runtime
     * @throws \Twig_Error_Syntax
     * insert fee for overdue items
     */
    public function addFee(Request $request)
    {
        $category = $request->get('category', '');
        $rate = $request->get('rate', '');
        $fee = $request->get('cost', '');

        $response = array("status"=>400,"msg"=>"An error prevented us from adding the fee");

        if (!$this->feeModel->add($category, $fee, $rate)) {
            if ($this->feeModel->hasError()) {
                $response["msg"] = $this->feeModel->getError()[0];
            }
        } else {
            $response["status"] = 200;

            $template = $this->twig->loadTemplate('Partial/Manager/feeList.php');
            $this->loadFees();
            $response["msg"] = $template->render($this->data);
        }

        return new Response(json_encode($response));
    }

    /**
     * @param Request $request for a username and remove username from Staff
     * @return Response to ajax to perform an action for the website
     * @throws \Throwable
     * @throws \Twig_Error_Loader
     * @throws \Twig_Error_Runtime
     * @throws \Twig_Error_Syntax
     * remove staff from table
     */
    public function removeStaff(Request $request)
    {
        $username = $request->get('username', '');
        $response = array("status"=>400,"msg"=>"An error prevented us from remove the manager");

        if (!$this->staffModel->removeStaff($username)) {
            if ($this->staffModel->hasError()) {
                $response["msg"] = $this->staffModel->getError()[0];
            }
        } else {
            $response["status"] = 200;

            $template = $this->twig->loadTemplate('Partial/Manager/staffList.php');
            $this->loadStaff();
            $response["msg"] = $template->render($this->data);
        }

        return new Response(json_encode($response));
    }

    /**
     * show all fees
     */
    public function loadFees()
    {
        $this->data["fees"] = $this->feeModel->all();
    }

    /**
     * @return Response to get data from the template
     * @throws \Throwable
     * @throws \Twig_Error_Loader
     * @throws \Twig_Error_Runtime
     * @throws \Twig_Error_Syntax
     * load category templates
     */
    public function loadCategoryForm()
    {
        $this->loadCategory();

        $template = $this->twig->loadTemplate('Partial/Manager/categoryForm.php');
        return new Response($template->render($this->data));
    }

    /**
     * @param Request $request to add inventory to the list
     * @return Response to ajax to perform an action for the website
     * insert a new item as it updated the item and category tables.
     */
    public function addInventory(Request $request)
    {
        $response = array("status"=>400,"msg"=>"We were not able to add the item to the inventory");


        if (!$this->inventoryModel->add($request)) {
            if ($this->inventoryModel->hasError()) {
                $response["msg"] = $this->inventoryModel->getError()[0];
            }
        } else {
            $response["status"] = 200;
            $response["msg"] = "successful added";
        }

        return new Response(json_encode($response));
    }

    /**
     * display inventory status
     * show items quantity in the inventory
     */
    public function loadInventoryStats()
    {
        $this->data["stats"] = $this->inventoryModel->inventoryStats();
    }

    /**
     * @param Request $request a website that was selected to get category from database for loading all inventory form
     * @return Response to ajax to perform an action for the website
     * @throws \Throwable
     * @throws \Twig_Error_Loader
     * @throws \Twig_Error_Runtime
     * @throws \Twig_Error_Syntax
     */
    public function loadInventoryForm(Request $request)
    {
        $category = $request->get('category', '');

        //$category = "b8ab3ff957e3e02179b4";

        //$category = "e8c89f5796937f47fe3b";
        $response = array("status"=>400, "msg"=>"we were not able to create the form");

        $form = $this->fieldModel->form($category);
        //print_r($form);
        if ($this->fieldModel->hasError()) {
            $response["msg"]= $this->fieldModel->getError()[0];
        } else {
            $response["status"] = 200;
            $template = $this->twig->loadTemplate('Partial/Manager/inventoryForm.php');
            $this->data["forms"] = $form;
            $response["msg"] = $template->render($this->data);
        }

        return new Response(json_encode($response));
    }

    /**
     * @param Request $request a website that was selected to get status with name, type, length, and required
     * @return Response to ajax to perform an action for the website
     * @throws \Throwable
     * @throws \Twig_Error_Loader
     * @throws \Twig_Error_Runtime
     * @throws \Twig_Error_Syntax
     * add new category
     */
    public function addCategory(Request $request)
    {
        $response = array("status"=>400,"msg"=>"An error prevented us from adding the new category");
        $category = $request->get('category', '');
        $names = $request->get('name', array());
        $types = $request->get('type', array());
        $length = $request->get('length', array());
        $required = $request->get('required', array());

        $parser = new Parser($names, $length, $types, $required);

        if (!$parser->make()) {
            if ($parser->hasError()) {
                $response["msg"] = $parser->getError()[0];
            }
        } else {
            $attributes = $parser->getAttributes();


            //failure occured
            if (!$this->categoryModel->addCategory($category, $attributes)) {
                if ($this->categoryModel->hasError()) {
                    $response["msg"] = $this->categoryModel->getError()[0];
                }
            } else {
                //update the UI .. all is well
                $response["status"] = 200;
                $template = $this->twig->loadTemplate('Partial/Manager/category.php');
                $this->loadCategory();
                $response["msg"] = $template->render($this->data);
            }
        }
        return new Response(json_encode($response));
    }

    /**
     * display users in the block list
     */
    public function loadBlocklist()
    {
        $this->data["blocklist"] = $this->Blocklist->all();
    }

    /**
     * @param Request $request a website that was selected to get username and add to the blocklist
     * @return Response to ajax to perform an action for the website
     * @throws \Throwable
     * @throws \Twig_Error_Loader
     * @throws \Twig_Error_Runtime
     * @throws \Twig_Error_Syntax
     * add users to the block list
     */
    public function addBlocklist(Request $request)
    {
        $username = $request->get('username', '');
        $response = array("status"=>400,"msg"=>"An error prevented us from add the manager");

        //successful added?
        if (!$this->Blocklist->addBlocklist($username)) {
            if ($this->Blocklist->hasError()) {
                $response["msg"] = $this->Blocklist->getError()[0];
            }
        } else {
            $response["status"] = 200;

            $template = $this->twig->loadTemplate('Partial/Manager/blocklistList.php');
            $this->loadBlocklist();
            $response["msg"] = $template->render($this->data);
        }

        return new Response(json_encode($response));
    }
}
