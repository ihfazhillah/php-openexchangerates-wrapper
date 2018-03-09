<?php

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
}
