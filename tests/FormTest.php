<?php

namespace Bricks\Tests;

use Bricks\Container;
use Bricks\Element\File;
use Bricks\Element\TextBox;
use Bricks\Element\TextInput;
use Bricks\Form;

class FormTest extends \PHPUnit_Framework_TestCase
{
    public function testConstructor()
    {
        $form = new Form('test');

        $this->assertCount(1, $form->getAttributes());
        $this->assertEmpty($form->getElements());

        $this->assertArrayHasKey('id', $form->getAttributes());
        $this->assertContains('test', $form->getAttributes());

        $form = new Form('test', ['class' => 'form']);

        $this->assertCount(2, $form->getAttributes());
        $this->assertEmpty($form->getElements());

        $this->assertArrayHasKey('id', $form->getAttributes());
        $this->assertArrayHasKey('class', $form->getAttributes());

        $this->assertContains('test', $form->getAttributes());
        $this->assertContains('form', $form->getAttributes());

        $form = new Form('test', ['data-name' => 'testForm'], [
            new Container('span', ['class' => 'column'], [
                new TextInput('Input 1', 'input1')
            ])
        ]);

        $this->assertCount(2, $form->getAttributes());

        $this->assertArrayHasKey('id', $form->getAttributes());
        $this->assertArrayHasKey('data-name', $form->getAttributes());

        $this->assertContains('test', $form->getAttributes());
        $this->assertContains('testForm', $form->getAttributes());

        $this->assertCount(1, $form->getElements());
        $this->assertArrayHasKey(0, $form->getElements());

        $elements = $form->getElements();
        /** @var Container $container */
        $container = reset($elements);

        $this->assertInstanceOf('Bricks\\Container', $container);
        $this->assertCount(1, $container->getElements());
    }

    public function testAddElement()
    {
        $form = new Form('test');

        $form->addElement(new TextBox('text'));

        $this->assertCount(1, $form->getElements());

        $form->addElement(new TextInput('value1'));
        $form->addElement(new File('image'));

        $this->assertCount(3, $form->getElements());
        $this->assertArrayHasKey('enctype', $form->getAttributes());
        $this->assertContains('multipart/form-data', $form->getAttributes());
    }
}