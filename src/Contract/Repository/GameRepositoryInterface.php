<?php

declare(strict_types = 1);

namespace Nova\Contract\Repository;

use Nova\Entity\Game;
use Nova\Exception\EntityNotFoundException;
use Nova\Exception\RepositoryException;

interface GameRepositoryInterface extends RepositoryInterface
{
    /**
     * @param $id
     *
     * @throws EntityNotFoundException
     * @throws RepositoryException
     *
     * @return Game
     */
    public function get($id): Game;

    /**
     * @param Game $game
     */
    public function add(Game $game): void;

    /**
     * @param Game $game
     */
    public function remove(Game $game): void;
}
