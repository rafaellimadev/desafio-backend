<?php

/* define data e hora padrão para o programa */
date_default_timezone_set('America/Sao_Paulo');

class Field {

    private $name;
    private $table_name;
    private $type = "varchar";
    private $size = 100;
    private $auto_increment = false;
    private $primary_key = false;
    private $nullable = false;
    private $input_label = null;
    private $input_type = null;
    private $input_value = null;
    private $input_placeholder = null;
    private $input_class = null;
    private $js_rule = null;
    private $php_rule = null;
    private $set_count = 0;
    private $has_html = false;
    private $read_only = false;
    private $join = array();
    private $parent = null;
    private $html = null;

    public function is_pk() {
        return $this->primary_key;
    }

    public function __call($name, $arguments) {
        $action = substr($name, 0, 3);
        switch ($action) {
            case 'get':
                $property = strtolower(substr($name, 4));
                if (property_exists($this, $property)) {
                    return $this->{$property};
                } else {
                    echo "Undefined Property $property";
                }
                break;
            case 'set':
                $property = strtolower(substr($name, 4));
                if (property_exists($this, $property)) {
                    $this->{$property} = $arguments[0];
                } else {
                    echo "Undefined Property";
                }
                break;
            default :
                return FALSE;
        }
    }

    public function field_name() {
        $field = $this->name;
        $table = $this->table_name;
        
                
        if(sizeof($this->join) > 0){
            if(isset($this->join['field_name'])) {
                $name = "{$this->join['field_name']} {$field}";
            } else $name = "{$this->join['table']}.{$field}";
        } else $name = "{$table}.{$field}";
        return $name;
    }

    private function input_label($value = null) {
        if ($value == null) {
            if ($this->input_label === null) {
                return $this->name;
            } else {
                return $this->input_label;
            }
        } else
            $this->input_label = $value;
    }

    private function input_type($value = null) {
        if ($this->input_type === null) {
            return "text";
        } else {
            return $this->input_type;
        }
    }

    private function input_value($value = null) {
        if ($this->input_value === null) {
            return array();
        } else {
            return $this->input_value;
        }
    }

    private function input_placeholder($value = null) {
        if ($this->input_placeholder === null) {
            return $this->input_label();
        } else {
            return $this->input_placeholder;
        }
    }

    private function input_class($value = null) {
        
    }

    private function js_rule($value = null) {
        
    }

    private function php_rule($value = null) {
        
    }

    public function get_php_rules() {
        $rules = $this->php_rule();
        if (!$this->nullable) {
            $rules.='|required';
        }
        if ($this->size > 0) {
            $rules.="|max_length[{$this->size}]";
        }
        if ($this->type == 'int') {
            $rules.="|max_length[{$this->size}]";
        }
        return array(
            'field' => $this->name,
            'label' => $this->input_label,
            'rules' => $rules
        );
    }

    public function xEditor($props = array()) {
        $field = $this->name;
        $key_name = $this->parent->pk_name();
        $key_value = $this->parent->$key_name;
        $value = $this->parent->$field;
        $type = 'text';
        $cus_props = '';
        if (isset($this->html)) {
            if (isset($this->html->listValues)) {
                $type = 'select';
                $cus_props = 'data-value="'.$value.'"';
                $value = '';
            }
        }
        return '<a href="#" id="' . $field . '" data-type="'.$type.'" data-pk="' . $key_value . '" class="x-editor-text" '.$cus_props.' >' . htmlentities($value, ENT_QUOTES, 'UTF-8') . '</a>';
    }
    
    public function xEditorJS() {
        $props = array();
        $lst_source = '';
        if (isset($this->html)) {
            if (isset($this->html->listValues)) {
                $list_values = $this->html->listValues;
                $lst_source = '[';
                $sep = '';
                foreach ($list_values as $key => $value) {
                    $lst_source.=$sep . "{value: '$key', text: '$value'}";
                    $sep = ',';
                }
                $lst_source.=']';
                $props['source'] = $lst_source;
            }
        }

        $result = null;
        $sep = '';
        foreach ($props as $key => $value) {
            $result.=$sep . "$key: $value";
            $sep = ',';
        }
        if($result===null){
            return '';
        } else return '{'.$result.'}';
    }

