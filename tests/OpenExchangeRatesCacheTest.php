<?php

use OpenExchangeRatesWrapper\caches\FileCache;
use OpenExchangeRatesWrapper\OpenExchangeRates;
use PHPUnit\Framework\TestCase;

class OpenExchangeWithCacheTest extends TestCase
{
    protected static $fakeId = "hello";
    public function testInstance(): void
    {
        $this->assertInstanceOf(
            OpenExchangeRates::class,
            new OpenExchangeRates(self::$fakeId)
        );
    }

    public function testDefaultCacheHandler(): void
    {
        $oxr = new OpenExchangeRates(self::$fakeId);
        $cache = $oxr->getCacheHandler();

        $this->assertEquals(
            24, // in hours
            $cache->getExpireAfter()
        );

        $this->assertInstanceOf(
            FileCache::class,
            $cache
        );
    }

    public function testCustomCacheHandler(): void
    {
        $cache = new FileCache(1);
        $oxr = new OpenExchangeRates(self::$fakeId, [
            'cacheHandler' => $cache,
        ]);

        $this->assertEquals(
            1,
            $oxr->getCacheHandler()->getExpireAfter()
        );

        $this->expectException(\Error::class);
        $oxr->cacheHandler;
    }

}
