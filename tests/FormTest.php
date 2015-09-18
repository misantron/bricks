<?php

namespace Bricks\Tests;

use Bricks\Element\Button;
use Bricks\Element\HTML;
use Bricks\Form;

class FormTest extends \PHPUnit_Framework_TestCase
{
    public function testConstructor()
    {
        $form = new Form('test', []);
        $form->addElement(new HTML('<span class="column">'));
        $form->addElement(new Button('Submit'));
        $form->addElement(new Button('Reset', 'reset'));
        $form->addElement(new HTML('</span>'));
        $form->render();
    }
}