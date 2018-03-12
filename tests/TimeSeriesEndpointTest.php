<?php 

use PHPUnit\Framework\TestCase;
use OpenExchangeRatesWrapper\Endpoints\TimeSeries;
use OpenExchangeRatesWrapper\Endpoints\Base;

class TestTimeSeriesEndpoint extends TestCase 
{
    protected static $fakeId = "hello";

    public function testInstance()
    {
        $this->assertInstanceOf(
            TimeSeries::class,
            new TimeSeries(self::$fakeId)
        );
    }

    public function testChildOfBase()
    {
        $this->assertTrue(
            is_subclass_of(
                TimeSeries::class,
                Base::class
            )
        );
    }

    public function testAppendQueries()
    {
        $this->assertEquals(
            ["start", "end"],
            (new TimeSeries(self::$fakeId))->getAppendQueries()
        );
    }
}
