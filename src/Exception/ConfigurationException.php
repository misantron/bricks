<?php

namespace Bricks\Exception;

/**
 * Class ConfigurationException
 * @package Bricks\Exception
 */
class ConfigurationException extends \InvalidArgumentException
{
    /**
     * @param string $message
     * @param int $code
     * @param \Throwable|null $previous
     */
    public function __construct(string $message, int $code = 0, \Throwable $previous = null)
    {
        $message = 'Configuration error: ' . $message;

        parent::__construct($message, $code, $previous);
    }
}