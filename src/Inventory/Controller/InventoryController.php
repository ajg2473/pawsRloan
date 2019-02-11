<?php
/**
 * Created by PhpStorm.
 * User: Superchang
 * Date: 12/12/18
 * Time: 14:13
 */

namespace Bolzen\Src\Inventory\Controller;

use Bolzen\Core\Controller\Controller;
use Bolzen\Core\Model\Model;
use Bolzen\Src\Inventory\Model\InventoryModel;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class InventoryController
 * @package Bolzen\Src\Inventory\Controller
 */
class InventoryController extends Controller
{
    private $InventoryModel;
    private $data;

    /**
     * InventoryController constructor.
     */
    public function __construct()
    {

        parent::__construct();
        $this->data = array();
        $this->InventoryModel = new InventoryModel();
    }

    /**
     * @param Request $request - request for category
     * @return Response - response in displaying data from request
     * @throws \Throwable
     * @throws \Twig_Error_Loader
     * @throws \Twig_Error_Runtime
     * @throws \Twig_Error_Syntax
     * list all requested items from category
     */
    public function listItems(Request $request)
    {
        $category = $request->get('category', '');
        //$category = "b8ab3ff957e3e02179b4";

        $this->data["inventoryListing"] = $this->InventoryModel->loadInventory($category);


        $template = $this->twig->loadTemplate('Shared/itemlist.php');
        return new Response($template->render($this->data));
        return new Response(json_encode($this->data));
    }
}
