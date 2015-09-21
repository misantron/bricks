<?php

namespace Bricks\Element;

class ListElement extends Element
{
    protected $options;

    public function __construct($label, $name, $options = [], $properties = [])
    {
        $this->options = $options;
        
        parent::__construct($label, $name, $properties);
    }
}