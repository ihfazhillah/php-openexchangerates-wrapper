<?php namespace OpenExchangeRatesWrapper\Endpoints;

class Currencies extends Base
{
    protected static $allowedQueries = ["show_alternative"];
    protected static $appendQueries = ["show_inactive"];

}
