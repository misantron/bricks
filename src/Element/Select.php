<?php

namespace Bricks\Element;

class Select extends ListElement
{
    protected $attributes = [];

    public function render()
    {
        if(isset($this->attributes['value'])) {
            if(!is_array($this->attributes['value'])) {
                $this->attributes['value'] = [$this->attributes['value']];
            }
        } else {
            $this->attributes['value'] = [];
        }
        if(!empty($this->attributes['multiple']) && strpos('[]', $this->attributes['name']) === false) {
            $this->attributes['name'] .= '[]';
        }
        echo '<select' . $this->renderAttributes(['value', 'selected']) . '>';
        foreach($this->getOptions() as $value => $title) {
            echo '<option value="' . htmlspecialchars($value, ENT_QUOTES) . '"';
            if(in_array($value, $this->attributes['value'])) {
                echo ' selected="selected"';
            }
            echo '>' . htmlspecialchars($title, ENT_QUOTES) . '</option>';
        }
        echo '</select>';
    }
}