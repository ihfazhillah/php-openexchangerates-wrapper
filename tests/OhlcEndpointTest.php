<?php

use PHPUnit\Framework\TestCase;
use OpenExchangeRatesWrapper\Endpoints\OHLC;
use OpenExchangeRatesWrapper\Endpoints\Base;

class OhlcEndpointTest extends TestCase
{
    protected static $fakeId = "hello";

    public function testInstanceOf(): void
    {
        $this->assertInstanceOf(
            OHLC::class,
            new OHLC(self::$fakeId)
        );
    }

    public function testBase(): void
    {
        $this->assertTrue(
            is_subclass_of(
                OHLC::class,
                Base::class
            )
        );
    }

    public function testGetAppendQueries(): void
    {
        $ohlc = new OHLC(self::$fakeId);
        $this->assertEquals(
            ["start_time", "period"],
            $ohlc->getAppendQueries()
        );
    }

    public function testGetAllowedQueries(): void
    {
        $ohlc = new OHLC(self::$fakeId);
        $this->assertEquals(
            ["symbols", "base"],
            $ohlc->getAllowedQueries()
        );
    }

    public function testStartTimePeriodRequired(): void
    {
        $ohlc = new OHLC(self::$fakeId);

        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage("start_time query is required");
        $ohlc->buildQuery([
            "period" => "1d"
        ]);


        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage("period query is required");
        $ohlc->buildQuery([
            "start_time" => "2017-09-20" // TODO: in datetime format not YYYY-MM-DD
        ]);
}
