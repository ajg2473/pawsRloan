<?php
/**
 * Created by PhpStorm.
 * User: Superchang
 * Date: 11/9/18
 * Time: 19:47
 */

namespace Bolzen\Src\tests\hour;
require_once __DIR__.'../../dependencies.php';

use Bolzen\Src\OperationHour\Model\HourModel;
use PHPUnit\Framework\TestCase;

class HourModelTest extends TestCase
{
    private $model;

    public function __construct(?string $name = null, array $data = [], string $dataName = '')
    {
        parent::__construct($name, $data, $dataName);
        $this->model = new HourModel();
    }

    public function testAdd()
    {
        $day = "Mon";
        $open = "";
        $close = "1700";
        $cal = $this->model->add($day, $open, $close);
        self::assertEquals(true, $cal);
    }

    public function testUpdateFail()
    {
        $day = "Mon";
        $open = "1800";
        $close = "2000";

        $stat = $this->model->update($day, $open, $close);
        self::assertEquals(false, $stat);
    }

    public function testGet()
    {
        $day = "Mon";
        $info = $this->model->get($day);


        self::assertEquals($day, $info["day"]);
    }
}