    function __construct($name, $metadata = array(), $table_name = '', $parent = null) {
        $this->table_name = $table_name;
        $this->name = $name;
        $this->parent = $parent;
        if (array_key_exists('type', $metadata)) {
            $this->type = $metadata['type'];
        }
        if (array_key_exists('size', $metadata)) {
            $this->size = $metadata['size'];
        }
        if (array_key_exists('auto_increment', $metadata)) {
            $this->auto_increment = $metadata['auto_increment'];
        }

        if (array_key_exists('primary key', $metadata)) {
            $this->primary_key = true;
        }

        if (array_key_exists('null', $metadata)) {
            $this->nullable = $metadata['null'];
        }

        if (array_key_exists('read_only', $metadata)) {
            $this->read_only = $metadata['read_only'];
        }

        if (array_key_exists('join', $metadata)) {
            $this->join = $metadata['join'];
        }

        if (array_key_exists('html', $metadata)) {
            $html = $metadata['html'];

            if (!array_key_exists('has_html', $html)) {
                $html['has_html'] = true;
            }
            $object = new stdClass();
            if ($html['has_html']) {
                foreach ($html as $prop => $value) {
                    $object->$prop = $value;
                    $this->$prop($value);
                }
            }
            $this->html = $object;
        }
    }

    public function __set($prop, $value) {
        if ($this->set_count > 3)
            die("rodou 3 vezes aqui e o valor é = $prop");
        $this->set_count++;
        if (!strpos($prop, " ") === false) {
            $prop = str_replace(" ", "_", $prop);
            $this->$prop = $value;
        } elseif (strpos($prop, "input_") === false) {
            $prop = 'input_' . $prop;
            $this->$prop = $value;
        } else {
            $trace = debug_backtrace();
            trigger_error(
                    'Undefined property via __get(): ' . $prop .
                    ' in ' . $trace[0]['file'] .
                    ' on line ' . $trace[0]['line'], E_USER_ERROR);
        }
    }

    private function get_sql_type() {
        if (strtoupper($this->type) == 'NUMERIC' || strtoupper($this->type) == 'VARCHAR') {
            $ret = "{$this->type}({$this->size})";
        } else {
            $ret = "{$this->type}";
        }
        return $ret;
    }

    public function get_sql_script() {
        /* não gera script para campos somente leitura */
        if ($this->read_only)
            return "";

        $script = "";
        $script.= "        `{$this->name}` " . $this->get_sql_type();
        $null_str = " null";
        if (!$this->nullable) {
            $null_str = ' not null';
        }
        $script.= $null_str;
        if ($this->auto_increment) {
            $script.= " AUTO_INCREMENT";
        }
        if ($this->primary_key) {
            $script.= ',' . PHP_EOL . "         PRIMARY KEY({$this->name})";
        }
        return $script;
    }

    public function get_html($indent) {
        $result = "";
        $type = $this->input_type();
        if ($type === "text" || $type === "email") {
            $result.= $indent . '<div class="form-group ' . $this->name . '-group">' . PHP_EOL;
            $result.= $indent . '  <label id="lbl' . $this->name . '" class="' . $this->name . '-label" for="txt' . $this->name . '">' . $this->input_label() . '</label>' . PHP_EOL;
            $result.= $indent . '  <input type="text" class="form-control ' . $this->name . '-control" id="txt' . $this->name . '" name="' . $this->name . '"  value="<?php echo set_value_x("' . $this->name . '",$form_data); ?>" placeholder="' . $this->input_placeholder() . '">' . PHP_EOL;
            $result.= $indent . '  <span class="' . $this->name . '-error small help-block errormsg field-validation-valid" data-valmsg-for="' . $this->name . '" data-valmsg-replace="true"><?php echo form_error("' . $this->name . '"); ?></span>' . PHP_EOL;
            $result.= $indent . '</div>';
        }
        return $result;
    }

}

class Record {

    private $values = array();
    private $fields_modified = array();
    private $original_values = array();
    protected $table_name = "";
    protected $table_fields = array();
    private $select_str = '';
    private $primary_key_name = 'id';

    public function select_str() {
        return $this->select_str;
    }

    function pk_name() {
        return $this->primary_key_name;
    }
    
    public function assign($object) {
        foreach($object as $key => $value) {
            if (array_key_exists($key, $this->table_fields)) {
                    $this->$key = $value;
            }
        }
    }

    public function to_array() {
        $result = array();
        foreach ($this->table_fields as $key => $value) {
            $result[$key] = $this->$key;
        }
        return $result;
    }

