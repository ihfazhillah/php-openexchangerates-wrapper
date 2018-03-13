<?php namespace OpenExchangeRatesWrapper\Helpers;

class Conversion
{
    public function __construct(object $rates = null)
    {
        $this->rates = $rates ? $rates : new \StdClass;
    }

    public function convert(float $value, string $to)
    {
        return $this->rates->$to * $value;
    }
}
