<?php
/**
 * Created by PhpStorm.
 * User: Superchang
 * Date: 11/29/18
 * Time: 13:50
 */

namespace Bolzen\Src\Manager\Model;

use Bolzen\Core\Model\Model;
use Bolzen\Src\Account\Model\AccountModel;
use Bolzen\Src\Service\Role\Role;

/**
 * Class ManagerModel
 * @package Bolzen\Src\Manager\Model
 * manager model class
 *
 * ManagerModel allows to modify update, and delete of data from a table through the user interfaces
 */
class ManagerModel extends Model
{
    private $accountModel;

    public function __construct()
    {
        parent::__construct();
        $this->accountModel = new AccountModel();
    }

    /**
     * @return array
     * show all managers
     */
    public function all()
    {
        $sql = "SELECT CONCAT(users.fName, ' ', users.lName) as name ,users.username 
                from userRoles inner join users 
                on users.username = userRoles.username
                INNER JOIN roles on userRoles.roleId = roles.roleID
                WHERE roles.name = ?";

        $bindings = array(Role::$MANAGER);

        return $this->database->genericSqlQueryBuilder($sql, $bindings)->fetchAll();
    }

    /**
     * @param string $username
     * @return bool
     * delete managers from table
     */
    public function delete(string $username):bool
    {
        if (empty($username)) {
            $this->setError("Username cannot be empty");
            return false;
        }


        //do the user exist within the users table?
        if (!$this->accountModel->hasAccount($username)) {
            $this->setError("Invalid username supplied");
            return false;
        }

        //do the user already has the manager role? no duplicate come on man
        if (!$this->accessControl->hasRole(Role::$MANAGER, $username)) {
            $this->setError("This user is not a manager");
            return false;
        }

        //strip role
        if (!$this->accessControl->stripRole($username, Role::$MANAGER)) {
            $this->setError("An error prevented us from stripping the role");
            return false;
        }
        //can we save the change?
        if (!$this->save()) {
            $this->setError("An error prevented us from saving your change");
            return false;
        }

        return true;
    }

    /**
     * @param string $username
     * @return bool
     * add new manager to table
     */
    public function addManager(string $username):bool
    {

        if (!$this->accountModel->isManager() && !$this->accountModel->isAdmin()) {
            $this->setError("You are not authorize to use this feature");
            return false;
        }

        if (empty($username)) {
            $this->setError("Username cannot be empty");
            return false;
        }

        if (!$this->accountModel->hasAccount($username)) {
            $this->setError("Invalid username supplied");
            return false;
        }

        if ($this->accessControl->hasRole(Role::$MANAGER, $username)) {
            $this->setError("This user is already a manager");
            return false;
        }

        if (!$this->accessControl->assignRole($username, Role::$MANAGER)) {
            $this->setError("An error prevented us from assigned the user $username as manager");
            return false;
        }

        if (!$this->save()) {
            $this->setError("An error prevented us from saving your change");
            return false;
        }

        return true;
    }
}