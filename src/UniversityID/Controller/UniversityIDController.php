<?php
/**
 * Created by PhpStorm.
 * User: root
 * Date: 11/2/18
 * Time: 11:30 AM
 */

namespace Bolzen\Src\UniversityID\Controller;


use Bolzen\Core\Controller\Controller;
use Bolzen\Src\UniversityID\Model\UniversityID;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class UniversityIDController
 * @package Bolzen\Src\UniversityID\Controller
 */
class UniversityIDController extends Controller
{
    private $universityID;

    /**
     * UniversityIDController constructor.
     */
    public function __construct()
    {
        parent::__construct();
        $this->universityID = new UniversityID();
    }

    /**
     * @param Request $request - update universityID
     * @return Response
     */
    public function update(Request $request)
    {
        $universityid = $request->get('university', '');
        //$universityid = "987654322";

        $response = array("status"=>400, "msg"=>"Error...Cannot update university ID");

        if (!$this->universityID->update($universityid)) {
            if ($this->universityID->hasError()) {
                $response["msg"] = $this->universityID->getError()[0];
            }
        } else {
            $response["status"] = 200;
            $response["msg"] = "successful update";
        }

        return new Response(json_encode($response));
    }

    /**
     * @param Request $request
     * @return Response
     */
    public function isVerify(Request $request)
    {
        $universityID = $request->get('universityid', '');
        $response = array("status"=>400,'msg'=>"Invalid University ID supplied");


        //id cannot be empty
        if (empty($universityID)) {
            $response['msg'] = "University ID cannot be empty";
        } else {
            //check to see whether the given id is valid
            $status = $this->universityID->hasUniversityID($universityID);

            //the given id is valid hence we will update the status to 200 otherwise we leave it unchanged
            if ($status) {
                $response["status"] = 200;
                $response["msg"] = "This university ID is valid";
            }
        }
        return new Response(json_encode($response));
    }
}