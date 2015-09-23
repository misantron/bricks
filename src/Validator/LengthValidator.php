<?php

namespace Bricks\Validator;

class LengthValidator extends Validator
{
    protected $message = '';
    protected $length;

    public function __construct($length, $message = '')
    {
        $this->length = (int)$length;
        parent::__construct($message);
    }

    public function validate($value)
    {
        return mb_strlen($value) === $this->length;
    }
}