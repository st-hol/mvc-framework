<?php
/**
 * Created by PhpStorm.
 * User: Stas
 * Date: 30/03/19
 * Time: 12:14
 */

require_once __DIR__ . "/../../core/TemplateEngine.php";



class Controller
{
    protected $templator;
    protected $middleware = [];

    /**
     * Controller constructor.
     * @param $templator
     */
    public function __construct()
    {
        $this->templator = new TemplateEngine();
    }

    /**
     * @return array
     */
    public function getMiddleware(): array
    {
        return $this->middleware;
    }


    public function checkAllMiddleware(){
        $can_continue = false;
        if (!empty($this->middleware)) {
            foreach ($this->middleware as $middleware_class_name) {
                $mdw = new $middleware_class_name;
                echo "<br><i>trying to process middleware</i>";
                if ($mdw->handle()){
                    $can_continue = true;
                } else {
                    $can_continue = false;
                    break;
                }
            }
        }
        return $can_continue;
    }

    public function callAction($method, $parameters=[]){
        return call_user_func_array(array($this, $method), $parameters);
    }

    public  function __call($method, $parameters=[])
    {
        print ("<br>Controller: Method [{$method}] does not exist");
    }
}