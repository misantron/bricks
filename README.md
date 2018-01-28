# Bricks

[![Build Status](http://img.shields.io/travis/misantron/bricks.svg?style=flat-square)](https://travis-ci.org/misantron/bricks)
[![Code Coverage](https://img.shields.io/scrutinizer/coverage/g/misantron/bricks.svg?style=flat-square)](https://scrutinizer-ci.com/g/misantron/bricks)
[![Scrutinizer Code Quality](https://img.shields.io/scrutinizer/g/misantron/bricks.svg?style=flat-square)](https://scrutinizer-ci.com/g/misantron/bricks)
[![PHP 7 Support](https://img.shields.io/badge/PHP%207-supported-blue.svg?style=flat-square)](https://github.com/misantron/bricks)

Flexible form builder with data validation and pre processing.

## Installation

Run [Composer](https://getcomposer.org) from command line:

```
composer require misantron/bricks
```

Or add dependency to `composer.json`:

```
{
    "require": {
        "misantron/bricks": "dev-master"
    }
}
```

## Usage

Create a new form by inheriting `\Bricks\AbstractForm`. Abstract method `fields()` must be implemented with configuration array:

```php
class SomeForm extends \Bricks\AbstractForm
{
    protected funtion fields(): array
    {
        return [
            'foo' => [
                'type' => 'string', // using for data type cast
                'validators' => [
                    'required' => true,
                    'lengthMax' => 64,
                ],
            ],
            'bar' => [
                'type' => 'integer', // using for data type cast
                'validators' => [
                    'required' => true,
                    'in' => function () {
                        return [1, 2];
                    }
                ],
                'cleanup' => true, // flag that field will be deleted from getData() method call response
            ],
        ];
    }
}
```

Form workflow inside application controller/service/etc.:

```php
$request = Request::fromGlobals(); // must implements \Psr\Http\Message\RequestInterface

$default = [
    'foo' => 'test',
];

$form = \SomeForm::create()
    ->setData($default) // allows to pass an initial data before handling the request
    ->handleRequest($request) // get data from the request and data processing
    ->validate(); // data validation

$data = $form->getData(); // extracting processed and validated data from form
```

## Built-in field types

`string`, `integer`, `float`, `boolean`, `array` (contains elements of different types), `dateTime` (transform datetime string or timestamp to [Carbon](https://github.com/briannesbitt/Carbon) object) , `intArray`, `strArray`, `floatArray`

Custom user type can be easily added:

```php
class MyCast extends \Bricks\Data\Cast
{
    private funtion myType($value)
    {
        // your custom logic here
    }
}
```

## Built-in field validation rules

See [Valitron](https://github.com/vlucas/valitron) documentation.
If form data is not valid - `\Bricks\Exception\ValidationException` will be thrown:

```php
try {
    $form->validate();
} catch (\Bricks\Exception\ValidationException $e) {
    var_dump($e->getData()); // getting fields error data
}
```
