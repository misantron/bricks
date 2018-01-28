<?php

namespace Bricks\Data;


use Bricks\Exception\ConfigurationException;
use Bricks\Exception\InvalidRequestException;
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
     * @param string|int $value
     * @return Carbon
     */
    private function dateTime($value): Carbon
    {
        if (is_string($value)) {
            return Carbon::parse($value);
        } elseif (is_int($value)) {
            return Carbon::now()->setTimestamp($value);
        }
        throw new InvalidRequestException('unexpected datetime format');
    }

    /**
     * @param array $value
     * @return array
     */
    private function intArray(array $value): array
    {
        $array = array_map(function ($val) {
            return $this->integer($val);
        }, array_filter($value));

        return array_values($array);
    }

    /**
     * @param array $value
     * @return array
     */
    private function floatArray(array $value): array
    {
        $array = array_map(function ($val) {
            return $this->float($val);
        }, array_filter($value));

        return array_values($array);
    }

    /**
     * @param array $value
     * @return array
     */
    private function strArray(array $value): array
    {
        $array = array_map(function ($val) {
            return $this->string($val);
        }, array_filter($value));

        return array_values($array);
    }

    /**
     * @param mixed $value
     * @return int
     */
    private function integer($value): int
    {
        return (int)$value;
    }

    /**
     * @param mixed $value
     * @return float
     */
    private function float($value): float
    {
        return (float)$value;
    }

    /**
     * @param mixed $value
     * @return string
     */
    private function string($value): string
    {
        return '' . $value;
    }

    /**
     * @param mixed $value
     * @return array
     */
    private function array($value): array
    {
        return (array)$value;
    }

    /**
     * @param mixed $value
     * @return bool
     */
    private function boolean($value): bool
    {
        return is_string($value) ? $value === 'true' : (bool)$value;
    }
}