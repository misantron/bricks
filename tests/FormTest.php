<?php

namespace Bricks\Tests;

use Bricks\Container;
use Bricks\Element\TextInput;
use Bricks\Form;

class FormTest extends \PHPUnit_Framework_TestCase
{
    public function testConstructor()
    {
        $form = new Form('test');

        $this->assertNotEmpty($form->getAttributes());
        $this->assertCount(1, $form->getAttributes());
        $this->assertArrayHasKey('id', $form->getAttributes());
        $this->assertContains('test', $form->getAttributes());

        $form = new Form('test', ['class' => 'form']);

        $this->assertNotEmpty($form->getAttributes());
        $this->assertCount(2, $form->getAttributes());
        $this->assertArrayHasKey('id', $form->getAttributes());
        $this->assertArrayHasKey('class', $form->getAttributes());
        $this->assertContains('test', $form->getAttributes());
        $this->assertContains('form', $form->getAttributes());

        $form = new Form('test', ['data-name' => 'testForm'], [
            new Container('span', ['class' => 'column'], [
                new TextInput('Input 1', 'input1')
            ])
        ]);
    }
}