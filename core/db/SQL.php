<?php
/**
 * Created by PhpStorm.
 * User: Stas
 * Date: 15.03.2019
 * Time: 21:55
 */

require "DBHandler.php";


class SQL {
    //private $me = NULL;
    private $model_class_name;

    private $select;
    private $from;
    private $order_by;
    private $where_col, $where_op, $where_val, $where_assoc;
    private $limit;

    public function table($from_options) {
        $me = new SQL();

        $query_part = " FROM " . $from_options;
        $me->from = $query_part;
        return $me;
    }

    // '*' by default
    public function select($select_options = "*") {
        $query_part = " SELECT ".$select_options;
        $this->select = $query_part;
        return $this;
    }

    public function where($column, $operator, $value, $association = ' ') {
        $this->where_col[] = $column;
        $this->where_op[] = $operator;
        $this->where_val[] = $value;
        $this->where_assoc[] = $association;

        return $this;
    }

    public function order_by($order_options, $order_type = 'ASC'){
        $query_part = " ORDER BY " . $order_options ." ". $order_type;
        $this->order_by = $query_part;
        return $this;
    }

    // '200' by default
    public function limit($limit_start_options = "0", $limit_end_options = "200") {
        $query_part = " LIMIT ". $limit_start_options . ", " .  $limit_end_options;
        $this->limit= $query_part;
        return $this;
    }

    /**
     * @return string
     */
    private function build_query() {
        $sql_query =  $this->select . $this->from;

        if (isset($where_col)) {
            $sql_query .= " WHERE ";
            for ($i = 0; $i < count($this->where_col); $i++) {
                $sql_query .= $this->where_col[$i];
                $sql_query .= $this->where_op[$i];
                $sql_query .= $this->where_val[$i];
                $sql_query .= $this->where_assoc[$i];
            }
        }

        $sql_query .= $this->order_by;
        $sql_query .= $this->limit;
        $sql_query .= ';';

        return $sql_query;
    }

    public function exct($sql_query) {
        $dsn = DBHandler::assemble_DSN('127.0.0.1','my_cars_db', 'utf8'); //Data_source_name
        $auth_data = DBHandler::assemble_auth_data('root', 'PASSWORD'); //login & password
        $dbh = DBHandler::getInstance($dsn, $auth_data['user'], $auth_data['pass']);

        $stmt = DBHandler::execute_query($dbh, $sql_query, $debug_mode_on = false);
        return $stmt;
    }


    /**
     * @param mixed $model_class_name
     */
    public function setModelClassName($model_class_name): void
    {
        $this->model_class_name = $model_class_name;
    }


    public function formatResult($result){
        $results_mapped_array = [];
        if ($this->model_class_name != null) {

            foreach ($result as $res_row) {
                $results_mapped_array[] = $this->model_class_name::extract_from_result_array($res_row);
                //... reflection $modelObj = new $this->model() call_user_func_array( [$modelObj, __construct()], $res_row);
            }
            return $results_mapped_array;
        } else {
            return $result;
        }
    }
    /**
     * @return mixed
     */
    public function get() {

        $sql_query = $this->build_query();
        $res = $this->exct($sql_query);

        //todo make extract in model constr
        return $this->formatResult($res);
    }

    public function first() {
        return $this->get()[0];
    }


    public function find($column, $operator, $value) {
        $this->select = " SELECT * ";

        $this->where_col = $column;
        $this->where_op = $operator;
        $this->where_val = $value;

        $sql_query = $this->select . $this->from . " WHERE " . $this->where_col . $this->where_op . $this->where_val . ";";
        $res = $this->exct($sql_query);

        return $this->formatResult($res);
    }

    public function update($table,$attributes)
    {
        $sql_query = " UPDATE ". $table ." SET ";
        foreach ($attributes as $field_name => $field_value){
            $sql_query .= $field_name . "=" . $field_value . ",";
        }
        $sql_query = rtrim($sql_query, ',');

        //id
        $sql_query .= " WHERE " . key($attributes) . "=" . $attributes[key($attributes)] . ";";
        $this->exct($sql_query);
    }


    public function insert ($table, $attributes)
    {
        $insert_field_names = [];
        $insert_field_values = [];
        foreach ($attributes as $field_name => $field_value){
            $insert_field_names[] = $field_name;
            $insert_field_values[] = $field_value;
        }


        $sql_query = " INSERT INTO " . $table . " (";
        for ($i = 0; $i < count($insert_field_names); $i++){
            $sql_query .= $insert_field_names[$i];
            if( $i != count($insert_field_names)-1){
                $sql_query .= ", ";
            }
        }
        $sql_query .= ") VALUES (";
        for ($i = 0; $i < count($insert_field_values); $i++){
            $sql_query .= "'$insert_field_values[$i]'";
            if( $i != count($insert_field_values) - 1 ){
                $sql_query .= ", ";
            }
        }
        $sql_query .= ");";
        $this->exct($sql_query);
    }

}



//$me = SQL::table("cars")
//    ->select()
//    ->where("price",">", "2000", " and ")
//    ->where("price","<", "30000")
//    ->order_by("price", "DESC")
//    ->limit(0,1)
//    ->get();




//$me = new SQL();
//$res = $me->table("cars")
//        ->select()
//        ->where("price",">", "2000", " and ")
//        ->where("price","<", "30000")
//        ->order_by("price", "DESC")
//        ->get();

//print_r($res);







//echo "\n\n\n<br><br><br>";
//DBHandler::execute_raw_sql(true, "SELECT * FROM ? WHERE ?<11000;", 'cars', 'price');
//DBHandler::execute_raw_sql(true, "SELECT * FROM ? WHERE ?<11000;", ['cars', 'price']);
//DBHandler::execute_raw_sql(true, "SELECT * FROM :cars WHERE :price<11000;", ["cars"=>'cars', "price"=>'price']);

