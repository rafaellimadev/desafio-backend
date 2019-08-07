<?php

namespace App\Models\MySql;

final class TransactionModel
{
    private $client_id;
    private $client_name;
    private $total_to_pay;
    private $credit_card;

    public function getClientId():String{
        return $this->client_id;
    }

    public function setClientid(String $client_id):TransactionModel{
        $this->client_id = $client_id;
        return $this;
    }

    public function getClientName():String{
        return $this->client_name;
    }

    public function setClientName(String $client_name):TransactionModel{
        $this->client_name = $client_name;
        return $this;
    }

    public function getTotalToPay():String{
        return $this->total_to_pay;
    }

    public function setTotalToPay(int $total_to_pay):TransactionModel{
        $this->total_to_pay = $total_to_pay;
        return $this;
    }

    public function getCreditCard():string{
        return $this->credit_card;
    }

    public function setCreditCard(string $credit_card):TransactionModel{
        $this->credit_card = $credit_card;
        return $this;
    }

    public function validFields(array $dados):bool{
        if(!isset($dados['client_id']) || empty($dados['client_id']))             throw new \InvalidArgumentException("Campo obrigatório 'client_id' não foi informado");
        elseif(!isset($dados['client_name']) || empty($dados['client_name']))     throw new \InvalidArgumentException("Campo obrigatório 'client_name' não foi informado");
        elseif(!isset($dados['total_to_pay'])  || empty($dados['total_to_pay']))  throw new \InvalidArgumentException("Campo obrigatório 'total_to_pay' não foi informado. Ele deve conter um valor maior que 0");
        elseif(!isset($dados['credit_card'])  || !is_array($dados['credit_card']))  throw new \InvalidArgumentException("Objeto obrigatório 'credit_card' não foi informado.");
        return true;
    }
}