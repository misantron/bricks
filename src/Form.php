<?php

namespace Bricks;


use Bricks\Element\Element;
use Bricks\Element\File;

class Form extends Object
{
    protected $attributes = [];
    protected $elements = [];
    /** @var View */
    protected $view;

    public function __construct($id, $properties = [])
    {
        $properties = array_merge($properties, ['id' => $id]);
        if($this->view === null) {
            $this->view = new View();
        }

        parent::__construct($properties);
    }

    public function addElement($element)
    {
        if(!$element instanceof Element) {
            throw new \InvalidArgumentException('');
        }
        if($element instanceof File && !isset($this->attributes['enctype'])) {
            $this->attributes['enctype'] = 'multipart/form-data';
        }
        $this->elements[] = $element;
    }

    public function addElements($list)
    {
        foreach ($list as $element) {
            $this->addElement($element);
        }
    }

    public function getElements()
    {
        return $this->elements;
    }

    public function validate()
    {

    }

    public function render($returnContent = false)
    {
        $this->view->setForm($this);

        if($returnContent) {
            ob_start();
        }

        $this->view->render();

        if($returnContent) {
            $content = ob_get_contents();
            ob_end_clean();
            return $content;
        }

        return true;
    }
}