<?php
/**
 * Created by PhpStorm.
 * User: Stas
 * Date: 30/03/19
 * Time: 14:03
 */

require_once __DIR__ . "/../core/db/SQL.php";

class ORM
{
    protected $table;
    protected $builder;
    protected $attributes;

    /**
     * ORM constructor.
     * @param $table
     */
    public function __construct($table = "")
    {
        if ($table == "") {
            $this->table = strtolower(get_class($this)) . "s";
        } else {
            $this->table = $table;
        }
    }


    public function __call($name, $arguments)
    {
        if ($name == "insert" or $name == "update" or $name = "exct"){
            $builder = SQL::table($this->table);
            return call_user_func_array([$builder, $name], $arguments);
        }
        $builder = $this->getBuilder();
        return call_user_func_array([$builder, $name], $arguments);
    }

    public static function __callStatic($name, $arguments)
    {
        //todo i have not statis builder
        $instance = new static(); //static - child. self - parent
        $builder = $instance->newBuilder();
        return call_user_func_array([$builder, $name], $arguments);
    }


    //st->name
    public function __get($name)
    {
        return $this->attributes[$name];
    }

    //st->name
    public function __set($name, $value)
    {
        $this->attributes[$name] = $value;
    }

    /**
     * @return SQL
     */
    public function getBuilder(): SQL
    {
        return $this->builder;
    }

    public function newBuilder(): SQL
    {
        $builder = SQL::table($this->table);
        $builder->setModelClassName(get_class($this));
        return $builder;
    }


    public static function extract_from_result_array($result_row)
    {
        $obj = new static();

        //fields is written to $attributes via magic set...
        foreach ($result_row as $field_name => $field_value) {
            $obj->attributes[$field_name] = $field_value;
        }

        return $obj;
    }

    public function save()
    {
        if ($this->is_already_existing_record($this->table, key($this->attributes), $this->attributes[key($this->attributes)])) {
            //$this->newBuilder();
            $this->update($this->table, $this->attributes);
        } else {
            $this->insert($this->table, $this->attributes);
        }
    }






    /**
     * @param $table
     * @param $field_name
     * @param $field_value
     * @return bool
     */
    public function is_already_existing_record($table, $field_name, $field_value){
        $dsn = DBHandler::assemble_DSN('127.0.0.1','my_cars_db', 'utf8'); //Data_source_name
        $auth_data = DBHandler::assemble_auth_data('root', 'PASSWORD'); //login & password
        $dbh = DBHandler::getInstance($dsn, $auth_data['user'], $auth_data['pass']);

        $select_all = "SELECT * FROM $table;";
        //$res = DBHandler::execute_query($dbh, $select_all, $debug_mode_on = false);
        $res = $this->exct($select_all);

        foreach ($res as $record){
            if ($record[$field_name] == $field_value){
                return true;
                break;
            }
        }
        return false;
    }


}


spl_autoload_register(function ($class){
    $file = "$class.php";
    if (is_file($file)){
        require_once $file;
    }
});



//$cars = Car::select()
//    ->where("price",">", "2000", " and ")
//    ->where("price","<", "30000")->first();
//
//foreach ($cars as $car){
//    print_r($car);
//}


//$c = Car::find("id_car", "=", "1");
////$c[0]->id_car = "1999";
//$c[0]->price = "49999119";
//$c[0]->save();


//Car::calcAveragePrice();
//Car::showAllBrands();
//



















//    public function save($id="-1", $needInsert = true)
//    {
//        if ($needInsert == true) {
//            $this->insert();
//        } else {
//            $this->update($id);
//        }
//    }







