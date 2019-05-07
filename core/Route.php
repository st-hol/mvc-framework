<?php
/**
 * Created by PhpStorm.
 * User: Stas
 * Date: 30/03/19
 * Time: 12:37
 */

require_once __DIR__ . "/../app/middleware/AuthMiddleware.php";


class Route
{
    protected static $instance = null;
    protected static $routes = [];
    protected static $routesPost = [];
    protected static $route = [];

    private function __construct()
    {
    }

    private function __clone()
    {
    }

    private function __wakeup()
    {
    }

    public static function getInstance()
    {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }


//    public static function add($regexp, $route = []){
//        self::$routes[$regexp] = $route;
//    }
    public static function add($regexp, $stringData_controllerMethod)
    {
        $data = explode('@', $stringData_controllerMethod);
        $route = [];
        $route['controller'] = $data[0];
        $route['action'] = $data[1];

        self::$routes[$regexp] = $route;
    }

    public static function addPost($regexp, $stringData_controllerMethod)
    {
        $data = explode('@', $stringData_controllerMethod);
        $route = [];
        $route['controller'] = $data[0];
        $route['action'] = $data[1];

        self::$routesPost[$regexp] = $route;
    }

    public static function dispatch($url)
    {
        $matcher_response = self::matchRoute($url);

        if ($matcher_response['is_matched']) {

            $controller = self::$route['controller'];


            if (class_exists($controller)) {
                $controllerObj = new $controller; // create controller
                $action = self::$route['action']; // access method


                if (method_exists($controllerObj, $action)) {

                    echo "<p style='color: darkblue'><br>trying to execute <strong>$action</strong>" .
                        " of <strong>$controller</strong></p>";

                    call_user_func_array([$controllerObj, $action], $matcher_response['var_param']);
                    echo "<br><p style='color: green'>executed</p>";

                } else {
                    echo "<p style='color: orangered'>method <strong>$action</strong>" .
                        " in controller <strong>$controller</strong>  not found</p>";
                }

            } else {
                echo "<p style='color: red'>controller <strong>$controller</strong> not exist</p>";
            }
            //debug(Route::getRoute());
        } else {
            require __DIR__ . "/../404.php";
            http_response_code(404);
            //echo file_get_contents(__DIR__ . "/../404.php");
        }
    }


    public static function matchRoute($url)
    {

        $url_is_equal = false;
        //$incoming_url_query_parts = explode('/', $url);

        echo "<span style='color: seagreen;'>used method: </span>" . self::getMethod() . "\n<br>";

        //1.check method
        if (self::getMethod() == "POST") {
            $incoming_url_query_parts = array_values($url);//in case $url is an array $_POST[]
            $appropriate_routes = self::$routesPost;
        } elseif (self::getMethod() == "GET") {
            $incoming_url_query_parts = explode('/', $url);
            $appropriate_routes = self::$routes;
        }
        echo "<p style='color: blueviolet'> URL PARTS: </p>";
        debug($incoming_url_query_parts);

        foreach ($appropriate_routes as $pattern => $route) {
            $noted_url_query_parts = explode('/', $pattern); //example: "posts/add" --- [posts, add]

            //2.check N
            if (count($incoming_url_query_parts) != count($noted_url_query_parts)) {
                continue; // if elements quantity are not equal --- don't check it
            }

            $var_param = []; // reset before cycle
            for ($i = 0; $i < count($noted_url_query_parts); $i++) { // for each word in query. example: [posts, add] as 1)posts...2)add
                $part_of_noted_pattern = $noted_url_query_parts[$i];
                $part_of_incoming_pattern = $incoming_url_query_parts[$i];

                $url_is_equal = false; // reset the logical counter
                //3.check if "{" is in
                if ($part_of_noted_pattern[0] == '{') {
                    $var_param[] = $incoming_url_query_parts[$i]; // remembering variable param
                    self::$route = $route;
                    $url_is_equal = true;
                    continue; // if it is {variable} param --- it always matches
                }

                $url_is_equal = false; // reset the logical counter
                if ($part_of_noted_pattern == $part_of_incoming_pattern) {
                    self::$route = $route;
                    $url_is_equal = true;
                } else {
                    break;
                    //$url_is_equal = false;
                }
            }
            if ($url_is_equal) {
                ECHO "<BR><p style='color: crimson'>THE MATCHED ROUTE IS :</p>";
                debug(self::$route);
                ECHO "<br>";
                return ['is_matched' => true, 'var_param' => $var_param]; //if matched - return true, param - array
            }
        }
        return ['is_matched' => false, 'var_param' => null]; //if not found - return false, param - null
    }


    //    public static function matchRoute($url){
    //        //echo $url;
    //        foreach (self::$routes as $pattern => $route){
    //            if ($url == $pattern){
    //                self::$route = $route;
    //                return true;
    //            }
    //        }
    //        return false;
    //    }

    /**
     * @return array
     */
    public static function getRoutes(): array
    {
        return self::$routes;
    }

    /**
     * @return array
     */
    public static function getRoute(): array
    {
        return self::$route;
    }

    /**
     * @return array
     */
    public static function getRoutesPost(): array
    {
        return self::$routesPost;
    }


    public static function getMethod()
    {
        return $_SERVER['REQUEST_METHOD'];
    }
}






















