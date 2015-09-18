<?php

namespace Bricks\Exception;

class UnknownPropertyException extends \Exception
{
    public function getName()
    {
        return 'Unknown Property';
    }
}