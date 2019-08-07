<?php

namespace App\Models\MySql;

final class ProductModel
{
    private $product_id;
    private $artist;
    private $year;
    private $album;
    private $price;
    private $store;
    private $thumb;
    private $date;

    public function getProductId():String{
        return $this->product_id;
    }

    public function setProductId(String $product_id):ProductModel{
        $this->product_id = $product_id;
        return $this;
    }

    public function getArtist():String{
        return $this->artist;
    }

    public function setArtist(String $artist):ProductModel{
        $this->artist = $artist;
        return $this;
    }
    
    public function setYear(int $year):ProductModel{
        $this->year = $year;
        return $this;
    }
    public function getYear():int{
        return $this->year;
    }
    
    public function getAlbum():String{
        return $this->album;
    }

    public function setAlbum(String $album):ProductModel{
        $this->album = $album;
        return $this;
    }

    public function getPrice():int{
        return $this->price;
    }

    public function setPrice(int $price):ProductModel{
        $this->price = $price;
        return $this;
    }

    public function getStore():String{
        return $this->store;
    }

    public function setStore(String $store):ProductModel{
        $this->store = $store;
        return $this;
    }

    public function getThumb():String{
        return $this->thumb;
    }

    public function setThumb(String $thumb):ProductModel{
        $this->thumb = $thumb;
        return $this;
    }
    
    public function getDate():String{
        return $this->date;
    }

    public function setDate(String $date):ProductModel{
        $this->date = $date;
        return $this;
    }

    public function validFields(array $dados):bool{
        if(!isset($dados['artist']) || empty($dados['artist']))             throw new \InvalidArgumentException("Campo obrigatório 'artist' não foi informado");
        elseif(!isset($dados['product_id']) || empty($dados['product_id'])) throw new \InvalidArgumentException("Campo obrigatório 'product_id' não foi informado");
        elseif(!isset($dados['year']) || empty($dados['year']))             throw new \InvalidArgumentException("Campo obrigatório 'year' não foi informado");
        elseif(!isset($dados['album']) || empty($dados['album']))           throw new \InvalidArgumentException("Campo obrigatório 'album' não foi informado");
        elseif(!isset($dados['price']) || empty($dados['price']))           throw new \InvalidArgumentException("Campo obrigatório 'price' não foi informado");
        elseif(!isset($dados['store']) || empty($dados['store']))           throw new \InvalidArgumentException("Campo obrigatório 'store' não foi informado");
        elseif(!isset($dados['thumb']) || empty($dados['thumb']))           throw new \InvalidArgumentException("Campo obrigatório 'thumb' não foi informado");
        elseif(!isset($dados['date']) || empty($dados['date']))             throw new \InvalidArgumentException("Campo obrigatório 'date' não foi informado");
        
        return true;
    }
}