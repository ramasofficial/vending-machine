<?php

use TDD\AddCoin;
use TDD\Balance;
use TDD\Client\Exception\RequestFailedException;
use TDD\Repositories\ProductRepository;
use TDD\VendingMachine;

require_once '../vendor/autoload.php';

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '/../');
$dotenv->safeLoad();

$client = new \TDD\Client\Client(new \GuzzleHttp\Client(
    ['base_uri' => $_ENV['API_URL']]
), new \TDD\Client\Transformer\ApiResponse(), 1);

$vendingMachine = new VendingMachine(
    new AddCoin(new Balance($client)),
    new Balance($client),
    new ProductRepository(),
    $client);

//$vendingMachine->add(1, 'pound');

//print_r($vendingMachine->selectProduct(100));
//print_r($vendingMachine->selectProduct(50));

//print_r($vendingMachine->refund());
//print_r($vendingMachine->reset());
//
//print_r($vendingMachine->checkBalance());

//$vendingMachine->refund();


//try {
//    $response = $client->getBalance();
//
//    print_r($response);
//} catch (RequestFailedException $e) {
//    echo $e->getMessage();
//}

//try {
//    $response = $client->addBalance(100);
//
//    print_r($response);
//} catch (RequestFailedException $e) {
//    echo $e->getMessage();
//}

//try {
//    $response = $client->selectProduct(100);
//
//    print_r($response);
//} catch (RequestFailedException $e) {
//    echo $e->getMessage();
//}

//try {
//    $response = $client->refund();
//
//    print_r($response);
//} catch (RequestFailedException $e) {
//    echo $e->getMessage();
//}