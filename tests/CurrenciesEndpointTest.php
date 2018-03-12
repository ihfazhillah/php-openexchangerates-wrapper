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

    public function testAllowedQueries()
    {
        $this->assertEquals(
            ["show_alternative"],
            (new Currencies(self::$fakeId))->getAllowedQueries()
        );
    }

    public function testGetAppendQueries()
    {
        $this->assertEquals(
            ["show_inactive"],
            (new Currencies(self::$fakeId))->getAppendQueries()
        );
    }

    public function testGetQueryStringWithAppendedShouldNotComplaint()
    {
        $this->assertEquals(
            "app_id=hello&show_alternative=true&show_inactive=true",
            (new Currencies(self::$fakeId))->buildQuery(
                [
                    "show_alternative" => true,
                    "show_inactive" => true
                ]
            )
        );
        $this->assertEquals(
            "app_id=hello&show_inactive=true",
            (new Currencies(self::$fakeId))->buildQuery(
                [
                    "show_alternative" => false,
                    "show_inactive" => true
                ]
            )
        );
    }

    public function testGetEndpoint()
    {
        $this->assertEquals(
            "http://openexchangerates.org/api/currencies.json?app_id=hello&show_inactive=true",
            (new Currencies(self::$fakeId))->getEndpoint([
                'show_inactive' => true,
                'show_alternative' => false
            ])
        );
    }
}
