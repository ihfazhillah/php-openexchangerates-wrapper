<?php namespace OpenExchangeRatesWrapper\Endpoints;

class TimeSeries extends Base
{
    
    protected static $name = "time-series.json";
    protected static $appendQueries = ["start", "end"];

    public function buildQuery(array $options = []): string
    {
        $required = ["start", "end"];

        foreach($required as $key){
            if (!isset($options[$key])){
                throw new \InvalidArgumentException($key . " query is required");
            }
        }

        return parent::buildQuery($options);
    }
}
