<?php

use OpenExchangeRatesWrapper\Caches\FileCache;
use org\bovigo\vfs\vfsStream;
use PHPUnit\Framework\TestCase;

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
        $cache = new FileCache(1, vfsStream::url("mydir"));
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
        $filename = $cache->setFile("hello");
        $this->assertTrue($this->root->hasChild("caches/hello", "file hello should exists"));
        $this->assertEquals(
            $this->root->getChild("caches/hello")->url(),
            $filename
        );
    }

    public function testIsValidDate(): void
    {
        $timestamp = time();

        $cache = new FileCache(1 / 360, vfsStream::url("mydir"));
        $this->assertTrue($cache->isValidTime($timestamp));
        $timestamp -= 10;
        $this->assertFalse($cache->isValidTime($timestamp));
    }

    public function testExpiredAt(): void
    {
        $timestamp = time();
        $expiredAt = $timestamp + 10;

        $cache = new FileCache(1 / 360, vfsStream::url("mydir"));
        $this->assertEquals(
            $expiredAt,
            $cache->getExpiredAt($timestamp)
        );
    }

    public function testSetFunction(): void
    {
        $cache = new FileCache(1 / 360, vfsStream::url("mydir"));
        $cache->set("hello", "this is value of hello");

        $this->assertTrue(
            $this->root->hasChild("caches/hello.json")
        );

        $content = $this->root->getChild("caches/hello.json")->getContent();
        $contentJson = json_decode($content);

        $this->assertEquals(
            "this is value of hello",
            $contentJson->value
        );
    }

    public function testSetFunctionShouldNotReplaceWhenNotExpired(): void
    {
        $timestamp = time();
        $content = [
            "timestamp" => $timestamp,
            "value" => "hello world",
        ];

        vfsStream::newFile('caches/hello.json')
            ->at($this->root)
            ->setContent(json_encode($content));

        $cache = new FileCache(1 / 360, vfsStream::url("mydir"));
        $cache->set("hello", "foo bar"); // should not replaced with this

        $newContent = $this->root->getChild("caches/hello.json")->getContent();
        $newContentJson = json_decode($newContent);

        $this->assertEquals(
            "hello world",
            $newContentJson->value
        );

        $this->assertEquals(
            $timestamp,
            $newContentJson->timestamp
        );
    }

    public function testSetFunctionShouldOverwriteIfExpired()
    {
        $timestamp = time() - 100;
        $content = [
            "timestamp" => $timestamp,
            "value" => "hello world",
        ];

        vfsStream::newFile('caches/hello.json')
            ->at($this->root)
            ->setContent(json_encode($content));

        $cache = new FileCache(1 / 360, vfsStream::url("mydir"));
        $cache->set("hello", "foo bar");

        $newContent = $this->root->getChild("caches/hello.json")->getContent();
        $newContentJson = json_decode($newContent);

        $this->assertEquals(
            "foo bar",
            $newContentJson->value
        );

        $this->assertTrue(
            $newContentJson->timestamp > $timestamp
        );

    }

    public function testGetFunction(): void
    {

        // create a file
        $timestamp = time();
        $content = [
            "timestamp" => $timestamp,
            "value" => "foo bar",
        ];

        vfsStream::newFile('caches/foo.json')->at($this->root)->setContent(json_encode($content));

        $cache = new FileCache(1 / 360, vfsStream::url("mydir"));
        $contentGet = $cache->get("foo");

        $this->assertEquals(
            $timestamp,
            $contentGet->timestamp
        );

        $this->assertEquals(
            "foo bar",
            $contentGet->value
        );

    }

    public function testGetFunctionReturnFalseIfExpired(): void
    {
        // create a file
        $timestamp = time() - 20;
        $content = [
            "timestamp" => $timestamp,
            "value" => "foo bar",
        ];

        vfsStream::newFile('caches/foo.json')
            ->at($this->root)
            ->setContent(json_encode($content));

        $cache = new FileCache(1 / 360, vfsStream::url("mydir"));

        $this->assertFalse($cache->get("foo"));
    }

    public function testGetFunctionReturnFalseIfNotFound(): void
    {
        $cache = new FileCache(1 / 360, vfsStream::url("mydir"));
        $this->assertFalse($cache->get("not found"));
    }

}
