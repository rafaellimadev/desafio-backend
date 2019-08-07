<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class MY_Controller extends CI_Controller {

    public $scope = 'restrict';
    public $debug = false;
    public $request_type = 'html';
    public $entidade_id = -1;
    public $entidade_suffix = -1;

    function __construct() {
        parent::__construct();
        $this->load->library('session');
        $this->load->helper('url');
        //$this->config['static_url'] = (isset($this->config['static_url'])) ? $this->config['static_url'] : base_url();
        $this->static_url = $this->config->item('static_url');
        if ($this->static_url === false || $this->static_url === '') {
            $this->static_url = base_url();
        }
        $_REQUEST['debug'] = (array_key_exists('debug', $_REQUEST)) ? $_REQUEST['debug'] : false;
        if ($_REQUEST['debug'] == 'false')
            $_REQUEST['debug'] = 0;
        if ($_REQUEST['debug'] || $this->debug) {
            $sections = array(
                'benchmarks' => TRUE,
                'memory_usage' => TRUE,
                'config' => FALSE,
                'controller_info' => FALSE,
                'get' => FALSE,
                'post' => FALSE,
                'queries' => TRUE,
                'uri_string' => FALSE,
                'http_headers' => FALSE,
                'session_data' => FALSE
            );
            $this->output->set_profiler_sections($sections);
            $this->output->enable_profiler(TRUE);
        }

        $this->auth = new stdClass;
        $this->auth->session_data = array();
        //$this->load->database();
        $this->load->helper('form');
        $this->load->vars('base_url', base_url());
        $this->load->vars('current_url', $this->uri->uri_to_assoc(1));
        $this->data = null;
        $this->filters = null;

        
    }

    public function _remap($method, $params = array()) {
        $arr = explode('.', $method);
        $method = $arr[0];
        $this->request_type = count($arr) > 1 ? strtolower($arr[1]) : 'html';
        if (!method_exists($this, $method)) {
            return show_404();
        }

        $controller = strtolower(get_class($this));
        $action = strtolower($method);

        if (extension_loaded('newrelic')) {
            newrelic_name_transaction("{$controller}/{$action}");
        }
        $granted = false;
        
        return call_user_func_array(array($this, $method), $params);
    }

    

    /*protected function vars($nome, $tipo = 2, $default = "", $index = -1) {
        $default = "$default";
        $entidade_id = str_pad($this->entidade_id, 3, "0", STR_PAD_LEFT);
        $caminho = APPPATH . DIRECTORY_SEPARATOR . "config" . DIRECTORY_SEPARATOR . "entidades" . DIRECTORY_SEPARATOR . "entidade_" . $entidade_id . ".php";

        if (!file_exists($caminho)) {
            $this->load->model('sistema/parametro_model', 'parametro');
            $this->load->model('sistema/parametrovalor_model', 'parametrovalor');
            $this->parametro->gerarArquivo($this->entidade_id);
        }
        $this->config->load("entidades/entidade_$entidade_id");
        $res = $this->config->item($nome, 'entidade');
        
        if ($index == -1) {
            return $res;
        } else {
            return $res[$index];
        }
    }*/

    /*
    protected function vars($nome, $tipo = 2, $default = "", $index = -1) {
        $default = "$default";
        $entidade_id = str_pad($this->entidade_id, 3, "0", STR_PAD_LEFT);
        $caminho = APPPATH . DIRECTORY_SEPARATOR . "config" . DIRECTORY_SEPARATOR . "entidades" . DIRECTORY_SEPARATOR . "entidade_" . $entidade_id . ".php";

        if (!file_exists($caminho)) {
            $this->load->model('sistema/parametro_model', 'parametro');
            $this->load->model('sistema/parametrovalor_model', 'parametrovalor');
            $this->parametro->gerarArquivo($this->entidade_id);
        }
        $this->config->load("entidades/entidade_$entidade_id");
        $res = $this->config->item($nome, 'entidade');
        if ($res) {
            if ($index == -1) {
                return $res;
            } else {
                return $res[$index];
            }
        } else {
            $this->load->model('sistema/parametro_model', 'parametro');
            $this->load->model('sistema/parametrovalor_model', 'parametrovalor');
            $par = $this->parametro->get_by(array('nome' => $nome));
            if ($par === NULL) {
                $par = new Parametro_record();
                $par->nome = $nome;
                $par->tipo = $tipo;
                if ($index == -1) {
                    $par->maxIndex = 1;
                } else {
                    $par->maxIndex = 99;
                }
                $par->valorPadrao = $default;
                $par->sistema = 1;
                $this->parametro->insert($par);
                $this->parametro->gerarArquivo($this->entidade_id);
                return $this->vars($nome, $tipo, $default, $index);
            }
            return $nome;
        }
    }
    */

}

