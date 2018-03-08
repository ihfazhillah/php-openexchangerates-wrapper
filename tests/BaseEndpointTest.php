<?php

use OpenExchangeRatesWrapper\Endpoints\Base;
use PHPUnit\Framework\TestCase;

class BaseEndpointTest extends TestCase
{
    protected static $fakeId = "hello";

    public function testInstance(): void
    {
        $this->assertInstanceOf(Base::class, new Base(self::$fakeId));
    }

    public function testAppIdIsRequired(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage("pass app_id argument, app_id argument is required");
        new Base();
    }

    public function testGetBaseUrl(): void
    {
        $this->assertEquals("http://openexchangerates.org/api/", (new Base(self::$fakeId))->getBaseUrl());
    }

    public function testGetBaseUrlWithHttps(): void
    {
        $this->assertEquals(
            "https://openexchangerates.org/api/",
            (new Base(self::$fakeId, ['https' => true]))->getBaseUrl()
        );
    }

    public function testQueryBuilderMustStartedWithAppId(): void
    {
        $this->assertEquals(
            "app_id=hello",
            (new Base(self::$fakeId))->buildQuery()
        );
    }

    public function testQueryBuilderWithOptions(): void
    {
        $this->assertEquals(
            "app_id=hello&foo=bar&ihfazh=hello",
            (new Base(self::$fakeId))->buildQuery(["foo" => "bar", "ihfazh" => "hello"])
        );
    }

    public function testQueryBuilderWithTrueValue(): void
    {
        $this->assertEquals(
            "app_id=hello&foo=true",
            (new Base(self::$fakeId))->buildQuery(
                ["foo" => true]
            )
        );
    }

    public function testQueryBuilderWithFalseValue(): void
    {
        // must delete / unset this key val

        $this->assertEquals(
            "app_id=hello&bar=true",
            (new Base(self::$fakeId))->buildQuery(
                [
                    "foo" => false,
                    "bar" => true,
                ]
            )
        );
    }

    public function testGetEndpoint(): void
    {
        $this->assertEquals(
            "http://openexchangerates.org/api/default?app_id=hello&bar=true",
            (new Base(self::$fakeId))->getEndpoint(
                [
                    "bar" => true,
                ]
            )
        );
    }
}
