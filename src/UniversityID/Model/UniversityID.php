<?php
/**
 * Created by PhpStorm.
 * User: root
 * Date: 11/2/18
 * Time: 11:30 AM
 */

namespace Bolzen\Src\UniversityID\Model;

use Bolzen\Core\Model\Model;
use Bolzen\Src\Service\Tables\Tables;

class UniversityID extends Model
{

    private $uidLimit = 10;
    private $table;

    public function __construct()
    {
        parent::__construct();
        $this->table = "users";
    }

    public function isValid(string $uid):bool
    {
        if (empty($uid)) {
            return false;
        }

        $uid = (int)$uid;

        return is_int($uid) && strlen($uid)>=9 && strlen($uid) < $this->uidLimit;
    }

    public function hasUniversityIDHash(string $hashUniversityID)
    {
        if (empty($hashUniversityID)) {
            return false;
        }

        $where = "universityID = ?";
        $bindings = array($hashUniversityID);
        return $this->database->select($this->table, $where, $bindings) instanceof \PDOStatement;
    }

    public function hasUniversityID(string $uid)
    {
        if (!$this->isValid($uid)) {
            return false;
        }
        $where="universityID = ?";


        $uid = $this->getHashedUniversityID($uid);


        $columns = "universityID";
        $bindings = array($uid);

        $result = $this->database->select($this->table, $where, $bindings, $columns);

        return $result === null ? false : true;
    }

    public function getHashedUniversityID(string $plaintextUniversityID):string
    {

        return hash('sha256', $plaintextUniversityID);
    }

    public function getUniversityID(string $username = ""):string
    {
        //if the username is empty, try and get the username of the current
        //logged user. if user is not logged in, it is anonymous
        if (empty($username)) {
            $username = $this->user->getUsername();
        }


        $where = "username = ?";
        $columns = "universityID";

        $bindings = array($username);
        $result = $this->database->select($this->table, $where, $bindings, $columns);

        return $result!==null ? $result->fetch()['universityID'] : "";
    }

    public function getUniversityIDOwnerUsername(string $hashedUniversityID):string
    {
        if (empty($hashedUniversityID)) {
            return "";
        }

        $where = "universityID = ?";
        $bindings = array($hashedUniversityID);
        $columns = "username";
        $info = $this->database->select($this->table, $where, $bindings, $columns);

        return $info === 0 ? "": $info->fetch()["username"];
    }

    public function update(string $universityID)
    {
        if (empty($universityID)) {
            $this->setError("university id cannot be empty");
            return false;
        }


        if (!$this->isValid($universityID)) {
            $this->setError("Invalid university ID supplied");
            return false;
        }

        $universityID = $this->getHashedUniversityID($universityID);

        $username = $this->user->getUsername();
        $sql = "UPDATE users set universityId = ? WHERE username = ?";
        $bindings = array($universityID, $username);

        if (!$this->database->isSqlQuerySuccessful($sql, $bindings)) {
            $this->setError("We were not able to update your universityID");
            return false;
        }

        if (!$this->save()) {
            $this->setError("We were not able to save your change");
            return false;
        }

        return true;
    }
}
