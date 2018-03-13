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

    public function testNoQueriesAllowed(): void
    {
        $status = new Status(self::$fakeId);
        $this->expectException(\InvalidArgumentException::class);
        $status->buildQuery([
            "base" => "USD"
        ]);

        $this->assertEquals(
            [],
            $status->getAllowedQueries()
        );

        $this->assertEquals(
            [],
            $status->getAppendQueries()
        );
    }

    public function testGetEndpoint(): void
    {
        $status = new Status(self::$fakeId);

        $this->assertEquals(
            "http://openexchangerates.org/api/usage.json?app_id=hello",
            $status->getEndpoint()
        );
    }
}
