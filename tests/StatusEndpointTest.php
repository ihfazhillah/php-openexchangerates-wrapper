<?php

use PHPUnit\Framework\TestCase;
use OpenExchangeRatesWrapper\Endpoints\Status;
use OpenExchangeRatesWrapper\Endpoints\Base;

class StatusEndpointTest extends TestCase 
{
    protected static $fakeId = "hello";

    public function testInstanceOf(): void
    {
        $this->assertInstanceOf(
            Status::class,
            new Status(self::$fakeId)
        );
    }

    public function testBase(): void
    {
        $this->assertTrue(
            is_subclass_of(
                Status::class,
                Base::class
            )
        );
    }
}
