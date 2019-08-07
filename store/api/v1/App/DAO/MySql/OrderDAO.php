<?php

namespace App\Dao\MySql;

//MODEL
use App\Models\MySql\OrderModel;

Class OrderDAO 
{
    public function __construct($db_log) {
        //PDO
        $this->db_log = $db_log;
    }

    //RETORNA TODAS AS COMPRAS REALIZADAS
    public function getOrders(String $client_id='0'):array{
        if(!empty($client_id)){
            $statement = $this->db_log->prepare("SELECT * FROM orders WHERE client_id like '{$client_id}'");
            $statement->execute();
            $orders = $statement->fetchAll(\PDO::FETCH_ASSOC);
        }else{
            $statement = $this->db_log->prepare("SELECT * FROM orders");
            $statement->execute();
            $orders = $statement->fetchAll(\PDO::FETCH_ASSOC);
        }                   
        
        return $orders;
    }

    //INSERE UMA ORDEM DE COMPRA
    public function insertOrder(OrderModel $order):void
    {
        //CRIA IDENTIFICADOR DA ORDEM DE COMPRA
        do{
            $id_base = "abcdefghijklmnopqrstuvwxyz0123456789"; 
            $id = str_shuffle($id_base);
            
            //VERIFICA SE ESSE IDENTIFICADOR JÁ EXISTE NO BANCO
            $statement = $this->db_log->prepare("SELECT * FROM orders WHERE order_id like '{$id}'");
            $statement->execute();
        }while($statement->rowCount() > 0);

        //INSERE A ORDEM
        $statement = $this->db_log->prepare("INSERT into orders(order_id, card_number, client_id, value, date) values(:order_id, :card_number, :client_id, :value, :date);");
        $statement->execute([
            'order_id'    => $id,
            'card_number' => $order->getCardNumber(),
            'client_id'   => $order->getClientId(),
            'value'       => $order->getValue(),
            'date'        => date("d/m/Y")
        ]);
    }
}