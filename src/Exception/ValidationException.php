<?php

namespace Bricks\Exception;

/**
 * Class ValidationException
 * @package Bricks\Exception
 */
class ValidationException extends \Exception
{
    /**
     * @var array
     */
    private $data;

    /**
     * @param array $data
     */
    public function __construct(array $data)
    {
        parent::__construct('Data validation error');

        $this->data = $data;
    }

    /**
     * @return array
     */
    public function getData(): array
    {
        return $this->data;
    }
}