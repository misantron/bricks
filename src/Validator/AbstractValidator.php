<?php

namespace Bricks\Validator;

abstract class AbstractValidator
{
    /** @var string */
    protected $message;

    /**
     * @param string $message
     */
    public function __construct($message = '')
    {
        if(!empty($message)) {
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