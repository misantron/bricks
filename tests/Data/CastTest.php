<?php

namespace Bricks\Tests\Data;


use Bricks\Data\Cast;
use Bricks\Tests\BaseTestCase;
use Carbon\Carbon;

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
            'foo.baz' => 'array',
            'foo.baz.quux' => 'dateTime'
        ];

        $expected = [
            'foo' => [
                'bar' => 'integer',
                'baz' => [
                    'quux' => 'dateTime'
                ],
            ],
        ];

        $service = Cast::create($config);

        $this->assertAttributeEquals($expected, 'config', $service);
    }

    /**
     * @expectedException \Bricks\Exception\ConfigurationException
     * @expectedExceptionMessage Configuration error: unknown field type: bar
     */
    public function testExecuteWithUnexpectedType()
    {
        $config = [
            'foo' => 'bar'
        ];

        Cast::create($config)->execute(['foo' => 'baz']);
    }

    public function testExecute()
    {
        $config = [
            'foo' => 'array',
            'foo.bar' => 'string',
            'foo.baz' => 'boolean',
            'quux' => 'integer',
        ];

        $values = [
            'foo' => [
                'bar' => 50,
                'baz' => 'true',
            ],
            'quux' => '23',
        ];

        $expected = [
            'foo' => [
                'bar' => '50',
                'baz' => true,
            ],
            'quux' => 23,
        ];

        $parsed = Cast::create($config)->execute($values);

        $this->assertEquals($expected, $parsed);
    }

    public function testCastDateTime()
    {
        $config = ['foo' => 'dateTime'];
        $values = ['foo' => (new \DateTime())->format('Y-m-d H:i:s')];

        $parsed = Cast::create($config)->execute($values);
        /** @var Carbon $dateTime */
        $dateTime = $parsed['foo'];

        $this->assertInstanceOf(Carbon::class, $dateTime);
        $this->assertEquals($values['foo'], $dateTime->toDateTimeString());
    }

    public function testCastIntArray()
    {
        $config = ['foo' => 'intArray'];
        $values = ['foo' => ['1', 2, null, '']];

        $parsed = Cast::create($config)->execute($values);

        $this->assertEquals(['foo' => [1, 2]], $parsed);
    }
}