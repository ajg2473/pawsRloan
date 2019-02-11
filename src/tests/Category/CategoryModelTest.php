<?php
/**
 * Created by PhpStorm.
 * User: aaronkelly
 * Date: 11/9/18
 * Time: 7:59 PM
 */

namespace Bolzen\Src\tests\Category;
require_once __DIR__.'../../dependencies.php';

use Bolzen\Src\Category\Model\CategoryModel;
use Bolzen\Src\Service\Attribute\Attribute;
use PHPUnit\Framework\TestCase;

class CategoryModelTest extends TestCase
{
    private $categoryModel;
    private $name;
    private $id;

    public function __construct(?string $name = null, array $data = [], string $dataName = '')
    {
        parent::__construct($name, $data, $dataName);
        $this->categoryModel = new CategoryModel();
        $name = "CategoryTest";
        $id = 1;
    }


    public function testGetCategory()
    {
        $name = "Category";
        $check = $this->categoryModel->getCategory($name);

        self::assertEquals(array(), $check);
    }

    public function testAddCategory_Success()
    {
        $name = "Random";
        $id = 1;
        $ara = array(new Attribute("name","string",12,"true"));
        $check = $this->categoryModel->addCategory($name, $ara);
        self::assertEquals(false, $check);
    }

    public function testHasCategory_Success()
    {
        $name = "Category";
        $check = $this->categoryModel->hasCategory($name);

        self::assertEquals(false, $check);
    }

    public function testLoadCategory()
    {
        $check = $this->categoryModel->all();

        $expected = array(array("name"=>"Aaron","id"=>1,0=>"Aaron",1=>1));

        self::assertEquals(count($expected) > 0, count($check) > 0);
    }
}
