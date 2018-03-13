<?php

use PHPUnit\Framework\TestCase;
use OpenExchangeRatesWrapper\Helpers\Conversion;


class TestConversionHelper extends TestCase
{
    protected $rates;

    protected function setUp()
    {
        $rates = new StdClass;
        $rates->CTM = 2;
        $rates->AKU = 2.5;

        $this->rates = $rates;
    }

    public function testInstance(): void
    {
        $this->assertInstanceOf(
            Conversion::class,
            new Conversion
        );
    }

    public function testConvertStaticMethod(): void
    {
        $conversion = new Conversion($this->rates);
        $this->assertEquals(
            20,
            $conversion->convert(10, "CTM")
        );
    }

    public function testConvertStaticMethodAgain(): void
    {
        $conversion = new Conversion($this->rates);
        $this->assertEquals(
            10,
            $conversion->convert(4, "AKU")
        );
    }

    public function testInvalidArgumentExceptionWhenToNotFound(): void
    {
        $conversion = new Conversion($this->rates);

        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage("YOU currency not available");
        $conversion->convert(10, "YOU");
    }

    public function testInvalidArgumentExceptionWhenNoRates(): void
    {
        $conversion = new Conversion();

        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage("AKU currency not available");
        $conversion->convert(10, "AKU");
    }
}
