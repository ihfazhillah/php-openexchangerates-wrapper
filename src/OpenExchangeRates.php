<?php namespace OpenExchangeRatesWrapper;

use GuzzleHttp\Client;

class OpenExchangeRates
{
    protected static $defaultOptions = [
        "https" => false
    ];

    public function __construct(string $app_id, array $options = [], Client $client = null)
    {
        $this->app_id = $app_id;
        $this->options = empty($options) ? self::$defaultOptions : $options;
        $this->client = $client ? $client : new Client(["http_errors" => false]);
        $this->endpoint = new Endpoint($app_id, $options);
    }

    public function getAppId(): string
    {
        return $this->app_id;
    }

    public function getOptions(): array
    {
        return $this->options;
    }


    protected function handleRequestResponse(string $endpointName, array $options = []): object
    {
        $endpoint = $this->endpoint->getEndpointInstance($endpointName);
        $url = $endpoint->getEndpoint($options);
        $response = $this->client->get($url);
        return Response::handleResponse($response);
    }

    public function latest(array $options = []): object
    {
        return $this->handleRequestResponse("latest", $options);
    }

    public function historical(string $date, array $options = []): object
    {
        $endpoint = $this->endpoint->getEndpointInstance("historical");
        $response = $this->client->get($endpoint->getEndpoint($options, $date));
        return Response::handleResponse($response);
    }

    public function currencies(array $options = [])
    {
        return $this->handleRequestResponse("currencies", $options);
    }

    public function timeSeries(string $start, string $end, $options): object
    {
    /**
     * this function not tested, we not have a plan with this endpoint
     */
        $options['start'] = $start;
        $options['end'] = $end;
        return $this->handleRequestResponse("time-series", $options);
    }

    public function convert(float $value, string $from, string $to, array $options): object
    {
    /**
     * not tested, same as above
     */

        $options["value"] = $value;
        $options["from"] = $from;
        $options["to"] = $to;
        return $this->handleRequestResponse("convert", $options);

    }

    public function ohlc(string $start_time, string $period, array $options)
    {
    /**
     * not tested, same as above
     */

        $options["start_time"] = $start_time;
        $options["period"] = $period;

        return $this->handleRequestResponse("ohlc", $options);
    }

    public function usage()
    {
        return $this->handleRequestResponse("status");
    }

}
