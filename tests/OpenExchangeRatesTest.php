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

    protected function getJsonResponseString(string $name): string
    {
        $filepath = __DIR__ . "/data/" . $name;
        return file_get_contents($filepath);
    }

    protected function createClient(string $responseBodyString)
    {
        $mock = new MockHandler([
            new Response(200, [], $responseBodyString)
        ]);

        $handler = HandlerStack::create($mock);

        return new Client(["handler" => $handler]);
    }

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

    public function testLatestEndpoint()
    {
        $client = $this->createClient(
            $this->getJsonResponseString("latest-success.json")
        );
        $oxr = new OpenExchangeRates(self::$fakeId, [], $client);
        $responseJsonObject = $oxr->latest();

        $this->assertEquals(
            "USD",
            $responseJsonObject->base
        );

        $this->assertEquals(
            13766.013762,
            $responseJsonObject->rates->IDR
        );
    }
}

