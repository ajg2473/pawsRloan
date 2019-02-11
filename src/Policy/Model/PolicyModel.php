<?php
/**
 * Created by PhpStorm.
 * User: aaronkelly
 * Date: 11/9/18
 * Time: 4:40 PM
 */

namespace Bolzen\Src\Policy\Model;

use Bolzen\Core\Model\Model;

class PolicyModel extends Model
{
    private $table;

    /**
     * PolicyModel constructor.
     * Setting a model for the policy table
     */
    public function __construct()
    {
        parent::__construct();
        $this->table = "item_policy";
    }


    /**
     * @param String $name - one of the column used to identify name
     * @param $value - one of the column used to describe name
     * @return array - one of the column to set and sort all data uniquely
     */
    public function getPolicy(string $categoryID):array
    {

        if (empty($categoryID)) {
            $this->setError("all parameter are required");
            return array();
        }

        $where="categoryId = ?";
        $binding=array($categoryID);
        $hold = $this->database->select($this->table, $where, $binding);

        if ($hold===null) {
            return array();
        }

        return $hold->fetch();
    }

    /**
     * This function checks if the policy exist
     * @param string $categoryID - the category of the policy to check
     * @return bool - true if the policy exist, false otherwise
     */
    public function hasPolicy(string $categoryID):bool
    {
        if (empty($categoryID)) {
            $this->setError("category id is required");
            return false;
        }

        $where = "categoryId = ?";
        $bindings = array($categoryID);
        $res = $this->database->select($this->table, $where, $bindings);

        return $res === null ? false : true;
    }

    /**
     * @param String $categoryId - id used to uniquely identify data
     * @param String $name - name of the data
     * @param String $policy - name of a policy
     * @return bool - decide whether the execution of adding a new policy is success
     */
    public function addPolicy(string $categoryId, string $name, string $policy): bool
    {


        if (empty($categoryId) || empty($name) || empty($policy)) {
            $this->setError(" all parameters are required");
            return false;
        }

        //prevent duplicate
        if ($this->hasPolicy($categoryId)) {
            $this->setError("Policy already exist");
            return false;
        }
        $column = "name, policy, categoryId";
        $values = "?,?,?";
        $binding = array($name, $policy, $categoryId);

        $res = $this->database->insert($this->table, $column, $values, $binding);

        if (!$res) {
            $this->setError("Executing insert statement failed");
            return false;
        }

        if (!$this->save()) {
            $this->setError("Setting save point for the insert statement failed");
            return false;
        }

        return true;
    }

    /**
     * @param String $categoryId - used to uniquely identify data
     * @param string $name - name of the data
     * @param string $policy - name of the policy
     * @return bool - decide whether the execution of updating data is success
     */
    public function updatePolicy(String $categoryId, string $name, string $policy): bool
    {
        if (empty($categoryId) || empty($name) || empty($policy)) {
            $this->setError(" all parameters are required");
            return false;
        }

        $set = "name = ?, policy = ?";
        $where = "categoryId = ?";

        $binding = array($name,$policy,$categoryId);
        $res = $this->database->update($this->table, $set, $where, $binding);

        if (!$res) {
            $this->setError("Executing update statement failed");
            return false;
        } elseif (!$this->save()) {
            $this->setError("Updating the table ".$this->table." failed during execution time");
            return false;
        }

        return true;
    }

    /**
     * @return array show all list of row from database about policy
     */
    public function loadPolicy():array
    {
        $info = $this->database->select($this->table);

        return $info === null ? array() : $info->fetchAll();
    }
}
