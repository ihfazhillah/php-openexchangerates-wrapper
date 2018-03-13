<?php

use PHPUnit\Framework\TestCase;
use GuzzleHttp\Client;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Psr7\Response;

use OpenExchangeRatesWrapper\OpenExchangeRates;
use OpenExchangeRatesWrapper\Endpoint;

class OpenExchangeRatesTest extends TestCase
{

    protected static $fakeId = "hello";

    protected function createLatestClient(): Client
    {
        $responseString = '{
    "disclaimer": "Usage subject to terms: https://openexchangerates.org/terms",
    "license": "https://openexchangerates.org/license",
    "timestamp": 1520913601,
    "base": "USD",
    "rates": {
        "AED": 3.672896,
        "AFN": 69.402457,
        "ALL": 106.763993,
        "AMD": 479.03,
        "ANG": 1.784764,
        "AOA": 215.064,
        "ARS": 20.191,
        "AUD": 1.26967,
        "AWG": 1.784998,
        "AZN": 1.68825,
        "BAM": 1.58492,
        "BBD": 2,
        "BDT": 82.63453,
        "BGN": 1.585705,
        "BHD": 0.377063,
        "BIF": 1754.113054,
        "BMD": 1,
        "BND": 1.313517,
        "BOB": 6.907988,
        "BRL": 3.261204,
        "BSD": 1,
        "BTC": 0.000110736307,
        "BTN": 65.02943,
        "BWP": 9.536515,
        "BYN": 1.953766,
        "BZD": 2.009666,
        "CAD": 1.284241,
        "CDF": 1613.322925,
        "CHF": 0.9474,
        "CLF": 0.0226,
        "CLP": 603.9,
        "CNH": 6.324954,
        "CNY": 6.32705,
        "COP": 2899.159664,
        "CRC": 567.545,
        "CUC": 1,
        "CUP": 25.5,
        "CVE": 89.75,
        "CZK": 20.640972,
        "DJF": 177.075,
        "DKK": 6.039492,
        "DOP": 49.45398,
        "DZD": 114.03514,
        "EGP": 17.6355,
        "ERN": 14.996667,
        "ETB": 27.467713,
        "EUR": 0.810866,
        "FJD": 2.023547,
        "FKP": 0.719432,
        "GBP": 0.719432,
        "GEL": 2.440802,
        "GGP": 0.719432,
        "GHS": 4.482569,
        "GIP": 0.719432,
        "GMD": 47.24,
        "GNF": 9008.866667,
        "GTQ": 7.381165,
        "GYD": 206.453743,
        "HKD": 7.839459,
        "HNL": 23.574104,
        "HRK": 6.037004,
        "HTG": 64.280736,
        "HUF": 252.865,
        "IDR": 13766.013762,
        "ILS": 3.442,
        "IMP": 0.719432,
        "INR": 64.935,
        "IQD": 1185.602494,
        "IRR": 37493.52369,
        "ISK": 99.82,
        "JEP": 0.719432,
        "JMD": 127.598916,
        "JOD": 0.709001,
        "JPY": 106.55116667,
        "KES": 101.151,
        "KGS": 68.205801,
        "KHR": 3952.863462,
        "KMF": 399.203187,
        "KPW": 900,
        "KRW": 1065.82,
        "KWD": 0.300296,
        "KYD": 0.83326,
        "KZT": 319.982741,
        "LAK": 8255.95,
        "LBP": 1507.062443,
        "LKR": 154.976427,
        "LRD": 130.95,
        "LSL": 11.81559,
        "LYD": 1.327892,
        "MAD": 9.1821,
        "MDL": 16.525,
        "MGA": 3170.201613,
        "MKD": 49.928637,
        "MMK": 1333.910206,
        "MNT": 2393.24936,
        "MOP": 8.072685,
        "MRO": 355,
        "MRU": 35.35,
        "MUR": 33.197,
        "MVR": 15.460011,
        "MWK": 725.021611,
        "MXN": 18.5868,
        "MYR": 3.908007,
        "MZN": 62.078957,
        "NAD": 11.81559,
        "NGN": 359.084562,
        "NIO": 31.004198,
        "NOK": 7.750211,
        "NPR": 104.030871,
        "NZD": 1.367615,
        "OMR": 0.385026,
        "PAB": 1,
        "PEN": 3.258498,
        "PGK": 3.230955,
        "PHP": 52.0555,
        "PKR": 110.327099,
        "PLN": 3.40885,
        "PYG": 5453.353363,
        "QAR": 3.641481,
        "RON": 3.781127,
        "RSD": 95.705,
        "RUB": 56.89,
        "RWF": 859.755711,
        "SAR": 3.75035,
        "SBD": 7.746334,
        "SCR": 13.455759,
        "SDG": 18.058641,
        "SEK": 8.24117,
        "SGD": 1.313051,
        "SHP": 0.719432,
        "SLL": 7664.007735,
        "SOS": 576.486453,
        "SRD": 7.468,
        "SSP": 130.2634,
        "STD": 19888.409179,
        "STN": 19.91,
        "SVC": 8.749008,
        "SYP": 514.98999,
        "SZL": 11.813246,
        "THB": 31.27,
        "TJS": 8.823745,
        "TMT": 3.499986,
        "TND": 2.392206,
        "TOP": 2.220132,
        "TRY": 3.842182,
        "TTD": 6.703571,
        "TWD": 29.281,
        "TZS": 2255.35,
        "UAH": 25.792549,
        "UGX": 3637.566138,
        "USD": 1,
        "UYU": 28.365637,
        "UZS": 8131.75,
        "VEF": 35190,
        "VND": 22765.432006,
        "VUV": 105.148831,
        "WST": 2.519438,
        "XAF": 531.892974,
        "XAG": 0.0605108,
        "XAU": 0.00075677,
        "XCD": 2.70255,
        "XDR": 0.689973,
        "XOF": 531.892974,
        "XPD": 0.00101941,
        "XPF": 96.762006,
        "XPT": 0.00103735,
        "YER": 250.281642,
        "ZAR": 11.827231,
        "ZMW": 9.637165,
        "ZWL": 322.355011
    }
    }';

