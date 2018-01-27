<?php

namespace Bricks\Validation;

use Bricks\Exception\ValidationException;

/**
 * Class Validator
 * @package Bricks\Validation
 */
class Validator extends \Valitron\Validator
{
    protected $validUrlPrefixes = [
        'http://',
        'https://',
        'ftp://',
    ];

    /**
     * @return bool
     * @throws ValidationException
     */
    public function validate()
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