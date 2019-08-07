<?php

namespace App\Models\MySql;

final class CartItemModel
{
    private $client_id;
    private $cart_id;
    private $product_id;
    private $date;
    private $time;

    public function getClientId():String{
        return $this->client_id;
    }

    public function setClientId(String $client_id):CartItemModel{
        $this->client_id = $client_id;
        return $this;
    }

    public function getCartId():String{
        return $this->cart_id;
    }

    public function setCartId(String $cart_id):CartItemModel{
        $this->cart_id = $cart_id;
        return $this;
    }

    public function getProductId():String{
        return $this->product_id;
    }

    public function setProductId(String $product_id):CartItemModel{
        $this->product_id = $product_id;
        return $this;
    }

    public function getDate():String{
        return $this->date;
    }

    public function setDate(String $date):CartItemModel{
        $this->date = $date;
        return $this;
    }

    public function getTime():String{
        return $this->time;
    }

    public function setTime(String $time):CartItemModel{
        $this->time = $time;
        return $this;
    }

    public function validFields(array $dados):bool{
        if(!isset($dados['client_id'])      || empty($dados['client_id']))   throw new \InvalidArgumentException("Campo obrigatório 'client_id' não foi informado");
        elseif(!isset($dados['cart_id'])    || empty($dados['cart_id']))     throw new \InvalidArgumentException("Campo obrigatório 'cart_id' não foi informado");
        elseif(!isset($dados['product_id']) || empty($dados['product_id']))  throw new \InvalidArgumentException("Campo obrigatório 'product_id' não foi informado");
        elseif(!isset($dados['date'])       || empty($dados['date']))        throw new \InvalidArgumentException("Campo obrigatório 'date' não foi informado");
        elseif(!isset($dados['time'])       || empty($dados['time']))        throw new \InvalidArgumentException("Campo obrigatório 'time' não foi informado");
        return true;
    }
}