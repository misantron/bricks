<?php

namespace Bricks\Element;

class Textarea extends Element
{
    protected $attributes = ['rows' => 3];

    public function render()
    {
        echo '<textarea'.$this->getAttributes(['value']).'>';
        if(isset($this->attributes['value']) && !empty($this->attributes['value'])) {
            echo htmlspecialchars($this->attributes['value']);
        }
        echo '</textarea>';
    }
}