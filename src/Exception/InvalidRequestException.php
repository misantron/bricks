<?php

namespace Bricks\Exception;

use Throwable;

/**
 * Class InvalidRequestException
 * @package Bricks\Exception
 */
class InvalidRequestException extends \RuntimeException
{
    public function __construct(string $message, int $code = 0, Throwable $previous = null)
    {
        $message = 'Invalid request: ' . $message;

        parent::__construct($message, $code, $previous);
    }
}