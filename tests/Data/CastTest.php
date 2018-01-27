<?php

namespace Bricks\Tests\Data;


use Bricks\Data\Cast;
use Bricks\Tests\BaseTestCase;

/**
 * Class CastTest
 * @package Bricks\Tests\Data
 */
class CastTest extends BaseTestCase
{
    public function testCreate()
    {
        $config = [
            'foo' => 'array',
            'foo.bar' => 'integer',
            'foo.baz' => 'boolean',
            'quux' => 'dateTime'
        ];

        $expected = [
            'foo' => [
                'bar' => 'integer',
                'baz' => 'boolean',
            ],
            'quux' => 'dateTime'
        ];

        $service = Cast::create($config);

        $this->assertAttributeEquals($expected, 'config', $service);
    }
}