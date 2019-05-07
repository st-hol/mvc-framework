<?php
/**
 * Created by PhpStorm.
 * User: Stas
 * Date: 15.03.2019
 * Time: 22:39
 */

class DBHandler
{

    protected static $dbh;

    private function __construct() {}
    private function __clone() {}
    private function __wakeup() {}


    public static function getInstance($dsn, $user, $pass)
    {
        if (self::$dbh === null) {
           //self::$dbh = new self();
            self::$dbh = self::initPDO($dsn, $user, $pass);
        }
        return self::$dbh;
    }

    /**
     * @param $dsn - String: data source name.
     * @param $user
     * @param $pass
     * @return PDO
     */
    private static function initPDO($dsn, $user, $pass) {
        try {
            $opt = [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_EMULATE_PREPARES => false,
            ];
            $dbh = new PDO($dsn, $user, $pass, $opt);

        } catch (PDOException $e) {
            die('Это фиаско, братан: ' . $e->getMessage());
        }

        return $dbh;
    }


    /**
     * Concatenates DSN - data source name.
     * @param $host
     * @param $db
     * @param $charset
     * @return string
     */
    public static function assemble_DSN($host, $db, $charset)
    {
        $dsn = "mysql:host=$host;dbname=$db;charset=$charset";
        return $dsn;
    }

    /**
     * @param $user
     * @param $pass
     * @return array
     */
    public static function assemble_auth_data($user, $pass)
    {
        return ['user' => $user, 'pass' => $pass];
    }

    /**
     * @param $dbh
     * @param $sql_query - string.
     * @param $debug_mode_on - boolean.
     * @return mixed $res - result of SQL fetching.
     */
    public static function execute_query($dbh, $sql_query, $debug_mode_on = false)
    {
        //echo "<br>\nYour SQL:\n    " . $sql_query;

        $stmt = $dbh->prepare($sql_query);
        $stmt->execute();
        $rows = $stmt->fetchAll();

        if ($debug_mode_on) {
            $d_rows = $rows;
            echo "<br>\nYour SQL:\n    " . $sql_query;
            echo "<br><br>\n\nYour result:";
            self::show_all($d_rows);
        }
        return $rows;
    }

    /**
     * @param $rows
     */
    public  static function  show_all($rows) {
        echo "\n<br>";
        foreach ($rows as $row){
            foreach ($row as $item){
                echo $item . "\t\t";
            }
            echo "\n<br>";
        }
    }


    #####################################################################
    /**
     * Лаб5: надбудова над pdo (абстрагувалися від pdo).
     * В якості третього(невизначеного заздалегідь) параметра
     * може приймати:
     *                  1)послідновність $val1, $val2, ..., $valN
     *                  2)simple array[]
     *                  3)associate array[key=>value]
     *
     * @param $debug_mode_on - boolean.
     * @param $sql_query - string.
     * @return mixed $res - result of SQL fetching.
     */
    public function execute_raw_sql($debug_mode_on, $sql_query) {
        $ARGS_OFFSET = 2;
        if ( ! is_array(func_get_arg($ARGS_OFFSET))){
            $args_array = func_get_args();
            $args_array =  array_slice($args_array, $ARGS_OFFSET);
            self::substitute_placeholders($args_array, $sql_query);
        } else {
            $args_array = func_get_arg($ARGS_OFFSET);
            //print_r($args_array);
            self::substitute_placeholders($args_array, $sql_query);
        }
        echo $sql_query;

        $stmt = self::$dbh->prepare($sql_query);
        $stmt->execute();
        $rows = $stmt->fetchAll();

        //in case debugging flag is on
        if ($debug_mode_on === true) {
            $d_rows = $rows;
            echo "\nYour SQL:\n " . $sql_query;
            echo "\n\nYour result:";
            self::show_all($d_rows);
        }

        return $rows;
    }

    /**
     * Вставляє параметри масива в сроку запиту SQL.
     * Параметр $sql_query передається за посиланням.
     *
     * @param $args_array
     * @param $sql_query
     */
    private function substitute_placeholders($args_array, &$sql_query) {
        if (self::isAssoc($args_array)){
            foreach($args_array as $key => $value)
            {
                $sql_query = str_replace(':'.$key, $value, $sql_query);
            }
        } else {
            foreach($args_array as $item)
            {
                echo $sql_query;
                $sql_query = self::str_replace_once("?", $item, $sql_query);
                //$sql_query = str_replace("?", $item, $sql_query);
            }
        }
    }

    /**
     * Перевірка масиву на асоціативність.
     * Взято з: http://qaru.site/questions/13047/how-to-check-if-php-array-is-associative-or-sequential
     *
     * @param array $arr
     * @return bool
     */
    private function isAssoc(array $arr)
    {
        if (array() === $arr) return false;
        return array_keys($arr) !== range(0, count($arr) - 1);
    }


    /**
     * Идея такая: сначала мы ищем первое вхождение нашей строчку,
     * и если находим его — то вырезаем и заменяем необходимой строкой.
     *
     * src: https://web.izjum.com/php-str_replace_once
     *
     * @param $search
     * @param $replace
     * @param $text
     * @return mixed
     */
    function str_replace_once($search, $replace, $text)
    {
        $pos = strpos($text, $search);
        return $pos !== false ? substr_replace($text, $replace, $pos, strlen($search)) : $text;
    }
}










//    private function show_all($stmt)
//    {
//        echo "\n<br>";
//        while ($row = $stmt->fetch()) {
//            //var_dump($row);
//            foreach ($row as $item) {
//                echo $item . "\t\t";
//            }
//            echo "\n<br>";
//        }
//    }









//            array_shift($args_array);
//            array_shift($args_array);