<?php namespace OpenExchangeRatesWrapper;

use GuzzleHttp\Psr7\Response as ResponseClient;
use OpenExchangeRatesWrapper\Exceptions\OpenExchangeRatesException;

class Response
{
    public static function handleResponse(ResponseClient $json): \stdClass
    {
        $json = json_decode($json->getBody());
        if (isset($json->error) && $json->error) {
            throw new OpenExchangeRatesException($json->message, $json->status);
        }

        return $json;
    }
}
