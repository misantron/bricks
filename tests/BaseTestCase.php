<?php

namespace Bricks\Tests;

use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\StreamInterface;
use Psr\Http\Message\UriInterface;

/**
 * Class BaseTestCase
 * @package Bricks\Tests
 */
class BaseTestCase extends TestCase
{
    /**
     * @param array $data
     * @param string $method
     *
     * @return MockObject|RequestInterface
     */
    protected function createQueryRequest(array $data, string $method = 'get')
    {
        $request = $this->getMockBuilder(RequestInterface::class)
            ->disableOriginalConstructor()
            ->getMock();

        $uri = $this->getMockBuilder(UriInterface::class)
            ->disableOriginalConstructor()
            ->getMock();

        $uri
            ->method('getQuery')
            ->willReturn(http_build_query($data));

        $request
            ->method('getMethod')
            ->willReturn($method);
        $request
            ->method('getUri')
            ->willReturn($uri);

        return $request;
    }

    /**
     * @param array|string $data
     * @param string $method
     *
     * @return MockObject|RequestInterface
     */
    protected function createJsonRequest($data, string $method = 'put')
    {
        $request = $this->getMockBuilder(RequestInterface::class)
            ->disableOriginalConstructor()
            ->getMock();

        $body = $this->getMockBuilder(StreamInterface::class)
            ->disableOriginalConstructor()
            ->getMock();

        $body
            ->method('getContents')
            ->willReturn(is_array($data) ? json_encode($data) : $data);

        $request
            ->method('getMethod')
            ->willReturn($method);
        $request
            ->method('getBody')
            ->willReturn($body);

        return $request;
    }
}