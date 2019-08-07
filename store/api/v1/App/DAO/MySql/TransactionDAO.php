<?php

namespace App\Dao\MySql;

//MODEL
use App\Models\MySql\TransactionModel;

Class TransactionDAO 
{
    public function __construct($db) {
        //PDO
        $this->db = $db;
    }

    //RETORNA TODAS AS COMPRAS REALIZADAS
    public function getTransactions():array{
        $registros = $this->db->query("SELECT * FROM transaction")->fetchAll(\PDO::FETCH_ASSOC);
        return $registros;
    }

    //INSERE UMA TRANSAÇÃO
    public function insertTransaction(TransactionModel $transaction):void
    {
        $statement = $this->db->prepare("INSERT into transaction(client_id, client_name, total_to_pay, credit_card) values(:client_id, :client_name, :total_to_pay, :credit_card);");
        $statement->execute([
            'client_id'      => $transaction->getClientId(),
            'client_name'    => $transaction->getClientName(),
            'total_to_pay'   => $transaction->getTotalToPay(),
            'credit_card'    => $transaction->getCreditCard(),
        ]);
    }
}