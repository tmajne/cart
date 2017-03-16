<?php

declare(strict_types = 1);

namespace Nova\Entity;

use Nova\Contract\EntityInterface;

class CartItem implements EntityInterface
{
    /** @var  mixed */
    private $id;

    /** @var Game  */
    private $product;

    /** @var  int */
    private $quantity;

    public function __construct(Game $game, int $quantity = 1)
    {
        $this->id = $game->getId();
        $this->product = $game;
        $this->quantity = $quantity;
    }

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return Game
     */
    public function getProduct(): Game
    {
        return $this->product;
    }

    /**
     * @return int
     */
    public function getQuantity(): int
    {
        return $this->quantity;
    }

    /**
     * @param int $quantity
     */
    public function setQuantity(int $quantity): void
    {
        $this->quantity = $quantity;
    }

    /**
     * @param int $quantity
     */
    public function increaseQuantity(int $quantity): void
    {
        $this->quantity += $quantity;
    }

    public function toArray(): array
    {
        return [
            'id' => $this->getId(),
            'product' => $this->getProduct()->toArray(),
            'quantity' => $this->getQuantity()
        ];
    }
}
