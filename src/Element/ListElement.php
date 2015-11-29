<?php

namespace Bricks\Element;

class ListElement extends Element
{
    /** @var array */
    protected $options;

    public function __construct($label, $name, $options = [], $properties = [], $validators = [])
    {
        $this->options = $options;
        
        parent::__construct($label, $name, $properties, $validators);
    }

    /**
     * @return array
     */
    public function getOptions()
    {
        return $this->options;
    }
}