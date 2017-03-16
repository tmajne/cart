<?php

declare(strict_types = 1);

namespace Nova\Dao\FastCache;

use Nova\Contract\Dao\DaoInterface;
use Nova\Dao\IdGeneratorTrait;
use Nova\Exception\DaoNotFoundException;
use phpFastCache\Cache\ExtendedCacheItemPoolInterface;

abstract class AbstractDao implements DaoInterface
{
    use IdGeneratorTrait;

    /** @var ExtendedCacheItemPoolInterface  */
    private $storage;

    /**
     * AbstractDao constructor.
     *
     * @param ExtendedCacheItemPoolInterface $storage
     */
    public function __construct(ExtendedCacheItemPoolInterface $storage)
    {
        $this->storage = $storage;
    }

    /**
     * @inheritdoc
     */
    public function get($id): array
    {
        $item = $this->storage->getItem($id);
        if (!$item->isHit()) {
            throw new DaoNotFoundException('Object not found. Id: '.$id);
        }

        return $item->get();
    }

    /**
     * @inheritdoc
     */
    public function getAll(int $page, int $limit): array
    {
        $items = $this->storage->getItemsByTag(static::TAG_NAME);

        $rows = [];
        if (is_array($items)) {
            $offset = ($page - 1) * $limit;
            $items = array_slice($items, $offset, $limit);
            foreach ($items as $item) {
                $rows[] = $item->get();
            }
        }

        return $rows;
    }

    /**
     * @inheritdoc
     */
    public function save(array $object)
    {
        if (true === empty($object['id'])) {
            $object['id'] = $this->generateId();
        }

        $item = $this->storage->getItem($object['id']);
        $item->set($object)
            ->addTags([static::TAG_NAME])
            ->expiresAfter(-1);

        $this->storage->save($item);

        return $object['id'];
    }

    /**
     * @inheritdoc
     */
    public function remove($id): void
    {
        $this->storage->deleteItem($id);
    }
}
