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
        if (!parent::validate()) {
            throw new ValidationException($this->errors());
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

    /**
     * @param array $rules
     * @param array $messages
     */
    public function rules($rules, $messages = [])
    {
        foreach ($rules as $ruleType => $params) {
            if (is_array($params)) {
                foreach ($params as $innerParams) {
                    if (!is_array($innerParams)) {
                        $innerParams = (array)$innerParams;
                    }
                    $fieldName = $innerParams[0];
                    $this->rule($ruleType, ...$innerParams);
                    if (isset($messages[$ruleType][$fieldName])) {
                        $this->message($messages[$ruleType][$fieldName]);
                    }
                }
            } else {
                $this->rule($ruleType, $params);
            }
        }
    }
}