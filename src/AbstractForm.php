<?php

namespace Bricks;

use Bricks\Data\Transducer;
use Bricks\Data\Validator;
use Bricks\Exception\ConfigurationException;
use Bricks\Exception\InvalidRequestException;
use Psr\Http\Message\RequestInterface;

/**
 * Class AbstractForm
 * @package Bricks
 */
abstract class AbstractForm implements FormInterface
{
    /**
     * @var array
     */
    protected $data = [];

    /**
     * @var array
     */
    private $types = [];

    /**
     * @var array
     */
    private $validators = [];

    /**
     * @var array
     */
    private $cleanup = [];

    public function __construct()
    {
        $fields = $this->fields();

        if (!is_array($fields) || empty($fields)) {
            throw new ConfigurationException('form is not configured yet');
        }

        $this->parseConfiguration($fields);
    }

    /**
     * @return static
     */
    public static function create()
    {
        return new static();
    }

    /**
     * @return array
     */
    abstract protected function fields(): array;

    /**
     * {@inheritDoc}
     */
    public function setData(array $data)
    {
        array_walk($data, function ($value, string $key) {
            if (isset($this->validators[$key])) {
                $this->data[$key] = $value;
            }
        });
        return $this;
    }

    /**
     * {@inheritDoc}
     */
    public function getData(): array
    {
        return array_filter($this->data, function (string $field) {
            return !isset($this->cleanup[$field]);
        }, ARRAY_FILTER_USE_KEY);
    }

    /**
     * {@inheritDoc}
     */
    public function handleRequest(RequestInterface $request)
    {
        $method = strtoupper($request->getMethod());

        if ($method === 'GET' || $method === 'DELETE') {
            parse_str($request->getUri()->getQuery(), $data);
        } else {
            $data = json_decode($request->getBody()->getContents(), true);
            if (!isset($data) && json_last_error() !== JSON_ERROR_NONE) {
                throw new InvalidRequestException('unable to parse json body: ' . json_last_error_msg());
            }
        }
        return $this->fetchRequestData($data);
    }

    /**
     * {@inheritDoc}
     */
    public function validate()
    {
        $rules = $this->buildValidationRules();
        $messages = $this->buildCustomValidationMessages();

        $validator = Validator::create($this->data);
        $validator->rules($rules, $messages);
        $validator->validate();

        return $this;
    }

    /**
     * @param array $data
     * @return AbstractForm
     */
    protected function fetchRequestData(array $data)
    {
        $processed = Transducer::create($this->types)->execute($data);

        $fields = array_keys($this->validators);

        foreach ($fields as $field) {
            if (isset($processed[$field])) {
                if (isset($this->data[$field]) && is_array($this->data[$field])) {
                    $this->data[$field] = array_merge($this->data[$field], $processed[$field]);
                } else {
                    $this->data[$field] = $processed[$field];
                }
            }
        }

        $unexpectedFields = array_diff(array_keys($data), $fields);
        if (!empty($unexpectedFields)) {
            throw new InvalidRequestException('unexpected fields: ' . implode(', ', $unexpectedFields));
        }

        return $this;
    }

    /**
     * @param array $fields
     */
    private function parseConfiguration(array $fields)
    {
        array_walk($fields, function (array $config, string $field) {
            $this->assertFieldConfigHasValidators($field, $config);
            $this->validators[$field] = $config['validators'];
            if (isset($config['type'])) {
                $this->types[$field] = $config['type'];
            }
            if (isset($config['cleanup']) && $config['cleanup'] === true) {
                $this->cleanup[$field] = true;
            }
        });
    }

    /**
     * @param string $field
     * @param array $config
     */
    private function assertFieldConfigHasValidators(string $field, array $config)
    {
        if (!isset($config['validators']) || !is_array($config['validators'])) {
            throw new ConfigurationException('fields validation rules are not set: ' . $field);
        }
    }

    /**
     * @return array
     */
    private function buildValidationRules(): array
    {
        $rules = [];
        foreach ($this->validators as $field => $config) {
            foreach ($config as $type => $data) {
                $rule = [$field];
                if (is_callable($data)) {
                    $rule[] = call_user_func($data);
                } elseif (!is_bool($data)) {
                    $rule[] = $data;
                }
                $rules[$type][] = $rule;
            }
        }
        return $rules;
    }

    /**
     * @return array
     */
    private function buildCustomValidationMessages(): array
    {
        $fields = $this->fields();
        $messages = [];
        foreach ($this->validators as $field => $config) {
            foreach ($config as $type => $data) {
                if (isset($fields[$field]['messages'][$type])) {
                    $messages[$type][$field] = $fields[$field]['messages'][$type];
                }
            }
        }

        return $messages;
    }
}