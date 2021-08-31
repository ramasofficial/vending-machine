<?php

declare(strict_types=1);

namespace Test\Unit\Transformer;

use GuzzleHttp\Psr7\Response;
use PHPUnit\Framework\TestCase;
use TDD\Client\Transformer\ApiResponse;

class ApiResponseTest extends TestCase
{
    private const BODY_JSON = '{"balance":10}';

    private const BODY_ARRAY = ['balance' => 10];

    private ApiResponse $transformer;

    public function setUp(): void
    {
        $this->transformer = new ApiResponse();
    }

    public function test_should_initialize_api_response_class(): void
    {
        $this->assertInstanceOf(ApiResponse::class, $this->transformer);
    }

    public function test_transform_response_to_array(): void
    {
        $response = new Response(200, ['Content-type' => 'application/json'], self::BODY_JSON);

        $actual = $this->transformer->transform($response);

        $this->assertIsArray($actual);

        $this->assertSame(self::BODY_ARRAY, $actual);
    }
}