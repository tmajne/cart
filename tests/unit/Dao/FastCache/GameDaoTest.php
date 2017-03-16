<?php

namespace Nova\Tests\Unit\Dao\FastCache;

use Nova\Contract\Dao\DaoInterface;
use Nova\Dao\FastCache\GameDao;
use Nova\Tests\TestCase; 
use Mockery;
use phpFastCache\Cache\ExtendedCacheItemPoolInterface;

class GameDaoTest extends TestCase
{
    /** @var ExtendedCacheItemPoolInterface */
    private $cache;

    /** @var GameDao */
    private $dao;

    protected function setUp()
    {
        parent::setUp();

        $this->cache = Mockery::mock(ExtendedCacheItemPoolInterface::class);
        $this->dao = new GameDao($this->cache);
    }

    public function testCreate()
    {
        $fastCacheMock = Mockery::mock(ExtendedCacheItemPoolInterface::class);
        $dao = new GameDao($fastCacheMock);

        $this->assertInstanceOf(DaoInterface::class, $dao);
    }

    /**
     * TODO: use data provider
     */
    public function testGet()
    {
        $id = 32;
        $expectedResult = ['id' => $id, 'title' => 'Quake', 'price' => 1200];

        $this->cache->shouldReceive('getItem')
            ->once()
            ->andReturnUsing(function($key) use ($expectedResult) {

                $this->assertSame($expectedResult['id'], $key);

                $item = Mockery::mock(ExtendedCacheItemInterface::class);
                $item->shouldReceive('isHit')->once()->andReturn(true);
                $item->shouldReceive('get')->once()->andReturn($expectedResult);
                return $item;
            });

        $data = $this->dao->get($id);

        $this->assertSame($expectedResult, $data);
    }

    /**
     * @expectedException \Nova\Exception\DaoNotFoundException
     * @expectedExceptionMessage Object not found. Id: 132
     */
    public function testGetNotFound()
    {
        $id = 132;

        $this->cache->shouldReceive('getItem')
            ->once()
            ->andReturnUsing(function($key) use ($id) {

                $this->assertSame($id, $key);

                $item = Mockery::mock(ExtendedCacheItemInterface::class);
                $item->shouldReceive('isHit')->once()->andReturn(false);
                return $item;
            });

        $this->dao->get($id);
    }
}
