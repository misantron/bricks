<?php

namespace Bricks\Tests\Element;

use Bricks\Element\Time;

class TimeTest extends \PHPUnit_Framework_TestCase
{
    public function testRender()
    {
        $time = new Time('Test title', 'test');
        $time->render();
    }
}