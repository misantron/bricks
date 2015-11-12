<?php

namespace Bricks;

abstract class Object
{
    /** @var array */
    protected $attributes = [];

    /**
     * @param array $properties
     */
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
     * @param string $name
     * @return mixed|null
     */
    public function getAttribute($name)
    {
        return isset($this->attributes[$name]) ? $this->attributes[$name] : null;
    }

    /**
     * @return array
     */
    public function getAttributes()
    {
        return $this->attributes;
    }

    /**
     * @param array|string $ignore
     * @return string
     */
    public function renderAttributes($ignore = '')
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