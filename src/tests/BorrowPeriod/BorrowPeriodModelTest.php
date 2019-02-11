<?php
/**
 * Created by PhpStorm.
 * User: Superchang
 * Date: 11/9/18
 * Time: 21:41
 */

namespace Bolzen\Src\tests\BorrowPeriod;
require_once __DIR__.'../../dependencies.php';


use Bolzen\Src\BorrowPeriod\Model\BorrowPeriodModel;
use PHPUnit\Framework\TestCase;

class BorrowPeriodModelTest extends TestCase
{
    private $BorrowPeriodModel;
    private $item;
    private $duration;

   public function __construct(string $name = null, array $data = [], string $dataName = '')
   {
       parent::__construct($name, $data, $dataName);
       $this->BorrowPeriodModel = new BorrowPeriodModel();
   }

    public function testAddBorrowPeriod()
    {
        $item = "boba";
        $duration = "10";
        $status = $this->BorrowPeriodModel->add($item, $duration);

        self::assertEquals(true, $status);
    }

    public function testGetBorrowPeriod()
    {
        $item = "boba";
        $fetch = $this->BorrowPeriodModel->get($item);
        self::assertEquals($item, $fetch["item"]);
    }
}
