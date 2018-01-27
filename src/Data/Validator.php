<?php

namespace Bricks\Data;

use Bricks\Exception\ValidationException;

/**
 * Class Validator
 * @package Bricks\Data
 */
class Validator extends \Valitron\Validator
{
    /**
     * @return bool
     * @throws ValidationException
     */
    public function validate(): bool
    {
        if (parent::validate() === false) {
            throw new ValidationException($this->_errors);
        }
        return true;
    }

    /**
     * @param array $data
     * @return static
     */
    public static function create(array $data = [])
    {
        return new static($data);
    }
}