        $mock = new MockHandler([
            new Response(200,[], $responseString),
        ]);
        $handler = HandlerStack::create($mock);
        $client = new Client([ "handler" => $handler]);

        return $client;
    }

    public function testInstanceOf()
    {
        $this->assertInstanceOf(
            OpenExchangeRates::class,
            new OpenExchangeRates(self::$fakeId)
        );
    }

    public function testGetAppId()
    {
        $this->assertEquals(
            "hello",
            (new OpenExchangeRates(self::$fakeId))->getAppId()
        );
    }

    public function testGetOptions()
    {
        $options = [
            "https" => true
        ];
        $this->assertEquals(
            $options,
            (new OpenExchangeRates(self::$fakeId, $options))->getOptions()
        );
    }

    public function testGetDefaultOptions()
    {
        $this->assertEquals(
            [
                "https" => false
            ],
            (new OpenExchangeRates(self::$fakeId))->getOptions()
        );
    }

    public function testClientInstance()
    {
        $this->assertInstanceOf(
            Client::class,
            (new OpenExchangeRates(self::$fakeId))->client
        );
    }

    public function testEndpointInstance()
    {
        $this->assertInstanceOf(
            Endpoint::class,
            (new OpenExchangeRates(self::$fakeId))->endpoint
        );
    }

    public function testLatestEndpoint()
    {
        $client = $this->createLatestClient();
        $oxr = new OpenExchangeRates(self::$fakeId, [], $client);
        $responseJsonObject = $oxr->latest();

        $this->assertEquals(
            "USD",
            $responseJsonObject->base
        );

        $this->assertEquals(
            13766.013762,
            $responseJsonObject->rates->IDR
        );
    }
}

