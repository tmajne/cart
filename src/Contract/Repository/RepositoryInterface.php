<?php

declare(strict_types = 1);

namespace Nova\Contract\Repository;

interface RepositoryInterface
{
    public const DEFAULT_PAGE = 1;
    public const DEFAULT_LIMIT = 3;

    /**
     * @param int $page
     * @param int $limit
     *
     * @return array
     */
    public function getAll(int $page = self::DEFAULT_PAGE, int $limit = self::DEFAULT_LIMIT): array;
}
