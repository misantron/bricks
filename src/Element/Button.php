<?php

namespace Bricks\Element;

class Button extends Element
{
    protected $attributes = ['type' => 'submit', 'value' => 'Submit'];

    public function __construct($label = 'Submit', $type = '', $properties = null)
    {
        if(!empty($type)) {
            $properties['type'] = $type;
        }
        if(empty($properties['value'])) {
            $properties['value'] = $label;
        }
        parent::__construct('', '', $properties);
    }
}