class ngTableRequest {

    public $count = 100;
    public $page = 1;
    public $filter = array();
    public $debug = false;
    public $sorting = array();
    public $fields = '*';
    public $join = array();
    private $CI = null;

    public function __construct() {
        $this->CI = & get_instance();
    }

    public function load() {
        $this->count = (array_key_exists('count', $_REQUEST)) ? $_REQUEST['count'] : $this->count;
        $this->page = (array_key_exists('page', $_REQUEST)) ? $_REQUEST['page'] : $this->page;
        $this->filter = (array_key_exists('filter', $_REQUEST)) ? $_REQUEST['filter'] : $this->filter;
        $this->debug = (array_key_exists('debug', $_REQUEST)) ? $_REQUEST['debug'] : $this->debug;
        $this->sorting = (array_key_exists('sorting', $_REQUEST)) ? $_REQUEST['sorting'] : $this->sorting;
    }

    private function filtrar() {
        foreach ($this->filter as $key => $value) {
            if (is_numeric($key)) {
                $this->CI->db->where($value);
            } else {
                $pos = strpos($key, ' ');
                if ($pos === false) {
                    $this->CI->db->like($key, $value);
                } else {
                    $this->CI->db->where($key, $value);
                }
            }
        }
    }

    private function joinTables() {
        foreach ($this->join as $key => $value) {
            $this->CI->db->join($value[0], $value[1], 'left');
        }
    }

    public function process($table) {
        $result = new ngTableResult();
//aplicando filtros para contagem
        foreach ($this->sorting as $key => $value) {
            $this->CI->db->order_by($key, $value); //field => (asc or desc)
        }
        $this->filtrar();
        $this->joinTables();
        $result->total = $this->CI->db->count_all_results($table);
//aplicando filtros para seleção de dados
        $this->filtrar();
        $this->joinTables();

        foreach ($this->sorting as $key => $value) {
            $this->CI->db->order_by($key, $value);
        }
        $offset = ($this->count * $this->page) - $this->count;
        $this->CI->db->limit($this->count, $offset);
        $this->CI->db->select($this->fields, false);
        $result->result = $this->CI->db->get($table)->result();
        return $result;
    }

}

class AjaxResponse {

    public $success = true;
    public $message = "";
    public $code = 200;

    function __construct() {
        
    }

    function output($type = 'json') {
        header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
        header("Cache-Control: post-check=0, pre-check=0", false);
        header("Pragma: no-cache");
        if ($type === 'json' || $type === 'xml') {
            if ($type === 'json') {
                header('Content-type: text/json');              //3
                header('Content-type: application/json');
                if ($this->code != 200) {
                    http_response_code($this->code);
                }/* elseif ($this->success == false) {
                  http_response_code(400);
                  } */
                echo json_encode($this);
            } elseif ($type === 'xml') {
                header('Content-Type: application/xml');
                echo $this->xml_encode(array('response' => $this));
            }
        }
    }

    private function xml_encode($mixed, $domElement = null, $DOMDocument = null) {
        if (is_null($DOMDocument)) {
            $DOMDocument = new DOMDocument;
            $DOMDocument->formatOutput = true;
            $this->xml_encode($mixed, $DOMDocument, $DOMDocument);
            echo $DOMDocument->saveXML();
        } else {
            if (is_array($mixed) || is_object($mixed)) {
                foreach ($mixed as $index => $mixedElement) {
                    if (is_int($index)) {
                        if ($index === 0) {
                            $node = $domElement;
                        } else {
                            $node = $DOMDocument->createElement($domElement->tagName);
                            $domElement->parentNode->appendChild($node);
                        }
                    } else {
                        $plural = $DOMDocument->createElement($index);
                        $domElement->appendChild($plural);
                        $node = $plural;
                        /* if (!(rtrim($index, 's') === $index)) {
                          $singular = $DOMDocument->createElement(rtrim($index, 's'));
                          $plural->appendChild($singular);
                          $node = $singular;
                          } */
                    }

                    $this->xml_encode($mixedElement, $node, $DOMDocument);
                }
            } else {
                $mixed = is_bool($mixed) ? ($mixed ? 'true' : 'false') : $mixed;
                $domElement->appendChild($DOMDocument->createTextNode($mixed));
            }
        }
    }

}

class ngTableResult extends AjaxResponse {

    public $result = array();
    public $total = 0;

    function __construct() {
        
    }

}
