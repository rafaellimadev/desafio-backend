<?php

namespace App\Dao\MySql;

//MODEL
use App\Models\MySql\CartItemModel;

Class CartItemDAO
{
    public function __construct($db) {
        //PDO
        $this->db = $db;
    }

    //INSERE O PRODUTO NO CARRINHO
    public function insertCartItem(CartItemModel $cartItem):void
    {
        //VERIFICA SE O PRODUTO INFORMADO EXISTE 
        $statement = $this->db->prepare("SELECT product.product_id FROM product WHERE product.product_id = :product_id");
        $statement->execute(['product_id' => $cartItem->getProductId()]); 
        
        //SE O PRODUTO NÃO EXISTIR, RODA UMA EXCEÇÃO
        if($statement->rowCount() <= 0) throw new \InvalidArgumentException("O produto informado '{$cartItem->getProductId()}' não existe no banco");
        
        $statement = $this->db->prepare("INSERT into cartitem(cart_id, client_id, product_id, date, time) values(:cart_id, :client_id, :product_id, :date, :time);");
        $statement->execute([
            'cart_id'     => $cartItem->getCartId(),
            'client_id'   => $cartItem->getClientId(),
            'product_id'  => $cartItem->getProductId(),
            'date'        => $cartItem->getDate(),
            'time'        => $cartItem->getTime(),
        ]);
    }

    //EXCLUI OS ITENS DO CARRINHO ESPECIFICADO
    public function deleteCartItem(String $cart_id):void
    {
        if(!empty($cart_id)){
            //VERIFICA SE EXISTEM ITENS DO CARRINHO INFORMADO 
            $statement = $this->db->prepare("SELECT cartitem.cart_id from cartitem WHERE cartitem.cart_id = :cart_id");
            $statement->execute(['cart_id' => $cart_id]); 
            
            //EXCLUI ITENS DO CARRINHO SE HOUVER
            if($statement->rowCount() > 0){
            
                $statement = $this->db->prepare("DELETE from cartitem WHERE cartitem.cart_id = :cart_id;");
                $statement->execute(['cart_id' => $cart_id]);
            }
        }
    }
}