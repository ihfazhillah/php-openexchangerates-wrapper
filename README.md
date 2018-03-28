# OpenExchangeApi wrapper for php



`php-openexchangerates-wrapper` helps you to make a request to https://openexchangerates.org/api, converting accross currencies with or without api and cache your result into files.



## Installation

```

composer require ihfazhillah/php-openexchangerates-wrapper

```



## Getting Started



```

<?php



require_once("vendor/autoload.php");



use OpenExchangeRatesWrapper\OpenExchangeRates;



$oxr = new OpenExchangeRates("YOUR APP ID");

$latest = $oxr->latest();

```



You can use https instead



```

$oxr = new OpenExchangeRates("YOUR APP ID", ["https" => true]);

```



or if you wish, you can add a cache handler to cache your result



```

use OpenExchangeRatesWrapper\Caches\FileCache;



$fileCache = new FileCache();

$oxr = new OpenExchangeRates("YOUR APP ID", ["cacheHandler" => $fileCache);

```



### `OpenExchangeRates`



`new OpenExchangeRates($app_id, $options)`



the only required argument for this constructor is `$app_id`. You need to register into https://openexchangerates.org.



`$options` : array with `cacheHandler` or `https` property. All is optional.



#### methods

|name|Description|

|------|------------|

|`latest`| calling the latest endpoint from openexchangerates|

|`historical`| call the historical endpoint from openexchangerates. pas `YYYY-MM-DD` as first argument|

|`currencies`|call currencies endpoint|

|`timeSeries`| call timeseries endpoint|

|`convert`|call convert endpoint. `$oxr->convert($value, $from, $to)`|

|`ohlc`||

|`usage`| get your openexchangerates api usage|

|`nativeConvert`| convert without calling `convert` endpoint api. `$oxr->nativeConvert($value, $to, $from)`|



### `FileCache`

`new FileCache($expiredAfter, $path)`



All arguments here is optionals. `$expiredAfter` default is 24 hours. Use hours instead seconds.








