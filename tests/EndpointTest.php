<?php

use OpenExchangeRatesWrapper\Endpoint;
use OpenExchangeRatesWrapper\Endpoints\Latest;
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

    public function testGetAllowedEndpoints(): void
    {
        $endpoint = new Endpoint(self::$fakeId);
        $this->assertEquals(
            ["latest", "historical", "currencies", "time-series", "convert", "ohlc", "status"],
            $endpoint->getAllowedEndpoints()
        );
    }

    public function testGetEndpointInstance(): void
    {
        $endpoint = new Endpoint(self::$fakeId);
        $this->assertInstanceOf(
            Latest::class,
            $endpoint->getEndpointInstance("latest")
        );
    }

    public function testGetEndpointInstanceNotFound(): void
    {
        $endpoint = new Endpoint(self::$fakeId);

        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage("hello endpoint not found");
        $endpoint->getEndpointInstance("hello");

    }

}
