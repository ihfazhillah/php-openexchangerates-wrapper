<?php

use OpenExchangeRatesWrapper\Endpoint;
use PHPUnit\Framework\TestCase;

class EndpointTest extends TestCase
{
    protected static $fakeId = "hello_world";

    public function testCanInitiateEndpoint(): void
    {
        $this->assertInstanceOf(
            Endpoint::class,
            new Endpoint(self::$fakeId)
        );
    }

    public function testMustIncludeAppId(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage("expecting app_id string as first argument");
        new Endpoint();
    }

    public function testGetBaseEndpoint(): void
    {
        $this->assertEquals(
            "http://openexchangerates.org/api/",
            (new Endpoint(self::$fakeId))->getBaseEndpoint()
        );
    }

    public function testGetBaseEndpointHttps(): void
    {
        $this->assertEquals(
            "https://openexchangerates.org/api/",
            (new Endpoint(self::$fakeId, ["https" => true]))->getBaseEndpoint()
        );
    }

    public function testLatestEndpoint(): void
    {
        $this->assertEquals(
            "http://openexchangerates.org/api/latest.json?app_id=hello_world",
            (new Endpoint(self::$fakeId))->latest()
        );
    }

    public function testLatestEndpointWithBase(): void
    {
        $this->assertEquals(
            "http://openexchangerates.org/api/latest.json?app_id=hello_world&base=IDR",
            (new Endpoint(self::$fakeId))->latest("IDR")
        );
    }

    public function testLatestEndpointWithSymbols(): void
    {
        $this->assertEquals(
            "http://openexchangerates.org/api/latest.json?app_id=hello_world&symbols=IDR%2CSAR",
            (new Endpoint(self::$fakeId))->latest("", "IDR,SAR")
        );
    }

    public function testLatestEndpointWithShowAlternative(): void
    {
        $this->assertEquals(
            "http://openexchangerates.org/api/latest.json?app_id=hello_world&show_alternatives=true",
            (new Endpoint(self::$fakeId))->latest("", "", true)
        );
        $this->assertEquals(
            "http://openexchangerates.org/api/latest.json?app_id=hello_world",
            (new Endpoint(self::$fakeId))->latest("", "", false)
        );
    }

    public function testHistoricalEndpointShouldWithDate(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage("date YYYY-MM-DD format required");
        (new Endpoint(self::$fakeId))->historical();
    }

    public function testHistoricalEndpointSuccess(): void
    {
        $this->assertEquals(
            "http://openexchangerates.org/api/historical/2010-08-12.json",
            (new Endpoint(self::$fakeId))->historical("2010-08-12")
        );
    }
}
