<?php

namespace Bricks\Helper;


class ArrHelper
{
    /**
     * @param array $array
     * @return bool
     */
    public static function isStrKeysArray(array $array): bool
    {
        $filtered = array_filter($array, function ($key) {
            return !is_string($key);
        }, ARRAY_FILTER_USE_KEY);

        return empty($filtered);
    }

    /**
     * @param array $array
     * @return array
     */
    public static function unflatten(array $array): array
    {
        $output = [];
        foreach ($array as $key => $value) {
            static::set($output, $key, $value);
            if (is_array($value) && strpos($key, '.') === false) {
                $output[$key] = static::unflatten($value);
            }
        }
        return $output;
    }

    /**
     * @param array $array
     * @param string $key
     * @param mixed $value
     */
    private static function set(array &$array, string $key, $value)
    {
        $keys = explode('.', $key);
        while (count($keys) > 1) {
            $key = array_shift($keys);
            if (!isset($array[$key]) || !is_array($array[$key])) {
                $array[$key] = [];
            }
            $array =& $array[$key];
        }
        $array[array_shift($keys)] = $value;
    }
}