<?php

use PHPUnit\Framework\TestCase;
use OpenExchangeRatesWrapper\Caches\FileCache;

class FileCacheTest extends TestCase
{
    public function testInstanceOf()
    {
        $this->assertInstanceOf(
            FileCache::class,
            new FileCache
        );
    }

    public function testGetDefaultDirOfCache()
    {
        $defaultdir = realpath(__DIR__ . '/../..');
        $this->assertEquals(
            $defaultdir,
            (new FileCache)->getCacheDir()
        );
    }

    public function testGetDirOfCache()
    {
        $this->assertEquals(
            realpath(__DIR__),
            (new FileCache(null, realpath(__DIR__)))->getCacheDir()
        );
    }

    public function testGetDefaultExpireAfter()
    {
        $this->assertEquals(
            24,
            (new FileCache)->getExpireAfter()
        );
    }

    public function testGetExpireAfter()
    {
        $this->assertEquals(
            1,
            (new FileCache(1))->getExpireAfter()
        );
    }

    public function testGetExpiredAfterInSeconds()
    {
        $this->assertEquals(
            1 * 60 * 60,
            (new FileCache(1))->getExpireAfterSeconds()
        );
    }

    public function testThrowErrorWhenParentDirNotFound()
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage("/hello/world dir not found");
        new FileCache(null, "/hello/world");
    }

}
