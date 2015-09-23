<?php

namespace Bricks;

use Bricks\Element\AbstractElement;
use Bricks\Element\Button;

class Container extends Object
{
    /** @var string */
    protected $tag;
    /** @var AbstractElement[] */
    protected $elements = [];
    /** @var array */
    protected $attributes = [];

    public function __construct($tag, $properties = [], $elements = [])
    {
        $this->tag = $tag;
        $this->addElements($elements);
        parent::__construct($properties);
    }

    /**
     * @param AbstractElement $element
     */
    public function addElement($element)
    {
        if(!$element instanceof AbstractElement) {
            throw new \InvalidArgumentException('Invalid element type');
        }
        $this->elements[] = $element;
    }

    /**
     * @param AbstractElement[] $elements
     */
    public function addElements($elements)
    {
        if(sizeof($elements) > 0) {
            foreach($elements as $element) {
                $this->addElement($element);
            }
        }
    }

    /**
     * @return AbstractElement[]
     */
    public function getElements()
    {
        return $this->elements;
    }

    /**
     * @param bool $returnContent
     * @return bool|string
     */
    public function render($returnContent = false)
    {
        if($returnContent) {
            ob_start();
        }

        echo '<' . $this->tag . $this->renderAttributes() . '>';

        $elements = $this->getElements();
        $elementsCount = sizeof($elements);

        for($i = 0; $i < $elementsCount; ++$i) {
            /** @var AbstractElement $element */
            $element = $elements[$i];

            if($element instanceof Button) {
                $element->render();
            } else {
                $element->renderLabel();
                $element->render();
            }
        }

        echo '</' . $this->tag . '>';

        if($returnContent) {
            $content = ob_get_contents();
            ob_end_clean();
            return $content;
        }

        return true;
    }
}