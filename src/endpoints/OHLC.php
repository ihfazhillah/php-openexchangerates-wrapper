<?php namespace  OpenExchangeRatesWrapper\Endpoints;

class OHLC extends Base
{
    protected static $name = "ohlc.json";
    protected static $appendQueries = ["start_time", "period"];
    protected static $allowedQueries = ["symbols", "base"];

    public function buildQuery(array $options = []): string
    {
        $required = ["start_time", "period"];

        $this->isOptionRequired($options, $required);

        return parent::buildQuery($options);
    }
}
