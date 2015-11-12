<?php

namespace Bricks\Element;

class Time extends Element
{
    public function __construct($label, $name, $properties = [], $validators = [])
    {
        $name .= '[]';
        parent::__construct($label, $name, $properties, $validators);
    }

    public function render()
    {
        $this->renderLabel();
        echo '<select' . $this->renderAttributes() . '>';
        for($i = 0; $i < 24; ++$i) {
            $value = str_pad((string)$i, 2, '0', STR_PAD_LEFT);
            echo '<option value="' . $value . '">' . $value . '</option>';
        }
        echo '</select>';
        echo '<span>:</span>';
        echo '<select' . $this->renderAttributes() . '>';
        for($i = 0; $i < 60; ++$i) {
            $value = str_pad((string)$i, 2, '0', STR_PAD_LEFT);
            echo '<option value="' . $value . '">' . $value . '</option>';
        }
        echo '</select>';
    }
}