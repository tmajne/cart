<?php

declare(strict_types = 1);

namespace Nova\Contract\Dao;

use Nova\Exception\DaoNotFoundException;

interface DaoInterface
{
    /**
     * @param mixed|string $id
     *
     * @throws DaoNotFoundException
     *
     * @return array
     */
    public function get($id): array;

    /**
     * @param int $page
     * @param int $limit
     *
     * @return array
     */
    public function getAll(int $page, int $limit): array;

    /**
     * @param array $game
     *
     * @return mixed|string
     */
    public function save(array $game);

    /**
     * @param mixed|string $id
     *
     * @return void
     */
    public function remove($id): void;
}
