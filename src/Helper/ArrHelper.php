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
}