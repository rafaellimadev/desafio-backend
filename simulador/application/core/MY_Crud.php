<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class MY_Crud extends CI_Controller{

    function __construct() {
        parent::__construct();
    }

    public function list($filter){
		$retorno = new StdClass();
		$retorno->success = true;
		$registros = $this->md->list($filter);
        $retorno->registros = $registros;
        $retorno->count = count($retorno->registros);
		return $retorno;
    }

    public function get($id){
		$retorno = new Ajaxresponse();
		$retorno->success = false;

		$registro = $this->md->get($id);
		if($registro){
            $registro = $registro->to_array();
			$retorno->success = true;
			$retorno->registro = $registro;
		}else{
			$retorno->message = 'Não foi possível localizar o registro!';
		}
		return $retorno;
    }
    
    public function save($data){
		$retorno = new Ajaxresponse();
		$retorno->success = false;

		$registro = $this->md->save($data);
		
		if($registro){
			$retorno->success = true;
			$retorno->message = 'Registro salvo com sucesso';
		}else{
			$retorno->message = 'Erro ao salvar registro';
		}
		return $retorno;
    }
    
    public function remove($id){
		$retorno = new Ajaxresponse();
		$retorno->success = false;
		
		$registro = $this->md->get($id);
		
		if($registro){
			$registro = $this->md->delete($registro);
			if($registro){
				$retorno->success = true;
				$retorno->message = 'Registro excluído com sucesso';
			}else{
				$retorno->message = 'Não foi possível excluir o registro';	
			}
		}else{
			$retorno->message = 'Registro não encontrado';
		}
		return $retorno;
	}
}
