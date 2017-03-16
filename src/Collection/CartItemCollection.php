<?php

declare(strict_types = 1);

namespace Nova\Collection;

use Nova\Contract\CollectionInterface;
use Nova\Entity\CartItem;
use Nova\Exception\CartItemNotFoundException;

class CartItemCollection implements CollectionInterface
{
    /** @var array  */
    private $items = [];

    public function getIterator()
    {
        return $this->items;
    }

    /**
     * @return int
     */
    public function count(): int
    {
        return count($this->items);
    }

    /**
     * @param CartItem $item
     */
    public function add(CartItem $item): void
    {
        if (!$this->contain($item)) {
            $this->items[$item->getId()] = $item;
        } else {
            $this->changeItemQuantity($item, $item->getQuantity());
        }
    }

    /**
     * @param CartItem $item
     * @throws CartItemNotFoundException
     */
    public function remove(CartItem $item): void
    {
        if (!$this->contain($item)) {
            throw new CartItemNotFoundException(
                "Item: {$item->getId()} does not exist in cart"
            );
        }

        unset($this->items[$item->getId()]);
    }

    /**
     * @param CartItem $item
     * @return bool
     */
    public function contain(CartItem $item): bool
    {
        return array_key_exists($item->getId(), $this->items);
    }

    /**
     * @param CartItem $item
     * @param int $deltaQuantity
     * @throws CartItemNotFoundException
     */
    public function changeItemQuantity(CartItem $item, int $deltaQuantity): void
    {
        $cartItem = $this->getItemByKey($item->getId());
        if (!$cartItem) {
            throw new CartItemNotFoundException(
                "Item: {$item->getId()} does not exist in cart"
            );
        }

        $cartItem->increaseQuantity($deltaQuantity);

        if ($cartItem->getQuantity() <= 0) {
            $this->remove($cartItem);
        }
    }

    /**
     * @return int
     */
    public function getTotalPrice(): int
    {
        $price = 0;
        foreach ($this->items as $item) {
            /** @var CartItem $item */
            $price += ($item->getQuantity() * $item->getProduct()->getPrice());
        }

        return $price;
    }

    /**
     * @param mixed $key
     * @return CartItem|null
     */
    private function getItemByKey($key): ?CartItem
    {
        return $this->items[$key] ?? null;
    }
}
