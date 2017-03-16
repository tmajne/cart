<?php

declare(strict_types = 1);

namespace Nova\Repository;

use Nova\Contract\Dao\DaoInterface;
use Nova\Contract\Repository\GameRepositoryInterface;
use Nova\Entity\Game;
use Nova\Exception\DaoException;
use Nova\Exception\DaoNotFoundException;
use Nova\Exception\EntityNotFoundException;
use Nova\Exception\RepositoryException;

class GameRepository implements GameRepositoryInterface
{
    /** @var DaoInterface */
    private $dao;

    /**
     * GameRepository constructor.
     *
     * @param DaoInterface $dao
     */
    public function __construct(DaoInterface $dao)
    {
        $this->dao = $dao;
    }

    /**
     * @inheritdoc
     */
    public function get($id): Game
    {
        try {
            $data = $this->dao->get($id);
            $entity = $this->hydrateEntity($data);
            return $entity;
        } catch (DaoNotFoundException $e) {
            throw new EntityNotFoundException('Entity not found: '.$id, 0, $e);
        } catch (DaoException $e) {
            throw new RepositoryException("Repository Error", 0, $e);
        }
    }

    /**
     * @inheritdoc
     */
    public function getAll(int $page = self::DEFAULT_PAGE, int $limit = self::DEFAULT_LIMIT): array
    {
        $rows = $this->dao->getAll($page, $limit);

        $games = [];
        if (is_array($games)) {
            $games = $this->hydrateEntities($rows);
        }

        return $games;
    }

    /**
     * @inheritdoc
     */
    public function add(Game $game): void
    {
        $data = $this->convertEntity($game);
        $this->dao->save($data);
    }

    /**
     * @inheritdoc
     */
    public function remove(Game $game): void
    {
        $this->dao->remove($game->getId());
    }

    /**
     * @param array $games
     * @return array
     */
    private function hydrateEntities(array $games): array
    {
        $entities = [];
        foreach ($games as $game) {
            $entities[] = $this->hydrateEntity($game);
        }

        return $entities;
    }

    /**
     * @param array $gameData
     * @return Game
     */
    private function hydrateEntity(array $gameData): Game
    {
        $game = new Game(
            $gameData['title'],
            $gameData['price']
        );
        $game->setId($gameData['id']);

        return $game;
    }

    /**
     * @param Game $game
     * @return array
     */
    private function convertEntity(Game $game): array
    {
        return $game->toArray();
    }
}
