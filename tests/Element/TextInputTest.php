<?php

namespace Bricks\Tests\Element;

use Bricks\Element\TextInput;
use Bricks\Tests\BaseTestCase;
use Bricks\Validator\RequiredValidator;

class TextInputTest extends BaseTestCase
{
    public function testConstructor()
    {
        $element = new TextInput('Test');
        $this->assertCount(1, $element->getAttributes());
        $this->assertArrayHasKey('type', $element->getAttributes());
        $this->assertEquals('text', $element->getAttribute('type'));
        $this->assertCount(0, $element->getValidators());
        $this->assertEmpty($element->getValidators());

        $element = new TextInput('Test', 'testName');
        $this->assertCount(2, $element->getAttributes());
        $this->assertArrayHasKey('name', $element->getAttributes());
        $this->assertEquals('testName', $element->getAttribute('name'));

        $element = new TextInput('Test', 'testName', ['class' => 'custom']);
        $this->assertCount(3, $element->getAttributes());
        $this->assertArrayHasKey('class', $element->getAttributes());
        $this->assertEquals('custom', $element->getAttribute('class'));

        $element = new TextInput('Test', 'testName', ['class' => 'custom'], [new RequiredValidator()]);
        $validators = $element->getValidators();
        $this->assertNotEmpty($validators);
        $this->assertCount(1, $validators);
        $this->assertInstanceOf('Bricks\\Validator\\RequiredValidator', $validators[0]);
    }

    public function testRenderLabel()
    {
        $element = new TextInput('Test');

        ob_start();
        $element->renderLabel();
        $content = ob_get_contents();
        ob_end_clean();

        $this->assertEquals('<label>Test</label>', $content);

        $element = new TextInput('Some long text label');

        ob_start();
        $element->renderLabel();
        $content = ob_get_contents();
        ob_end_clean();

        $this->assertEquals('<label>Some long text label</label>', $content);
    }

    public function testRender()
    {
        $element = new TextInput('Test');

        ob_start();
        $element->render();
        $content = ob_get_contents();
        ob_end_clean();

        $this->assertEquals('<label>Test</label><input type="text" />', $content);

        $element = new TextInput('Test', 'testName');

        ob_start();
        $element->render();
        $content = ob_get_contents();
        ob_end_clean();

        $this->assertEquals('<label>Test</label><input type="text" name="testName" />', $content);

        $element = new TextInput('Test', 'testName', ['class' => 'custom']);

        ob_start();
        $element->render();
        $content = ob_get_contents();
        ob_end_clean();

        $this->assertEquals('<label>Test</label><input type="text" class="custom" name="testName" />', $content);
    }

    public function testValidate()
    {
        $element = new TextInput('Test');

        $this->assertTrue($element->validate(111));

        $element = new TextInput('Test', 'testName', [], [new RequiredValidator()]);

        $this->assertTrue($element->validate(123));
        $this->assertFalse($element->validate(''));
        $this->assertFalse($element->validate(null));
    }
}