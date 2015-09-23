<?php

namespace Bricks\Tests;

use Bricks\Container;
use Bricks\Element\TextInput;

class ContainerTest extends \PHPUnit_Framework_TestCase
{
    public function testConstructor()
    {
        $container = new Container('span', ['class' => 'column'], [
            new TextInput('Input 1', 'input1')
        ]);
        $container->render();
    }
}