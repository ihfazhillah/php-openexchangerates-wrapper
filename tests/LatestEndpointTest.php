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
}
