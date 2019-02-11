<?php
/**
 * Created by PhpStorm.
 * User: root
 * Date: 11/9/18
 * Time: 2:03 PM
 */

namespace Bolzen\Src\Service\Attribute;

use http\Exception\InvalidArgumentException;

/**
 * This class serves as an abstract data structure of attribute
 * it is able to hold name, type length and required parameter
 * Class Attribute
 * @package Bolzen\Src\Service\Attribute
 */
class Attribute
{
    private $name;
    private $type;
    private $length;
    private $isRequired;

    private $allowedType = array("string","integer","date","decimal","time","year");


    /**
     * This function takes an attributes and assign the variables
     * @param string $name - the name of the desired field
     * @param string $type - the type of the field
     * @param string $length - the length of the field
     * @param string $isRequired -  a boolean expression on whether the field is required or not
     */
    public function __construct(string $name, string $type, string $length, string $isRequired)
    {
        //no special characters in the name
        if (!ctype_alpha($name)) {
            throw new \InvalidArgumentException("name cannot be empty or  contain special characters. Characters only");
        }


        //no special character in type
        if (!ctype_alpha($type)) {
            throw new \InvalidArgumentException("Type cannot contain special characters. 
            Please choose from the list of the drop down");
        }

        //is the type in the allowed list?
        $type = strtolower($type);
        if (!in_array($type, $this->allowedType)) {
            throw new \InvalidArgumentException("Invalid type supplied. Choose from the drop down list");
        }

        //user want to specified a length
        if (!empty($length)) {
            //length must be an int
            try {
                $length = (int)$length;
            } catch (\Exception $e) {
                throw new \InvalidArgumentException("Length must be an integer value");
            }


            //must be a positive value
            if ((int)$length<0) {
                throw new \InvalidArgumentException("Length must be a positive integer");
            }
        }

        if ($type==="integer" && $length ===0) {
            throw new \InvalidArgumentException("Length must be greater than 0 when using an integer type!");
        }

        //required must be a boolean
        try {
            $isRequired = (bool)$isRequired;
        } catch (\Exception $e) {
            throw new \InvalidArgumentException("is required field must be a boolean");
        }


        //all is well so go ahead and save it
        $this->name = $name;
        $this->type = $type;
        $this->length = $length;
        $this->isRequired = $isRequired;
    }


    /**
     * @return string - the name of the attribute
     */
    public function getName():string
    {
        return $this->name;
    }

    /**
     * @return string the type of the attribute
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * @return int - return the desired length for the field
     */
    public function getLength()
    {
        return $this->length;
    }

    /**
     * @return bool - return a boolean expression on whether a field is required
     */
    public function getIsRequired()
    {
        return $this->isRequired;
    }
}