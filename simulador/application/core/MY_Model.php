<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class MY_Model extends CI_Model {

    private $record_class_name = "";
    private $rec = null;
    protected $dependents = array();// array('model_name'=>array('my_field'=>'theirs_field'));
    protected $title = '';
    protected $logUpdate = false;

    public function title() {
        return $this->title;
    }
    
    public function get_record_class_name() {
        return $this->record_class_name;
    }

    protected function rec() {
        if ($this->rec == null) {
            $this->rec = $this->get_record();
        }
        return $this->rec;
    }

    protected function make_join() {
        $joins = $this->rec()->get_joins();
        foreach ($joins as $join) {
            if (!array_key_exists('type', $join)) {
                $this->db->join($join['table'], $join['on']);
            } else {
                $this->db->join($join['table'], $join['on'], $join['type']);
            }
        }
    }

    function __construct($record_class_name = '') {
        parent::__construct();
        $this->record_class_name = $record_class_name;
        if ($record_class_name != '') {
            $this->title = $this->rec()->table_name();
            $sync_tables = $this->config->item('sync_table');
            if ($sync_tables) {
                $this->sync_table();
            }
        }
        
    }

    public function record_count($where = array()) {
        return $this->db->where($where)->count_all_results($this->rec()->table_name());
    }

    public function &fetch_records($limit, $start, $where = array()) {
        $where = ($where === null) ? array() : $where;
        $this->db->limit($limit, $start);
        //$this->db->order_by($this->rec()->table_name().".id");
        $this->make_join();
        $this->db->where($where);
        $this->db->select($this->rec()->select_str());
        $query = $this->db->get($this->rec()->table_name());

        if ($query->num_rows() > 0) {
            foreach ($query->result($this->record_class_name) as $row) {
                $data[] = $row;
            }
            return $data;
        }
        return array();
    }

    public function fetch_csv($limit = 0, $start = 0, $where = array()) {
        $this->load->dbutil();
        $this->load->helper('csv');
        $this->db->where($where);
        if ($limit > 0) {
            $this->db->limit($limit, $start);
        }
        //$this->db->order_by($this->rec()->table_name().".id");
        $this->make_join();
        $this->db->select($this->rec->select_str());
        $query = $this->db->get($this->rec()->table_name());


        if ($query->num_rows() > 0) {
            return query_to_csv($query); // $this->dbutil->csv_from_result($query);
        }
        return false;
    }

    public function &insert($rec) {
        $this->db->insert($rec->table_name(), $rec->get_changes());
        $id = $this->db->insert_id();
        if(!$id ){
            $id = $rec->{$rec->pk_name()};
        }
        $reco = $this->get($id);
        return $reco;
    }

    public function delete($id){
        $message = '';
        $rec = $this->get($id);
        foreach($this->dependents as $key => $value){
            $mod = model_load_model($key);
            $where = array();
            foreach ($value as $mine => $theirs){
                $where[$theirs] = $rec->{$mine};
            }
            $total = $mod->record_count($where);
            if($total > 1) {
                $message.="$total registros na tabela ".$mod->title()."\n";
            }elseif($total == 1) {
                $message.="$total registro na tabela ".$mod->title()."\n";
            }
        }

        if($message==='') {
            $this->db->delete($rec->table_name(), $rec->get_keys());
            $message = $this->db->_error_message();
        } else {
            $message = "Foram encontradas as seguintes pendências:\n".trim($message, "\n");
        }
        return ($message==='') ? true : $message;
    }

    public function create_table() {
        $rec = $this->get_record();
        $sql = $rec->get_mysql_table_script();
        return $this->db->query($sql);
    }

    public function sync_table() {
        $this->create_table();
        $rec = $this->get_record();
        $sql = "select COLUMN_NAME from `information_schema`.`COLUMNS` where table_name = '{$rec->table_name()}' and table_schema = '{$this->db->database}'";
        $query = $this->db->query($sql);
        $result = $query->result_array();
        $fields = array();
        foreach ($result as $row) {
            $fields[] = $row['COLUMN_NAME'];
        }
        $sql = $rec->sync_table($fields);
        if ($sql != '')
            $this->db->query($sql); //atualiza banco de dados
    }

    public function get_record() {
        //echo $this->record_class_name;
        return new $this->record_class_name();
    }

    public function &get($id, $type = "object") {
        $rec = $this->get_record();
        $this->db->select($rec->select_str());
        $field = $rec->table_name() . ".".$rec->pk_name();
        $query = $this->db->where(array( $field=> $id));
        $this->make_join();
        $query = $this->db->get($rec->table_name());
        if ($type == "array") {
            foreach ($query->result($this->record_class_name) as $row) {
                return $row->to_array();
            }
        } else {
            foreach ($query->result($this->record_class_name) as $row) {
                $row->reset_fields();
                return $row;
            }
        }
        $rec->reset_fields();
        $null = null;
        return $null;
    }

    public function &get_by($where, $type = "object") {
        $rec = $this->get_record();
        $this->db->select($rec->select_str());
        $query = $this->db->where($where);
        $this->make_join();
        $query = $this->db->get($rec->table_name());
        $null = null;
        if (!$query)
            return $null;

        if ($type == "array") {
            foreach ($query->result($this->record_class_name) as $row) {
            $array = $row->to_array();
                return $array;
            }
        } else {
            foreach ($query->result($this->record_class_name) as $row) {
                $row->reset_fields();
                return $row;
            }
        }
        return $null;
    }

    public function &list_by($where, $type = "object", $order_by = '') {
        $rec = $this->get_record();
        $this->db->select($rec->select_str());
        $this->make_join();
        if ($order_by != '') {
            $this->db->order_by($order_by);
        }
        $query = $this->db->get_where($rec->table_name(), $where);
        $result = array();
        if ($type == "array") {
            foreach ($query->result($this->record_class_name) as $row) {
                $result[] = $row->to_array();
            }
        } else {
            foreach ($query->result($this->record_class_name) as $row) {
                $row->reset_fields();
                $result[] = $row;
            }
        }
        return $result;
    }

    public function list($fields="*", $joins=array(), $filtros=array(), $order_by = '', $type = "array" ) {
        $rec = $this->get_record();
        $this->db->select($rec->select_str());
        $this->make_join();
        if ($order_by != '') {
            $this->db->order_by($order_by);
        }
        $sql = "SELECT {$fields} FROM {$rec->table_name()}";
        foreach($joins as $join){
            $sql .= " ".$join;
        }
        $sql .= " WHERE 1=1";
        foreach($filtros as $campo => $filtro){
            $sql .= " AND {$campo} = {$filtro}";
        }
        $sql .= " ".$order_by;
        $result = $this->db->query($sql)->result_array();
        /*$result = array();
        if ($type == "array") {
            foreach ($dados as $row) {
                $result[] = $row->to_array();
            }
        } else {
            foreach ($dados as $row) {
                $row->reset_fields();
                $result[] = $row;
            }
        }*/
            return $result;
    }

    public function get_by_md5($md5) {
        $rec = $this->get_record();
        $this->db->select($rec->select_str());
        $this->make_join();
        $query = $this->db->get_where($rec->table_name(), array('md5Val' => $md5));
        foreach ($query->result($this->record_class_name) as $row) {
            $row->reset_fields();
            return $row;
        }
        return null;
    }

    public function &update($rec) {
		$changes = $rec->get_changes();
		//if($this->logUpdate) {
          $changes = $rec->log_changes('array');
		 // $keys = $rec->get_keys('array');
		  //model_load_model('seguranca/historicoalteracao_model');
          /*
		  foreach($changes as $campo => $change){
			  $config = array();
			  $config['entidade'] = $rec->table_name();
			  $config['campo'] = $campo;
			  $config['valorInicial'] = $change['inicial'];
			  $config['valorFinal'] = $change['final'];
			  $config['valorFinal'] = $change['final'];
			  $config['chave'] = array_values($keys)[0];
			  if(isset($this->user_profile->upro_nome_completo))
				  $config['nomeUsuario'] = $this->user_profile->upro_nome_completo;
        if(isset($this->user_profile->uacc_id))
				  $config['usuario_id'] = $this->user_profile->uacc_id;
			  $config['dtAlteracao'] = date('Y-m-d H:i:s');
			  $config['entidade_id'] = $this->entidade_id;
			  $this->db->insert('historicoalteracao', $config);
		  }
		  */
          //rafa -> implementar aqui um insert sem utilizar o model;
        //}
        
        if(sizeof($changes) > 0) {
            $this->db->update($rec->table_name(), $rec->get_changes(), $rec->get_keys());
            $rec->reset_fields();
        }
        
        
        
        return $rec;
    }

    public function save($dt){
        $obj = new $this->record_class_name;
        if(!isset($dt->{$obj->pk_name()}))
            $dt->{$obj->pk_name()} = -1;
        if($dt->{$obj->pk_name()} > 0){
            $obj = $this->get($dt->{$obj->pk_name()});
            $obj->assign($dt);
            return $this->update($obj);
        }else{
            $obj->assign($dt);
            $obj->{$obj->pk_name()} = null;
            return $this->insert($obj);
        }
        
    }

}
