<?php

namespace Bricks\Validator;

class NumericValidator extends Validator
{
    protected $message = '';

    public function validate($value)
    {
        return is_numeric($value);
    }
}