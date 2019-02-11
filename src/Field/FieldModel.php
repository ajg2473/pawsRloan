<?php
/**
 * Created by PhpStorm.
 * User: root
 * Date: 12/6/18
 * Time: 8:27 AM
 */

namespace Bolzen\Src\Field;

use Bolzen\Core\Model\Model;
use Bolzen\Src\Category\Model\CategoryModel;

/**
 * Class FieldModel
 * @package Bolzen\Src\Field
 */
class FieldModel extends Model
{

    private $convert;
    private $categoryModel;

    /**
     * FieldModel constructor.
<<<<<<< HEAD
=======
     * Set up array with string, integer and text
     * Start CategoryModel method
>>>>>>> be0cb265b451315bd05f1fbfadcf3884810f6d14
     */
    public function __construct()
    {
        parent::__construct();
        $this->convert = array("varchar"=>"string","int"=>"integer","text"=>"string");
        $this->categoryModel = new CategoryModel();
    }

    /**
     * @param string $tablename
<<<<<<< HEAD
     * @return array
=======
     * @return array for list of tablename that is available from database
>>>>>>> be0cb265b451315bd05f1fbfadcf3884810f6d14
     */
    public function columns(string $tablename):array
    {
        if (empty($tablename)) {
            $this->setError("tablename cannot be empty");
            return array();
        }

        $sql = "SELECT COLUMN_NAME FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_SCHEMA = ? AND TABLE_NAME = ? ";
        $bindings = array($this->database->getDatabaseName(), $tablename);

        return $this->database->genericSqlQueryBuilder($sql, $bindings)->fetchAll();
    }

    /**
<<<<<<< HEAD
     * @param string $column
     * @param string $table
     * @return string
=======
     * @param string $column a name for each column
     * @param string $table a name of table
     * @return string with a type of data
>>>>>>> be0cb265b451315bd05f1fbfadcf3884810f6d14
     */
    public function getType(string $column, string $table):string
    {
        $info = $this->getColumnTypeInfo($column, $table);
        if (empty($info)) {
            $this->setError("Not able to fetch the type");
            return "";
        }

        $info = $info[0];
        $info = strtolower($info);

        if (isset($this->convert[$info])) {
            return $this->convert[$info];
        }
        return $info;
    }

    /**
<<<<<<< HEAD
     * @param string $column
     * @param string $table
     * @return array
=======
     * @param string $column a name for each column
     * @param string $table a name of table
     * @return array list of array for column type
>>>>>>> be0cb265b451315bd05f1fbfadcf3884810f6d14
     */
    private function getColumnTypeInfo(string $column, string $table):array
    {
        if (empty($column) || empty($table)) {
            $this->setError("column  or table cannot be empty");
            return array();
        }

        $sql = $this->filterSQL("COLUMN_TYPE");
        $bindings = array($table,$column);

        $info = $this->database->genericSqlQueryBuilder($sql, $bindings)->fetch()['COLUMN_TYPE'];


        if (strpos($info, '(') !== false) {
            str_replace(")", "", $info);
            $info = explode("(", $info);
        }

        if (is_string($info)) {
            $info = array($info);
        }

        return $info;
    }

    /**
<<<<<<< HEAD
     * @param string $column
     * @param string $table
     * @return bool
=======
     * @param string $column a name for each column
     * @param string $table a name of table
     * @return bool if column and table are not empty and is null
>>>>>>> be0cb265b451315bd05f1fbfadcf3884810f6d14
     */
    public function isRequired(string $column, string $table)
    {
        if (empty($column) || empty($table)) {
            $this->setError("column name and table name cannot be empty");
            return false;
        }

        $sql = $this->filterSQL("IS_NULLABLE");
        $bindings = array($table,$column);

        $result = $this->database->genericSqlQueryBuilder($sql, $bindings)->fetch()['IS_NULLABLE'];
        $result = strtolower($result);
        return $result === "no" ? true : false;
    }

<<<<<<< HEAD
    /**
     * @param string $column
     * @param string $table
     * @return int
=======

    /**
     * @param string $column a name for each column
     * @param string $table a name of table
     * @return int a number of integer that was return for the result from the database
>>>>>>> be0cb265b451315bd05f1fbfadcf3884810f6d14
     */
    public function getLength(string $column, string $table):int
    {
        $info = $this->getColumnTypeInfo($column, $table);
        if (empty($info)) {
            $this->setError("Not able to fetch the type");
            return 0;
        }

        if (count($info)!==2) {
            return 0;
        }

        return (int)$info[1];
    }

    /**
<<<<<<< HEAD
     * @param string $column
     * @param string $table
     * @return bool
=======
     * @param string $column a name for each column
     * @param string $table a name of table
     * @return bool if a result for a number is greater than 0
>>>>>>> be0cb265b451315bd05f1fbfadcf3884810f6d14
     */
    public function needLength(string $column, string $table) :bool
    {
        return $this->getLength($column, $table) > 0 && $this->getType($column, $table)!=="";
    }

    /**
<<<<<<< HEAD
     * @param string $target
     * @return string
=======
     * @param string $target a column name
     * @return string that is a sql
>>>>>>> be0cb265b451315bd05f1fbfadcf3884810f6d14
     */
    private function filterSQL(string $target)
    {
        $database = $this->database->getDatabaseName();
        $sql = "SELECT $target FROM INFORMATION_SCHEMA.COLUMNS 
                WHERE TABLE_SCHEMA = '$database'  AND table_name = ? AND COLUMN_NAME = ?;";

        //echo $sql;
        return $sql;
    }

    /**
<<<<<<< HEAD
     * @param string $categoryId
     * @return array
=======
     * @param string $categoryId is a categoryId column name
     * @return array a list data type for column name, type, length, how many length and required
>>>>>>> be0cb265b451315bd05f1fbfadcf3884810f6d14
     */
    public function form(string $categoryId):array
    {

        $table = $this->categoryModel->getCategoryName($categoryId);
        if (empty($table)) {
            $this->setError("Invalid category id supplied");
            return array();
        }

        $formType = array("string"=>"text","integer"=>"number","year"=>"number");

        $res = array();

        foreach ($this->columns($table) as $column) {
            $column = $column['COLUMN_NAME'];

            $type = $this->getType($column, $table);
            if (isset($formType[$type])) {
                $type = $formType[$type];
            }
            $length = $this->getLength($column, $table);
            $needLength = $this->needLength($column, $table);
            $required = $this->isRequired($column, $table);
            $struct = array
            ("name"=>$column, "type"=>$type, "length"=>$length, "needLength"=>$needLength,"required"=>$required);

            array_push($res, $struct);
        }

        return $res;
    }

}