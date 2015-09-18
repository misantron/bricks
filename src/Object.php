<?php

namespace Bricks;

use Bricks\Exception\UnknownMethodException;
use Bricks\Exception\UnknownPropertyException;

abstract class Object
{
    public function __construct($properties = [])
    {
        if(is_array($properties) && sizeof($properties) > 0) {
            foreach ($properties as $name => $value) {
                $this->setAttribute($name, $value);
            }
        }
        $this->init();
    }

    public function init()
    {

    }

    public function setAttribute($name, $value)
    {
        if(!isset($this->attributes)) {
            $this->attributes = [];
        }
        $this->attributes[$name] = $value;
    }

    public function getAttributes()
    {
        $attributes = [];
        foreach($this->attributes as $name => $value) {
            $str = $name;
            if($value !== '') {
                $str .= '="'.htmlspecialchars($value).'"';
            }
            $attributes[] = $str;
        }
        return implode(' ', $attributes);
    }
}