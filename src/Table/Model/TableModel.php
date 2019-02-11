<?php
/**
 * Created by PhpStorm.
 * User: root
 * Date: 11/9/18
 * Time: 11:39 AM
 */

namespace Bolzen\Src\Table\Model;

use Bolzen\Core\Model\Model;
use Bolzen\Src\Service\Attribute\Attribute;

/**
 * This class is used to add tables. This class is only intented to be used by the add new category
 * method
 * Class TableModel
 * @package Bolzen\Src\Table\Model
 */

class TableModel extends Model
{
    /**
     * This function is use to check whether a table exist.
     * Given that we will only create tables for new inventory categories
     * we will just check the category table if it exist in the category table
     * @param string $table
     * @return bool
     */
    public function hasTable(string $table)
    {
        if (empty($table)) {
            return false;
        }

        //allow only alphabets
        if (!ctype_alpha($table)) {
            $this->setError("Only alphabets are allowed!");
            return false;
        }

        $table = "category";
        $where = "name = ?";
        $bindings = array($table);
        $res = $this->database->select($table, $where, $bindings);

        return $res instanceof \PDOStatement ? true: false;
    }

    /**
     * This method adds a table to the datbase. Only use by category->add
     * @param string $tablename - the name of the table to add
     * @param array $attributes - the attributes to add to the table
     * @return bool - true if the table was successful added. False otherwise
     */
    public function createTable(string $tablename, array $attributes):bool
    {
        $columns = "";
        foreach ($attributes as $attribute) {
            $columns.=$this->createColumns($attribute).",";
        }

        $columns.=" itemId VARCHAR(60), name TEXT";

        $createTable = "CREATE table IF NOT EXISTS $tablename($columns )";

        $this->database->getPDO()->exec($createTable);

        //did it successful created?
        try {
            $sql = "SELECT 1 FROM $tablename LIMIT 1";
            $this->database->getPDO()->query($sql);
        } catch (\Exception $e) {
            return false;
        }

        return true;
    }

    /**
     * This function removes a table from the database. this is only use if the add Category->add method was failed
     * @param $tableName - the name of the table to  remove
     * @return bool - true if successful removed. False otherwise
     */
    public function removeTable($tableName):bool
    {
        $sql = "DROP TABLE $tableName;";
        return $this->database->getPDO()->exec($sql);
    }

    /**
     * This is a helper function that is used to create columns to add to the table
     * @param Attribute $attribute - an instance of Attribute
     * @return string - the sql for the column
     */
    private function createColumns(Attribute $attribute):string
    {
        $column = $attribute->getName();

        //get the type
        //type is int
        if ($attribute->getType() == "integer") {
            $column.= " INT (".$attribute->getLength().")";
        } elseif ($attribute->getType()==="string") {
            //if the user didnt specified the length that is the length is 0
            if ($attribute->getLength()==0) {
                $column.=" TEXT ";
            } else {
                $column.=" VARCHAR ( ".$attribute->getLength()." )";
            }
        } else {
            $column.=strtoupper($attribute->getType());
        }

        //required?
        if ((bool)$attribute->getIsRequired()) {
            $column.= "NOT NULL";
        }

        return $column;
    }
}