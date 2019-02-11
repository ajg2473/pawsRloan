<?php
/**
 * Created by PhpStorm.
<<<<<<< HEAD
 * User: root
 * Date: 11/9/18
 * Time: 8:40 PM
 */

namespace Bolzen\Src\tests\LateFee;
require_once __DIR__.'../../dependencies.php';


use Bolzen\Src\LateFee\Model\FeeModel;
use PHPUnit\Framework\TestCase;

class LateFeeModelTest extends TestCase
{

    private $lateModel;
    private $categoryId;
    private $fee;
    private $rate;

    public function __construct(?string $name = null, array $data = [], string $dataName = '')
    {
        parent::__construct($name, $data, $dataName);
        $this->lateModel = new FeeModel();

        $this->categoryId = "100";
        $this->fee = 1000000;
        $this->rate = 100002;
    }

    public function testAdd()
    {
        $status = $this->lateModel->add($this->categoryId, $this->fee, $this->rate);
        self::assertEquals(true, $status);
    }

    public function testGet()
    {

        $fetch = $this->lateModel->get("100");
        self::assertEquals(1000000, $fetch["fee"]);
    }

}
