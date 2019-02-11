<?php
/**
 * Created by PhpStorm.
 * User: root
 * Date: 12/9/18
 * Time: 12:52 AM
 */

namespace Bolzen\Src\Inventory\Model;


use Bolzen\Core\Model\Model;
use Bolzen\Src\Category\Model\CategoryModel;
use Bolzen\Src\Field\FieldModel;
use Symfony\Component\HttpFoundation\Request;

/**
 * Class InventoryModel
 * @package Bolzen\Src\Inventory\Model
 */
class InventoryModel extends Model
{
    private $categoryModel;
    private $fieldModel;

    /**
     * InventoryModel constructor.
     */
    public function __construct()
    {
        parent::__construct();
        $this->categoryModel = new CategoryModel();
        $this->fieldModel = new FieldModel();
    }

    /**
     * @return array - display array of inventory stat
     * display all inventory status
     */
    public function inventoryStats():array
    {
        $res = array();

        $tables = $this->database->select("Category");

        if ($tables!==null) {
            $tables = $tables->fetchAll();

            foreach ($tables as $table) {
                $table = $table["name"];

                $sql = "SELECT count(tableName.itemId) as total,Category.name,Category.categoryId,
                        (SELECT count(loan.itemId) FROM loan WHERE loan.itemId = tableName.itemId AND 
                        loan.dateReturned is NULL
                        ) as checkOut
                        FROM tableName INNER join item ON item.itemId = tableName.itemId
                        INNER JOIN Category ON Category.categoryId = item.categoryId
                        having count(tableName.itemId) > 0";
                $sql = str_replace("tableName", $table, $sql);
                //echo $sql;

                $result = $this->database->genericSqlQueryBuilder($sql, array());

                if ($result->rowCount() > 0) {
                    $res = array_merge($res, $result->fetchAll());
                }
            }
        }

        return $res;
    }

    /**
     * @param string $categoryId - request for categoryID
     * @return array - display array of results from table
     * load all inventory from category Id
     */
    public function loadInventory(string $categoryId):array
    {
        $where = "categoryId = ?";
        $bindings = array($categoryId);
        $tables = $this->database->select("Category", $where, $bindings);

        if ($tables !== null) {
            $tables = $tables->fetch();
            $table = $tables["name"];

            $sql = "SELECT * FROM tableName";
            $sql = str_replace("tableName", $table, $sql);
            $res = array();
            $info = $this->database->genericSqlQueryBuilder($sql, array())->fetchAll();

            if (!empty($info)) {
                $header = array();

                foreach ($info as $item) {
                    $count = 0;
                    $values = array();
                    foreach ($item as $key => $value) {
                        if ($count % 2 == 0) {
                            if (!in_array($key, $header)) {
                                array_push($header, $key);
                            }

                            array_push($values, $value);
                        }
                        $count+=1;

                    }

                    if (!empty($values)) {
                        array_push($res, $values);
                    }
                }

                return array_merge(array("contents"=>$res), array("headers"=>$header));
            }

        }
        return array();
    }

    /**
     * @param Request $request
     * @return bool
     * add in inventory by adding a new category ID
     */
    public function add(Request $request):bool
    {
        $category = $request->get('category', '');
        //$category = "b8ab3ff957e3e02179b4";
        if (empty($category)) {
            $this->setError("category id cannot be empty");
            return false;
        }

        $table = $this->categoryModel->getCategoryName($category);
        if (empty($table)) {
            $this->setError("Invalid category id supplied. We were not able to located the category");
            return false;
        }

        $sql = "INSERT INTO $table (";
        $count = 0;
        $bindings = array();
        $itemId = "";
        foreach ($this->fieldModel->columns($table) as $column) {
            $column = $column['COLUMN_NAME'];

            $field = $request->get($column);
            if ($field===null) {
                $this->setError("The parameter for $column is missing");
                return false;
            }

            $length = $this->fieldModel->getLength($column, $table);
            $needLength = $this->fieldModel->needLength($column, $table);
            $required = $this->fieldModel->isRequired($column, $table);

            if ($required && empty($field)) {
                $this->setError("The field $column cannot be empty");
                return false;
            }

            if ($needLength && strlen($field) > $length) {
                $this->setError("The field $column can only hold up to $length length");
                return false;
            }

            if ($column==="itemId") {
                $itemId = $field;
            }

            $sql.="$column,";
            $count++;
            array_push($bindings, $field);
        }

        $sql = rtrim($sql, ",");
        $sql.=")";
        $sql.="VALUES(";
        for ($i = 0; $i<$count; $i++) {
            $sql.="?,";
        }
        $sql = rtrim($sql, ",");
        $sql.=")";

        if (!$this->database->isSqlQuerySuccessful($sql, $bindings)) {
            $this->setError("We were not able to insert the request");
            return false;
        }

        $columns = "itemId,categoryId";
        $values = "?,?";
        $bindings = array($itemId,$category);
        if (!$this->database->insert("item", $columns, $values, $bindings)) {
            $this->setError("An error occurred when establishing the item mapping");
            return false;
        }

        if (!$this->save()) {
            $this->setError("We were not able to save your change");
            return false;
        }

        return true;
    }

}