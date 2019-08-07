<?php

namespace App\Dao\MySql;

//MODEL
use App\Models\MySql\ProductModel;

Class ProductDAO
{
    public function __construct($db) {
        //PDO
        $this->db = $db;
    }

    //RETORNA TODOS OS PRODUTOS
    public function getProducts():array{
        $registros = $this->db->query("SELECT * FROM product")->fetchAll(\PDO::FETCH_ASSOC);
        return $registros;
    }

    //INSERE PRODUTO
    public function insertProduct(ProductModel $product):void
    {
        //VERIFICA SE JÁ EXISTE ALGUM PRODUTO COM O ID INFORMADO 
        $statement = $this->db->prepare("SELECT product.product_id FROM product WHERE product.product_id = :product_id");
        $statement->execute([
            'product_id' => $product->getProductId()
        ]); 

        //SE EXISTIR, RETORNA UMA EXCEÇÃO
        if($statement->rowCount() > 0) throw new \InvalidArgumentException("já existe um produto com o identificador '{$product->getProductId()}'");

        //INSERE PRODUTO NO BANCO
        $statement = $this->db->prepare("INSERT into product(product_id, artist, year, album, price, store, thumb, date) values(:product_id,:artist, :year, :album, :price, :store, :thumb, :date);");
        $statement->execute([
            'product_id' => $product->getProductId(),
            'artist'     => $product->getArtist(),
            'year'       => $product->getYear(),
            'album'      => $product->getAlbum(),
            'price'      => $product->getPrice(),
            'store'      => $product->getStore(),
            'thumb'      => $product->getThumb(),
            'date'       => $product->getDate()
        ]); 
    }
}