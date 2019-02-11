<?php
/**
 * Created by PhpStorm.
 * User: Superchang
 * Date: 11/9/18
 * Time: 16:13
 */

namespace Bolzen\Src\OperationHour\Model;


use Bolzen\Core\Model\Model;

/**
 * Class HourModel
 * @package Bolzen\Src\OperationHour\Model
 */
class HourModel extends Model
{
    private $table;

    /**
     * HourModel constructor.
     */
    public function __construct()
    {
        parent::__construct();
        $this->table = "operationHours";
    }

    /**
     * @param string $day - request for day
     * @return bool
     * add new schedule by inserting day, hours open and hours close
     */
    public function add(string $day, string $openHour, string $closeHour):bool
    {
        $columns = "day, openHour, closeHour";
        $values = "?,?,?";
        $bindings = array($day, $openHour, $closeHour);

        $status = $this->database->insert($this->table, $columns, $values, $bindings);

        if(!$status) {
            $this->setError("Unable to add");
            return false;
        }

        if(!$this->save()) {
            $this->setError("Unable able to save");
            return false;
        }

        return true;
    }

    /**
     * @param string $day - request for day
     * @return array
     * select day and show hours on day selected
     */
    public function get(string $day) :array
    {
        if (empty($day)) {
            return array();
        }
        $where = "day = ?";
        $binding = array($day);
        $fetch = $this->database->select($this->table, $where, $binding);

        return $fetch === null ? array(): $fetch->fetch();
    }

    /**
     * @param string $openHour - request for new open hour
     * @param string $closeHour - request for new close hour
     * @param string $day - request for day
     * @return bool
     * update schedule with new day and hours
     */
    public function update(string $openHour, string $closeHour, string $day) :bool
    {
        $where = "day = ?";
        $set = "openHour = ?, closeHour = ?";
        $binding = array($openHour, $closeHour, $day);
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