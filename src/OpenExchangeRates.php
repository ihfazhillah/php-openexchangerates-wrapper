<?php namespace OpenExchangeRatesWrapper;

use GuzzleHttp\Client;
use GuzzleHttp\Psr7\Response as ResponseClient;
use OpenExchangeRatesWrapper\Helpers\Conversion;

/**
 * simple php wrapper of openexchangerates.org
 *
 * ## Quickstart
 * ```
 * $oxr = new OpenExchangeRates('YOUR APP ID');
 * $latest = $oxr->latest();
 * var_dump($latest);
 * ```
 * or if you wish to use https
 * ```
 * $oxr = new OpenExchangeRates('YOUR APP ID', ['https' => true]);
 * $historical = $oxr->historical('2017-09-10');
 * ```
 *
 * and you can cache the result to json file. Use `OpenExchangeRatesWrapper\Caches\FileCache`.
 * here the example
 *
 * ```
 * $filecache = new \OpenExchangeRatesWrapper\Caches\FileCache(1, '.'); // 1
 * $oxr = new \OpenExchangeRatesWrapper\OpenExchangeRates("YOUR APP ID", ['cacheHandler' => $fileCache]);
 * $oxr->latest(['symbols' => 'IDR,SAR']);
 * ```
 *
 * `FileCache` accept two optional argument. first expireAfter in hours. and second is where the caches dir placed
 * the, use the instance and pass it to `cacheHandler` value in $options array in the OpenExchangeRates argument.
 *
 * In example above, we use the current directory. After you call `latest` method, you can see the `caches/latest.json` file.
 * that's
 *
 * @param string $app_id  your app id, its required
 * @param string $options  you can pass `https` with boolean value and `cacheHandler` with instance of `OpenExchangeRatesWrapper\Caches\FileCache` value
 * @param GuzzleHttp\Client $client
 */
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

    protected function handleGetFromCache(string $endpointName, bool $skipCache = false)
    {

        if ($this->cacheHandler && !$skipCache) {
            $fromCache = $this->cacheHandler->get($endpointName);

            if ($fromCache) {
                $response = new ResponseClient(200, [], $fromCache->value);
                return Response::handleResponse($response);
            }
        }
    }

    protected function handleSetToCache(string $endpointName, string $responseBody, bool $skip_cache)
    {
        if ($this->cacheHandler && !$skip_cache) {
            $this->cacheHandler->set($endpointName, $responseBody);
        }
    }

    protected function handleRequestResponse(string $endpointName, array $options = []): \stdClass
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

    /**
     * get latest rates from openexchangerates.org
     *
     * accepts `base`, `symbols`, `show_alternative` options.
     * 1. symbols = comma separated value like `'IDR,SAR'`
     * 2. show_alternative = boolean
     *
     * example:
     *
     * ```
     * $latest = $oxr->latest(); // you will get all currencies
     * $latestWithAlternative = $oxr->latest(['show_alternative' => true]); // note you should pass true as boolean not string
     * $latestWithBase = $oxr->latest(['base' => 'IDR']); // not for free plan
     * $latestWithSpecifiedSymbols = $oxr->latest(['symbols' => 'IDR,SAR,SGD']); // value is comma separated value
     * ```
     *
     * @param array $options
     * @return object json_decoded from response
     */
    public function latest(array $options = []): \stdClass
    {
        return $this->handleRequestResponse("latest", $options);
    }

    /**
     * get historical exchange rates from any date.
     *
     *
     * another allowed options are:
     * 1. base : to change base
     * 2. show_alternative : true or false (bool)
     * 3. symbols: comma separated value of currency code
     *
     * ```
     * $historical = $oxr->historical(['date' => '2018-01-29']);
     * ```
     *
     * @param string $date required - YYYY-MM-DD format
     * @param array $options
     * @return object json_decoded from response
     */
    public function historical(string $date, array $options = []): \stdClass
    {
        $options['date'] = $date;
        return $this->handleRequestResponse("historical", $options);
    }

    /**
     * get all currencies available from openexchangerates.org
     *
     * allowed options:
     * 1. show_alternative = bool
     * 2. show_inactive = bool
     *
     * @param array $options
     * @return object json_decoded from response
     */
    public function currencies(array $options = []): \stdClass
    {
        return $this->handleRequestResponse("currencies", $options);
    }

    /**
     * get exchange rates from given period.
     *
     * required params:
     * 1. start = YYYY-MM-DD format
     * 2. end = YYYY-MM-DD format
     *
     * allowed options:
     * 1. base
     * 2. show_alternative
     * 3. symbols
     *
     * @param string $start
     * @param string $end
     * @return object
     */
    public function timeSeries(string $start, string $end, $options): \stdClass
    {
        /**
         * this function not tested, we not have a plan with this endpoint
         */
        $options['start'] = $start;
        $options['end'] = $end;
        return $this->handleRequestResponse("time-series", $options);
    }

    /**
     * convert $value $from $to with openexchangerates.org api. Dont confuse with `nativeConvert`
     *
     * @param float $value
     * @param string $from
     * @param string $to
     * @return object
     */
    public function convert(float $value, string $from, string $to, array $options): \stdClass
    {
        /**
         * not tested, same as above
         */

        $options["value"] = $value;
        $options["from"] = $from;
        $options["to"] = $to;
        return $this->handleRequestResponse("convert", $options);

    }

    public function ohlc(string $start_time, string $period, array $options): \stdClass
    {
        /**
         * not tested, same as above
         */

        $options["start_time"] = $start_time;
        $options["period"] = $period;

        return $this->handleRequestResponse("ohlc", $options);
    }

    /**
     * get status or usage of api
     *
     * @return object
     */
    public function usage(): \stdClass
    {
        return $this->handleRequestResponse("status", [
            "skip_cache" => true,
        ]);
    }

    /**
     * convert with latest response from openexchangerates.org
     * note: that from is USD as default, you cannot change this in this version
     *
     * @param float $value
     * @param string $to
     * @return float
     */
    public function nativeConvert(float $value, string $to, string $from = null): float
    {
        // make usd as base
        $latest = $this->latest();
        $conversion = new Conversion($latest->rates, "USD");
        return $conversion->convert($value, $to, $from);
    }

}
