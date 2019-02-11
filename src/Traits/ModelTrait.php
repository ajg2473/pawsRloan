<?php
/**
 * Created by PhpStorm.
 * User: root
 * Date: 8/21/18
 * Time: 4:38 PM
 */

namespace Bolzen\Src\Traits;

trait ModelTrait
{

    protected $errors = array();

    /**
     * This function commit the change to the database
     * @param string $error
     */
    final public function setError(string $error)
    {
        array_push($this->errors, $error);
    }

    /**
     * This function savet he change to the database
     */
    final public function save():bool
    {
        return $this->database->commit();
    }

    /**
     * This function return all the errors
     * @return array
     */
    final public function getError():array
    {
        return $this->errors;
    }

    final public function hasError():bool
    {
        return count($this->errors) > 0;
    }

}

