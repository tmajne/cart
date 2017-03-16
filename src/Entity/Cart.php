<?php

declare(strict_types = 1);

namespace Nova\Entity;

use Nova\Collection\CartItemCollection;
use Nova\Contract\EntityInterface;
use Nova\Exception\CartItemLimitException;

class Cart implements EntityInterface
{
    /** @var  mixed|string */
    private $id;

    /** @var  CartItem[] */
    private $items;

    /** @var string  */
    private $currency = 'USD';

    /** @var int  */
    private $itemsLimit = 3;

    public function __construct(array $items = [])
    {
        $this->items = new CartItemCollection();

        foreach ($items as $item) {
            if (!$item instanceof CartItem) {
                throw new \InvalidArgumentException('Wrong type');
            }
            $this->items->add($item);
        }
    }

    public function getId()
    {
        return $this->id;
    }

    /**
     * @param mixed $id
     *
     * @return void
     */
    public function setId($id): void
    {
        $this->id = $id;
    }

    /**
     * @return CartItem[]
     */
    public function getItems(): array
    {
        return $this->items->getIterator();
    }

    /**
     * @param Game $game
     * @param int $count
     * @throws CartItemLimitException
     * @return void
     */
    public function addItem(Game $game, int $count = 1): void
    {
        if ($count <= 0) {
            throw new \InvalidArgumentException('Product quantity must greater than 0');
        }

        $cartItem = new CartItem($game, $count);
        if ($this->items->count() >= $this->itemsLimit && !$this->items->contain($cartItem)) {
            throw new CartItemLimitException("Yo can add only {$this->itemsLimit} items");
        }

        $this->items->add($cartItem);
    }

    /**
     * @param Game $game
     * @return void
     */
    public function removeItem(Game $game): void
    {
        $cartItem = new CartItem($game);
        $this->items->remove($cartItem);
    }

    /**
     * @param Game $game
     * @param int $deltaQuantity
     * @return void
     */
    public function changeItemQuantity(Game $game, int $deltaQuantity): void
    {
        $cartItem = new CartItem($game);
        $this->items->changeItemQuantity($cartItem, $deltaQuantity);
    }

    /**
     * @return int
     */
    public function getPrice(): int
    {
        return $this->items->getTotalPrice();
    }

    /**
     * @return string
     */
    public function getPriceHR(): string
    {
        $price = $this->items->getTotalPrice();
        $price = $price/100 . ' ' .$this->currency;

        return $price;
    }


    /**
     * @return string
     */
    public function getCurrency(): string
    {
        return $this->currency;
    }

    /**
     * @param string $currency
     * @return void
     */
    public function setCurrency(string $currency): void
    {
        $this->currency = $currency;
    }

    /**
     * @return int
     */
    public function getItemsLimit(): int
    {
        return $this->itemsLimit;
    }

    /**
     * @param int $itemsLimit
     * @return void
     */
    public function setItemsLimit(int $itemsLimit): void
    {
        $this->itemsLimit = $itemsLimit;
    }

    /**
     * @return array
     */
    public function toArray(): array
    {
        $items = [];

        foreach ($this->getItems() as $entityItem) {
            /** var CartItem $entityItem */
            $items[$entityItem->getId()] = $entityItem->toArray();
        }

        return [
            'id' => $this->id,
            'items_limit' => $this->itemsLimit,
            'currency' => $this->currency,
            'items' => $items,
            'price' => $this->getPrice(),
            'price_hr' => $this->getPriceHR()
        ];
    }
}
