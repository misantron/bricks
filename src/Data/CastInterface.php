<?php

namespace Bricks\Data;

/**
 * Interface CastInterface
 * @package Bricks\Data
 */
interface CastInterface
{
    /**
     * @param array $data
     * @return array
     */
    public function execute(array $data): array;
}