<?php

namespace Bricks\Element;

class ListElement extends Element
{
    protected $options;

    public function __construct($label, $name, $options = [], $properties = [], $validators = [])
    {
        $this->options = $options;
        
        parent::__construct($label, $name, $properties, $validators);
    }

    public function getOptions()
    {
        return $this->options;
    }
}