    function __construct($table, $metadata) {
        $this->table_name = $table;
        //$this->table_fields = $metadata;
        foreach ($metadata as $name => $conf) {
            $this->table_fields[$name] = new Field($name, $conf, $table, $this);
            if ($this->table_fields[$name]->is_pk() == true)
                $this->primary_key_name = $name;
        }

        $str = '';
        $sep = '';
        foreach ($this->table_fields as $field) {
            $str.= $sep . " " . $field->field_name();
            $sep = ',';
        }
        $this->select_str = $str;
    }

    public function get_joins() {
        $result = array();
//    echo "<pre>";
        foreach ($this->table_fields as $key => $value) {
            $join = $value->get_join();
            if (sizeof($join) > 1) {
                $result[$key] = $join;
            }
        }
        return $result;
    }

    public function table_name() {
        return $this->table_name;
    }

    public function reset_fields() {
        $this->fields_modified = array();
        $this->original_values = $this->values;
    }

    public function __set($prop, $value) {
        if (array_key_exists($prop, $this->table_fields)) {
            /* monta o nome dos métodos de dados */
            $call_get = "get_" . $prop;
            $call_set = "set_" . $prop;
            /* busca o valor atual do campo */
            $prior_value = null;
            if (array_key_exists($prop, $this->original_values)) {
                $prior_value = $this->original_values[$prop];
            }
            /* altera o vampo */
            $this->$call_set($value);
            /* busca o valor atual do campo */
            $value = $this->$call_get();
            /* identifica se o campo foi modificado */
            $this->fields_modified[$prop] = $value !== $prior_value;
        }
    }

    public function __get($prop) {
        if (array_key_exists($prop, $this->table_fields)) {
            if (array_key_exists($prop, $this->values)) {
                $call = "get_" . $prop;
                return $this->$call();
            }
            return null;
        } else {
            trigger_error("\n<br>Error Code 0x00001 - Invalid Field: $prop ");
        }
    }

    public function get_changes() {
        $result = array();
        foreach ($this->fields_modified as $key => $value) {
            if ($value && !$this->table_fields[$key]->get_read_only()) {
                $result[$key] = $this->values[$key];
            }
        }
        return $result;
    }

    public function log_changes($tipo='string') {
        $result = array();
        foreach ($this->fields_modified as $key => $value) {
            if ($value && !$this->table_fields[$key]->get_read_only()) {
                if($tipo=='array'){
                  $result[$key]['inicial'] = $this->original_values[$key];
                  $result[$key]['final']   = $this->values[$key];
                }
                else
                  $result[$key] = "Campo $key modificado de {$this->original_values[$key]} para {$this->values[$key]}";
            }
        }
        return $result;
    }

    public function get_keys() {
        $result = array();

        foreach ($this->table_fields as $key => $value) {
            if ($value->get_primary_key() && array_key_exists($key, $this->values)) {
                $result[$key] = $this->values[$key];
            }
        }
        return $result;
    }

    public function load_metadata($values = null) {
        if ($values !== null) {
            $this->table_fields = $values;
        }
    }

