<?php

namespace Bricks\Validator;

abstract class AbstractValidator
{
    protected $message;

    public function __construct($message = '')
    {
        if(!empty($message)) {
            $this->message = $message;
        }
    }

    public function getMessage()
    {
        return $this->message;
    }

    abstract public function validate($value);
}