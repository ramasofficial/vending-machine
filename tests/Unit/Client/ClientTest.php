<?php

declare(strict_types=1);

namespace Test\Unit\Client;

use GuzzleHttp\Psr7\Response;
use PHPUnit\Framework\TestCase;
use TDD\Client\Client;
use TDD\Client\Exception\RequestFailedException;
use TDD\Client\Transformer\ApiResponse;

class ClientTest extends TestCase
{
    private const GET_BALANCE_BODY_JSON = '{"balance":10}';

    private const GET_BALANCE_BODY_ARRAY = [
        'balance' => 10
    ];

    private const ADD_BALANCE_BODY_JSON = '{"balance":100,"currency":"pences"}';

    private const ADD_BALANCE_BODY_ARRAY = [
        'balance' => 100,
        'currency' => 'pences'
    ];

    private const SELECT_PRODUCT_BODY_JSON = '{"selected_product":"BOTTLE OF WATER","current_balance":800}';

    private const SELECT_PRODUCT_BODY_ARRAY = [
        'selected_product' => 'BOTTLE OF WATER',
        'current_balance' => 800
    ];

    private const SELECT_PRODUCT_ERROR_BODY_JSON = '{"error":"Your balance is lower than product price, insert the money and try again."}';

    private const SELECT_PRODUCT_ERROR_BODY_ARRAY = [
        'error' => 'Your balance is lower than product price, insert the money and try again.',
    ];

    private const REFUND_BODY_JSON = '{"refund":800,"currency":"pences"}';

    private const REFUND_BODY_ARRAY = [
        'refund' => 800,
        'currency' => 'pences'
    ];

    private const ONE_HUNDRED_PENCES = 100;

    private const SUCCESS_STATUS = 200;

    private const RESPONSE_HEADERS = ['Content-type' => 'application/json'];

    private Client $client;

    private \GuzzleHttp\Client $http;

    public function setUp(): void
    {
        $this->http = $this->createMock(\GuzzleHttp\Client::class);
        $this->client = new Client($this->http, new ApiResponse(), 1);
    }

    public function test_should_initialize_api_response_class(): void
    {
        $this->assertInstanceOf(Client::class, $this->client);
    }

    /**
     * @throws RequestFailedException
     */
    public function test_should_return_balance(): void
    {
        $this->http->method('request')->willReturn(new Response(self::SUCCESS_STATUS, self::RESPONSE_HEADERS, self::GET_BALANCE_BODY_JSON));

        $actual = $this->client->getBalance();

        $this->assertSame(self::GET_BALANCE_BODY_ARRAY, $actual);
    }

    /**
     * @throws RequestFailedException
     */
    public function test_should_add_balance(): void
    {
        $this->http->method('request')->willReturn(new Response(self::SUCCESS_STATUS, self::RESPONSE_HEADERS, self::ADD_BALANCE_BODY_JSON));

        $actual = $this->client->addBalance(self::ONE_HUNDRED_PENCES);

        $this->assertSame(self::ADD_BALANCE_BODY_ARRAY, $actual);
    }

    /**
     * @throws RequestFailedException
     */
    public function test_should_select_product(): void
    {
        $this->http->method('request')->willReturn(new Response(self::SUCCESS_STATUS, self::RESPONSE_HEADERS, self::SELECT_PRODUCT_BODY_JSON));

        $actual = $this->client->selectProduct(self::ONE_HUNDRED_PENCES);

        $this->assertSame(self::SELECT_PRODUCT_BODY_ARRAY, $actual);
    }

    /**
     * @throws RequestFailedException
     */
    public function test_should_get_error_if_balance_is_lower_than_product_price(): void
    {
        $this->http->method('request')->willReturn(new Response(self::SUCCESS_STATUS, self::RESPONSE_HEADERS, self::SELECT_PRODUCT_ERROR_BODY_JSON));

        $actual = $this->client->selectProduct(self::ONE_HUNDRED_PENCES);

        $this->assertSame(self::SELECT_PRODUCT_ERROR_BODY_ARRAY, $actual);
    }

    /**
     * @throws RequestFailedException
     */
    public function test_should_refund_money(): void
    {
        $this->http->method('request')->willReturn(new Response(self::SUCCESS_STATUS, self::RESPONSE_HEADERS, self::REFUND_BODY_JSON));

        $actual = $this->client->refund();

        $this->assertSame(self::REFUND_BODY_ARRAY, $actual);
    }
}