<?php
/**
 * Created by PhpStorm.
 * User: Stas
 * Date: 02/05/19
 * Time: 13:23
 */


require_once "Controller.php";
require_once __DIR__ . "/../middleware/AuthMiddleware.php";

class LoginController extends Controller
{

    protected $middleware = ["AuthMiddleware"];

    public function login()
    {
        session_start();

        if ($this->checkAllMiddleware()) {

            $user = $_SESSION['user'];

            echo "<p> Приветствуем вас, <strong style='color: red'> $user </strong> в вашем личном кабинете.</p>";


            $city = $_COOKIE['city'];
            echo "Ваш город: " . $city;
            echo "<br>";

            $lang = $_COOKIE['language'];
            echo "Ваш язык: " . $lang;
            echo "<br>";
        }
        else{
            echo "<br>Middleware не пропустил нас.";
        }
    }





}