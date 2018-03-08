<?php namespace OpenExchangeRatesWrapper\Endpoints;

class Base
{

    protected static $BASE_URL = "://openexchangerates.org/api/";

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
        if (isset($options["https"])) {
            if ($options["https"]) {
                $protocol = "https";
            }
        } else {
            $protocol = "http";
        }
        return $protocol . self::$BASE_URL;
    }

    public function buildQuery(array $options = []): string
    {
        $firstQuery = [
            "app_id" => $this->app_id,
        ];

        foreach ($options as $key => $val) {
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

}
