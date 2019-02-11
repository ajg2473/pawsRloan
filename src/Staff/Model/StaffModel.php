<?php
/**
 * Created by PhpStorm.
 * User: root
 * Date: 12/8/18
 * Time: 12:08 PM
 */

namespace Bolzen\Src\Staff\Model;


use Bolzen\Core\Model\Model;
use Bolzen\Src\Account\Model\AccountModel;
use Bolzen\Src\Loan\Model\LoanModel;
use Bolzen\Src\Service\Role\Role;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class StaffModel
 * @package Bolzen\Src\Staff\Model
 */
class StaffModel extends Model
{
    private $accountModel;
    private $loan;

    public function __construct()
    {
        parent::__construct();
        $this->accountModel = new AccountModel();
        $this->loan = new LoanModel();
    }

    /**
     * @return array
     * list all names
     */
    public function all()
    {
        $sql = "SELECT CONCAT(fName,' ',lName) as name,users.username FROM users 
                INNER JOIN userRoles ON userRoles.username = users.username 
                INNER JOIN roles ON userRoles.roleId = roles.roleId WHERE roles.name = ?";

        $bindings = array(Role::$STAFF);

        return $this->database->genericSqlQueryBuilder($sql, $bindings)->fetchAll();
    }

    /**
     * @param string $username
     * @return bool if addStaff has an account, not a manager, and has no error.
     * add staff to table
     */
    public function addStaff(string $username):bool
    {
        if (empty($username)) {
            $this->setError("Username cannot be empty");
            return false;
        }

        if (!$this->accountModel->hasAccount($username)) {
            $this->setError("We were not able to locate the username $username");
            return false;
        }

        if ($this->accountModel->isStaff($username)) {
            $this->setError("$username is already a staff");
            return false;
        }

        if (!$this->accessControl->assignRole($username, Role::$STAFF)) {
            $this->setError("We were not able to assign $username as manager");
            return false;
        }

        if (!$this->save()) {
            $this->setError("We were not able to save your change");
            return false;
        }
        return true;
    }

    /**
     * @param string $username
     * @return bool if the username is on the list, has an account and is not a manager and has no error.
     * remove staff to table
     */
    public function removeStaff(string $username):bool
    {
        if (empty($username)) {
            $this->setError("Username cannot be empty");
            return false;
        }

        if (!$this->accountModel->hasAccount($username)) {
            $this->setError("We were not able to locate the username $username");
            return false;
        }

        if (!$this->accountModel->isStaff($username)) {
            $this->setError("$username is not a staff");
            return false;
        }

        if (!$this->accessControl->stripRole($username, Role::$STAFF)) {
            $this->setError("We were not able to remove the staff role from $username");
            return false;
        }

        if (!$this->save()) {
            $this->setError("We were not able to save your change");
            return false;
        }

        return true;
    }

    /**
     * @return array with a list of row from database
     * show overdue items
     */
    public function getOverDueItems():array
    {


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
                dateReturned is NULL AND dueDate < NOW()";

                $sql = str_replace("tableName", $table, $sql);

                $res = $this->database->genericSqlQueryBuilder($sql, array())->fetchAll();

                if (!empty($res)) {
                    $list = array_merge($list, $res);
                }
            }
        }

        return $list;
    }

    /**
     * @return array with a list of row from database
     * list all check out items
     */
    public function listCheckOut()
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
                INNER JOIN tableName ON loan.itemId = tableName.itemId WHERE loan.dateReturned is NULL";


                $sql = str_replace("tableName", $table, $sql);

                $res = $this->database->genericSqlQueryBuilder($sql, array())->fetchAll();

                if (!empty($res)) {
                    $list = array_merge($list, $res);
                }
            }
        }


        return $list;
    }

    /**
     * @return array with a list of row from database
     * list all history
     */
    public function listHistory()
    {
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
                INNER JOIN tableName ON loan.itemId = tableName.itemId";


                $sql = str_replace("tableName", $table, $sql);

                $res = $this->database->genericSqlQueryBuilder($sql, array())->fetchAll();

                if (!empty($res)) {
                    $list = array_merge($list, $res);
                }
            }
        }


        return $list;
    }

    /**
     * @return array with a list of row from database
     * list all check in
     */
    public function listCheckIn():array
    {
        $tables = $this->database->select("Category");
        $result = array();
        $checkResult = array();

        if ($tables!==null) {

                $sql = "SELECT Category.name as itemType, item.itemName as itemName1, loan.dueDate as dateDue From Category
                						INNER join item ON item.categoryId = Category.categoryId
                						INNER join loan on loan.categoryId = item.categoryId
                						WHERE loan.dueDate < current_date";
                $sql1 = "SELECT Category.name as itemType From Category";


                $bindings = array();
                $res = $this->database->genericSqlQueryBuilder($sql1, $bindings)->fetchAll();

                array_push($result, $res);


            }

        return $result;
    }

    /**
     * view all history
     */
    public function getViewHistory()
    {
        $sql = "Select Category.name, testy.name from Category inner join testy on Category.categoryId = testy.categoryid;";

        $this->accountModel = new AccountModel();
    }
}