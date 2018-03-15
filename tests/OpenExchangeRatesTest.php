<?php

use GuzzleHttp\Client;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\Psr7\Response;
use OpenExchangeRatesWrapper\Endpoint;
use OpenExchangeRatesWrapper\OpenExchangeRates;
use PHPUnit\Framework\TestCase;

class OpenExchangeRatesTest extends TestCase
{

    protected static $fakeId = "hello";

    protected function setUp()
    {
        $id = getenv("OPENEXCHANGERATES_ID", true);
        if (!$id) {
            $this->markTestSkipped(
                "no OPENEXCHANGERATES_ID environment var"
            );
        }

        $this->id = $id;
    }

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
            "https" => true,
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
                "https" => false,
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
        $this->markTestSkipped();
        $oxr = new OpenExchangeRates($this->id);
        $responseJsonObject = $oxr->latest();

        $this->assertEquals(
            "USD",
            $responseJsonObject->base
        );

        $this->assertObjectHasAttribute("IDR", $responseJsonObject->rates);
    }

    public function testLatestEndpointWithQuery()
    {
        $this->markTestSkipped();
        $oxr = new OpenExchangeRates($this->id);
        $responseJsonObject = $oxr->latest(["symbols" => "IDR,SAR"]);

        $this->assertObjectHasAttribute("IDR", $responseJsonObject->rates);
        $this->assertObjectHasAttribute("SAR", $responseJsonObject->rates);
        $this->assertObjectNotHasAttribute("AED", $responseJsonObject->rates);
    }

    public function testHistoricalEndpoint()
    {
        $dateString = "2017-01-01";
        $oxr = new OpenExchangeRates($this->id);
        $response = $oxr->historical(['date' => $dateString]);

        $this->assertEquals(
            $dateString,
            date("Y-m-d", $response->timestamp)
        );

        $this->assertObjectHasAttribute("IDR", $response->rates);
    }

    public function testHistoricalEndpointWithSymbols()
    {
        $dateString = "2017-01-01";
        $oxr = new OpenExchangeRates($this->id);
        $response = $oxr->historical([
            'date' => $dateString,
            "symbols" => "IDR",
        ]);

        $this->assertEquals(
            $dateString,
            date("Y-m-d", $response->timestamp)
        );

        $this->assertObjectHasAttribute("IDR", $response->rates);
        $this->assertObjectNotHasAttribute("SAR", $response->rates);
    }

    public function testCurrenciesEndpoint()
    {
        $this->markTestSkipped();
        $oxr = new OpenExchangeRates($this->id);
        $response = $oxr->currencies();
        $this->assertObjectHasAttribute("IDR", $response);
        $this->assertObjectNotHasAttribute("VEF_BLKMKT", $response);
        $this->assertEquals("Afghan Afghani", $response->AFN);
    }

    public function testCurrenciesEndpointWithOption()
    {
        $this->markTestSkipped();
        $oxr = new OpenExchangeRates($this->id);
        $response = $oxr->currencies([
            "show_alternative" => true,
        ]);
        $this->assertObjectHasAttribute("IDR", $response);
        $this->assertObjectHasAttribute("VEF_BLKMKT", $response);
        $this->assertEquals("Afghan Afghani", $response->AFN);
    }

    public function testTimeSeriesEndpoint()
    {
        $this->markTestSkipped();

        $start = "2017-01-01";
        $end = "2017-01-03";
        $oxr = new OpenExchangeRates($this->id);
        $response = $oxr->timeSeries($start, $end);

        $this->assertObjectHasAttribute($start, $response->rates);
        $this->assertObjectHasAttribute($end, $response->rates);
    }

    public function testUsage()
    {
        $oxr = new OpenExchangeRates($this->id);
        $response = $oxr->usage();

        $this->assertObjectHasAttribute("data", $response);
        $this->assertObjectHasAttribute("plan", $response->data);
        $this->assertObjectHasAttribute("usage", $response->data);
    }

    public function testNativeConvert(): void
    {
        $responseString = $this->getJsonResponseString("latest-success.json");
        $client = $this->createClient($responseString);

        $oxr = new OpenExchangeRates(self::$fakeId, [], $client);

        $this->assertEquals(
            12,
            $oxr->nativeConvert(6, "BBD")
        );

        $client = $this->createClient($responseString);
        $oxr = new OpenExchangeRates(self::$fakeId, [], $client);
        $this->assertEquals(
            20,
            $oxr->nativeConvert(10, "BBD")
        );
    }
}
