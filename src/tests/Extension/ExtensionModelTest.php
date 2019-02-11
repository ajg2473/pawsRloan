<?php
/**
 * Created by PhpStorm.
 * User: Superchang
 * Date: 11/9/18
 * Time: 19:18
 */

namespace Bolzen\Src\tests\Extension;
require_once __DIR__.'../../dependencies.php';

use Bolzen\Src\Extension\Model\ExtensionModel;
use PHPUnit\Framework\TestCase;

class ExtensionModelTest extends TestCase
{
    private $model;

    public function  __construct(?string $name = null, array $data = [], string $dataName = '')
    {
        parent::__construct($name, $data, $dataName);
        $this->model = new ExtensionModel();
    }



    public function testAdd()
    {
        $itemId = "bunny";
        $date = "06/24/96";
        $status = $this->model->add($date, $itemId);
        self::assertEquals(true, $status);
    }

    public function testGet()
    {
        $itemId = "bunny";
            $info = $this->model->get($itemId);
            //print_r($info);
        self::assertEquals($itemId, $info["itemId"]);
    }

//    public function testUpdate()
//    {
//        $date = "linux";
//        $itemID = "101";
//        $status = $this->model->update($date, $itemID);
//        self::assertEquals(true, $status);
//    }
}
