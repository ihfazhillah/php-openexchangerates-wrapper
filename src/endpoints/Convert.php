<?php namespace OpenExchangeRatesWrapper\Endpoints;

class Convert extends Base 
{
    protected static $name = "convert/";
    protected static $allowedQueries = [];

    public function getEndpoint(array $options = []): string
    {
        $required = ["value", "from", "to"];

        $this->isOptionRequired($options, $required);

        $endpoint = $this->getBaseUrl() . self::$name . $options["value"] . "/" . $options["from"] . "/" . $options["to"] . "?app_id=" . $this->app_id;

        return $endpoint;
    }

}
