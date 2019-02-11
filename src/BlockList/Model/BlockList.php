<?php
/**
 * Created by PhpStorm.
 * User: root
 * Date: 11/9/18
 * Time: 9:43 PM
 */

namespace Bolzen\Src\BlockList\Model;

use Bolzen\Core\Model\Model;

class BlockList extends Model
{
    private $table ;

    public function __construct()
    {
        parent:: __construct();
        $this->table = "blockList";
    }

    /**
     * @param string $userName - borrower's name
     * @param string $date_added - add a date (Month, Day, Year) when username becomes on the blocklist
     * @param int $categoryId - a number of category
     * @return bool - return true if the insert is successful
     */
    public function add(string $userName, string $date_added, string $categoryId):bool
    {

        if (empty($userName) || empty($date_added) || empty($categoryId)) {
            $this->setError(" all parameters are required");
            return false;
        }
        if (!$this->isDate($date_added)) {
            $this->setError("Invalid date supplied");
            return false;
        }

        $columns = "username,dateAdded,categoryId";
        $values = "?,?,?";
        $bindings = array($userName,$date_added,$categoryId);


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
     * Check if the given date is a valid date
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
     * @param string $user - Borrower's user id
     * @return array - if a borrower is listed, the name is in array l
     */
    public function hasBlockList(string $user) :bool
    {
        if (empty($user)) {
            return array();
        }
        $where = "username = ?";
        $binding = array($user);
        $fetch = $this->database->select($this->table, $where, $binding);

        return $fetch === null ? false : true;
    }
}