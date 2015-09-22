<?php

namespace Bricks;

use Bricks\Element\AbstractElement;
use Bricks\Element\File;
use Bricks\Exception\ValidationException;

class Form extends Object
{
    /** @var array */
    protected $attributes = [];
    /** @var array */
    protected $elements = [];
    /** @var View */
    protected $view;

    /**
     * @param string $id
     * @param array $properties
     */
    public function __construct($id, $properties = [])
    {
        $properties = array_merge($properties, ['id' => $id]);
        if($this->view === null) {
            $this->view = new View;
        }

        parent::__construct($properties);
    }

    /**
     * @param AbstractElement $element
     */
    public function addElement($element)
    {
        if(!$element instanceof AbstractElement) {
            throw new \InvalidArgumentException('');
        }
        if($element instanceof File && !isset($this->attributes['enctype'])) {
            $this->attributes['enctype'] = 'multipart/form-data';
        }
        $this->elements[] = $element;
    }

    /**
     * @param AbstractElement[] $elements
     */
    public function addElements($elements)
    {
        foreach ($elements as $element) {
            $this->addElement($element);
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
     * @return View
     */
    public function getView()
    {
        return $this->view;
    }

    /**
     * @param array $data
     * @throws ValidationException
     */
    public function validate($data)
    {
        $errors = [];
        foreach($this->elements as $element) {
            /** @var AbstractElement $element */
            $name = $element->getAttribute('name');
            if(strpos('[]', $name) !== false) {
                $name = mb_substr($name, 0, -2);
            }
            $value = null;
            if(isset($data[$name])) {
                $value = $data[$name];
                if(is_array($value)) {
                    $valueCount = sizeof($value);
                    for($i = 0; $i < $valueCount; ++$i) {
                        $value[$i] = stripslashes($value[$i]);
                    }
                } else {
                    $value = stripslashes($value);
                }
            }
            if(!$element->validate($value)) {
                $errors[$name] = $element->getErrors();
            }
        }
        if(sizeof($errors) > 0) {
            throw new ValidationException($errors);
        }
    }

    /**
     * @param bool $returnContent
     * @return bool|string
     */
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