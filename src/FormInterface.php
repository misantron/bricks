<?php

namespace Bricks;


use Bricks\Validation\ValidationException;
use Psr\Http\Message\RequestInterface;

interface FormInterface
{
    /**
     * @return array
     */
    public function getData(): array;

    /**
     * @param string $className
     *
     * @return object
     */
    public function mapData(string $className);

    /**
     * Получение данных из запроса
     *
     * @param RequestInterface $request
     *
     * @return FormInterface
     */
    public function handleRequest(RequestInterface $request);

    /**
     * Валидация полученных данных
     *
     * @return FormInterface
     *
     * @throws ValidationException
     */
    public function validate();
}