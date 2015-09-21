<?php

namespace Bricks;

abstract class Object
{
    public function __construct($properties = [])
    {
        if(is_array($properties) && sizeof($properties) > 0) {
            foreach ($properties as $name => $value) {
                $this->setAttribute($name, $value);
            }
        }
    }

    /**
     * @param string $name
     * @param mixed $value
     */
    public function setAttribute($name, $value)
    {
        if(!isset($this->attributes)) {
            $this->attributes = [];
        }
        $this->attributes[$name] = $value;
    }

    /**
     * @param array|string $ignore
     * @return string
     */
    public function getAttributes($ignore = '')
    {
        $str = '';
        if(!empty($this->attributes)) {
            if(!is_array($ignore)) {
                $ignore = [$ignore];
            }
            $attributes = array_diff(array_keys($this->attributes), $ignore);
            foreach($attributes as $key) {
                $str .=  ' ' .$key;
                if($this->attributes[$key] !== '') {
                    $str .= '="'.htmlspecialchars($this->attributes[$key], ENT_QUOTES).'"';
                }
            }
        }

        return $str;
    }
}