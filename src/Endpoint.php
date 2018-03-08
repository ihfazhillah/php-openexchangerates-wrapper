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

    public function latest(array $options = [])
    {
        $endpoint = $this->getBaseEndpoint() . "latest.json";
        if (isset($options['show_alternatives'])) {

            if (!$options['show_alternatives']) {
                unset($options['show_alternatives']);
            } else {
                $options['show_alternatives'] = 'true';
            }
        }

        $queries = [
            "app_id" => $this->app_id,
        ];

        $queries = array_merge($queries, $options);

        $queriesString = http_build_query($queries);

        return $endpoint . '?' . $queriesString;
    }

    public function historical(string $date = '', array $options = [])
    {
        if (empty($date)) {
            throw new \InvalidArgumentException("date YYYY-MM-DD format required");

        }

        $endpoint = $this->getBaseEndpoint() . "historical/" . $date . ".json";

        if (isset($options['show_alternatives'])) {

            if (!$options['show_alternatives']) {
                unset($options['show_alternatives']);
            } else {
                $options['show_alternatives'] = 'true';
            }
        }

        $queries = [
            "app_id" => $this->app_id,
        ];

        $queries = array_merge($queries, $options);

        $queriesString = http_build_query($queries);

        return $endpoint . "?" . $queriesString;

    }

}
