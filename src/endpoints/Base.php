<?php namespace OpenExchangeRatesWrapper\Endpoints;

class Base
{

    protected static $BASE_URL = "://openexchangerates.org/api/";

    protected static $name = "default";

    protected static $allowedQueries = ["base", "symbols", "show_alternative"];

    protected static $appendQueries = [];

    public function __construct(string $app_id = "", array $options = [])
    {
        if (empty($app_id)) {
            throw new \InvalidArgumentException("pass app_id argument, app_id argument is required");
        }

        $this->app_id = $app_id;
        $this->options = $options;
    }

    public function getBaseUrl(): string
    {

        $options = $this->options;
        $protocol = "http";
        if (isset($options["https"])) {
            if ($options["https"]) {
                $protocol = "https";
            }
        }

        return $protocol . self::$BASE_URL;
    }

    public function buildQuery(array $options = []): string
    {
        $firstQuery = [
            "app_id" => $this->app_id,
        ];

        $allowedQueries = array_merge(static::$allowedQueries, static::$appendQueries);

        foreach ($options as $key => $val) {
            
            if (!in_array($key, $allowedQueries)){
                throw new \InvalidArgumentException("queries not allowed. Please use one of " . implode(",", $allowedQueries));
            }

            if (gettype($val) == 'boolean') {
                if ($val) {
                    $options[$key] = "true";
                } else {
                    unset($options[$key]);
                }
            }
        }

        $queries = array_merge($firstQuery, $options);

        return http_build_query($queries);
    }

    public function getEndpoint(array $options = []): string
    {
        $queryString = $this->buildQuery($options);
        return $this->getBaseUrl() . static::$name . "?" . $queryString;
    }

    public function getAllowedQueries(): array
    {
        return static::$allowedQueries;
    }

    public function getAppendQueries(): array
    {
        return static::$appendQueries;
    }

}
