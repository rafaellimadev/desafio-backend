<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Home extends MY_Controller {
	
	function __construct() {
		$this->scope = 'public';
		parent::__construct();
  	}

	public function index()
	{
		$this->load->view("home/index");
	}

	public function getProdutos()
	{
		$return = new AjaxResponse();
		$return->success = true;
		
		$curl = curl_init();

		curl_setopt_array($curl, array(
			CURLOPT_URL => "http://localhost/store/api/v1/product",
			CURLOPT_RETURNTRANSFER => true,
			CURLOPT_ENCODING => "",
			CURLOPT_MAXREDIRS => 10,
			CURLOPT_TIMEOUT => 30,
			CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
			CURLOPT_CUSTOMREQUEST => "GET",
			CURLOPT_HTTPHEADER => array(
				"Authorization: Bearer eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJzdWIiOiIxMjM0NTY3ODkwIiwibmFtZSI6IkpvaG4gRG9lIn0.srxnMFb9bs_RslX14Hw-ByAXnc3UFqTxS0yIepxmAs8",
				"Cache-Control: no-cache",
				"Connection: keep-alive",
				"Host: localhost",
				"User-Agent: PostmanRuntime/7.15.2",
				"cache-control: no-cache"
			),
		));

		$response = curl_exec($curl);
		$err = curl_error($curl);

		curl_close($curl);
		$produtos = json_decode($response);
		$return->produtos = $produtos;
		$return->output('json');
	}

	public function getHistorico()
	{
		$return = new AjaxResponse();
		$return->success = true;
		
		$curl = curl_init();

		curl_setopt_array($curl, array(
			CURLOPT_URL => "http://localhost/store/api/v1/history",
			CURLOPT_RETURNTRANSFER => true,
			CURLOPT_ENCODING => "",
			CURLOPT_MAXREDIRS => 10,
			CURLOPT_TIMEOUT => 30,
			CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
			CURLOPT_CUSTOMREQUEST => "GET",
			CURLOPT_HTTPHEADER => array(
				"Authorization: Bearer eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJzdWIiOiIxMjM0NTY3ODkwIiwibmFtZSI6IkpvaG4gRG9lIn0.srxnMFb9bs_RslX14Hw-ByAXnc3UFqTxS0yIepxmAs8",
				"Cache-Control: no-cache",
				"Connection: keep-alive",
				"Host: localhost",
				"User-Agent: PostmanRuntime/7.15.2",
				"cache-control: no-cache"
			),
		));

		$response = curl_exec($curl);
		$err = curl_error($curl);

		curl_close($curl);
		$historico = json_decode($response);
		$return->historico = $historico;
		$return->output('json');
	}
	
	public function getItensCarrinho($cart_id)
	{
		$return = new AjaxResponse();
		$return->success = true;

		$sql = "SELECT * FROM cartitem WHERE cart_id = '{$cart_id}'";
		$itens = $this->db->query($sql)->result_array();
		$return->itens = $itens;
		$return->output('json');
	}	
	
	public function addCarrinho()
	{
		$return = new AjaxResponse();
		$return->success = true;
		
		$request_body = file_get_contents('php://input');
		//$request_body = '{"client_id":"4321-1234","cart_id":"123-123-123","product_id":"1234-4321","date":"12/07/2019","time":"12:00:00"}';
		$dados = json_decode($request_body);
		//$dados = json_encode($dados);
		
		$curl = curl_init();
		$teste = http_build_query ($dados);
		curl_setopt_array($curl, array(
			CURLOPT_URL => "http://localhost/store/api/v1/add_to_cart",
			CURLOPT_RETURNTRANSFER => true,
			CURLOPT_ENCODING => "",
			CURLOPT_MAXREDIRS => 10,
			CURLOPT_TIMEOUT => 30,
			CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
			CURLOPT_CUSTOMREQUEST => "POST",
			CURLOPT_POSTFIELDS => '{
				"client_id":"'.$dados->client_id.'","cart_id":"'.$dados->cart_id.'","product_id":"'.$dados->product_id.'","date":"'.$dados->date.'","time":"'.$dados->time.'"
			}',
			CURLOPT_HTTPHEADER => array(
			  "Authorization: Bearer eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJzdWIiOiIxMjM0NTY3ODkwIiwibmFtZSI6IkpvaG4gRG9lIn0.srxnMFb9bs_RslX14Hw-ByAXnc3UFqTxS0yIepxmAs8",
			  "Content-Type: application/json"
			  
			),
		  ));
		$response = curl_exec($curl);
		$err = curl_error($curl);

		curl_close($curl);
		
		$return->output('json');
	}
	public function addProduto()
	{
		$return = new AjaxResponse();
		$return->success = true;
		
		$request_body = file_get_contents('php://input');
		//$request_body = '{"client_id":"4321-1234","cart_id":"123-123-123","product_id":"1234-4321","date":"12/07/2019","time":"12:00:00"}';
		$dados = json_decode($request_body);
		//$dados = json_encode($dados);
		
		$curl = curl_init();
		$teste = http_build_query ($dados);
		curl_setopt_array($curl, array(
			CURLOPT_URL => "http://localhost/store/api/v1/product",
			CURLOPT_RETURNTRANSFER => true,
			CURLOPT_ENCODING => "",
			CURLOPT_MAXREDIRS => 10,
			CURLOPT_TIMEOUT => 30,
			CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
			CURLOPT_CUSTOMREQUEST => "POST",
			CURLOPT_POSTFIELDS => '{
				"product_id":"'.$dados->product_id.'","year":"2019","artist":"teste","year":"2019","album":"teste","price":"100","store":"teste","thumb":"teste","date":"12/02/2019"
			}',
			CURLOPT_HTTPHEADER => array(
			  "Authorization: Bearer eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJzdWIiOiIxMjM0NTY3ODkwIiwibmFtZSI6IkpvaG4gRG9lIn0.srxnMFb9bs_RslX14Hw-ByAXnc3UFqTxS0yIepxmAs8",
			  "Content-Type: application/json"
			  
			),
		  ));
		$response = curl_exec($curl);
		$ret = json_decode($response);
		$err = curl_error($curl);

		curl_close($curl);
		
		if(isset($ret->status) && $ret->status != 200){
			$return->success = false;
			$return->message = $ret->message;
		}
		$return->output('json');
	}
	
	public function finalizar()
	{
		$return = new AjaxResponse();
		$return->success = true;
		
		$dados = '{
			"client_id":"4321-1234",
			"client_name":"Rafael",
			"cart_id":"569c30dc-6bdb-407a-b18b-3794f9b206a1",
			"total_to_pay":"10",
			"credit_card":{
				"card_number":"1234",
				"card_holder_name":"teste",
				"cvv":"111",
				"exp_date":"02/19"
			}
		}';
		
		$curl = curl_init();
		curl_setopt_array($curl, array(
			CURLOPT_URL => "http://localhost/store/api/v1/buy",
			CURLOPT_RETURNTRANSFER => true,
			CURLOPT_ENCODING => "",
			CURLOPT_MAXREDIRS => 10,
			CURLOPT_TIMEOUT => 30,
			CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
			CURLOPT_CUSTOMREQUEST => "POST",
			CURLOPT_POSTFIELDS => $dados,
			CURLOPT_HTTPHEADER => array(
			  "Authorization: Bearer eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJzdWIiOiIxMjM0NTY3ODkwIiwibmFtZSI6IkpvaG4gRG9lIn0.srxnMFb9bs_RslX14Hw-ByAXnc3UFqTxS0yIepxmAs8",
			  "Content-Type: application/json"
			  
			),
		  ));
		$response = curl_exec($curl);
		$err = curl_error($curl);

		curl_close($curl);
		
		$return->output('json');
	}
}