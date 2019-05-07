<?php
/**
 * Created by PhpStorm.
 * User: Stas
 * Date: 30/03/19
 * Time: 12:14
 */

require_once __DIR__ . "/../../core/Route.php";

Route::add('cars/avg-price', 'CarsController@showAveragePrice');
Route::add('cars/all-brands', 'CarsController@showAllBrands');
Route::add('cars/inc', 'CarsController@incrementPrices');
Route::add('cars/{value}', 'CarsController@showSingleCar');


Route::addPost('login-with-middleware/{v}/{v}/{v}/{v}', 'LoginController@login');


Route::add('', 'MainController@index');
Route::add('test', 'TestController@test');
Route::add('some', 'TestController@some');
//Route::add('posts/add', 'PostsController@add');
//Route::add('posts', 'PostsController@index');
//Route::add('cars', 'CarsController@showCars');
//
//
//Route::addPost('post/form', 'PostMethodController@doSomething');
//
//
//
//Route::add('not','NotExistingConstroller@notExistingMethod');
//Route::add('{some}', 'TestController@someParametrized');
//Route::add('car/{value}', 'SingleCarController@showSingleCar');
//Route::add('param/{value}/{value}','TestController@param2');



















//Route::add('param/{value}', 'TestController@param');















//Route::add('', ['controller' => 'MainController', 'action'=> 'index']);
//Route::add('test', ['controller' => 'TestController', 'action'=> 'test']);
//Route::add('some', ['controller' => 'TestController', 'action'=> 'some']);
//Route::add('posts/add', ['controller' => 'PostsController', 'action'=> 'add']);
//Route::add('posts', ['controller' => 'PostsController', 'action'=> 'index']);
//Route::add('{some}', ['controller' => 'TestController', 'action'=> 'someParametrized']);
//Route::add('param/{value}', ['controller' => 'TestController', 'action'=> 'param']);
//Route::add('param/{value}/{value}', ['controller' => 'TestController', 'action'=> 'param2']);









//Route::get('test', 'TestController@test');
//Route::get('param/{value}', 'TestController@param');

//Route::add('test', ['controller' => 'TestController', 'action'=> 'test']);
//Route::add('param/{value}', ['controller' => 'TestController', 'action'=> 'param']);
//
//Route::add('some', ['controller' => 'TestController', 'action'=> 'some']);
//Route::add('{some}', ['controller' => 'TestController', 'action'=> 'someParametrized']);
//
//
//Route::add('posts/add', ['controller' => 'PostsController', 'action'=> 'add']);
//Route::add('posts', ['controller' => 'PostsController', 'action'=> 'index']);
//Route::add('', ['controller' => 'MainController', 'action'=> 'index']);

//
//Route::add('^?', ['controller' => 'MainController', 'action'=> 'index']);
//Route::add('([a-z-]+)/([a-z-]+)');



