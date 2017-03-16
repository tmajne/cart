<?php

declare(strict_types = 1);

namespace Nova\Dao;

trait IdGeneratorTrait
{
    /**
     * Example generator
     * @return string
     */
    private function generateId(): string
    {
        return uniqid("", true);
    }
}
