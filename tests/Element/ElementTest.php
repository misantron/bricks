<?php

namespace Bricks\Tests\Element;

use Bricks\Element\Element;

class ElementTest extends \PHPUnit_Framework_TestCase
{
    public function testConstructor()
    {
        $element = new Element('Test label');
        $this->assertEquals('Test label', $element->getLabel());
        $this->assertEmpty($element->getAttributes());
        $this->assertArrayNotHasKey('name', $element->getAttributes());

        $element = new Element('Test label', 'test');
        $this->assertEquals('Test label', $element->getLabel());
        $this->assertNotEmpty($element->getAttributes());
        $this->assertArrayHasKey('name', $element->getAttributes());
        $this->assertEquals('test', $element->getAttribute('name'));

        $element = new Element('Test label', 'test', ['id' => 'test1', 'class' => 'first']);
        $this->assertEquals('Test label', $element->getLabel());
        $this->assertNotEmpty($element->getAttributes());
        $this->assertArrayHasKey('name', $element->getAttributes());
        $this->assertEquals('test', $element->getAttribute('name'));
        $this->assertArrayHasKey('id', $element->getAttributes());
        $this->assertEquals('test1', $element->getAttribute('id'));
        $this->assertArrayHasKey('class', $element->getAttributes());
        $this->assertEquals('first', $element->getAttribute('class'));
    }

    public function testGetLabel()
    {
        $element = new Element('Test label');
        $this->assertEquals('Test label', $element->getLabel());

        $element = new Element('New label');
        $this->assertEquals('New label', $element->getLabel());
    }

    public function testRender()
    {
        ob_start();
        $element = new Element('Test label', 'test');
        $element->render();
        $content = ob_get_contents();
        ob_end_clean();
        $this->assertEquals('<input name="test" />', $content);
    }

    public function testRenderLabel()
    {
        ob_start();
        $element = new Element('Test label', 'test');
        $element->renderLabel();
        $content = ob_get_contents();
        ob_end_clean();
        $this->assertEquals('<label>Test label</label>', $content);
    }
}