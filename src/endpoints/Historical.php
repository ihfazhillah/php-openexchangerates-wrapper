<?php namespace OpenExchangeRatesWrapper\Endpoints;

class Historical extends Base
{

    protected static $name = "historical/";

    public function getEndpoint(array $options = []): string
    {
        if (!isset($options['date'])) {
            throw new \InvalidArgumentException("date argument is required");
        }

        $date = $options['date'];
        unset($options['date']);

        self::$name = "historical/" . $date . ".json";

        return parent::getEndpoint($options);
    }
}
