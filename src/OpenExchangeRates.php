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

    public function latest(): object
    {
        $latestEndpoint = $this->endpoint->getEndpointInstance("latest");
        $response = $this->client->get($latestEndpoint->getEndpoint());
        $responseBody = $response->getBody();
        $responseJson = json_decode($responseBody);

        return Response::handleResponse($responseJson);
    }
}
