<?php

namespace Bricks\Validator;

class MaxLengthValidator extends LengthValidator
{
    protected $message = '';

    public function validate($value)
    {
        return mb_strlen($value) <= $this->length;
    }
}