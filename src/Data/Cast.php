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
        foreach ($config as $key => $type) {
            $parts = explode('.', $key, 2);
            if (isset($parts[1])) {
                if (isset($this->config[$parts[0]]) && is_string($this->config[$parts[0]])) {
                    $this->config[$parts[0]] = [];
                }
                $this->config[$parts[0]][$parts[1]] = $type;
            } else {
                $this->config[$parts[0]] = $type;
            }
        }
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
        $casted = [];
        foreach ($data as $field => $value) {
            if (is_array($value) && ArrHelper::isStrKeysArray($value)) {
                foreach ($value as $key => $val) {
                    $method = $this->config[$field][$key] ?? null;
                    if (empty($method) || $val === null) {
                        $casted[$field][$key] = $val;
                    } else {
                        if (!method_exists($this, $method)) {
                            throw new ConfigurationException('unknown field type: ' . $method);
                        }
                        $casted[$field][$key] = call_user_func([$this, $method], $value);
                    }
                }
            } else {
                $method = $this->config[$field] ?? null;
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
        }, $value);

        return array_values(array_filter($array));
    }

    /**
     * @param string $value
     * @return string
     */
    private function email(string $value)
    {
        return mb_strtolower($value, 'utf-8');
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