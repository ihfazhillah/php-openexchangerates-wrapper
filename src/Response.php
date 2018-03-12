<?php namespace OpenExchangeRatesWrapper;

use OpenExchangeRatesWrapper\Exceptions\OpenExchangeRatesException;

class Response
{
    public static function handleResponse(object $json): object
    {
        if (isset($json->error) && $json->error){
            throw new OpenExchangeRatesException($json->message, $json->status);
        }

        return $json;
    }
}
