<?php

namespace Bricks\Validator;

abstract class Validator
{
    /** @var string */
    protected $message;

    /**
     * @param string|null $message
     */
    public function __construct($message = null)
    {
        if(is_string($message) && mb_strlen($message) > 0) {
            $this->message = $message;
        }
    }

    /**
     * @return string
     */
    public function getMessage()
    {
        return $this->message;
    }

    abstract public function validate($value);
}