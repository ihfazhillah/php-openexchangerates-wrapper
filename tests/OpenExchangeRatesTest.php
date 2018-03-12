<?php

use PHPUnit\Framework\TestCase;
use GuzzleHttp\Client;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Psr7\Response;

use OpenExchangeRatesWrapper\OpenExchangeRates;
use OpenExchangeRatesWrapper\Endpoint;

class OpenExchangeRatesTest extends TestCase
{

    protected static $fakeId = "hello";

    public function testInstanceOf()
    {
        $this->assertInstanceOf(
            OpenExchangeRates::class,
            new OpenExchangeRates(self::$fakeId)
        );
    }

    public function testGetAppId()
    {
        $this->assertEquals(
            "hello",
            (new OpenExchangeRates(self::$fakeId))->getAppId()
        );
    }

    public function testGetOptions()
    {
        $options = [
            "https" => true
        ];
        $this->assertEquals(
            $options,
            (new OpenExchangeRates(self::$fakeId, $options))->getOptions()
        );
    }

    public function testGetDefaultOptions()
    {
        $this->assertEquals(
            [
                "https" => false
            ],
            (new OpenExchangeRates(self::$fakeId))->getOptions()
        );
    }

    public function testClientInstance()
    {
        $this->assertInstanceOf(
            Client::class,
            (new OpenExchangeRates(self::$fakeId))->client
        );
    }

    public function testEndpointInstance()
    {
        $this->assertInstanceOf(
            Endpoint::class,
            (new OpenExchangeRates(self::$fakeId))->endpoint
        );
    }
}

