<?php

namespace App\Models\MySql;

final class CreditCardModel
{
    private $card_number;
    private $card_holder_name;
    private $cvv;
    private $exp_date;

    public function getCardNumber():String{
        return $this->card_number;
    }

    public function setCardNumber(String $card_number):CreditCardModel{
        $this->card_number = $card_number;
        return $this;
    }

    public function getCardHolderName():String{
        return $this->card_holder_name;
    }

    public function setCardHolderName(String $card_holder_name):CreditCardModel{
        $this->card_holder_name = $card_holder_name;
        return $this;
    }

    public function getCvv():int{
        return $this->cvv;
    }

    public function setCvv(int $cvv):CreditCardModel{
        $this->cvv = $cvv;
        return $this;
    }

    public function getExpDate():String{
        return $this->exp_date;
    }

    public function setExpDate(String $exp_date):CreditCardModel{
        $this->exp_date = $exp_date;
        return $this;
    }

    public function validFields(array $dados):bool{
        if(!isset($dados['card_number'])          || empty($dados['card_number']))       throw new \InvalidArgumentException("Campo obrigatório 'card_number' não foi informado");
        elseif(!isset($dados['card_holder_name']) || empty($dados['card_holder_name']))  throw new \InvalidArgumentException("Campo obrigatório 'card_holder_name' não foi informado");
        elseif(!isset($dados['cvv'])              || empty($dados['cvv']))               throw new \InvalidArgumentException("Campo obrigatório 'cvv' não foi informado");
        elseif(!isset($dados['exp_date'])         || empty($dados['exp_date']))          throw new \InvalidArgumentException("Campo obrigatório 'exp_date' não foi informado");
        elseif($dados['cvv'] <= 0)                                                       throw new \InvalidArgumentException("Campo obrigatório 'cvv' deve ser um número inteiro");
        return true;
    }
}