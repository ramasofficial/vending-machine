<?php

declare(strict_types=1);

namespace TDD\Client;

use GuzzleHttp\ClientInterface;
use GuzzleHttp\Exception\GuzzleException;
use TDD\Client\Exception\RequestFailedException;
use TDD\Client\Transformer\ApiResponse;
use TDD\Client\Transformer\ResponseInterface;

class Client implements \TDD\Client\ClientInterface
{
    private const GET = 'GET';

    private const POST = 'POST';

    private ClientInterface $client;

    private int $vendingMachineId;

    public function __construct(ClientInterface $client, int $vendingMachineId)
    {
        $this->client = $client;
        $this->vendingMachineId = $vendingMachineId;
    }

    /**
     * @throws RequestFailedException
     */
    public function getBalance(): ?ResponseInterface
    {
        $url = '/api/vending-machine/balance/' . $this->vendingMachineId;

        return $this->makeRequest(self::GET, $url);
    }

    /**
     * @throws RequestFailedException
     */
    public function addBalance(int $amount): ?ResponseInterface
    {
        $url = '/api/vending-machine/balance/add/' . $this->vendingMachineId;

        $requestData = [
            'form_params' => [
                'amount' => $amount
            ]
        ];

        return $this->makeRequest(self::POST, $url, $requestData);
    }

    /**
     * @throws RequestFailedException
     */
    public function selectProduct(int $pence): ?ResponseInterface
    {
        $url = '/api/vending-machine/select-product/' . $this->vendingMachineId;

        $requestData = [
            'form_params' => [
                'pence' => $pence
            ]
        ];

        return $this->makeRequest(self::POST, $url, $requestData);
    }

    /**
     * @throws RequestFailedException
     */
    public function refund(): ?ResponseInterface
    {
        $url = '/api/vending-machine/refund/' . $this->vendingMachineId;

        return $this->makeRequest(self::GET, $url);
    }

    /**
     * @throws RequestFailedException
     */
    private function makeRequest(string $method, string $url, array $params = []): ?ResponseInterface
    {
        try {
            return ApiResponse::fromResponse($this->client->request($method, $url, $params));
        } catch (GuzzleException $e) {
            throw new RequestFailedException($e->getMessage());
        }
    }
}