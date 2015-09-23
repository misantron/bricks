<?php

namespace Bricks\Element;

class HTML extends Element
{
    protected $content;

    /**
     * @param string $content
     */
    public function __construct($content = '')
    {
        if(!empty($content)) {
            $this->content = $content;
        }
    }

    public function render()
    {
        echo $this->content;
    }
}