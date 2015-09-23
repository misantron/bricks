<?php

namespace Bricks\Validator;

class UrlValidator extends Validator
{
    protected $message = '';

    public function validate($value)
    {
        if(filter_var($value, FILTER_VALIDATE_URL) === false) {
            return false;
        }
        return true;
    }
}