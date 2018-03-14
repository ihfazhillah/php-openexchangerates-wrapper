<?php namespace OpenExchangeRatesWrapper;

use GuzzleHttp\Client;
use OpenExchangeRatesWrapper\Helpers\Conversion;

class OpenExchangeRates
{
    protected static $defaultOptions = [
        "https" => false,
    ];

    protected $cacheHandler;

    public function __construct(string $app_id, array $options = [], Client $client = null)
    {
        $this->app_id = $app_id;
        $this->options = empty($options) ? self::$defaultOptions : $options;
        $this->client = $client ? $client : new Client(["http_errors" => false]);
        $this->endpoint = new Endpoint($app_id, $options);

        $this->cacheHandler = isset($options['cacheHandler']) ? $options['cacheHandler'] : null;
    }

    public function getAppId(): string
    {
        return $this->app_id;
    }

    public function getOptions(): array
    {
        return $this->options;
    }

    public function getCacheHandler()
    {
        return $this->cacheHandler;
    }

    protected function handleGetFromCache(string $endpointName, bool $skipCache)
    {

        if ($this->cacheHandler && !$skipCache) {
            $fromCache = $this->cacheHandler->get($endpointName);

            if ($fromCache) {
                return Response::handleResponse($fromCache->value);
            }
        }
    }

    protected function handleSetToCache(string $endpointName, string $responseBody, bool $skip_cache)
    {
        if ($this->cacheHandler && !$skip_cache) {
            $this->cacheHandler->set($endpointName, $responseBody);
        }
    }

    protected function handleRequestResponse(string $endpointName, array $options = []): object
    {

        $skip_cache = isset($options['skip_cache']) && $options['skip_cache'] ? true : false;
        unset($options['skip_cache']);

        $this->handleGetFromCache($endpointName, $skip_cache);

        $endpoint = $this->endpoint->getEndpointInstance($endpointName);
        $url = $endpoint->getEndpoint($options);
        $response = $this->client->get($url);

        $this->handleSetToCache($endpointName, $response->getBody(), $skip_cache);

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
        return $this->handleRequestResponse("status", [
            "skip_cache" => true,
        ]);
    }

    public function nativeConvert(float $value, string $to): float
    {
        $latest = $this->latest();
        $conversion = new Conversion($latest->rates);
        return $conversion->convert($value, $to);
    }

}
