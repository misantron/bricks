<?php

namespace Bricks;

use Bricks\Data\Cast;
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

        $validator = Validator::create($this->data);
        $validator->rules($rules);
        $validator->validate();

        return $this;
    }

    /**
     * @param array $data
     * @return AbstractForm
     */
    protected function fetchRequestData(array $data)
    {
        $casted = Cast::create($this->types)->execute($data);

        $fields = array_keys($this->validators);

        foreach ($fields as $field) {
            if (isset($casted[$field])) {
                if (isset($this->data[$field]) && is_array($this->data[$field])) {
                    $this->data[$field] = array_merge($this->data[$field], $casted[$field]);
                } else {
                    $this->data[$field] = $casted[$field];
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
        $invalidConfigFields = [];

        array_walk($fields, function (array $config, string $field) use (&$invalidConfigFields) {
            if (isset($config['validators'])) {
                $this->validators[$field] = $config['validators'];
                if (isset($config['type'])) {
                    $this->types[$field] = $config['type'];
                }
                if (isset($config['cleanup']) && $config['cleanup'] === true) {
                    $this->cleanup[$field] = true;
                }
            } else {
                $invalidConfigFields[] = $field;
            }
        });

        if (!empty($invalidConfigFields)) {
            throw new ConfigurationException(
                'fields validation rules are not set: ' . implode(',', $invalidConfigFields)
            );
        }
    }

    /**
     * Построение правил валидации на основе заданного конфига
     *
     * @return array
     */
    private function buildValidationRules()
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
}