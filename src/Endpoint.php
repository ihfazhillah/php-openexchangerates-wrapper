<?php namespace OpenExchangeRatesWrapper;

class Endpoint
{
    protected static $BASE_ENDPOINT = "://openexchangerates.org/api/";

    protected static $default_options = [
        'https' => false,
    ];

    protected static $endpointMaps = [
        "latest" => "\OpenExchangeRatesWrapper\Endpoints\Latest",
        "historical" => "\OpenExchangeRatesWrapper\Endpoints\Historical",
        "currencies" => "\OpenExchangeRatesWrapper\Endpoints\Currencies",
        "time-series" => "\OpenExchangeRatesWrapper\Endpoints\TimeSeries",
        "convert" => "\OpenExchangeRatesWrapper\Endpoints\Convert",
        "ohlc" => "\OpenExchangeRatesWrapper\Endpoints\OHLC",
        "status" => "\OpenExchangeRatesWrapper\Endpoints\Status",
    ];

    public function __construct(string $app_id = '', array $options = [])
    {

        if (empty($app_id)) {
            throw new \InvalidArgumentException("expecting app_id string as first argument");
        }

        $this->app_id = $app_id;
        $this->options = empty($options) ? self::$default_options : $options;

    }

    public function getAllowedEndpoints()
    {
        return array_keys(self::$endpointMaps);
    }

    public function getEndpointInstance(string $endpoint)
    {
        if (!array_key_exists($endpoint, self::$endpointMaps)) {
            throw new \InvalidArgumentException($endpoint . " endpoint not found");
        }

        return new self::$endpointMaps[$endpoint]($this->app_id, $this->options);
    }

}
