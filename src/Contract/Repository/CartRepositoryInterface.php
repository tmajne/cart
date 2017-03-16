<?php

declare(strict_types = 1);

namespace Nova\Contract\Repository;

use Nova\Entity\Cart;

interface CartRepositoryInterface extends RepositoryInterface
{
    /**
     * @param $id
     *
     * @throws EntityNotFoundException
     * @throws RepositoryException
     *
     * @return Cart
     */
    public function get($id): Cart;

    /**
     * @param Cart $cart
     */
    public function add(Cart $cart): void;

    /**
     * @param Cart $cart
     *
     * @return void
     */
    public function remove(Cart $cart): void;
}
