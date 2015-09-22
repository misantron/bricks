<?php

namespace Bricks\Element;

use Bricks\Object;

class Element extends Object
{
    protected $label;
    protected $weight = 1;
    protected $attributes = [];

    public function __construct($label, $name = '', $properties = [])
    {
        if(!empty($name)) {
            $properties['name'] = $name;
        }
        $this->label = $label;

        parent::__construct($properties);
    }

    public function render()
    {
        echo '<input' . $this->renderAttributes() . ' />';
    }

    public function renderLabel()
    {
        $label = $this->label;
        if($label !== '') {
            echo '<label>' . $label . '</label>';
        }
    }

    public function getLabel()
    {
        return $this->label;
    }
}