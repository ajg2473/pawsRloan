<?php
/**
 * Created by PhpStorm.
 * User: Superchang
 * Date: 11/9/18
 * Time: 12:57
 */

namespace Bolzen\Src\Loan\Model;


use Bolzen\Core\Model\Model;
use Bolzen\Src\Category\Model\CategoryModel;
use Bolzen\Src\UniversityID\Model\UniversityID;

/**
 * This model is an abstract model that is used to interact with
 * the loan at the data layer level
 * Class LoanModel
 * @package Bolzen\Src\Loan\Model
 */

class LoanModel extends Model
{
    private $table ;
    private $categoryModel;
    private $universityIdModel;

    public function __construct()
    {
        parent::__construct();
        $this->table = "loan";
        $this->categoryModel = new CategoryModel();
        $this->universityIdModel = new UniversityID();

    }

    /**
     * This method add a new item to the loan table
     * @param string $item_id - the item to add
     * @param string $date_loan  -t the date the item was loaned
     * @param string $dueDate - the due date
     * @param string $borrower - the dce of the borrower
     * @param string $authorizedOut - the dce of the current staff on duty
     * @return bool - true if successful added, false otherwise
     */

    public function add(
        string $item_id,
        string $date_loan,
        string $dueDate,
        string $borrower,
        string $authorizedOut
    ):bool {

        if (!$this->isDate($date_loan) || !$this->isDate($dueDate)) {
            $this->setError("Please enter a valid date");
            return false;
        }
        //ensure that dates are valid date

        $columns = "item_id, date_loan, dueDate, borrower, authorizedOut";
        $values = "?,?,?,?,?";
        $bindings = array($item_id,$date_loan,$dueDate,$borrower,$authorizedOut);

        $status = $this->database->insert($this->table, $columns, $values, $bindings);

        if (!$status) {
            $this->setError("Not able to perform insert");
            return false;
        }

        if (!$this ->save()) {
            $this->setError("Unable able to save");
            return false;
        }

        return true;
    }

    /**
     * @param string $universityId - one of the column field used to identify a person's school id
     * @param string $categoryId - one of the column field used to link from one table to another
     * @param array $itemId - id for each name uniquely
     * @param array $dueDate - due date expected for an item to be return before
     * @return bool - return of boolean when the method done execution
     *
     * Loading the given data into the loan table and return boolean based on it execution
     * successful or not
     */
    public function loan(string $universityId, string $categoryId, array $itemId, array $dueDate):bool
    {

        if (empty($universityId) || empty($categoryId) || empty($itemId)) {
            $this->setError("All parameters are required");
            return false;
        }

        if (!$this->categoryModel->hasCategory($this->categoryModel->getCategoryName($categoryId))) {
            $this->setError("Invalid category id supplied");
            return false;
        }

        for ($i = 0; $i < count($itemId); $i++) {
            $item = $itemId[$i];
            $due = $dueDate[$i];

            $sql = "INSERT INTO loan(itemId,categoryId,dueDate,borrower,authorizedOut,date_loan) 
                    VALUES(?,?,?,?,?,NOW())";

            $authorized = $this->user->getUsername();
            $authorized = $this->universityIdModel->getUniversityID($authorized);
            $bindings = array($item,$categoryId,$due,$universityId,$authorized);

            if (!$this->database->isSqlQuerySuccessful($sql, $bindings)) {
                $this->setError("An error occurred while marking the item as loan");
                return false;
            }
        }

        //can we save the changes?
        if (!$this->save()) {
            $this->setError("An error occurred while saving your changes");
            return false;
        }

        return true;
    }

    /**
     * This method checks whether the date is a valid one
     * @param string $date
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

    /**
     * This retrived an item from the loan table
     * @param string $itemId - the id of the item to retrived
     * @return array - loan data
     */
    public function get(string $itemId) :array
    {
        if (empty($itemId)) {
            return array();
        }
        $where = "item_id = ?";
        $binding = array($itemId);
        $fetch = $this->database->select($this->table, $where, $binding);

        return $fetch === null ? array(): $fetch->fetch();
    }

    public function getAll() : array
    {
        $info = $this->database->select($this->table);
        return $info == null ?  array() : $info->fetchAll();
    }

    /**
     * This method update the loan table notifying that an item has been returned
     * @param string $itemId - the id of the item being returned
     * @param string $authorizedInBy - the dce of the staff worker on duty who checked in the item
     * @return bool
     */
    public function return(string $itemId) :bool
    {
//        $where = "item_id = ?";
//        $set = "authorizedIn = ?,dateReturned = ?";
//        $dateReturned = "NOW()";
//        $binding = array($authorizedInBy,$dateReturned,$itemId);
//        $update = $this->database->update($this->table, $set, $where, $binding);

        if (empty($itemId)) {
            $this->setError("item id cannot be empty");
            return false;
        }

        $sql = "UPDATE loan set authorizedIn = ?, dateReturned = NOW() WHERE itemId = ? and dateReturned is NULL";
        $universityId = $this->universityIdModel->getUniversityID($this->user->getUsername());
        $bindings = array($universityId, $itemId);
        $update = $this->database->isSqlQuerySuccessful($sql, $bindings);

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
     * @param string $categoryId
     * @return array
     * display all available items
     */
    public function showAvailableItems(string $categoryId):array
    {
        $table = $this->categoryModel->getCategoryName($categoryId);

        if (empty($table)) {
            $this->setError("Invalid category Id supplied");
            return array();
        }

        $sql = "SELECT * FROM tableName WHERE tableName.itemId NOT IN 
                (SELECT loan.itemId FROM loan WHERE dateReturned is NULL AND loan.categoryId = ?)";

        $sql = str_replace("tableName", $table, $sql);
        $bindings = array($categoryId);

        $res = $this->database->genericSqlQueryBuilder($sql, $bindings);

        if ($res->rowCount() ===0) {
            $this->setError("No Item for this category is current available for loan");
            return array();
        }

        return $res->fetchAll();
    }
}