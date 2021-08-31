<?php

use TDD\Client\Exception\RequestFailedException;

require_once '../vendor/autoload.php';

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '/../');
$dotenv->safeLoad();

$client = new \TDD\Client\Client(new \GuzzleHttp\Client(
    ['base_uri' => $_ENV['API_URL']]
), new \TDD\Client\Transformer\ApiResponse(), 1);


//try {
//    $response = $client->getBalance();
//
//    print_r($response->toArray());
//} catch (RequestFailedException $e) {
//    echo $e->getMessage();
//}

//try {
//    $response = $client->addBalance(100);
//
//    print_r($response->toArray());
//} catch (RequestFailedException $e) {
//    echo $e->getMessage();
//}

//try {
//    $response = $client->selectProduct(100);
//
//    print_r($response->toArray());
//} catch (RequestFailedException $e) {
//    echo $e->getMessage();
//}

//try {
//    $response = $client->refund();
//
//    print_r($response->toArray());
//} catch (RequestFailedException $e) {
//    echo $e->getMessage();
//}