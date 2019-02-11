<?php
/**
 * Created by PhpStorm.
 * User: Amina
 * Date: 11/9/2018
 * Time: 4:10 PM
 */

namespace Bolzen\Src\BorrowPeriod\Model;


use Bolzen\Core\Model\Model;

/**
 * Class BorrowPeriodModel
 * @package Bolzen\Src\BorrowPeriod\Model
 * One of the model class, extended from a Model interface, implements one
 * of the table named BorrowPeriod which contains all items with an item and a
 * duration indicated how long each one has since
 */
class BorrowPeriodModel extends Model
{
    private $table;

    /**
     * BorrowPeriodModel constructor.
     */
    public function __construct()
    {
        parent::__construct();
        $this->table = "borrowPeriod";
    }

    /**
     * @param string $item - one of the column field used to identify of naming items
     * @param int $duration - one of the column field, duration, used to specify how long
     * @return bool - return true when sql execution contains data,
     * otherwise false when no data is exists
     *
     * Add the given data, with parameters, into the BorrowPeriod table.
     */
    public function add( string $item, int $duration):bool {
        $column = "item, duration";
        $value = "?,?";
        $bindings = array($item, $duration);

        $status = $this->database->insert($this->table,$column, $value, $bindings);

        if(!$status){
            $this->setError("Not able to save");
            return false;
        }

        if(!$this->save()){
            $this->setError("Unable able to save");
            return false;
        }
        return true;
    }

    /**
     * @param string $item - name of an item
     * @return array - return an array of data in String
     *
     * Obtain data with name match to given data
     */
    public function get(string $item) :array
    {
        if (empty($item)) {
            return array();
        }
        $where = "item = ?";
        $binding = array($item);
        $fetch = $this->database->select($this->table, $where, $binding);

        return $fetch === null ? array(): $fetch->fetch();
    }

    /**
     * @param string $item - name of an item
     * @param string $duration - duration of an item how long it has since
     * @return bool - return of boolean
     *
     * Update the BorrowPeriod table and return boolean
     * based on its successful execution.
     */
    public function update(string $item, string $duration) :bool
    {
        $where = "item = ?";
        $set = "duration = ?";
        $binding = array($item, $duration);
        $update = $this->database->update($this->table, $set, $where, $binding);

        if (!$update) {
            $this->setError("Unable to update");
            return false;
        }

        if (!$this ->save()) {
            $this->setError("Unable able to save");
            return false;
        }

        return true;
    }

}