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
        $properties = array_merge($properties, ['label' => $label, 'name' => $name]);

        parent::__construct($properties);
    }

    public function render()
    {
        echo '<label>' . $this->getLabel() . '</label>';
        echo '<input' . $this->getAttributes() . '/>';
    }
}