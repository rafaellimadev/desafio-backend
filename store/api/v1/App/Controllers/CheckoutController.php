<?php

namespace App\Controllers;

//FUNÇÃO VALIDAR DATA
use function src\validateDate;

//BIBLIOTECAS RESPONSE - REQUEST
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;

//MODELS - DAO
use App\Dao\MySql\CartItemDAO;
use App\Models\MySql\CartItemModel;
use App\Dao\MySql\TransactionDAO;
use App\Models\MySql\TransactionModel;
use App\Dao\MySql\CreditCardDAO;
use App\Models\MySql\CreditCardModel;
use App\Dao\MySql\OrderDAO;
use App\Models\MySql\OrderModel;

final class CheckoutController
{
    
    public function __construct($container) {
        //PDO
        $this->db = $container->get('db');
        $this->db_log = $container->get('db_log');
    }

    //ADICIONA ITEM AO CARRINHO
    public function add_to_cart(Request $request, Response $response, array $args): Response
    {
        try{
            $data = $request->getParsedBody();
            
            //OBJETO CARRINHO
            $md_cartitem = new CartItemModel();
            
            //VALIDA OS DADOS
            $md_cartitem->validFields($data);
            validateDate($data['date']." ".$data['time'], "d/m/Y H:i:s");
            //SETA OS VALORES
            $md_cartitem->setCartId($data['cart_id'])
                        ->setClientId($data['client_id'])
                        ->setProductId($data['product_id'])
                        ->setDate($data['date'])
                        ->setTime($data['time']);
            
            //INICIA TRANSAÇÃO COM O BANCO
            $this->db->beginTransaction();
            
            //INSERE PRODUTO NO CARRINHO
            $cartitemDAO = new CartItemDAO($this->db);
            $cartitemDAO->insertCartItem($md_cartitem);
            
            //COMITA ALTERAÇÕES NO BANCO
            $this->db->commit();
            
            //RETORNO
            return $response->withJson(['message' => 'Item adicionado ao carrinho']);
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
                'message'   => 'Houve algum problema. Não foi possível inserir o produto no carrinho',
                'message_detail'   => $ex->getMessage()
            ], 500);
        }
    }

    //FINALIZA A COMPRA
    public function buy(Request $request, Response $response, array $args): Response
    {
        try{
            //DADOS POST
            $data = $request->getParsedBody();
            
            //OBJETO TRANSAÇÃO
            $md_transaction = new TransactionModel();
            
            //VALIDA DADOS
            $md_transaction->validFields($data);

            //SETA DADOS DO OBJETO TRANSAÇÃO
            $md_transaction->setClientId($data['client_id'])
                            ->setClientName($data['client_name'])
                            ->setTotalToPay($data['total_to_pay']);
            
            //OBJETO CARTÃO
            $md_credit_card = new CreditCardModel();
            
            //VALIDA DADOS
            $md_credit_card->validFields($data['credit_card']);
            validateDate($data['credit_card']['exp_date'],'m/y');
            
            //SETA DADOS DO OBJETO CARTÃO
            $md_credit_card->setCardNumber($data['credit_card']['card_number'])
                            ->setCardHolderName($data['credit_card']['card_holder_name'])
                            ->setCvv($data['credit_card']['cvv'])
                            ->setExpDate($data['credit_card']['exp_date']);
            
            //ATUALIZA TRANSAÇÃO COM DADOS DO CARTÃO
            $md_transaction->setCreditCard($data['credit_card']['card_number']);

            //OBJETO ORDEM
            $md_order = new OrderModel();
            
            //SETA DADOS DO OBJETO ORDER
            $md_order->setValue($data['total_to_pay'])
                        ->setClientId($data['client_id'])
                        ->setCardNumber($data['credit_card']['card_number']);

            //INICIA TRANSAÇÃO COM O BANCO
            $this->db->beginTransaction();

            //INSERE TRANSAÇÃO
            $transactionDAO = new TransactionDAO($this->db);
            $transactionDAO->insertTransaction($md_transaction);
            
            //INSERE CARTÃO DE CRÉDITO
            $creditcardDAO = new CreditCardDAO($this->db);
            $creditcardDAO->insertCreditCard($md_credit_card);
            
            //INICIA TRANSAÇÃO COM O BANCO
            $this->db_log->beginTransaction();
            
            //INSERE ORDEM
            $orderDAO = new OrderDAO($this->db_log);
            $orderDAO->insertOrder($md_order);
            
            //LIMPA O CARRINHO
            $cartitemDAO = new CartItemDAO($this->db);
            $cartitemDAO->deleteCartItem($data['cart_id']??'');
            
            //COMITA ALTERAÇÕES NOS BANCOS
            $this->db->commit();
            $this->db_log->commit();
            
            //RETORNO
            return $response->withJson(['message' => 'Compra finalizada com sucesso']);
            
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
                'message'   => 'Houve algum problema. Não foi possível finalizar a compra',
                'message_detail'   => $ex->getMessage()
            ], 500);
        }
    }
}