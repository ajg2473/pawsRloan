<?php
/**
 * Created by PhpStorm.
 * User: root
 * Date: 11/30/18
 * Time: 11:36 AM
 */

namespace Bolzen\Src\Service\Attribute;

use Bolzen\Core\Model\Model;

class Parser extends Model
{
    private $attributes;
    private $names;
    private $lengths;
    private $types;
    private $isRequireds;


    /**
     * Parser constructor.
     * @param array $names
     * @param array $lengths
     * @param array $types
     * @param array $isRequireds
     */
    public function __construct(array $names, array $lengths, array $types, array $isRequireds)
    {
        parent::__construct();
        $this->names = $names;
        $this->lengths = $lengths;
        $this->types = $types;
        $this->isRequireds = $isRequireds;
        $this->attributes = array();
    }

    /**
     * @return bool if names, lengths, and types are match and save information on name, types, and lengths
     */
    public function make():bool
    {
        if (empty($this->names) || empty($this->lengths) || empty($this->types)) {
            $this->setError("The parameters names, lengths and types cannot be empty");
            return false;
        }

        if (count($this->lengths)!==count($this->names) || count($this->lengths)!==count($this->types) ||
            count($this->isRequireds) > count($this->names)) {
            $this->setError("all objects must have the same amount of elements");
            return false;
        }

        for ($i = 0; $i < count($this->names); $i++) {
            $required = false;

            //this checkbox exist
            if (count($this->isRequireds) > $i) {
                $required = true;
            }

            $name = $this->names[$i];
            $type = $this->types[$i];
            $length = $this->lengths[$i];

            try {
                array_push($this->attributes, new Attribute($name, $type, $length, $required));
            } catch (\Exception $e) {
                $this->setError($e->getMessage());
                return false;
            }
        }

        return true;
    }

    /**
     * @return array a list of attributes
     */
    public function getAttributes()
    {
        return $this->attributes;
    }
}
