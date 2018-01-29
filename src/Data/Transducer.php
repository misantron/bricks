<?php

namespace Bricks\Data;


use Bricks\Exception\ConfigurationException;
use Bricks\Exception\InvalidRequestException;
use Bricks\Helper\ArrHelper;
use Carbon\Carbon;

/**
 * Class Transducer
 * @package Bricks\Data
 */
class Transducer implements TransducerInterface
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
        return $this->process($data, $this->config);
    }

    /**
     * @param array $data
     * @param array $config
     * @return array
     */
    private function process(array $data, array $config): array
    {
        $processed = [];
        foreach ($data as $field => $value) {
            if (is_array($value) && !empty($value) && ArrHelper::isStrKeysArray($value)) {
                $processed[$field] = $this->process($value, $config[$field]);
            } else {
                $method = $config[$field] ?? null;
                if (empty($method) || $value === null) {
                    $processed[$field] = $value;
                } else {
                    $this->assertConversionMethodExists($method);
                    $processed[$field] = call_user_func([$this, $method], $value);
                }
            }
        }
        return $processed;
    }

    /**
     * @param string|int $value
     * @return Carbon
     */
    protected function dateTime($value): Carbon
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
    protected function intArray(array $value): array
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
    protected function floatArray(array $value): array
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
    protected function strArray(array $value): array
    {
        $array = array_map(function ($val) {
            return (string)$val;
        }, array_filter($value));

        return array_values($array);
    }

    /**
     * @param mixed $value
     * @return int
     */
    protected function integer($value): int
    {
        return (int)$value;
    }

    /**
     * @param mixed $value
     * @return float
     */
    protected function float($value): float
    {
        return (float)$value;
    }

    /**
     * @param mixed $value
     * @return string
     */
    protected function string($value): string
    {
        return '' . $value;
    }

    /**
     * @param mixed $value
     * @return array
     */
    protected function array($value): array
    {
        return (array)$value;
    }

    /**
     * @param mixed $value
     * @return bool
     */
    protected function boolean($value): bool
    {
        return is_string($value) ? $value === 'true' : (bool)$value;
    }

    /**
     * @param string $method
     *
     * @throws ConfigurationException
     */
    private function assertConversionMethodExists(string $method)
    {
        if (!method_exists($this, $method)) {
            throw new ConfigurationException('unknown field type: ' . $method);
        }
    }
}