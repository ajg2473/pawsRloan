<?php
/**
 * Created by PhpStorm.
 * User: Aaron Kelly
 * Date: 12/5/18
 * Time: 18:34
 */

namespace Bolzen\Src\Borrower\Model;

use Bolzen\Core\Model\Model;
use Bolzen\Src\Account\Model\AccountModel;

/**
 * Class BorrowerModel
 * @package Bolzen\Src\Borrower\Model
 * One of the account model is design for general people who has a normal access to the system
 * is Borrower role
 * where they can only view data from the database and no edit is available for them
 */

class BorrowerModel extends Model
{
    private $accountModel;


    /**
     * BorrowerModel constructor.
     */
    public function __construct()
    {
        parent::__construct();
        $this->accountModel = new AccountModel();
    }

    /**
     * A method checkOut where returns array when the table is exists
     * focus on the data where the item is currently available for return
     * as long as their due date is not expire
     * @return array
     */
    public function checkOut()
    {
        $list = array();

        $tables = $this->database->select("Category");

        if ($tables!==null) {
            $tables = $tables->fetchAll();
            foreach ($tables as $table) {
                $table = $table["name"];
                $sql = "SELECT date_loan as dateLoan,CONCAT(users.fName ,' ',users.lName) as borrower,tableName.name as itemName,
                Category.name as categoryName,loan.itemId FROM loan INNER JOIN users ON loan.borrower = users.universityId
                INNER JOIN Category ON loan.categoryId = Category.categoryId
                INNER JOIN tableName ON loan.itemId = tableName.itemId WHERE loan.dateReturned is NULL
                AND username = ?";

//                $sql = "SELECT date_loan as dateLoan,CONCAT(users.fName ,' ',users.lName) as borrower
//                        FROM loan INNER JOIN users ON loan.borrower = users.universityId ";

                $sql = str_replace("tableName", $table, $sql);

                $res = $this->database->genericSqlQueryBuilder($sql, array( $this->user->getUsername() ))->fetchAll();

                if (!empty($res)) {
                    $list = array_merge($list, $res);
                }
            }
        }

//        print_r($list);
//        exit;

        return $list;
    }

    /**
     * A method history where an array is return when the data for the history
     * is exists
     * @return array|string
     */
    public function history(){
        $list = array();

        $tables = $this->database->select("Category");

        if ($tables!==null) {
            $tables = $tables->fetchAll();
            foreach ($tables as $table) {
                $table = $table["name"];
                $sql = "SELECT date_loan as dateLoan,CONCAT(users.fName ,' ',users.lName) as borrower,tableName.name as 
                itemName,Category.name as categoryName,dateReturned as returned, loan.dueDate as due
                FROM loan INNER JOIN users ON loan.borrower = users.universityId
                INNER JOIN Category ON loan.categoryId = Category.categoryId
                INNER JOIN tableName ON loan.itemId = tableName.itemId WHERE users.username = ?";


                $sql = str_replace("tableName", $table, $sql);

                $res = $this->database->genericSqlQueryBuilder($sql, array($this->user->getUsername()))->fetchAll();

                if (!empty($res)) {
                    $list = array_merge($list, $res);
                }
            }
        }


        return $list;
    }

    /**
     * A method lateItems returns an array when any of the items are late for the due date
     * @return array
     */
    public function lateItems(){
        $list = array();

        $tables = $this->database->select("Category");

        if ($tables!==null) {
            $tables = $tables->fetchAll();
            foreach ($tables as $table) {
                $table = $table["name"];
                $sql = "SELECT dueDate as due,CONCAT(users.fName ,' ',users.lName) as borrower,tableName.name as itemName,
                Category.name as categoryName, DATEDIFF(NOW(),dueDate) as days,loan.date_loan as loanDate
                FROM loan INNER JOIN users ON loan.borrower = users.universityId 
                INNER JOIN Category ON loan.categoryId = Category.categoryId 
                INNER JOIN tableName ON loan.itemId = tableName.itemId AND 
                dateReturned is NULL AND dueDate < NOW() AND users.username = ?";

                $sql = str_replace("tableName", $table, $sql);

                $res = $this->database->genericSqlQueryBuilder($sql, array($this->user->getUsername()))->fetchAll();

                if (!empty($res)) {
                    $list = array_merge($list, $res);
                }
            }
        }

        return $list;
    }

    /**
     * A method policy where an array is return when a data for policy for each item is available
     * @return array
     */
    public function policy(){
        $tables = $this->database->select("Category");
        $result = array();

        if( $tables != null ){
            $sql = "SELECT loan.itemId as id, item_policy.name, Category.name as type, item_policy.policy as desctribe
                FROM item
                inner join loan
                inner join Category
                inner join item_policy
                WHERE loan.borrower is not null";

            $res = $this->database->genericSqlQueryBuilder($sql, array())->fetchAll();
            array_push($result, $res);
        }
        return $result;
    }

}