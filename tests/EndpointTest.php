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
            (new Endpoint(self::$fakeId))->latest(["base" => "IDR"])
        );
    }

    public function testLatestEndpointWithSymbols(): void
    {
        $this->assertEquals(
            "http://openexchangerates.org/api/latest.json?app_id=hello_world&symbols=IDR%2CSAR",
            (new Endpoint(self::$fakeId))->latest(["symbols" => "IDR,SAR"])
        );
    }

    public function testLatestEndpointWithShowAlternative(): void
    {
        $this->assertEquals(
            "http://openexchangerates.org/api/latest.json?app_id=hello_world&show_alternative=true",
            (new Endpoint(self::$fakeId))->latest(["show_alternative" => true])
        );
        $this->assertEquals(
            "http://openexchangerates.org/api/latest.json?app_id=hello_world",
            (new Endpoint(self::$fakeId))->latest(['show_alternative' => false])
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
            "http://openexchangerates.org/api/historical/2010-08-12.json?app_id=hello_world",
            (new Endpoint(self::$fakeId))->historical("2010-08-12")
        );
    }

    public function testHistoricalEndpointWithBase(): void
    {
        $this->assertEquals(
            "http://openexchangerates.org/api/historical/2010-08-12.json?app_id=hello_world&base=IDR",
            (new Endpoint(self::$fakeId))->historical("2010-08-12", ["base" => "IDR"])
        );
    }

    public function testHistoricalEndpointWithSymbols(): void
    {
        $this->assertEquals(
            "http://openexchangerates.org/api/historical/2010-08-12.json?app_id=hello_world&symbols=IDR%2CSAR",
            (new Endpoint(self::$fakeId))->historical("2010-08-12", ["symbols" => "IDR,SAR"])
        );
    }

    public function testHistoricalEndpointWithShowAlternatives(): void
    {
        $this->assertEquals(
            "http://openexchangerates.org/api/historical/2010-08-12.json?app_id=hello_world&show_alternatives=true",
            (new Endpoint(self::$fakeId))->historical("2010-08-12", ["show_alternatives" => true])
        );
    }
    public function testHistoricalEndpointWithShowAlternativesFalse(): void
    {
        $this->assertEquals(
            "http://openexchangerates.org/api/historical/2010-08-12.json?app_id=hello_world",
            (new Endpoint(self::$fakeId))->historical("2010-08-12", ["show_alternatives" => false])
        );
    }

}
