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
}
