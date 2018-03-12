<?php namespace  OpenExchangeRatesWrapper\Endpoints;

class OHLC extends Base
{
    protected static $appendQueries = ["start_time", "period"];
    protected static $allowedQueries = ["symbols", "base"];

    public function buildQuery(array $options = []): string
    {
        $required = ["start_time", "period"];

        foreach($required as $key) {
            if (!isset($options[$key])){
                throw new \InvalidArgumentException($key . " query is required");
            }
        }

        return parent::buildQuery($options);
    }
}
