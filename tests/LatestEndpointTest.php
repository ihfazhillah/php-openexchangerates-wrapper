<?php

use OpenExchangeRatesWrapper\Endpoints\Base;
use OpenExchangeRatesWrapper\Endpoints\Latest;
use PHPUnit\Framework\TestCase;

class LatestEndpointTest extends TestCase
{

    protected static $fakeId = "hello";

    public function testEndpointInstance(): void
    {

        $this->assertInstanceOf(
            Latest::class,
            new Latest(self::$fakeId)
        );
    }

    public function testLatestExtendingBase(): void
    {
        $this->assertTrue(
            is_subclass_of(new Latest(self::$fakeId), Base::class)
        );
    }

    public function testGetBaseUrl(): void
    {
        $this->assertEquals(
            "http://openexchangerates.org/api/",
            (new Latest(self::$fakeId))->getBaseUrl()
        );
    }

    public function testGetEndPoint(): void
    {
        $queries = [
            "base" => "IDR",
            "symbols" => "SAR,USD",
        ];

        $endpoint = (new Latest(self::$fakeId))->getEndpoint($queries);

        $this->assertEquals(
            "http://openexchangerates.org/api/latest.json?app_id=hello&base=IDR&symbols=SAR%2CUSD",
            $endpoint
        );

    }
}
