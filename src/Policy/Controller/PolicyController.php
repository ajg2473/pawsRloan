<?php
/**
 * Created by PhpStorm.
 * User: root
 * Date: 12/12/18
 * Time: 4:09 PM
 */

namespace Bolzen\Src\Policy\Controller;


use Bolzen\Core\Controller\Controller;
use Bolzen\Src\Policy\Model\PolicyModel;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class PolicyController
 * @package Bolzen\Src\Policy\Controller
 * policy controller class
 */
class PolicyController extends Controller
{
    private $policyModel;

    /**
     * PolicyController constructor.
     */
    public function __construct()
    {
        parent::__construct();
        $this->policyModel = new PolicyModel();
    }

    /**
     * @param Request $request - request for categoryID
     * @return Response - display policy from requested categoryID
     * display policy using query from model
     */
    public function viewPolicy(Request $request)
    {
        $categoryId = $request->get('categoryid', '');
        $response = "We were not able to load the requested policy";

        $policy = $this->policyModel->getPolicy($categoryId);

        if ($this->policyModel->hasError()) {
            $response = $this->policyModel->getError()[0];
        } else {
            if (!empty($policy)) {
                $response = $policy["policy"];
            }
        }
        return new Response($response);
    }
}