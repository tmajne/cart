<?php

declare(strict_types = 1);

namespace Gog;

use Nova\Dao\FastCache\CartDao;
use Nova\Repository\CartRepository;
use Nova\Dao\FastCache\GameDao;
use Nova\Repository\GameRepository;
use phpFastCache\CacheManager;

class Container
{
    /**
     * @return \ArrayAccess
     */
    public static function get(): \ArrayAccess
    {
        return static::load();
    }

    /**
     * @return \ArrayAccess
     */
    private static function load(): \ArrayAccess
    {
        $dic = new \Pimple\Container();

        $dic['cart.repository'] = function ($c) {
            return new CartRepository($c['cart.dao']);
        };

        $dic['cart.dao'] = function ($c) {
            return new CartDao($c['fastcache']);
        };

        $dic['game.repository'] = function ($c) {
            return new GameRepository($c['game.dao']);
        };

        $dic['game.dao'] = function ($c) {
            return new GameDao($c['fastcache']);
        };

        $dic['fastcache'] = function () {
            CacheManager::setDefaultConfig([
                'path' => sys_get_temp_dir()
            ]);
            return CacheManager::getInstance('sqlite');
        };

        return $dic;
    }
}
