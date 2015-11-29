<?php

namespace Bricks\Tests\Validator;

use Bricks\Tests\BaseTestCase;
use Bricks\Validator\RequiredValidator;

class RequiredValidatorTest extends BaseTestCase
{
    public function testConstructor()
    {
        $validator = new RequiredValidator();
        $this->assertEquals('is required', $validator->getMessage());
        $validator = new RequiredValidator('');
        $this->assertEquals('is required', $validator->getMessage());
        $validator = new RequiredValidator('field is required');
        $this->assertEquals('field is required', $validator->getMessage());
    }

    public function testGetMessage()
    {
        $validator = new RequiredValidator();
        $this->assertEquals('is required', $validator->getMessage());
        $validator = new RequiredValidator(false);
        $this->assertEquals('is required', $validator->getMessage());
        $validator = new RequiredValidator('field is required');
        $this->assertEquals('field is required', $validator->getMessage());
    }

    public function testValidate()
    {
        $validator = new RequiredValidator();
        $result = $validator->validate(null);
        $this->assertFalse($result);
        $result = $validator->validate('');
        $this->assertFalse($result);
        $result = $validator->validate(['', '', '']);
        $this->assertFalse($result);
        $result = $validator->validate([1, null, 'test']);
        $this->assertTrue($result);
        $result = $validator->validate('test');
        $this->assertTrue($result);
    }
}