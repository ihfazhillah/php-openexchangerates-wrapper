<?php

use GuzzleHttp\Psr7\Response as ResponseClient;
use OpenExchangeRatesWrapper\Exceptions\OpenExchangeRatesException;
use OpenExchangeRatesWrapper\Response;
use PHPUnit\Framework\TestCase;

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
        $errorResponse =  new ResponseClient(200, [], '{
  "error": true,
  "status": 401,
  "message": "invalid_app_id",
  "description": "Invalid App ID provided - please sign up at https://openexchangerates.org/signup, or contact support@openexchangerates.org."
}');
        $this->expectException(OpenExchangeRatesException::class);
        $this->expectExceptionMessage("invalid_app_id");
        $this->expectExceptionCode(401);

        Response::handleResponse($errorResponse);
    }

    public function testResponseSuccess(): void
    {
        $response = new ResponseClient(200, [], '{"status": 200}');

        $this->assertEquals(
            200,
            Response::handleResponse($response)->status
        );
    }
}
