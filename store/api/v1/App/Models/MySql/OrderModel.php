<?php

namespace App\Models\MySql;

final class OrderModel
{
    private $order_id;
    private $value;
    private $client_id;
    private $card_number;
    private $date;

    public function getOrderId():String{
        return $this->order_id;
    }

    public function setOrderId(String $order_id):OrderModel{
        $this->order_id = $order_id;
        return $this;
    }

    public function getValue():int{
        return $this->value;
    }

    public function setValue(int $value):OrderModel{
        $this->value = $value;
        return $this;
    }

    public function getClientId():String{
        return $this->client_id;
    }

    public function setClientId(String $client_id):OrderModel{
        $this->client_id = $client_id;
        return $this;
    }

    public function getCardNumber():string{
        return $this->card_number;
    }

    public function setCardNumber(string $card_number):OrderModel{
        $this->card_number = $card_number;
        return $this;
    }
    
    public function getDate():string{
        return $this->date;
    }

    public function setDate(string $date):OrderModel{
        $this->date = $date;
        return $this;
    }

    public function validFields(array $dados):bool{
        if(!isset($dados['client_id'])       || empty($dados['client_id']))   throw new \InvalidArgumentException("Campo obrigatório 'client_id' não foi informado.");
        elseif(!isset($dados['card_number']) || empty($dados['card_number'])) throw new \InvalidArgumentException("Campo obrigatório 'card_number' não foi informado.");
        elseif(!isset($dados['value'])       || empty($dados['value']))       throw new \InvalidArgumentException("Campo obrigatório 'value' não foi informado. Ele deve conter um valor maior que 0.");
        return true;
    }
}