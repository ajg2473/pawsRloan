<?php
/**
 * Created by PhpStorm.
 * User: root
 * Date: 11/9/18
 * Time: 5:42 PM
 */

namespace Bolzen\Src\tests\Loan;
require_once __DIR__.'../../dependencies.php';

use Bolzen\Src\Loan\Model\LoanModel;
use PHPUnit\Framework\TestCase;

class LoanModelTest extends TestCase
{
    private $loanModel;
    private $itemID;

    public function __construct(?string $name = null, array $data = [], string $dataName = '')
    {
        parent::__construct($name, $data, $dataName);
        $this->loanModel = new LoanModel();
        $this->itemID= "123456idfjd";
    }

    public function testAddFailed()
    {
        $date = "random";
        $due = "random";
        $borrower = "ksc2650";
        $authorize = "aj494";
        $status = $this->loanModel->add($this->itemID, $date, $due, $borrower, $authorize);

        self::assertEquals(false, $status);
    }


    public function testAddSuccessful()
    {
        $date = "2/20/2018";
        $due = "2/20/2018";
        $borrower = "ksc2650";
        $authorize = "aj494";
        $status = $this->loanModel->add($this->itemID, $date, $due, $borrower, $authorize);

        self::assertEquals(true, $status);
    }

    public function testReturnFailed()
    {
        $itemID = "someIdDoesntExist";
        $return = $this->loanModel->return($itemID, "ksc2650");
        self::assertEquals(false, $return);
    }

    public function testReturnSuccessful()
    {
        $return = $this->loanModel->return($this->itemID, "ksc2650");
        self::assertEquals(true, $return);
    }

    public function testGet()
    {
        $retrived = $this->loanModel->get($this->itemID);
        self::assertEquals($this->itemID, $retrived["item_id"]);
    }

//
//    public function testGet()
//    {
//
//    }


}
