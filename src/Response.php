<?php namespace OpenExchangeRatesWrapper;

use OpenExchangeRatesWrapper\Exceptions\OpenExchangeRatesException;
use GuzzleHttp\Psr7\Response as ResponseClient;

class Response
{
    public static function handleResponse(ResponseClient $json): object
    {
        $json = json_decode($json->getBody());
        if (isset($json->error) && $json->error){
            throw new OpenExchangeRatesException($json->message, $json->status);
        }

        return $json;
    }
}
