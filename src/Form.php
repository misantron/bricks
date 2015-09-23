<?php

namespace Bricks;

use Bricks\Element\Element;
use Bricks\Element\File;
use Bricks\Exception\ValidationException;

class Form extends Container
{
    /**
     * @param string $id
     * @param array $properties
     * @param array $elements
     */
    public function __construct($id, $properties = [], $elements = [])
    {
        $properties = array_merge($properties, ['id' => $id]);

        parent::__construct('form', $properties, $elements);
    }

    /**
     * @param Object $element
     */
    public function addElement($element)
    {
        if($element instanceof File && !isset($this->attributes['enctype'])) {
            $this->attributes['enctype'] = 'multipart/form-data';
        }
        parent::addElement($element);
    }

    /**
     * @param array $data
     * @return bool|void
     * @throws ValidationException
     */
    public function validate($data)
    {
        $elements = $errors = [];
        foreach($this->elements as $element) {
            if($element instanceof Container) {
                $elements = array_merge($elements, $element->getElements());
            } else {
                $elements[] = $element;
            }
        }
        foreach($elements as $element) {
            /** @var Element $element */
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
}