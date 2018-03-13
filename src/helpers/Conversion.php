<?php namespace OpenExchangeRatesWrapper\Helpers;

class Conversion
{
    public function __construct(object $rates = null)
    {
        $this->rates = $rates ? $rates : new \StdClass;
    }

    public function convert(float $value, string $to): float
    {
        if(!property_exists($this->rates, $to))
        {
            throw new \InvalidArgumentException($to . " currency not available");
        }

        return $this->rates->$to * $value;
    }
}
