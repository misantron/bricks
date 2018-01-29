<?php

namespace Bricks\Data;

/**
 * Interface TransducerInterface
 * @package Bricks\Data
 */
interface TransducerInterface
{
    /**
     * @param array $data
     * @return array
     */
    public function execute(array $data): array;
}