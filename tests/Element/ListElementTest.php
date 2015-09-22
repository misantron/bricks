<?php

namespace Bricks\Tests\Element;

use Bricks\Element\ListElement;
use Bricks\Validator\RequiredValidator;

class ListElementTest extends \PHPUnit_Framework_TestCase
{
    public function testConstructor()
    {
        $element = new ListElement('Test label', 'test');

        $this->assertNotEmpty($element->getAttributes());
        $this->assertArrayHasKey('name', $element->getAttributes());
        $this->assertEquals('Test label', $element->getLabel());

        $this->assertEquals([], $element->getOptions());
        $this->assertEquals([], $element->getValidators());

        $element = new ListElement('Test label', 'test', ['value1', 'value2']);

        $this->assertNotEmpty($element->getAttributes());
        $this->assertArrayHasKey('name', $element->getAttributes());
        $this->assertEquals('Test label', $element->getLabel());

        $this->assertNotEmpty($element->getOptions());
        $this->assertCount(2, $element->getOptions());
        $this->assertEquals(['value1', 'value2'], $element->getOptions());
        $this->assertContains('value1', $element->getOptions());
        $this->assertContains('value2', $element->getOptions());

        $this->assertEquals([], $element->getValidators());

        $element = new ListElement(
            'Test label',
            'test',
            ['value1', 'value2'],
            ['class' => 'common']
        );

        $this->assertNotEmpty($element->getAttributes());
        $this->assertArrayHasKey('name', $element->getAttributes());
        $this->assertArrayHasKey('class', $element->getAttributes());
        $this->assertContains('test', $element->getAttributes());
        $this->assertContains('common', $element->getAttributes());
        $this->assertCount(2, $element->getAttributes());

        $this->assertEquals('Test label', $element->getLabel());

        $this->assertNotEmpty($element->getOptions());
        $this->assertCount(2, $element->getOptions());
        $this->assertEquals(['value1', 'value2'], $element->getOptions());
        $this->assertContains('value1', $element->getOptions());
        $this->assertContains('value2', $element->getOptions());

        $element = new ListElement(
            'Test label',
            'test',
            ['value1', 'value2'],
            ['class' => 'common'],
            [new RequiredValidator()]
        );

        $this->assertNotEmpty($element->getAttributes());
        $this->assertArrayHasKey('name', $element->getAttributes());
        $this->assertArrayHasKey('class', $element->getAttributes());
        $this->assertContains('test', $element->getAttributes());
        $this->assertContains('common', $element->getAttributes());
        $this->assertCount(2, $element->getAttributes());

        $this->assertEquals('Test label', $element->getLabel());

        $this->assertNotEmpty($element->getOptions());
        $this->assertCount(2, $element->getOptions());
        $this->assertEquals(['value1', 'value2'], $element->getOptions());
        $this->assertContains('value1', $element->getOptions());
        $this->assertContains('value2', $element->getOptions());

        $this->assertNotEmpty($element->getValidators());
        $this->assertCount(1, $element->getValidators());
        $this->assertContainsOnlyInstancesOf('\\Bricks\\Validator\\AbstractValidator', $element->getValidators());
    }
}