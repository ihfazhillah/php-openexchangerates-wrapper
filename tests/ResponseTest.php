<?php

use PHPUnit\Framework\TestCase;
use OpenExchangeRatesWrapper\Response;
use OpenExchangeRatesWrapper\Exceptions\OpenExchangeRatesException;



//  $errorJson = json_decode($errorJsonString);


class ResponseTest extends TestCase
{
    public function testInstanceOf(): void
    {
        $this->assertInstanceOf(
            Response::class,
            new Response
        );
    }

    public function testParseResponseWithError(): void
    {
        $errorJson = json_decode('{
  "error": true,
  "status": 401,
  "message": "invalid_app_id",
  "description": "Invalid App ID provided - please sign up at https://openexchangerates.org/signup, or contact support@openexchangerates.org."
}');
        $this->expectException(OpenExchangeRatesException::class);
        $this->expectExceptionMessage("invalid_app_id");
        $this->expectExceptionCode(401);

        Response::handleResponse($errorJson);
    }

    public function testResponseSuccess(): void
    {
        $response = json_decode('{"status": 200}');

        $this->assertEquals(
            200,
            Response::handleResponse($response)->status
        );
    }
}
