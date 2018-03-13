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

    public function latest(array $options = []): object
    {
        $latestEndpoint = $this->endpoint->getEndpointInstance("latest");
        $response = $this->client->get($latestEndpoint->getEndpoint($options));
        return Response::handleResponse($response);
    }

    public function historical(string $date, array $options = []): object
    {
        $endpoint = $this->endpoint->getEndpointInstance("historical");
        $response = $this->client->get($endpoint->getEndpoint($options, $date));
        return Response::handleResponse($response);
    }

    public function currencies(array $options = [])
    {
        $endpoint = $this->endpoint->getEndpointInstance("currencies");
        $endpointUrl = $endpoint->getEndpoint($options);
        $response = $this->client->get($endpointUrl);
        return Response::handleResponse($response);
    }

    public function timeSeries(string $start, string $end, $options): object
    {
    /**
     * this function not tested, we not have a plan with this endpoint
     */
        $endpoint = $this->endpoint->getEndpointInstance("time-series");
        $options['start'] = $start;
        $options['end'] = $end;
        $endpointUrl = $endpoint->getEndpoint($options);
        $response = $this->client->get($endpointUrl);
        return Response::handleResponse($response);
    }

    public function convert(float $value, string $from, string $to, array $options): object
    {
    /**
     * not tested, same as above
     */

        $options["value"] = $value;
        $options["from"] = $from;
        $options["to"] = $to;

        $endpoint = $this->endpoint->getEndpointInstance("convert");
        $url = $endpoint->getEndpoint($options);
        $response = $this->client->get($url);
        return Response::handleResponse($response);
    }

    public function ohlc(string $start_time, string $period, array $options)
    {
    /**
     * not tested, same as above
     */

        $options["start_time"] = $start_time;
        $options["period"] = $period;

        $endpoint = $this->endpoint->getEndpointInstance("ohlc");
        $url = $endpoint->getEndpoint($options);
        $response = $this->client->get($url);
        return Response::handleResponse($response);
    }

}