//MIDDLE
//if (method_exists($controllerObj, $action)) {
//
//    echo "<p style='color: darkblue'><br>trying to execute <strong>$action</strong>" .
//        " of <strong>$controller</strong></p>";
//
//    //middleware chain of responsibility
//    if ( ! empty($controllerObj->getMiddleware())) {
//        foreach ($controllerObj->getMiddleware() as $middleware_class_name) {
//            //extract md
//            //todo require
//            $middleware = new $middleware_class_name;
//
//
//            //echo "trying to exec midel";
//            if ($middleware->handle()) {
//                //$controllerObj->$action($matcher_response['var_param']); //call method
//                call_user_func_array([$controllerObj, $action], $matcher_response['var_param']);
//                echo "<br><p style='color: green'>executed after passing middleware</p>";
//            } else {
//                echo "<br>Can not handle this";
//            }
//        }
//    } else {
//        call_user_func_array([$controllerObj, $action], $matcher_response['var_param']);
//        echo "<br><p style='color: green'>executed</p>";
//    }
















//public static function matchRoute($url){
//    $url_is_equal = false;
//
//    $incoming_url_query_parts = explode('/', $url);

//echo "<br>--------------------------";
//debug($incoming_url_query_parts);
//echo "<br>--------------------------";


//    foreach (self::$routes as $pattern => $route){
//        $noted_url_query_parts = explode('/', $pattern); //example: "posts/add" --- [posts, add]
//        //debug($noted_url_query_parts);
//
//        //1.check N
////            $b = count($incoming_url_query_parts) != count($noted_url_query_parts);
////            echo "<br><br>boleann=" . (string)$b;
////            echo "<br>first(inc)==" . count($incoming_url_query_parts);
////            echo "<br>second(noted)==" .  count($noted_url_query_parts);
////            echo "<br>--------------------------";
//        if (count($incoming_url_query_parts) != count($noted_url_query_parts)){
//            continue; // if elements quantity are not equal --- don't check it
//        }
//
//        //echo "dbg " . $pattern . debug($noted_url_query_parts);
//        //$var_param = null; // reset before cycle
//        $var_param = [];
//        for ($i = 0; $i < count($noted_url_query_parts); $i++) { // for each word in query. example: [posts, add] as 1)posts...2)add
//            $part_of_noted_pattern = $noted_url_query_parts[$i];
//            $part_of_incoming_pattern = $incoming_url_query_parts[$i];
//            //echo "<br>$i". debug($part_of_noted_pattern) . debug($part_of_incoming_pattern);
//
//            //2.check if "{" is in
//            if ($part_of_noted_pattern[0] == '{') {
//                $var_param[] = $incoming_url_query_parts[$i]; // remembering variable param
//                self::$route = $route;
//                $url_is_equal = true;
//                //echo "<br>iter. number $i skipped";
//                continue; // if it is {variable} param --- it always matches
//            }
//
//            $url_is_equal = false; // reset the logical counter
//            if ($part_of_noted_pattern == $part_of_incoming_pattern){
//                self::$route = $route;
//                $url_is_equal = true;
//                //return true;
//            }
//        }
//        if ($url_is_equal) {
//            ECHO "<BR>THE MATCHED ROUTE IS :";
//            debug(self::$route);
//            ECHO "<br>";
//            //debug($var_param);
//            return ['is_matched' => true, 'var_param' => $var_param];
//        }
//        //return $url_is_equal;
//        //return true;
//    }
//    return ['is_matched' => false, 'var_param' => null];
//}


//    public static function handle($query){
//        //return self::getInstance()->handleRequest();
//
//        //self::$instance = self::getInstance();
//
//        $controller_and_method = explode("@", $controller_str);
//        $controller = $controller_and_method[0];
//        $method = $controller_and_method[1];
//
//        $obj = new $controller();
//
//        call_user_func_array($obj, []);
//    }


//class Route
//{
//    protected static $instance;
//    protected static $routes = [
//        'test' => 'fff.html',
//        'home' => 'index',
//        'students/1' => 'cache/some',
//    ];
//
//    protected $current_route;
//
//
//    private function __construct() {}
//    private function __clone() {}
//    private function __wakeup() {}
//
//
//    public static function getInstance()
//    {
//        if (self::$instance === null) {
//            self::$instance = new self();
//        }
//        return self::$instance;
//    }
//
//    private function getMethod(){
//        return $_SERVER['REQUEST_METHOD'];
//    }
//
//    public static function handle($url){
//        return self::getInstance()->handleRequest();
//    }
//
//    public  static  function get($uri, $controller_str){
//        self::$instance = self::getInstance();
//
//        $controller_and_method = explode("@", $controller_str);
//        $controller = $controller_and_method[0];
//        $method = $controller_and_method[1];
//
//        $obj = new $controller();
//
//        call_user_func_array($obj, []);
//    }
//
//    public function handleRequest()
//    {
//        foreach (self::$routes as $route){
//            if ( $this->current_route == $route){
//                echo "!!!!";
//            }
//        }
//
//
//
////        $success = false;
////        $response = '';
////
////        //$method = $this->getMethod();
////        //$routes = [];
////        //if ($method == 'GET') $routes = $this->routes
//    }
//}