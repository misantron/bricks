<?php

namespace Bricks\Tests;


use Bricks\AbstractForm;
use Bricks\Exception\ValidationException;
use Bricks\Tests\Fixture\Form;

/**
 * Class AbstractFormTest
 * @package Bricks\Tests
 */
class AbstractFormTest extends BaseTestCase
{
    /**
     * @expectedException \Bricks\Exception\ConfigurationException
     * @expectedExceptionMessage Configuration error: form is not configured yet
     */
    public function testConstructorWithEmptyConfig()
    {
        new class extends AbstractForm
        {
            protected function fields(): array
            {
                return [];
            }
        };
    }

    /**
     * @expectedException \Bricks\Exception\ConfigurationException
     * @expectedExceptionMessage Configuration error: fields validation rules are not set: foo,bar
     */
    public function testConstructorWithInvalidConfig()
    {
        new class extends AbstractForm
        {
            protected function fields(): array
            {
                return [
                    'foo' => [
                        'type' => 'string'
                    ],
                    'bar' => [
                        'type' => 'integer',
                        'cleanup' => true
                    ]
                ];
            }
        };
    }

    public function testConstructor()
    {
        $form = new class extends AbstractForm
        {
            protected function fields(): array
            {
                return [
                    'foo' => [
                        'type' => 'string',
                        'validators' => [
                            'required' => true,
                            'email' => true,
                            'lengthMax' => 255,
                        ]
                    ],
                    'bar' => [
                        'type' => 'integer',
                        'validators' => [
                            'required' => true,
                        ],
                        'cleanup' => true,
                    ],
                    'baz' => [
                        'type' => 'intArray',
                        'validators' => [
                            'array' => true,
                            'arrayContains' => function () {
                                return [1,2,3];
                            }
                        ]
                    ],
                ];
            }
        };

        $validators = [
            'foo' => [
                'required' => true,
                'email' => true,
                'lengthMax' => 255,
            ],
            'bar' => [
                'required' => true,
            ],
            'baz' => [
                'array' => true,
                'arrayContains' => function () {
                    return [1,2,3];
                }
            ],
        ];

        $types = [
            'foo' => 'string',
            'bar' => 'integer',
            'baz' => 'intArray',
        ];

        $cleanup = [
            'bar' => true
        ];

        $this->assertAttributeEquals([], 'data', $form);
        $this->assertAttributeEquals($validators, 'validators', $form);
        $this->assertAttributeEquals($types, 'types', $form);
        $this->assertAttributeEquals($cleanup, 'cleanup', $form);
    }

    public function testCreate()
    {
        $this->assertInstanceOf(Form::class, Form::create());
    }

    public function testSetData()
    {
        $form = $this->createForm();
        $this->assertAttributeEquals([], 'data', $form);

        $form->setData([
            'foo' => 'test@example.com',
            'bar' => 2,
            'baz' => [],
            'quux' => false
        ]);

        $this->assertAttributeEquals(['foo' => 'test@example.com', 'bar' => 2, 'baz' => []], 'data', $form);
    }

    /**
     * @expectedException \Bricks\Exception\InvalidRequestException
     * @expectedExceptionMessage Invalid request: unexpected fields: quux
     */
    public function testHandleRequestWithUnexpectedFields()
    {
        $request = $this->createQueryRequest([
            'foo' => 'test',
            'bar' => 3,
            'baz' => [1,2],
            'quux' => false
        ]);

        $form = $this->createForm();
        $form->handleRequest($request);
    }

    public function testHandleRequestFromQueryString()
    {
        $request = $this->createQueryRequest([
            'foo' => 'test',
            'bar' => 3,
            'baz' => [1,2]
        ]);

        $form = $this->createForm();
        $form->handleRequest($request);

        $this->assertAttributeEquals(['foo' => 'test', 'bar' => 3, 'baz' => [1,2]], 'data', $form);
    }

    /**
     * @expectedException \Bricks\Exception\InvalidRequestException
     * @expectedExceptionMessage Invalid request: unable to parse json body: Syntax error
     */
    public function testHandleRequestWithInvalidJsonBody()
    {
        $request = $this->createJsonRequest('{["foo": "baz"]}');

        $form = $this->createForm();
        $form->handleRequest($request);
    }

    public function testHandleRequestFromJsonBody()
    {
        $request = $this->createJsonRequest([
            'foo' => 'test',
            'bar' => 3,
            'baz' => [1,2],
        ]);

        $form = $this->createForm();
        $form->handleRequest($request);

        $this->assertAttributeEquals(['foo' => 'test', 'bar' => 3, 'baz' => [1,2]], 'data', $form);
    }

    public function testHandleRequestWithMergedData()
    {
        $form = $this->createForm();
        $form->setData(['baz' => [1,2]]);

        $request = $this->createJsonRequest([
            'foo' => 'test',
            'bar' => 3,
            'baz' => [3,4],
        ]);

        $form->handleRequest($request);

        $this->assertAttributeEquals(['foo' => 'test', 'bar' => 3, 'baz' => [1,2,3,4]], 'data', $form);
    }

    public function testValidateNegative()
    {
        $request = $this->createJsonRequest([
            'foo' => null,
            'baz' => [],
        ]);

        $form = $this->createForm();
        $form
            ->setData(['baz' => [1,2]])
            ->handleRequest($request);

        $errors = [
            'foo' => [
                'Foo is required',
                'Foo must not exceed 64 characters',
                'Foo contains invalid value'
            ],
            'bar' => [
                'Bar is required'
            ]
        ];

        try {
            $form->validate();
        } catch (\Exception $e) {
            /** @var ValidationException $e */
            $this->assertInstanceOf(ValidationException::class, $e);
            $this->assertEquals('Data validation error', $e->getMessage());
            $this->assertEquals($errors, $e->getData());
        }
    }

    public function testValidatePositive()
    {
        $request = $this->createJsonRequest([
            'foo' => 'ready',
            'bar' => 10,
        ]);

        $form = $this->createForm();
        $form
            ->setData(['baz' => [1,2]])
            ->handleRequest($request);

        try {
            $form->validate();
        } catch (\Exception $e) {
            $this->fail('Unexpected exception throw');
        }

        $this->assertTrue(true);
    }

    public function testGetData()
    {
        $form = $this->createForm();
        $form->setData([
            'foo' => 'test',
            'bar' => 4,
            'baz' => [1,2]
        ]);

        $this->assertEquals(['foo' => 'test', 'baz' => [1,2]], $form->getData());
    }

    /**
     * @return AbstractForm
     */
    private function createForm()
    {
        return new class extends AbstractForm
        {
            protected function fields(): array
            {
                return [
                    'foo' => [
                        'type' => 'string',
                        'validators' => [
                            'required' => true,
                            'lengthMax' => 64,
                            'in' => function () {
                                return ['test', 'ready'];
                            }
                        ]
                    ],
                    'bar' => [
                        'type' => 'integer',
                        'validators' => [
                            'required' => true,
                        ],
                        'cleanup' => true,
                    ],
                    'baz' => [
                        'type' => 'intArray',
                        'validators' => [
                            'array' => true,
                        ]
                    ]
                ];
            }
        };
    }
}