<?php

namespace Bricks\Tests\Helper;

use Bricks\Helper\ArrHelper;
use Bricks\Tests\BaseTestCase;

/**
 * Class ArrHelperTest
 * @package Bricks\Tests\Helper
 */
class ArrHelperTest extends BaseTestCase
{
    /**
     * @dataProvider isStrKeysArrayProvider
     *
     * @param array $data
     * @param bool $expected
     */
    public function testIsStrKeysArray(array $data, bool $expected)
    {
        $this->assertEquals($expected, ArrHelper::isStrKeysArray($data));
    }

    public function isStrKeysArrayProvider()
    {
        return [
            [array_fill_keys([1, 34, 'test', null], 1), false],
            [array_fill_keys(['foo', 'bar', 'test', 'false'], 1), true],
            [array_fill_keys(['54', '34', 'test', 'false'], 1), false],
            [array_fill_keys([54, 467, 424, 866], 1), false],
        ];
    }

    public function testUnflatten()
    {
        $array = [
            'foo' => 'val1',
            'foo.bar' => 'val2',
            'foo.bar.baz' => 'val3',
            'foo.bar.quux' => 'val4',
            'test' => 'val5'
        ];

        $expected = [
            'foo' => [
                'bar' => [
                    'baz' => 'val3',
                    'quux' => 'val4',
                ]
            ],
            'test' => 'val5'
        ];

        $this->assertEquals($expected, ArrHelper::unflatten($array));
    }
}