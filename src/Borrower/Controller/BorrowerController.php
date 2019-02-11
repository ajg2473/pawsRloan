<?php
/**
 * Created by PhpStorm.
 * User: Aaron
 * Date: 11/28/2018
 * Time: 6:10 PM
 */

namespace Bolzen\Src\Borrower\Controller;


use Bolzen\Core\Controller\Controller;
use Bolzen\Src\Borrower\Model\BorrowerModel;
use Bolzen\Src\Policy\Model\PolicyModel;
use Symfony\Component\HttpFoundation\Request;

/**
 * Class BorrowerController
 * @package Bolzen\Src\Borrower\Controller
 * One of the controller used to grant a relationship between the Database Data Layer
 * and the user interface
 */
class BorrowerController extends Controller
{
    private $data;
    private $borrowerModel;
    private $policyModel;

    /**
     * BorrowerController constructor.
     */
    public function __construct()
    {
        parent::__construct();
        $this->borrowerModel = new BorrowerModel();
        $this->data = array();
        $this->policyModel = new PolicyModel();
    }

    public function loadPolicy()
    {
        $this->data["policies"] = $this->policyModel->loadPolicy();
    }

    /**
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     * @throws \Twig_Error_Loader
     * @throws \Twig_Error_Runtime
     * @throws \Twig_Error_Syntax
     *
     * A method index used to initially run the data as the web is loading
     * Requires no button is use
     */
    public function index(Request $request){
        $this->data["borrower"] = "yes";
        $this->data["histories"] = $this->borrowerModel->history();
        $this->data["overdue"] = $this->borrowerModel->lateItems();
        $this->data["checkout"] = $this->borrowerModel->checkOut();
        $this->loadPolicy();
        return $this->render($request, $this->data);
    }
}