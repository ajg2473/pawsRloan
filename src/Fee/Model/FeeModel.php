<?php
/**
 * Created by PhpStorm.
 * User: Amina
 * Date: 11/9/2018
 * Time: 3:23 PM
 */

namespace Bolzen\Src\Fee\Model;

use Bolzen\Core\Model\Model;

class FeeModel extends Model
{
    private $table;

    /**
     * FeeModel constructor.
     */

    public function __construct()
    {
        parent::__construct();
        $this->table = "fees";
    }

    /**
     * @param string $category_Id - request for categoryID
     * @param int $fee - request for fee amount
     * @return bool - this method is bool, true if all imputs are valid, false otherwise
     * add new fee
     */

    public function add(string $category_Id, string $fee, string $rate):bool
    {

        if (empty($category_Id) || empty($rate) || empty($fee)) {
            $this->setError("All parameters are required!");
            return false;
        }

        $rate = strtolower($rate);

        if ($rate!=="day" && $rate!=="hour") {
            $this->setError("Rate must be hour or day");
            return false;
        }

        if (!$this->isCurrency($fee)) {
            $this->setError("Fee must be in money format");
            return false;
        }

        //update or delete?
        if (!$this->hasFee($category_Id)) {
            //no fees for this category hence add
            $column = "categoryId, fee, rate";
            $values = "?,?,?";


            $bindings = array($category_Id, $fee, $rate);

            $status = $this->database->insert($this->table, $column, $values, $bindings);

            if (!$status) {
                $this->setError("Not able to save");
                return false;
            }

            if (!$this->save()) {
                $this->setError("Unable able to save");
                return false;
            }

            return true;
        }

        //update
        return $this->updateFee($category_Id, $fee, $rate);
    }

    /**
     * @param string $category_Id - request for categoryID for update
     * @param string $fee - request for fee for update
     * @param string $rate - request for rate for update
     * @return bool - true if all inputs are valid, false otherwise
     * update fee by adding rate of changes by specific item
     */
    public function updateFee(string $category_Id, string $fee, string $rate)
    {
        if (empty($category_Id) || empty($rate) || empty($fee)) {
            $this->setError("All parameters are required!");
            return false;
        }

        $rate = strtolower($rate);

        if ($rate!=="day" && $rate!=="hour") {
            $this->setError("Rate must be hour or day");
            return false;
        }

        if (!$this->isCurrency($fee)) {
            $this->setError("Fee must be in money format");
            return false;
        }

        if (!$this->hasFee($category_Id)) {
            $this->setError("Not able to located the category");
            return false;
        }

        $set = "fee = ?, rate = ?";
        $where = "categoryId = ?";
        $bindings = array($fee,$rate,$category_Id);

        if (!$this->database->update($this->table, $set, $where, $bindings)) {
            $this->setError("We were not able to update the fee");
            return false;
        }

        if (!$this->save()) {
            $this->setError("We were not able to save your change");
            return false;
        }

        return true;
    }

    /**
     * @param string $categoryId - request for categoryID
     * @return bool - true if categoryID input is valid, false otherwise
     * boolean to show if the fee exist on specific categoryId
     */
    public function hasFee(string $categoryId)
    {
        if (empty($categoryId)) {
            return false;
        }

        $where = "categoryId = ?";
        $bindings = array($categoryId);
        $status = $this->database->select($this->table, $where, $bindings);

        return $status === null ? false : true;
    }

    private function isCurrency($number):bool
    {
        return preg_match("/^-?[0-9]+(?:\.[0-9]{1,2})?$/", $number);
    }

    /**
     * @param string $categoryId - request for categoryID
     * @return array - returns arrays of categoryID
     */
    public function get(string $categoryId): array
    {
        if (empty($categoryId)) {
            return array();
        }
        $where = "categoryId = ?";
        $binding = array($categoryId);
        $fetch = $this->database->select($this->table, $where, $binding);


        return $fetch === null ? array(): $fetch->fetch();
    }

    /**
     * @return array - array of all category id and its description
     * display all fees
     */
    public function all():array
    {
        $sql = "SELECT fees.categoryId,fee,rate,name FROM fees INNER JOIN Category 
                ON Category.categoryId = fees.categoryId";

        $bindings = array();

        return $this->database->genericSqlQueryBuilder($sql, $bindings)->fetchAll();
    }

}