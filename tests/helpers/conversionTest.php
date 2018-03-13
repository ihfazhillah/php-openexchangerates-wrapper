<?php

use PHPUnit\Framework\TestCase;
use OpenExchangeRatesWrapper\Helpers\Conversion;

class TestConversionHelper extends TestCase
{
    public function testInstance(): void
    {
        $this->assertInstanceOf(
            Conversion::class,
            new Conversion()
        );
    }

    public function testConvertStaticMethod(): void
    {
        $this->assertEquals(
            20,
            Conversion::convert(10, "CTM")
        );
    }
}
