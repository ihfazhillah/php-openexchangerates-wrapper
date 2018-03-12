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

    public function testStartEndAsRequiredQueries()
    {
        $timeSeries = new TimeSeries(self::$fakeId);

        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage("start option is required");
        $timeSeries->buildQuery(
            [
                'end' => '2017-10-09'
            ]
        );

        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage("end option is required");
        $timeSeries->buildQuery(
            [
                'start' => '2017-10-09'
            ]
        );
    }

    public function testGetEndpoint()
    {
        $timeSeries = new TimeSeries(self::$fakeId);

        $this->assertEquals(
            "http://openexchangerates.org/api/time-series.json?app_id=hello&start=2017-03-01&end=2017-03-04&base=USD",
            $timeSeries->getEndpoint(
                [
                    'start' => '2017-03-01',
                    'end' => '2017-03-04',
                    'base' => "USD"
                ]
            )
        );
    }

}
