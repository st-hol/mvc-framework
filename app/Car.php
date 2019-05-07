<?php
/**
 * Created by PhpStorm.
 * User: Stas
 * Date: 29/04/19
 * Time: 22:37
 */

require_once "ORM.php";

class Car extends ORM
{

    public function calcAveragePrice(){
        $cars = Car::select()->get();

        $average = 0;
        foreach ($cars as $car){
            $average += $car->price;
        }

        return $average/count($cars);
//        echo "\nAverage  price is: ";
//        echo $average/count($cars) . "\n\n";
    }


    public function getAllBrands(){
        $cars = Car::select()->get();

        $brands = [];
        foreach ($cars as $car) {
            $brand_value = Brand::find("id_brand", "=", $car->id_brand)[0];
            if ( ! in_array($brand_value, $brands)) {
                $brands[] = $brand_value;
            }
        }

        return $brands;
//        echo "\n All brands are: \n" ;
//        foreach ($brands as $brand){
//            echo "NAME: " . $brand->name_brand . "\n";
//            echo "DESCRIPTION: " . $brand->description . "\n";
//            echo "-------------------------------------\n";
//        }
    }


    public function getCarById($id){
        return Car::find("id_car", "=", $id);
    }

    public function incrementPrices(){
        $cars = Car::select()->get();

        foreach ($cars as $car) {
          $car->price += 123;
          $car->save();
        }
    }

}


















/**
 * Car extract_from_result_array.
 * @param $id_car
 * @param $price
 * @param $model_id
 * @param $registry_id
 * @param $id_brand
 */
//    public static function extract_from_result_array($id_car, $price, $model_id, $registry_id, $id_brand)
//    {
//        $car = new Car();
//        //fields is written to $attributes via magic set...
//        $car->id_car = $id_car;
//        $car->price = $price;
//        $car->model_id = $model_id;
//        $car->registry_id = $registry_id;
//        $car->id_brand = $id_brand;
//
//        return $car;
//    }