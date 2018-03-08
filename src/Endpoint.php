<?php namespace OpenExchangeRatesWrapper;

class Endpoint
{
    protected static $BASE_ENDPOINT = "://openexchangerates.org/api/";

    protected static $default_options = [
        'https' => false,
    ];

    public function __construct(string $app_id = '', array $options = [])
    {

        if (empty($app_id)) {
            throw new \InvalidArgumentException("expecting app_id string as first argument");
        }

        $this->app_id = $app_id;
        $this->options = empty($options) ? self::$default_options : $options;

    }

    public function getBaseEndpoint()
    {
        if ($this->options["https"]) {
            $protocol = "https";
        } else {
            $protocol = "http";
        }
        return $protocol . self::$BASE_ENDPOINT;
    }

    public function latest(string $base = "", string $symbols = "", bool $show_alternatives = false)
    {
        $endpoint = $this->getBaseEndpoint() . "latest.json";
        $queries = ['app_id' => $this->app_id];
        if (!empty($base)) {
            $queries["base"] = $base;
        }

        if (!empty($symbols)) {
            $queries["symbols"] = $symbols;
        }

        if ($show_alternatives) {
            $queries["show_alternatives"] = "true";
        }

        $queries_string = http_build_query($queries);
        return $endpoint . '?' . $queries_string;
    }

}
