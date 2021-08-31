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
    private ResponseInterface $transformer;

    public function __construct(ClientInterface $client, ResponseInterface $transformer, int $vendingMachineId)
    {
        $this->client = $client;
        $this->vendingMachineId = $vendingMachineId;
        $this->transformer = $transformer;
    }

    /**
     * @throws RequestFailedException
     */
    public function getBalance(): ?array
    {
        $url = '/api/vending-machine/balance/' . $this->vendingMachineId;

        return $this->makeRequest(self::GET, $url);
    }

    /**
     * @throws RequestFailedException
     */
    public function addBalance(int $amount): ?array
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
    public function selectProduct(int $pence): ?array
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
    public function refund(): ?array
    {
        $url = '/api/vending-machine/refund/' . $this->vendingMachineId;

        return $this->makeRequest(self::GET, $url);
    }

    /**
     * @throws RequestFailedException
     */
    private function makeRequest(string $method, string $url, array $params = []): ?array
    {
        try {
            return $this->transformer->transform($this->client->request($method, $url, $params));
        } catch (GuzzleException $e) {
            throw new RequestFailedException($e->getMessage());
        }
    }
}