<?php

namespace Bricks\Tests\Data;


use Bricks\Data\Transducer;
use Bricks\Tests\BaseTestCase;
use Carbon\Carbon;

/**
 * Class TransducerTest
 * @package Bricks\Tests\Data
 */
class TransducerTest extends BaseTestCase
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

        $service = Transducer::create($config);

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

        Transducer::create($config)->execute(['foo' => 'baz']);
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

        $parsed = Transducer::create($config)->execute($values);

        $this->assertEquals($expected, $parsed);
    }

    /**
     * @expectedException \Bricks\Exception\InvalidRequestException
     * @expectedExceptionMessage Invalid request: unexpected datetime format
     */
    public function testDateTimeWithUnexpectedInitialData()
    {
        $config = ['foo' => 'dateTime'];
        $values = ['foo' => false];

        Transducer::create($config)->execute($values);
    }

    public function testDateTime()
    {
        $config = [
            'foo' => 'dateTime',
            'bar' => 'dateTime',
        ];
        $values = [
            'foo' => (new \DateTime())->format('Y-m-d H:i:s'),
            'bar' => (new \DateTime())->getTimestamp(),
        ];

        $parsed = Transducer::create($config)->execute($values);

        /** @var Carbon $dateTime */
        $dateTime = $parsed['foo'];

        $this->assertInstanceOf(Carbon::class, $dateTime);
        $this->assertEquals($values['foo'], $dateTime->toDateTimeString());

        /** @var Carbon $dateTime */
        $dateTime = $parsed['bar'];

        $this->assertInstanceOf(Carbon::class, $dateTime);
        $this->assertEquals($values['bar'], $dateTime->getTimestamp());
    }

    public function testIntArray()
    {
        $config = ['foo' => 'intArray'];
        $values = ['foo' => ['1', 2, null, '']];

        $parsed = Transducer::create($config)->execute($values);

        $this->assertEquals(['foo' => [1, 2]], $parsed);
    }

    public function testStrArray()
    {
        $config = ['foo' => 'strArray'];
        $values = ['foo' => ['1', 2, null, '']];

        $parsed = Transducer::create($config)->execute($values);

        $this->assertEquals(['foo' => ['1', '2']], $parsed);
    }

    public function testFloatArray()
    {
        $config = ['foo' => 'floatArray'];
        $values = ['foo' => ['1', 2, null, '']];

        $parsed = Transducer::create($config)->execute($values);

        $this->assertEquals(['foo' => [1.0, 2.0]], $parsed);
    }

    public function testInteger()
    {
        $config = [
            'foo' => 'integer',
            'bar' => 'integer',
        ];
        $values = [
            'foo' => 123.26,
            'bar' => '12',
        ];

        $parsed = Transducer::create($config)->execute($values);

        $this->assertEquals([
            'foo' => 123,
            'bar' => 12,
        ], $parsed);
    }

    public function testFloat()
    {
        $config = [
            'foo' => 'float',
            'bar' => 'float',
        ];
        $values = [
            'foo' => 123,
            'bar' => '10.34',
        ];

        $parsed = Transducer::create($config)->execute($values);

        $this->assertEquals([
            'foo' => 123.0,
            'bar' => 10.34,
        ], $parsed);
    }

    public function testString()
    {
        $config = [
            'foo' => 'string',
            'bar' => 'string',
        ];
        $values = [
            'foo' => 12345,
            'bar' => false,
        ];

        $parsed = Transducer::create($config)->execute($values);

        $this->assertEquals([
            'foo' => '12345',
            'bar' => '',
        ], $parsed);
    }

    public function testArray()
    {
        $config = [
            'foo' => 'array',
            'bar' => 'array',
        ];
        $values = [
            'foo' => 5,
            'bar' => ['baz'],
        ];

        $parsed = Transducer::create($config)->execute($values);

        $this->assertEquals([
            'foo' => [5],
            'bar' => ['baz'],
        ], $parsed);
    }

    public function testBoolean()
    {
        $config = [
            'foo' => 'boolean',
            'bar' => 'boolean',
        ];
        $values = [
            'foo' => 'true',
            'bar' => 0,
        ];

        $parsed = Transducer::create($config)->execute($values);

        $this->assertEquals([
            'foo' => true,
            'bar' => false,
        ], $parsed);
    }
}