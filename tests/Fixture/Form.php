<?php

namespace Bricks\Tests\Fixture;


use Bricks\AbstractForm;

/**
 * Class Form
 * @package Bricks\Tests\Fixture
 */
class Form extends AbstractForm
{
    protected function fields(): array
    {
        return [
            'bar' => [
                'type' => 'integer',
                'validators' => [
                    'required' => true,
                ],
            ],
        ];
    }
}