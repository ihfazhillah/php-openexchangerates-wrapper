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
        $this->client = $client ? $client : new Client();
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
}
