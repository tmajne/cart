<?php

declare(strict_types = 1);

namespace Nova\Entity;

use Nova\Contract\EntityInterface;

class Game implements EntityInterface
{
    /** @var  int */
    private $id;

    /** @var  string */
    private $title;

    /** @var  int */
    private $price;

    /**
     * Game constructor.
     *
     * @param string $title
     * @param int    $price
     */
    public function __construct(string $title, int $price)
    {
        $this->title = $title;
        $this->price = $price;
    }

    public function getId()
    {
        return $this->id;
    }

    public function setId($id): void
    {
        $this->id = $id;
    }

    /**
     * @return string
     */
    public function getTitle(): string
    {
        return $this->title;
    }

    /**
     * @param string $title
     */
    public function setTitle(string $title): void
    {
        $this->title = $title;
    }

    /**
     * @return int
     */
    public function getPrice(): int
    {
        return $this->price;
    }

    /**
     * @return float
     */
    public function getPriceHr(): float
    {
        return $this->price;
    }

    /**
     * @param int $price
     */
    public function setPrice(int $price): void
    {
        $this->price = $price;
    }

    /**
     * @return array
     */
    public function toArray(): array
    {
        return [
            'id' => $this->getId(),
            'title' => $this->getTitle(),
            'price' => $this->getPrice()
        ];
    }
}
