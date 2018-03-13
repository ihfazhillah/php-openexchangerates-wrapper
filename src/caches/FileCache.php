<?php namespace OpenExchangeRatesWrapper\Caches;

class FileCache
{
    protected $defaultDir = __DIR__ . '/../../';

    public function __construct(float $expiredAfter = null, string $cacheDir = null)
    {
        $this->cacheDir = $cacheDir ? $cacheDir : realpath($this->defaultDir);

        if (!realpath($this->cacheDir))
        {
            throw new \InvalidArgumentException($this->cacheDir . " dir not found");
        }

        $this->expiredAfter = $expiredAfter ? $expiredAfter : 24;
    }

    public function getCacheDir(): string
    {
        return $this->cacheDir ;
    }

    public function getExpireAfter(): float
    {
        return $this->expiredAfter;
    }

    public function getExpireAfterSeconds(): float
    {
        return $this->expiredAfter * 60 * 60;
    }


}
