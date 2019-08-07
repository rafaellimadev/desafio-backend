<?php

namespace App\Dao\MySql;

//MODEL
use App\Models\MySql\CreditCardModel;

Class CreditCardDAO
{
    public function __construct($db) {
        //PDO
        $this->db = $db;
    }

    //INSERE O CARTÃO DE CRÉDITO
    public function insertCreditCard(CreditCardModel $creditCard):void
    {
        //VERIFICA SE O CARTÃO DE CRÉDITO JÁ EXISTE NA BASE 
        $statement = $this->db->prepare("SELECT creditcard.card_number FROM creditcard WHERE creditcard.card_number = :card_number");
        $statement->execute([
            'card_number' => $creditCard->getCardNumber()
        ]); 

        //SE O CARTÃO AINDA NÃO EXISTIR NA BASE, INSERE
        if($statement->rowCount() <= 0)
        {
            $statement = $this->db->prepare("INSERT into creditcard(card_number, card_holder_name, cvv, exp_date) values(:card_number, :card_holder_name, :cvv, :exp_date);");
            $statement->execute([
                'card_number'       => $creditCard->getCardNumber(),
                'card_holder_name'  => $creditCard->getCardHolderName(),
                'cvv'               => $creditCard->getCvv(),
                'exp_date'          => $creditCard->getExpDate()
            ]);
        }
    }
}