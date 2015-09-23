<?php

namespace Bricks\Validator;

class DateValidator extends Validator
{
    protected $message = '';

    public function validate($value)
    {
        try {
            new \DateTime($value);
            return true;
        } catch(\Exception $e) {
            return false;
        }
    }
}