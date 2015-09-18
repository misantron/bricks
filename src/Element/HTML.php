<?php

namespace Bricks\Element;

class HTML extends Element
{
    public function __construct($content)
    {
        parent::__construct('', '', ['value' => $content]);
    }

    public function render()
    {
        echo $this->attributes['value'];
    }
}