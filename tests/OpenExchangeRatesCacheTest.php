<?php

use OpenExchangeRatesWrapper\caches\FileCache;
use OpenExchangeRatesWrapper\OpenExchangeRates;
use org\bovigo\vfs\vfsStream;
use PHPUnit\Framework\TestCase;

class OpenExchangeWithCacheTest extends TestCase
{
    protected static $fakeId = "hello";

    public function setUp()
    {
        $this->root = vfsStream::setup("mydir");
        $this->id = getenv("OPENEXCHANGERATES_ID");
    }

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
            null,
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
    }

    public function testCacheHandlerPropertyIsProtected(): void
    {
        $oxr = new OpenExchangeRates(self::$fakeId);
        $this->expectException("Error");
        $oxr->cacheHandler;
    }

    public function testCacheHandlerWorkWhenSpecified(): void
    {

        if (!$this->id) {
            $this->markTestSkipped(
                "No id from env"
            );
        }

        // skip now
        $this->markTestSkipped(
            "comment or delete this if you will testing this again"
        );

        $cache = new FileCache(1 / 360, vfsStream::url("mydir"));
        $oxr = new OpenExchangeRates($this->id, [
            'cacheHandler' => $cache,
        ]);

        $this->assertFalse($this->root->hasChild("caches/latest.json"));

        $latestFromApi = $oxr->latest();

        $this->assertTrue($this->root->hasChild("caches/latest.json"));

        $latestFromCache = $oxr->latest();

        $this->assertEquals(
            $latestFromApi,
            $latestFromCache
        );
    }

    public function testCacheHandlerNotCachingWhenNotSpecified(): void
    {
        if (!$this->id) {
            $this->markTestSkipped(
                "no id from env"
            );
        }

        $oxr = new OpenExchangeRates($this->id);

        $this->assertFalse($this->root->hasChild("caches/latest.json"));

        $latest = $oxr->latest();

        $this->assertFalse($this->root->hasChild("caches/latest.json"));

        $fullpath = realpath(__DIR__ . '/../caches/latest.json');

        $this->assertFalse(file_exists($fullpath));

    }

}
