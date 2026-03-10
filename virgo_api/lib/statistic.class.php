<?php

/**
 * Description of statistic
 * @author marcinw
 */
class Statistic implements \JsonSerializable
{

    protected $id;
    protected $object;
    protected $rent;
    protected $location;
    protected $quartersOrStreet;
    protected $date;
    protected $priceFrom;
    protected $priceTo;
    protected $areaFrom;
    protected $areaTo;
    protected $quantity;

    public function __construct(\stdClass $objData)
    {
        foreach (get_object_vars($objData) as $name => $value) {
            if (property_exists($this, $name)) {
                $this->$name = $value;
            }
        }
    }

    public function __call($name, $arguments)
    {
        if (substr($name, 0, 3) === 'get') {
            $name = lcfirst(substr($name, 3, strlen($name) - 3));
            if (property_exists($this, $name)) {
                return $this->$name;
            }
            return null;
        } else if (substr($name, 0, 3) === 'set' && reset($arguments) !== null) {
            $name = lcfirst(substr($name, 3, strlen($name) - 3));
            if (property_exists($this, $name)) {
                $this->$name = reset($arguments);
            }
            return $this;
        }
    }
    
    public function jsonSerialize()
    {
        $vars = get_object_vars($this);
        return $vars;
    }

}
