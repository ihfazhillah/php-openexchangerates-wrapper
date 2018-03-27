<?php namespace OpenExchangeRatesWrapper\Helpers;

class Conversion
{
    protected $base;

    public function __construct(object $rates = null, string $base = null)
    {
        $this->rates = $rates ? $rates : new \StdClass;
        $this->base = $base ? $base : "USD";
    }

    public function getBase()
    {
        return $this->base;
    }

    public function convert(float $value, string $to, string $from = null): float
    {

        if (!property_exists($this->rates, $to)) {
            throw new \InvalidArgumentException($to . " currency not available");
        }

        if ($from) {
            if (!property_exists($this->rates, $from) && $this->base !== $from) {
                throw new \InvalidArgumentException($from . " currency not available");
            }

            if ($from !== $this->base) {
                return $value / $this->rates->$from * $this->rates->$to;
            }

        }

        return $this->rates->$to * $value;
    }
}
