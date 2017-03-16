<?php

declare(strict_types = 1);

namespace Gog\Api;

use Gog\Container;
use Gog\Contract\Repository\CartRepository;
use Gog\Entity\Cart;
use Gog\Entity\Game;
use Gog\Exception\CartItemNotFoundException;
use Gog\Repository\GameRepository;

class CartApi
{
    /** @var CartRepository */
    private $cartRepository;

    /** @var GameRepository */
    private $gameRepository;

    public function __construct()
    {
        $dic = Container::get();
        $this->cartRepository = $dic['cart.repository'];
        $this->gameRepository = $dic['game.repository'];
    }

    public function index($page = 1, $limit = 3)
    {
        // For admin ?
        $carts = $this->cartRepository->getAll($page, $limit);
        print_r($carts);
    }

    public function show($id)
    {
        $cart = $this->cartRepository->get($id);

        // max 3 products
        // return total price of products

        print_r($cart);
    }

    public function create()
    {
        $cart = new Cart();
        $this->cartRepository->add($cart);
    }

    public function remove($id)
    {
        $cart = $this->cartRepository->get($id);
        $this->cartRepository->remove($cart);
    }

    public function addItem($cartId, $gameId, int $count = 1)
    {
        /** @var Game $game */
        $game = $this->gameRepository->get($gameId);

        /** @var Cart $cart */
        $cart = $this->cartRepository->get($cartId);

        $cart->addItem($game, $count);
        //$cart->addItem($game);

        $this->cartRepository->add($cart);
        print_r($cart);
    }

    public function removeItem($cartId, $gameId, int $count = 0)
    {
        /** @var Game $game */
        $game = $this->gameRepository->get($gameId);

        /** @var Cart $cart */
        $cart = $this->cartRepository->get($cartId);

        try {
            $cart->removeItem($game, $count);
            $this->cartRepository->add($cart);
        } catch (CartItemNotFoundException $e) {
            echo "EXCEPTION: ".$e->getMessage()."\n\n";
        }
    }

    public function changeItemQuantity($cartId, $gameId, int $deltaQuantity)
    {
        /** @var Game $game */
        $game = $this->gameRepository->get($gameId);

        /** @var Cart $cart */
        $cart = $this->cartRepository->get($cartId);

        try {
            $cart->changeItemQuantity($game, $deltaQuantity);
            $this->cartRepository->add($cart);
        } catch (CartItemNotFoundException $e) {
            echo "EXCEPTION: ".$e->getMessage()."\n\n";
        }
    }
}
