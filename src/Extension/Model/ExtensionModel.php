<?php
/**
 * Created by PhpStorm.
 * User: Superchang
 * Date: 11/9/18
 * Time: 15:16
 */

namespace Bolzen\Src\Extension\Model;

use Bolzen\Core\Model\Model;

class ExtensionModel extends Model
{
    private $table ;

    /**
     * ExtensionModel constructor.
     */
    public function __construct()
    {
        parent::__construct();
        $this->table = "extension";
    }

    /**
     * @param string $extensionDate - request for date of extension
     * @param string $itemId - request for which itemID to extend
     * @return bool - true if itemID is valid, false otherwise
     */
    public function add(string $extensionDate, string $itemId):bool
    {
        if (!$this->isDate($extensionDate)) {
            $this->setError("Please enter a valid date");
            return false;
        }
        $columns = "extensionDate, itemId";
        $values = "?,?";
        $bindings = array($extensionDate, $itemId);

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
     * @param string $itemId - display itemID
     * @return array - display array of itemID description from table
     */
    public function get(string $itemId) :array
    {
        if (empty($itemId)) {
            return array();
        }
        $where = "itemId = ?";
        $binding = array($itemId);
        $fetch = $this->database->select($this->table, $where, $binding);

        return $fetch === null ? array(): $fetch->fetch();
    }
    /**
     * @param string $extensionDate - request new extension date
     * @param string $itemId - request itemID
     * @return bool - true if inputs are valid, false otherwise
     */
    public function update(string $extensionDate, string $itemId) :bool
    {
        if (!$this->isDate($extensionDate)) {
            $this->setError("Please enter a valid date");
            return false;
        }
        $where = "itemId = ?";
        $set = "extensionDate = ?";
        $binding = array($extensionDate, $itemId);
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

    /**
     * @param string $date - request for date
     * @return bool
     */
    private function isDate(string $date)
    {
        $dateTime = \DateTime::createFromFormat('m/d/Y', $date);

        $errors = \DateTime::getLastErrors();
        if (!empty($errors['warning_count'])) {
            return false;
        }

        return $dateTime !== false;
    }
}