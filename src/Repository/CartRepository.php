<?php

declare(strict_types = 1);

namespace Nova\Repository;

use Nova\Contract\Dao\DaoInterface;
use Nova\Contract\Repository\CartRepositoryInterface;
use Nova\Entity\Cart;
use Nova\Entity\Game;
use Nova\Exception\DaoException;
use Nova\Exception\DaoNotFoundException;
use Nova\Exception\EntityNotFoundException;
use Nova\Exception\RepositoryException;

class CartRepository implements CartRepositoryInterface
{
    /** @var DaoInterface  */
    private $dao;

    /**
     * CartRepository constructor.
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
    public function get($id): Cart
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

        $carts = [];
        if (is_array($carts)) {
            $carts = $this->hydrateEntities($rows);
        }

        return $carts;
    }

    /**
     * @inheritdoc
     */
    public function add(Cart $cart): void
    {
        $data = $this->convertEntity($cart);
        $this->dao->save($data);
    }

    /**
     * @inheritdoc
     */
    public function remove(Cart $cart): void
    {
        $this->dao->remove($cart->getId());
    }

    /**
     * @param array $carts
     * @return array
     */
    private function hydrateEntities(array $carts): array
    {
        $entities = [];
        foreach ($carts as $cart) {
            $entities[] = $this->hydrateEntity($cart);
        }

        return $entities;
    }

    /**
     * @param array $cartData
     * @return Cart
     */
    private function hydrateEntity(array $cartData): Cart
    {
        $cart = new Cart();
        $cart->setId($cartData['id']);

        if (is_array($cartData['items'])) {
            foreach ($cartData['items'] as $item) {
                $productData = $item['product'];
                $product = new Game($productData['title'], $productData['price']);
                $product->setId($item['id']);
                $cart->addItem($product, $item['quantity']);
            }
        }

        return $cart;
    }

    /**
     * @param Cart $cart
     * @return array
     */
    private function convertEntity(Cart $cart): array
    {
        $data = [];
        $data['id'] = $cart->getId();

        $items = [];
        foreach ($cart->getItems() as $item) {
            /** @var Game $product */
            $product = $item->getProduct();
            $items[$item->getId()] = [
                'id' => $item->getId(),
                'product' => [
                    'id' => $product->getId(),
                    'title' => $product->getTitle(),
                    'price' => $product->getPrice()
                ],
                'quantity' => $item->getQuantity()
            ];
        }

        return [
            'id' => $cart->getId(),
            'items' => $items
        ];
    }
}
