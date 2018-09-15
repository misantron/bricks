<?php

namespace Bricks;

use Bricks\Exception\ValidationException;
use Psr\Http\Message\RequestInterface;

/**
 * Interface FormInterface
 * @package Bricks
 */
interface FormInterface
{
    /**
     * @param array $data
     *
     * @return FormInterface
     */
    public function setData(array $data);

    /**
     * @return array
     */
    public function getData(): array;

    /**
     * @param RequestInterface $request
     *
     * @return FormInterface
     */
    public function handleRequest(RequestInterface $request);

    /**
     * @return FormInterface
     *
     * @throws ValidationException
     */
    public function validate();
}