<?php

use OpenExchangeRatesWrapper\caches\FileCache;
use OpenExchangeRatesWrapper\OpenExchangeRates;
use org\bovigo\vfs\vfsStream;
use PHPUnit\Framework\TestCase;

class OpenExchangeWithCacheTest extends TestCase
{
    protected static $fakeId = "hello";

    private function invokeMethod($object, $methodName, $params = [])
    {
        $reflection = new \ReflectionClass(get_class($object));
        $method = $reflection->getMethod($methodName);
        $method->setAccessible(true);

        return $method->invokeArgs($object, $params);
    }

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

        $latestFromCache = json_decode($this->root->getChild("caches/latest.json")
                ->getContent());

        $this->assertEquals(
            $latestFromApi,
            json_decode($latestFromCache->value)
        );

        $latestAgain = $oxr->latest();
        $this->assertEquals(
            $latestAgain,
            json_decode($latestFromCache->value)
        );

    }

    public function testCacheHandlerNotCachingWhenNotSpecified(): void
    {
        if (!$this->id) {
            $this->markTestSkipped(
                "no id from env"
            );
        }
        // skip now
        $this->markTestSkipped(
            "comment or delete this if you will testing this again"
        );

        $oxr = new OpenExchangeRates($this->id);

        $this->assertFalse($this->root->hasChild("caches/latest.json"));

        $latest = $oxr->latest();

        $this->assertFalse($this->root->hasChild("caches/latest.json"));

        $fullpath = realpath(__DIR__ . '/../caches/latest.json');

        $this->assertFalse(file_exists($fullpath));

    }

    public function testShouldNotCacheStatusEndpoint(): void
    {
        // skip now
        $this->markTestSkipped(
            "comment or delete this if you will testing this again"
        );
        $cache = new FileCache(12, vfsStream::url("mydir"));
        $oxr = new OpenExchangeRates($this->id, [
            "cacheHandler" => $cache,
        ]);

        $this->assertFalse($this->root->hasChild('caches/status.json'));
        $oxr->usage();
        $this->assertFalse($this->root->hasChild('caches/status.json'));

    }

    public function testHandleSetToCache(): void
    {
        $cache = new FileCache(12, vfsStream::url("mydir"));
        $oxr = new OpenExchangeRates($this->id, [
            "cacheHandler" => $cache,
        ]);

        $this->assertFalse($this->root->hasChild('caches/latest.json'));
        $this->invokeMethod($oxr, 'handleSetToCache', [
            'latest', 'hello world', false,
        ]);

        $this->assertTrue($this->root->hasChild('caches/latest.json'));
    }

    public function testHandleGetFromCacheIfFileNotValid(): void
    {
        vfsStream::newFile("caches/hello.json")
            ->at($this->root);

        $cache = new FileCache(12, vfsStream::url("mydir"));
        $oxr = new OpenExchangeRates($this->id, [
            "cacheHandler" => $cache,
        ]);

        $this->assertNull(
            $this->invokeMethod($oxr, 'handleGetFromCache', ['hello'])
        );

    }

    public function testHandleGetFromCacheIfFileNoValueArg(): void
    {
        vfsStream::newFile("caches/hello.json")
            ->at($this->root)
            ->setContent(json_encode(['timestamp' => 'timestamp']));

        $cache = new FileCache(12, vfsStream::url("mydir"));
        $oxr = new OpenExchangeRates($this->id, [
            "cacheHandler" => $cache,
        ]);

        $this->assertNull(
            $this->invokeMethod($oxr, 'handleGetFromCache', ['hello'])
        );
    }

    public function testHandleGetFromCacheIfFileNoTimestampArg(): void
    {

        vfsStream::newFile("caches/hello.json")
            ->at($this->root)
            ->setContent(json_encode(['value' => 'timestamp']));

        $cache = new FileCache(12, vfsStream::url("mydir"));
        $oxr = new OpenExchangeRates($this->id, [
            "cacheHandler" => $cache,
        ]);

        $this->assertNull(
            $this->invokeMethod($oxr, 'handleGetFromCache', ['hello'])
        );
    }

    public function testHandleGetFromCache(): void
    {

        $timestamp = time();

        vfsStream::newFile("caches/hello.json")
            ->at($this->root)
            ->setContent(json_encode([
                'value' => '{"name" : "me"}',
                'timestamp' => $timestamp,
            ]));

        $cache = new FileCache(12, vfsStream::url("mydir"));
        $oxr = new OpenExchangeRates($this->id, [
            "cacheHandler" => $cache,
        ]);

        $response = $this->invokeMethod($oxr, 'handleGetFromCache', ['hello']);

        $this->assertEquals(
            'me',
            $response->name
        );
    }

}
