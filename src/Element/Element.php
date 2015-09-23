<?php

namespace Bricks\Element;

use Bricks\Object;
use Bricks\Validator\AbstractValidator;

abstract class Element extends Object
{
    /** @var string */
    protected $label;
    /** @var array */
    protected $errors = [];
    /** @var AbstractValidator[] */
    protected $validators = [];

    public function __construct($label, $name = '', $properties = [], $validators = [])
    {
        $this->label = $label;
        if(!empty($name)) {
            $properties['name'] = $name;
        }
        if(!empty($validators)) {
            $this->validators = $validators;
        }

        parent::__construct($properties);
    }

    public function render()
    {
        $this->renderLabel();
        echo '<input' . $this->renderAttributes() . ' />';
    }

    public function renderLabel()
    {
        $label = $this->label;
        if($label !== '') {
            echo '<label>' . $label . '</label>';
        }
    }

    /**
     * @return string
     */
    public function getLabel()
    {
        return $this->label;
    }

    /**
     * @param mixed $value
     * @return bool
     */
    public function validate($value)
    {
        $isValid = true;
        if(sizeof($this->validators) > 0) {
            foreach($this->validators as $validator) {
                if(!$validator->validate($value)) {
                    $this->errors[] = $validator->getMessage();
                    $isValid = false;
                }
            }
        }
        return $isValid;
    }

    /**
     * @return AbstractValidator[]
     */
    public function getValidators()
    {
        return $this->validators;
    }

    /**
     * @return array
     */
    public function getErrors()
    {
        return $this->errors;
    }
}