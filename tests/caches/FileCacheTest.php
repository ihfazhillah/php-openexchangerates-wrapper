<?php

use PHPUnit\Framework\TestCase;
use OpenExchangeRatesWrapper\Caches\FileCache;
use org\bovigo\vfs\vfsStream,
    org\bovigo\vfs\vfsStreamDirectory;

class FileCacheTest extends TestCase
{
    protected $root;

    public function setUp()
    {
        $this->root = vfsStream::setup("mydir");
    }

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

    public function testTestsDirIsCreated(): void
    {
        $cache = new FileCache(1,vfsStream::url("mydir"));
        $this->assertFalse($this->root->hasChild("caches"), "folder caches must not exists");
        $cache->setDirectory();
        $this->assertTrue($this->root->hasChild("caches", "folder caches should exists after creation"));
        $cache->setDirectory();
        $this->assertTrue($this->root->hasChild("caches", "folder caches should exists after creation"));
    }

    public function testFileIsCreated(): void
    {
        $cache = new FileCache(1, vfsStream::url("mydir"));
        $this->assertFalse($this->root->hasChild("caches/hello", "file hello should noot exists"));
        $cache->setFile("hello");
        $this->assertTrue($this->root->hasChild("caches/hello", "file hello should exists"));
    }

    public function testIsValidDate(): void
    {
        $timestamp = time();

        $cache = new FileCache(1/360, vfsStream::url("mydir"));
        $this->assertTrue($cache->isValidTime($timestamp));
        $timestamp -= 10;
        $this->assertFalse($cache->isValidTime($timestamp));
    }

    public function testExpiredAt(): void
    {
        $timestamp = time();
        $expiredAt = $timestamp + 10;

        $cache = new FileCache(1/360, vfsStream::url("mydir"));
        $this->assertEquals(
            $expiredAt,
            $cache->getExpiredAt($timestamp)
        );
    }

}
