<?php

namespace Bricks\Element;

use Bricks\Object;
use Bricks\Validator\AbstractValidator;

class AbstractElement extends Object
{
    /** @var string */
    protected $label;
    /** @var array */
    protected $errors = [];
    /** @var AbstractValidator[] */
    protected $validators = [];
    /** @var array */
    protected $attributes = [];

    public function __construct($label, $name = '', $properties = [], $validators = [])
    {
        $this->label = $label;
        if(!empty($name)) {
            $properties['name'] = $name;
        }
        if(sizeof($validators) > 0) {
            $this->validators = $validators;
        }

        parent::__construct($properties);
    }

    public function render()
    {
        echo '<input' . $this->renderAttributes() . ' />';
    }

    public function renderLabel()
    {
        $label = $this->label;
        if($label !== '') {
            echo '<label>' . $label . '</label>';
        }
    }

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

    public function getErrors()
    {
        return $this->errors;
    }
}