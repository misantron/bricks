<?php

namespace Bricks\Element;

class Textarea extends AbstractElement
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