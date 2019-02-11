<?php
/**
 * Created by PhpStorm.
 * User: aaronkelly
 * Date: 11/9/18
 * Time: 12:46 PM
 */

namespace Bolzen\Src\Category\Model;

use Bolzen\Core\Database\Database;
use Bolzen\Core\Model\Model;
use Bolzen\Src\Service\Attribute\Attribute;
use Bolzen\Src\Table\Model\TableModel;

class CategoryModel extends Model
{
    private $table;

    /**
     * CategoryModel constructor.
     */
    public function __construct()
    {
        parent::__construct();
        $this->table="Category";
    }

    public function getCategory(string $name):array
    {
        $where = "name = ?";
        $bindings = array($name);
        $res = $this->database->select($this->table, $where, $bindings);

        if ($res===null) {
            return array();
        }

        return $res->fetch();
    }

    /**
     * @param string $name - one of the column to be search for
     * @return bool - decide whether the name is exists
     */
    public function hasCategory(string $name):bool
    {
        if (empty($name)) {
            $this->setError("name cannot be empty");
            return false;
        }

        $where = "name = ?";
        $bindings = array($name);
        $status = $this->database->select($this->table, $where, $bindings);

        return $status == null ? false : true;
    }

    public function all(): array
    {
        if (!empty($this->table)) {
            $results =  $this->database->select($this->table);

            return $results!==null ? $results->fetchAll() : array();
        } else {
            $this->setError("Variable table is empty.");
            return array();
        }
    }

    /**
     * @param $name - name of the data
     * @return bool - decide whether the execution of an insert SQL is success
     */
    public function addCategory(string $name, array $attributes):bool
    {
        if (empty($name)) {
            $this->setError("Name cannot be empty");
            return false;
        }

        //prevent duplicate
        if ($this->hasCategory($name)) {
            $this->setError("Table already exist");
            return false;
        }

        //attempt to create the table
        $tableModel = new TableModel();



        //we were not able to create the table hence don't continue
        if (!$tableModel->createTable($name, $attributes)) {
            $this->setError("An error occurred while attempted to create the table");
            return false;
        }

        $column = "name,categoryId";
        $values = "?,?";
        $bindings = array($name,$this->accessControl->newToken());
        $status = $this->database->insert($this->table, $column, $values, $bindings);

        if (!$status) {
            $this->setError("Not able to insert the data");
            $tableModel->removeTable($name);
            return false;
        }

        if (!$this->save()) {
            $this->setError("Not able to save the data");
            $tableModel->removeTable($name);
            return false;
        }

        return true;


        return true;
    }

    /**
     * @param string $categoryId - request for categoryID
     * @return string - display result in string
     * get name of the category selected by its category ID
     */
    public function getCategoryName(string $categoryId):string
    {

        $where = "categoryId = ?";
        $bindings = array($categoryId);
        $res = $this->database->select($this->table, $where, $bindings);

        if ($res===null) {
            return "";
        }

        return $res->fetch()["name"];
    }
}