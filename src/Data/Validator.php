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

    /**
     * @param array $rules
     */
    public function rules($rules)
    {
        foreach ($rules as $ruleType => $params) {
            if (is_array($params)) {
                foreach ($params as $innerParams) {
                    if (!is_array($innerParams)) {
                        $innerParams = (array)$innerParams;
                    }
                    array_unshift($innerParams, $ruleType);
                    call_user_func_array([$this, 'rule'], $innerParams);
                    $arguments = array_pop($innerParams);
                    if (is_array($arguments) && isset($arguments['message'])) {
                        $this->message($arguments['message']);
                    }
                }
            } else {
                $this->rule($ruleType, $params);
            }
        }
    }
}