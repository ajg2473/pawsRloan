<?php
/**
 * Created by PhpStorm.
 * User: root
 * Date: 11/9/18
 * Time: 9:41 PM
 */

namespace Bolzen\Src\tests\BlockList;
require_once __DIR__.'../../dependencies.php';


use Bolzen\Src\BlockList\Model\BlockList;
use PHPUnit\Framework\TestCase;

class BlockListModelTest extends TestCase
{
    private $categoryId;
    private $username;
    private $dateAdded;
    private $model;

    public function __construct(?string $name = null, array $data = [], string $dataName = '')
    {
        parent::__construct($name, $data, $dataName);
        $this->model = new BlockList();
        $this->username = "ksc2650";
        $this->dateAdded = "garbage";
        $this->categoryId = "100";
    }

    public function testAddFailure()
    {
        $status = $this->model->add($this->username,$this->dateAdded,$this->categoryId);
        self::assertEquals(false,$status);
    }

    public function testAddSuccess()
    {
        $this->dateAdded = "2/20/2018";
        $status = $this->model->add($this->username,$this->dateAdded,$this->categoryId);
        self::assertEquals(true,$status);
    }

    public function testHasBlockList()
    {
        $status = $this->model->hasBlockList($this->username);
        self::assertEquals(true,$status);
    }

}
