<?php

namespace Bricks\Element;

class TextBox extends Element
{
    protected $attributes = ['rows' => 3];

    public function render()
    {
        echo '<textarea'.$this->renderAttributes(['value']).'>';
        if(isset($this->attributes['value']) && !empty($this->attributes['value'])) {
            echo htmlspecialchars($this->attributes['value']);
        }
        echo '</textarea>';
    }
}