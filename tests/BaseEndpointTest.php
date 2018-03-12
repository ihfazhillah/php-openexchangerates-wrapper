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
            "app_id=hello&symbols=USD&base=USD",
            (new Base(self::$fakeId))->buildQuery(["symbols" => "USD", "base" => "USD"])
        );
    }

    public function testQueryBuilderWithTrueValue(): void
    {
        $this->assertEquals(
            "app_id=hello&show_alternative=true",
            (new Base(self::$fakeId))->buildQuery(
                ["show_alternative" => true]
            )
        );
    }

    public function testQueryBuilderWithFalseValue(): void
    {
        // must delete / unset this key val

        $this->assertEquals(
            "app_id=hello&base=USD",
            (new Base(self::$fakeId))->buildQuery(
                [
                    "show_alternative" => false,
                    "base" => "USD",
                ]
            )
        );
    }

    public function testGetEndpoint(): void
    {
        $this->assertEquals(
            "http://openexchangerates.org/api/default?app_id=hello&show_alternative=true",
            (new Base(self::$fakeId))->getEndpoint(
                [
                    "show_alternative" => true,
                ]
            )
        );
    }

    public function testGetAllowedQueries(): void
    {
        $this->assertEquals(
            ["base", "symbols", "show_alternative"],
            (new Base(self::$fakeId))->getAllowedQueries()
        );
    }

    public function testMustErrorWhenQueriesNotInGetAllowedQueries(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage("queries not allowed. Please use one of base,symbols,show_alternative");
        (new Base(self::$fakeId))->buildQuery([
            "hello" => "world",
            "foo" => true
        ]);
    }

    public function testGetAppendQueries(): void
    {
        $this->assertEquals(
            [],
            (new Base(self::$fakeId))->getAppendQueries()
        );

    }

    public function testIsOptionRequired(): void
    {
        $base = new Base(self::$fakeId);
        $required = ["foo", "bar"];

        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage("foo option is required");
        $base->isOptionRequired([], $required);

        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage("bar option is required");
        $base->isOptionRequired([
            "foo" => "hello"
        ], $required);

        $base->isOptionRequired([
            "foo" => "hello",
            "bar" => "world"
        ]);
    }
}

