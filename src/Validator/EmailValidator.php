<?php

namespace Bricks\Validator;

class EmailValidator extends Validator
{
    protected $message = '';

    public function validate($value)
    {
        if(filter_var($value, FILTER_VALIDATE_EMAIL) === false) {
            return false;
        }
        return true;
    }
}