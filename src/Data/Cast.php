<?php

namespace Bricks\Data;


use Bricks\Exception\ConfigurationException;
use Bricks\Helper\ArrHelper;
use Carbon\Carbon;

/**
 * Class Cast
 * @package Bricks\Data
 */
class Cast implements CastInterface
{
    /**
     * @var array
     */
    private $config;

    /**
     * @param array $config
     */
    private function __construct(array $config)
    {
        $this->config = ArrHelper::unflatten($config);
    }

    /**
     * @param array $config
     * @return static
     */
    public static function create(array $config)
    {
        return new static($config);
    }

    /**
     * @param array $data
     * @return array
     */
    public function execute(array $data): array
    {
        return $this->cast($data, $this->config);
    }

    /**
     * @param mixed $data
     * @param array $config
     * @return array
     */
    private function cast($data, array $config)
    {
        $casted = [];
        foreach ($data as $field => $value) {
            if (is_array($value) && !empty($value) && ArrHelper::isStrKeysArray($value)) {
                $casted[$field] = $this->cast($value, $config[$field]);
            } else {
                $method = $config[$field] ?? null;
                if (empty($method) || $value === null) {
                    $casted[$field] = $value;
                } else {
                    if (!method_exists($this, $method)) {
                        throw new ConfigurationException('unknown field type: ' . $method);
                    }
                    $casted[$field] = call_user_func([$this, $method], $value);
                }
            }
        }
        return $casted;
    }

    /**
     * @param string $value
     * @return Carbon
     */
    private function dateTime(string $value)
    {
        return Carbon::parse($value);
    }

    /**
     * @param array $value
     * @return array
     */
    private function intArray(array $value)
    {
        $array = array_map(function ($val) {
            return (int)$val;
        }, array_filter($value));

        return array_values($array);
    }

    /**
     * @param array $value
     * @return array
     */
    private function floatArray(array $value)
    {
        $array = array_map(function ($val) {
            return (float)$val;
        }, array_filter($value));

        return array_values($array);
    }

    /**
     * @param array $value
     * @return array
     */
    private function strArray(array $value)
    {
        $array = array_map(function ($val) {
            return (string)$val;
        }, array_filter($value));

        return array_values($array);
    }

    /**
     * @param string|int $value
     * @return int
     */
    private function integer($value)
    {
        return (int)$value;
    }

    /**
     * @param string|float $value
     * @return int
     */
    private function float($value)
    {
        return (float)$value;
    }

    /**
     * @param string|int $value
     * @return int
     */
    private function string($value)
    {
        return (string)$value;
    }

    /**
     * @param string|array $value
     * @return array
     */
    private function array($value)
    {
        return (array)$value;
    }

    /**
     * @param string|bool $value
     * @return bool
     */
    private function boolean($value)
    {
        return is_string($value) ? $value === 'true' : (bool)$value;
    }
}