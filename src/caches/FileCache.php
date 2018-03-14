<?php namespace OpenExchangeRatesWrapper\Caches;

class FileCache
{
    protected $defaultDir = __DIR__ . '/../../';
    protected $childCacheDir;

    public function __construct(float $expiredAfter = null, string $cacheDir = null)
    {
        $this->cacheDir = $cacheDir ? $cacheDir : realpath($this->defaultDir);

        if (file_exists($this->cacheDir) === false) {
            throw new \InvalidArgumentException($this->cacheDir . " dir not found");
        }

        $this->expiredAfter = $expiredAfter ? $expiredAfter : 24;
    }

    public function getCacheDir(): string
    {
        return $this->cacheDir;
    }

    public function getExpireAfter(): float
    {
        return $this->expiredAfter;
    }

    public function getExpireAfterSeconds(): float
    {
        return $this->expiredAfter * 60 * 60;
    }

    public function setDirectory()
    {
        $this->childCacheDir = $this->cacheDir . "/caches";

        if (file_exists($this->childCacheDir) === false) {
            mkdir($this->childCacheDir, 0775);
        }
    }

    public function setFile(string $name)
    {
        $this->setDirectory();
        $fullname = $this->childCacheDir . "/" . $name;

        if (file_exists($fullname) === false) {
            $handle = fopen($fullname, 'w');
            fclose($handle);
        }

        return $fullname;
    }

    public function isValidTime(int $timestamp): bool
    {
        $expiredAt = $timestamp + $this->getExpireAfterSeconds();

        return time() < $expiredAt;
    }

    public function getExpiredAt(int $timestamp)
    {
        return $timestamp + $this->getExpireAfterSeconds();
    }

    public function set(string $key, string $value)
    {

        $contentOld = $this->get($key);

        if ($contentOld) {
            return false;
        }

        $fullname = $this->setFile($key . ".json");

        $content = [
            "timestamp" => time(),
            "value" => $value,
        ];

        file_put_contents($fullname, json_encode($content));
    }

    public function get(string $key)
    {
        $filename = $this->setFile($key . ".json");
        $content = file_get_contents($filename);

        if (!$content) {
            return false;
        }

        $contentJson = json_decode($content);

        if (!$this->isValidTime($contentJson->timestamp)) {
            return false;
        }

        return $contentJson;
    }

}