    public function __call($name, $args) {
        /*         * ***************************SET****************************** */
        if ((substr($name, 0, 4) == 'set_') && (strlen($name) > 4)) {
            $prop = substr($name, 4, strlen($name) - 4);
            if (array_key_exists($prop, $this->table_fields)) {
                $field_value = null;
                $field_value_valid = false;
                /* se for um array pega o primeiro elemento */
                if (is_array($args)) {
                    $field_value = array_shift($args);
                    $field_value_valid = true;
                } else {
                    /* se não é um array pega p valor total */
                    $field_value = $args;
                    $field_value_valid = true;
                }
                if ($field_value == "")
                    $field_value = null;

                /*                 * ********************* TRATAMENTO DE DATAS ********************** */
                $ftype = strtoupper($this->table_fields[$prop]->get_type());
                if (($ftype === 'DATE') && $field_value !== null) {
                    if(strlen($field_value) > 10) $field_value = substr ($field_value, 0,10);
                    $format = 'd/m/Y';
                    $finalFormat = 'Y-m-d';
                    if(strpos($field_value,'-')!==false) {
                        $format = 'Y-m-d';
                    }
                    $date = DateTime::createFromFormat($format, $field_value);
                    if($date && $date->format($format) === $field_value) {
                        $field_value = $date->format($finalFormat);
                    } else {
                        $field_value = null;
                    }
                }elseif (($ftype === 'DATETIME') && $field_value !== null) {
                    $format = 'd/m/Y H:i:s';
                    $finalFormat = 'Y-m-d H:i:s';
                    if(strpos($field_value,'-')!==false) {
                        $format = 'Y-m-d H:i:s';
                    }
                    $date = DateTime::createFromFormat($format, $field_value);
                    if($date && $date->format($format) === $field_value) {
                        $field_value = $date->format($finalFormat);
                    } else {
                        $field_value = null;
                    }
                }
                $this->values[$prop] = $field_value;
                return;
            }
            /*             * ***************GET********************************* */
        } elseif ((substr($name, 0, 4) == 'get_') && (strlen($name) > 4)) {
            $prop = substr($name, 4, strlen($name) - 4);
            if (array_key_exists($prop, $this->table_fields)) {
                if (array_key_exists($prop, $this->values)) {
                    $result = $this->values[$prop];
                    /*                     * ********************* TRATAMENTO DE DATAS ********************** */
                    $ftype = strtoupper($this->table_fields[$prop]->get_type());
                    if (($ftype == 'DATE') && trim($result) != "") {
                        $result = implode("/", array_reverse(explode("-", $result)));
                    }elseif (($ftype == 'DATETIME') && trim($result) != "") {
                        $date = DateTime::createFromFormat('Y-m-d H:i:s', $result);
                        $result = $date->format('d/m/Y H:i:s');
                    }
                    return $result;
                } else
                    return null;
            }
        }
        trigger_error("\n<br>Error Code 0x00001 - Invalid method: $name");
    }

    public function sync_table($database_fields) {
        $sql = '';
        $sep = '';
        foreach ($this->table_fields as $key => $value) {
            if (!in_array($key, $database_fields)) {

                $fsql = $value->get_sql_script();
                if ($fsql != '') {
                    $sql.= $sep . " ADD COLUMN $fsql";
                    $sep = ",\n";
                }
            }
        }
        if ($sql != '') {
            $sql = "Alter table {$this->table_name} $sql";
        }
        return $sql;
    }

    public function get_mysql_table_script() {
        $sep = "";
        $script = 'CREATE TABLE IF NOT EXISTS `' . $this->table_name . '` (';
        foreach ($this->table_fields as $key => $value) {
            $sql = $value->get_sql_script();
            if ($sql != '') {
                $script.= $sep . PHP_EOL;
                $sep = ',';
                $script.= $value->get_sql_script();
            }
        }
        $script.= PHP_EOL . ');' . PHP_EOL;
        return $script;
    }

    public function get_html_elements() {
        $sep = "";
        $indent = "";
        $script = PHP_EOL . '<form role="form" id="frm' . $this->table_name . '" method="POST">' . PHP_EOL;
        foreach ($this->table_fields as $key => $value) {
            $indent = "  ";
            $script.= $value->get_html($indent) . PHP_EOL;
        }
        $script.= PHP_EOL . '</form>' . PHP_EOL;
        return $script;
    }

    public function field($name) {
        if (array_key_exists($name, $this->table_fields)) {
            return $this->table_fields[$name];
        } else
            return null;
    }

}

/**
 *
 * Allow models to use other models
 *
 * This is a substitute for the inability to load models
 * inside of other models in CodeIgniter.  Call it like
 * this:
 *
 * $salaries = model_load_model('salary');
 * ...
 * $salary = $salaries->get_by($employee_id);
 *
 * @param string $model_name The name of the model that is to be loaded
 *
 * @return object The requested model object
 *
 */
function model_load_model($model_name) {
    $name = explode('/',$model_name);
    $name = $name[count($name)-1];
    $CI = & get_instance();
    $CI->load->model($model_name,$name);
    
    return $CI->{$name};
}

function set_radio_x($field, $value, $data = "") {
    if (is_array($data)) {
        if (array_key_exists($field, $data)) {
            return set_radio($field, $value, $data[$field] == $value);
        }
        return set_radio($field, $value);
    }
    return set_radio($field, $data);
}

function set_value_x($field, $data = "") {
    if (is_array($data)) {
        if (array_key_exists($field, $data)) {
            return set_value($field, $data[$field]);
        }
        return set_value($field);
    }
    return set_value($field, $data);
}
