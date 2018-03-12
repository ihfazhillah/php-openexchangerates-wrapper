<?php namespace OpenExchangeRatesWrapper\Endpoints;

class TimeSeries extends Base
{
    
    protected static $name = "time-series.json";
    protected static $appendQueries = ["start", "end"];

    public function buildQuery(array $options = []): string
    {
        $required = ["start", "end"];

        $this->isOptionRequired($options, $required);

        return parent::buildQuery($options);
    }
}
