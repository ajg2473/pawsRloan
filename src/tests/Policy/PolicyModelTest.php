<?php
/**
 * Created by PhpStorm.
 * User: root
 * Date: 11/9/18
 * Time: 7:07 PM
 */

namespace Bolzen\Src\tests\Policy;
require_once __DIR__.'../../dependencies.php';

use Bolzen\Src\Policy\Model\PolicyModel;
use PHPUnit\Framework\TestCase;

class PolicyModelTest extends TestCase
{
    private $policyModel;
    private $policyName;
    private $policy;
    private $categoryID;


    public function __construct(?string $name = null, array $data = [], string $dataName = '')
    {
        parent::__construct($name, $data, $dataName);
        $this->policyModel = new PolicyModel();
        $this->categoryID = "100";
        $this->policy = "SOME POLICY FOO FOO FOO FOO FOO FOO FOO FOO FOO";
        $this->policyName = "policy1";
    }



    public function testAddPolicy()
    {
        $status = $this->policyModel->addPolicy($this->categoryID, $this->policyName, $this->policy);


        self::assertEquals(true, $status);
    }

    public function testGetPolicy()
    {
        $fetch = $this->policyModel->getPolicy($this->categoryID);


        self::assertEquals($this->policyName, $fetch["name"]);
    }

    public function testUpdatePolicy()
    {
        $update = $this->policyModel->updatePolicy($this->categoryID, "new name", $this->policy);

        self::assertEquals(true, $update);
    }
}
