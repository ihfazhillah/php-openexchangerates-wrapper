<?php namespace OpenExchangeRatesWrapper\Endpoints;

class Historical extends Base
{

    protected static $name = "historical/";

    public function getEndpoint(array $options = [], string $date = ""): string
    {
        if(empty($date)){
            throw new \InvalidArgumentException("date argument is required");
        }

        self::$name = "historical/" . $date . ".json";

        return parent::getEndpoint($options);
    }
}
