<?php

namespace Bricks\Validator;

class MinLengthValidator extends LengthValidator
{
    protected $message = '';

    public function validate($value)
    {
        return mb_strlen($value) >= $this->length;
    }
}