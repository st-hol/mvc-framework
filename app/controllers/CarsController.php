<?php
/**
 * Created by PhpStorm.
 * User: Stas
 * Date: 31/03/19
 * Time: 14:53
 */


require_once __DIR__ . "/../../core/db/SQL.php";
require_once __DIR__ . "/../../core/utility/util_functions.php";

require_once  __DIR__ . "/../Car.php";
require_once  __DIR__ . "/../Brand.php";

class CarsController extends Controller
{
    public function showAveragePrice(){
        echo "<br>\nAverage  price is: ";
        echo Car::calcAveragePrice();
    }


    public function showAllBrands(){
        $brands = Car::getAllBrands();

        echo "\n All brands are: \n<br>" ;
        foreach ($brands as $brand){
            echo "NAME: " . $brand->name_brand . "\n<br>";
            echo "DESCRIPTION: " . $brand->description . "\n<br>";
            echo "-------------------------------------\n<br>";
        }
    }


    public function showSingleCar($id) {
        debug(Car::getCarById($id));
    }

    public function incrementPrices() {
        Car::incrementPrices();
        echo "<br>\nPrice of each car was successfully incremented";
    }
}



//$c = new CarsController();
//$c->showAllBrands();
//$c->showAveragePrice();
//$c->showSingleCar(1);
//





//public function showCars(){
//    $me = new SQL();
//    $res = $me->table("cars")
//        ->select()
//        ->where("price",">", "2000", " and ")
//        ->where("price","<", "30000")
//        ->order_by("price", "DESC")
//        ->get();
//
//    debug($res);
//}




//$s = new CarsController();
//$s->showCars();