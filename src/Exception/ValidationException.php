<?php

namespace Bricks\Exception;

class ValidationException extends \Exception
{
    /** @var array */
    private $data;

    public function __construct($data)
    {
        $this->data = $data;

        parent::__construct('Validation error');
    }

    /**
     * @return array
     */
    public function getData()
    {
        return $this->data;
    }
}