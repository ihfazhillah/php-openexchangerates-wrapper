<?php

use OpenExchangeRatesWrapper\Endpoints\Base;
use OpenExchangeRatesWrapper\Endpoints\Currencies;
use PHPUnit\Framework\TestCase;

class CurrenciesEndpointTest extends TestCase
{

    protected static $fakeId = "hello";

    public function testInstance()
    {
        $this->assertInstanceOf(
            Currencies::class,
            new Currencies(self::$fakeId)
        );
    }

    public function testChildOfBase()
    {
        $this->assertTrue(
            is_subclass_of(Currencies::class, Base::class)
        );
    }

    public function testGetBaseUrl()
    {
        $this->assertEquals(
            "http://openexchangerates.org/api/",
            (new Currencies(self::$fakeId))->getBaseUrl()
        );
    }
}
