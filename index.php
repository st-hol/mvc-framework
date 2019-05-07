<?php
/**
 * Created by PhpStorm.
 * User: Stas
 * Date: 18/03/19
 * Time: 15:22
 */


//$query = rtrim($_SERVER['QUERY_STRING'], '/');
//echo "q_s:<br>".$query;

$query = rtrim($_GET['route'] = substr($_SERVER["REQUEST_URI"], 6), '/'); // to cut "\lab8\" out
//echo "get:<br>".$query;

require_once 'core/Route.php';
require_once 'core/utility/util_functions.php';
require_once 'app/routes_web/web.php';


echo "<a href=http://localhost/lab8/login.php>Click to log in.</a>";

define("APP", __DIR__ . "/app");
spl_autoload_register(function ($class){
    $file = APP . "/controllers/$class.php";
    if (is_file($file)){
        require_once $file;
    }
});


debug(Route::getRoutes());



//if (Route::matchRoute($query)){
//    debug(Route::getRoute());
//} else{
//    echo '404';
//}

debug($_POST);

debug($_GET);


if (Route::getMethod() == "POST") {
    Route::dispatch($_POST);
}
else if (Route::getMethod() == "GET") {
    Route::dispatch($query); //run
}



?>

<!--    <br><br><br><br>-->
<!--<form action="http://localhost/lab8/" method="post">-->
<!--    <label>-->
<!--<input type="text" name="login">-->
<!--    </label>-->
<!--    <br>-->
<!--    <label>-->
<!--<input type="text" name="password">-->
<!--    </label>-->
<!--    <br>-->
<!--    <input type="submit" value="submit">-->
<!--</form>-->













<?php


//require_once 'app/controllers/MainControllerController.php';
//require_once 'app/controllers/PostsControllerController.php';

//Route::handle($query); //run



//echo  "<br>" . $_SERVER['REQUEST_URI'];
//echo  "<br>" . $_GET['route'];
//
//if ( ! isset($_GET['route'])){
//    $_GET['route'] = substr($_SERVER["REQUEST_URI"], 4); // start from "4" to cut "\lab7" out
//}
//// else {
////    $_GET['route'] = "index";
////}
//
//require_once  'app/routes_web/web.php';
//require_once __DIR__."/core/Route.php";
//
//Route::handle($_GET['route']); //run





//
//if ( ! Route::success) {
//    http_response_code(404);
//}

//
//
//include "core/TemplateEngine.php";
//$templator = new TemplateEngine();
//$html = $templator->output("some",
//    ['title' => 'Моя страница',
//        'uname' => 'admin',
//        'rainbow' =>
//            ['Каждый — красный',
//                'Охотник — оранжевый',
//                'Желает — жёлтый',
//                'Знать — зелёный',
//                'Где — голубой',
//                'Сидит — синий',
//                'Фазан — фиолетовый'],
//        'img_url' => "img/rainbow.png"]);
//
//$templator->showPage($html);


//
//
//if ( ! isset($_GET['route'])){
//    $_GET['route'] = substr($_SERVER["REQUEST_URI"], 1);
//}
//
//
//include __DIR__ . '/app/config/init.php';
//require __DIR__ . 'core/core.php';
//require  __DIR__ . 'app/routes_web/web.php';
//
//
//global $app;
//$app = getApp();
//
//
//Route::handle();//run
//
//if ( ! Route::success){
//    http_response_code(404);
//}






















//
//// URL адрес сайта
//$url = 'http' . ((isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] != 'off') ? 's' : '') . '://';
//$url = $url . $_SERVER['SERVER_NAME'] . $_SERVER['REQUEST_URI'];
////define('URL', $url);
//echo  "<br>33".  $url;
