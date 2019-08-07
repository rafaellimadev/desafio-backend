<?php

//SCRIPT DE CONFIGURAÇÃO DO SLIM
use function src\slimConfiguration;

//CONTROLLERS
use App\Controllers\ProductController;
use App\Controllers\CheckoutController;
use App\Controllers\OrderController;

//INSTANCIA APP
$app = new \Slim\App(slimConfiguration());

//CRIA UM CONTAINER PARA ARMAZENAR OBJETOS E FUNÇÕES
$container = $app->getContainer();

//CUSTOMIZAÇÃO DA RESPOSTA AO USUÁRIO CASO ROTA NÃO ENCONTRADA
$container['notFoundHandler'] = function ($c) {
    return function ($request, $response) use ($c) {
        return $response->withJson([
            'error'     => \Exception::class,
            'status'    => 404,
            'message'   => "A rota informada não existe. Verifique se todos os parâmetros e barras foram preenchidos corretamente."
        ], 404);
    };
};

//ARMAZENA O OBJETO PDO PARA TRABALHARMOS COM O BANCO DE DADOS
$container['db'] = function ($c) {
    //PEGA OS DADOS DE CONEXÃO ARMAZENADOS NO ARQUIVO DE CONFIGURAÇÃO
    $pdo_config = $c->get('settings')['db_main'];
    
    //MONTA STRING DE CONEXÃO
    $dsn = "mysql:dbname=" . $pdo_config['dbname'] . ";host=" . $pdo_config['host'];
    
    //INSTANCIA OBJETO PDO
    $pdo = new PDO($dsn, $pdo_config['user'], $pdo_config['pass'], [PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES 'UTF8'"]);
    
    return $pdo;
};

//ARMAZENA O OBJETO PDO PARA TRABALHARMOS COM O BANCO DE DADOS
$container['db_log'] = function ($c) {
    //PEGA OS DADOS DE CONEXÃO ARMAZENADOS NO ARQUIVO DE CONFIGURAÇÃO
    $pdo_config = $c->get('settings')['db_log'];
    
    //MONTA STRING DE CONEXÃO
    $dsn = "mysql:dbname=" . $pdo_config['dbname'] . ";host=" . $pdo_config['host'];
    
    //INSTANCIA OBJETO PDO
    $pdo = new PDO($dsn, $pdo_config['user'], $pdo_config['pass'], [PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES 'UTF8'"]);
    
    return $pdo;
};

//VERSÃO 1
$app->group('',function() use($app){
    
    /***ROTA PRODUCT ***/
    $app->get('/product',  ProductController::class.':getProducts');
    $app->post('/product', ProductController::class.':insertProduct');
    
    /***ROTA CHECKOUT ***/
    $app->post('/add_to_cart', CheckoutController::class.':add_to_cart');
    $app->post('/buy',         CheckoutController::class.':buy');
    
    /***ROTA ORDER ***/
    $app->get('/history[/{client_id}]', OrderController::class.':getHistory');

})->add(new Tuupola\Middleware\JwtAuthentication([
    "secret" => "senha@teste"
]));

//RODA APLICAÇÃO
$app->run();