<?php

namespace Bricks;

class Container extends Object
{
    /** @var string */
    protected $tag;
    /** @var Object[] */
    protected $elements = [];

    public function __construct($tag, $properties = [], $elements = [])
    {
        $this->tag = $tag;
        $this->addElements($elements);
        parent::__construct($properties);
    }

    /**
     * @param Object $element
     */
    public function addElement($element)
    {
        if(!$element instanceof Object) {
            throw new \InvalidArgumentException('Invalid element type');
        }
        $this->elements[] = $element;
    }

    /**
     * @param Object[] $elements
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
     * @return array
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
            /** @var Object $element */
            $element = $elements[$i];
            $element->render();
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