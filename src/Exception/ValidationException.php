<?php

namespace Bricks\Exception;

class ValidationException extends \Exception
{
    private $data;

    public function __construct($data)
    {
        $this->data = $data;

        parent::__construct('Validation error');
    }

    public function getData()
    {
        return $this->data;
    }
}