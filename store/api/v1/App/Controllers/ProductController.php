<?php

namespace App\Controllers;

//FUNÇÃO VALIDAR DATA
use function src\validateDate;

//BIBLIOTECAS RESPONSE - REQUEST
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;

//MODELS - DAO
use App\Dao\MySql\ProductDAO;
use App\Models\MySql\ProductModel;

final class ProductController
{
    public function __construct($container) {
        //PDO
        $this->db = $container->get('db');
    }

    //LISTA 1 OU MAIS PRODUTOS
    public function getProducts(Request $request, Response $response, array $args): Response
    {
        try{
            //BUSCA OS PRODUTOS NO BANCO
            $product = new ProductDAO($this->db);
            $products = $product->getProducts();

            //RETORNO
            return $response->withJson($products);
        }catch(\InvalidArgumentException $ex){
            return $response->withJson([
                'error'  => \InvalidArgumentException::class,
                'status' => 400,
                'code'   => '001',
                'message'   => 'Não foi possível listar os produtos'
            ], 400);
        }catch(\Exception | \Throwable $ex){
            return $response->withJson([
                'error'  => \Exception::class,
                'status' => 500,
                'code'   => '001',
                'message'   => 'Houve algum problema. Não foi possível listar os produtos'
            ], 500);
        }
    }

    //INSERE O PRODUTO
    public function insertProduct(Request $request, Response $response, array $args): Response
    {
        try{
            //POST DADOS
            $data = $request->getParsedBody();
            
            //INICIA O OBJETO PRODUTO
            $md_product = new ProductModel();
            
            //REALIZA A VALIDAÇÃO DOS DADOS
            $md_product->validFields($data);
            validateDate($data['date']);
            
            //SETA OS VALORES
            $md_product->setArtist($data['artist'])
                        ->setProductId($data['product_id'])
                        ->setYear($data['year'])
                        ->setAlbum($data['album'])
                        ->setPrice($data['price'])
                        ->setStore($data['store'])
                        ->setThumb($data['thumb'])
                        ->setDate($data['date']);
            
            //INICIA TRANSAÇÃO COM O BANCO
            $this->db->beginTransaction();
            
            //INSERE PRODUTO
            $product = new ProductDAO($this->db);
            $product->insertProduct($md_product);
            
            //COMITA ALTERAÇÕES NO BANCO
            $this->db->commit();
            
            //RETORNO
            return $response->withJson(['message' => 'Produto inserido com sucesso']);
            
        }catch(\InvalidArgumentException $ex){
            return $response->withJson([
                'error'     => \InvalidArgumentException::class,
                'status'    => 400,
                'code'      => '001',
                'message'   => "Algo não está certo nas informações recebidas. Verifique se todos os campos atendem aos critérios.",
                'message_detail'   => $ex->getMessage()
            ], 400);
        }catch(\Exception | \Throwable $ex){
            return $response->withJson([
                'error'  => \Exception::class,
                'status' => 500,
                'code'   => '001',
                'message'   => 'Houve algum problema. Não foi possível inserir o produto',
                'message_detail'   => $ex->getMessage()
            ], 500);
        }
    }
}