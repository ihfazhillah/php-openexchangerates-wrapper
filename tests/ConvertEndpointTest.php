<?php

use PHPUnit\Framework\TestCase;
use OpenExchangeRatesWrapper\Endpoints\Convert;
use OpenExchangeRatesWrapper\Endpoints\Base;

class ConvertEndpointTest extends TestCase
{
    protected static $fakeId = "hello";

    public function testInstanceOf(): void
    {
        $this->assertInstanceOf(
            Convert::class,
            new Convert(self::$fakeId)
        );
    }

    public function testBase(): void
    {
        $this->assertTrue(
            is_subclass_of(
                Convert::class,
                Base::class
            )
        );
    }

    public function testNoQueries(): void
    {
        $convert = new Convert(self::$fakeId);

        $this->assertEquals(
            [],
            $convert->getAllowedQueries()
        );

        $this->expectException(\InvalidArgumentException::class);
        $convert->buildQuery(
            [
                "base" => "USD"
            ]
        );
    }
}
