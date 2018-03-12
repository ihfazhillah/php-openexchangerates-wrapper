<?php

use PHPUnit\Framework\TestCase;
use OpenExchangeRatesWrapper\Endpoints\Historical;
use OpenExchangeRatesWrapper\Endpoints\Base;

class HistoricalEndpointTest extends TestCase
{
    protected static $fakeId = 'hello';

    public function testInstanceOf(): void
    {
        $this->assertInstanceOf(
            Historical::class,
            new Historical(self::$fakeId)
        );
    }

    public function testBase(): void
    {
        $this->assertTrue(
            is_subclass_of(
                Historical::class,
                Base::class
            )
        );
    }

    public function testBuildQuery(): void
    {
        $historical = new Historical(self::$fakeId);
        $this->assertEquals(
            "app_id=hello&show_alternative=true",
            $historical->buildQuery(
                [
                    "show_alternative" => true
                ]
            )
        );
    }

    public function testGetEndpoint(): void
    {
        $historical = new Historical(self::$fakeId);
        $this->assertEquals(
            "http://openexchangerates.org/api/historical/2018-01-01.json?app_id=hello",
            $historical->getEndpoint([], "2018-01-01")
        );
    }

    public function testDateInGetEnpointIsRequired(): void
    {
        $historical = new Historical(self::$fakeId);
        $this->expectException(\InvalidArgumentException::class);
        $historical->getEndpoint();
    }

    // TODO: check the date format
}
