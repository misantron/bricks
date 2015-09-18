<?php

namespace Bricks\Element;

use Bricks\Object;

abstract class Element extends Object
{
    protected $label;
    protected $weight = 1;
    protected $attributes = [];

    public function __construct($label, $name, $properties = [])
    {
        if(!empty($name)) {
            $properties['name'] = $name;
        }
        $this->setLabel($label);

        parent::__construct($properties);
    }

    public function render()
    {
        echo '<input ' . $this->getAttributes() . ' />';
    }

    public function renderLabel()
    {
        $label = $this->getLabel();
        if($label !== '') {
            echo '<label>' . $label . '</label>';
        }
    }

    public function getLabel()
    {
        return $this->label;
    }

    public function setLabel($label)
    {
        $this->label = $label;
    }
}