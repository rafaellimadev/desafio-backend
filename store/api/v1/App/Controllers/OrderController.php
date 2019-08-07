<?php

namespace App\Controllers;

//BIBLIOTECAS RESPONSE - REQUEST
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;

//MODELS - DAO
use App\Dao\MySql\OrderDAO;

final class OrderController
{
    public function __construct($container) {
        //PDO
        $this->db_log = $container->get('db_log');
    }

    //BUSCA HISTÓRICO DE COMPRAS
    public function getHistory(Request $request, Response $response, array $args): Response
    {
        $client_id = $args['client_id']??0;
        $order = new OrderDAO($this->db_log);
        $orders = $order->getOrders($client_id);

        return $response->withJson($orders);
    }
}