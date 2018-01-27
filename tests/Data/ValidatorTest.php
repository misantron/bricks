<?php

namespace Bricks\Tests\Data;


use Bricks\Data\Validator;
use Bricks\Exception\ValidationException;
use Bricks\Tests\BaseTestCase;

/**
 * Class ValidatorTest
 * @package Bricks\Tests\Data
 */
class ValidatorTest extends BaseTestCase
{
    public function testValidatePositive()
    {
        $data = [
            'foo' => 'baz'
        ];

        $validator = new Validator($data);
        $validator->rules([
            'required' => ['foo'],
            'alpha' => ['foo'],
        ]);

        $this->assertTrue($validator->validate());
    }

    public function testValidateNegative()
    {
        $data = ['foo' => 'baz'];

        $validator = new Validator($data);
        $validator->rules([
            'required' => ['foo', 'bar']
        ]);

        try {
            $validator->validate();
        } catch (\Exception $e) {
            /** @var ValidationException $e */
            $this->assertInstanceOf(ValidationException::class, $e);
            $this->assertEquals('Data validation error', $e->getMessage());
            $this->assertEquals(['bar' => ['Bar is required']], $e->getData());
        }
    }

    public function testCreate()
    {
        $validator = Validator::create();
        $validator->rules([
            'required' => ['foo', 'bar']
        ]);

        $this->assertInstanceOf(Validator::class, $validator);
    }
}