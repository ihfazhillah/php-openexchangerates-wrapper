<?php 

use PHPUnit\Framework\TestCase;
use OpenExchangeRatesWrapper\Endpoints\TimeSeries;

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
}
