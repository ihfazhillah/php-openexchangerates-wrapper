<?php namespace OpenExchangeRatesWrapper\Caches;

/**
 * cache handler for OpenExchangeRates
 *
 * ```
 * $cache = new FileCache(1, "/home/yourdir");
 * $cache->set('foo', 'bar');
 * $cache->get('foo'); // return 'bar'
 * $cache->get('bar'); // return false
 * ```
 *
 * @param float $expiredAfter in hour ya, default is 24 (one day)
 * @param string $cacheDir default is this root directory
 */
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

    /**
     * get cachedir
     * @return string
     */
    public function getCacheDir(): string
    {
        return $this->cacheDir;
    }

    /**
     * get expiredAfter in hours
     *
     * @return float
     */
    public function getExpireAfter(): float
    {
        return $this->expiredAfter;
    }

    /**
     * get expiredAfter in seconds
     *
     * @return float
     */
    public function getExpireAfterSeconds(): float
    {
        return $this->expiredAfter * 60 * 60;
    }

    /**
     * set the caches directory.
     * if not found, this will create a new caches directory
     *
     * @return void
     */
    public function setDirectory()
    {
        $this->childCacheDir = $this->cacheDir . "/caches";

        if (file_exists($this->childCacheDir) === false) {
            mkdir($this->childCacheDir, 0775);
        }
    }

    /**
     * set the file, create a cache file and return full path to that file
     *
     * @param string $name
     * @return string
     */
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

    /**
     * check if timestamp in cache file valid or not
     *
     * @param int $timestamp
     * @return bool
     */
    public function isValidTime(int $timestamp): bool
    {
        $expiredAt = $timestamp + $this->getExpireAfterSeconds();

        return time() < $expiredAt;
    }

    /**
     * get timestamp expiredat the cache
     *
     * @return int timestamp
     */
    public function getExpiredAt(int $timestamp)
    {
        return $timestamp + $this->getExpireAfterSeconds();
    }

    /**
     * set the value of cache into file
     *
     * @param string $key name of the file without .json ext
     * @param string $value value that you want to cache it
     *
     * @return void
     */
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

    /**
     * get value from cache
     * return false if:
     * 1. no content found or file not found
     * 2. no timestamp key in file
     * 3. no value key in file
     * 4. the time is not valid: string or expired
     *
     *
     * @return string | bool
     */
    public function get(string $key)
    {
        $filename = $this->setFile($key . ".json");
        $content = file_get_contents($filename);

        if (!$content) {
            return false;
        }

        $contentJson = json_decode($content);

        if (!property_exists($contentJson, 'timestamp')) {
            return false;
        }

        if (!property_exists($contentJson, 'value')) {
            return false;
        }

        if (!$this->isValidTime($contentJson->timestamp)) {
            return false;
        }

        return $contentJson;
    }

}
