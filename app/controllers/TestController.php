<?php
/**
 * Created by PhpStorm.
 * User: Stas
 * Date: 30/03/19
 * Time: 12:27
 */

require_once "Controller.php";

class TestController extends Controller
{
    public function test(){
        $html = $this->templator->output('test', [
            'some' => 'some-data'
        ]);

        $this->templator->showPage($html);


//        $content = output('test', [
//            'some' => 'some-data'
//        ]);
//        return output('html', [
//            'content' => $content
//        ]);
    }

    public function some() {

        echo $this->templator->output("some",
            ['title' => 'Моя страница',
                'uname' => 'admin',
                'rainbow' =>
                    ['Каждый — красный',
                        'Охотник — оранжевый',
                        'Желает — жёлтый',
                        'Знать — зелёный',
                        'Где — голубой',
                        'Сидит — синий',
                        'Фазан — фиолетовый'],
                'img_url' => "img/rainbow.png"]);

       // $this->templator->showPage($html);
    }

    public function someParametrized(){
        $html = $this->templator->output('test', [
            'some' => 'some-PARAMETRIZED-data'
        ]);
        $this->templator->showPage($html);
    }

    //param...
    public function param($value){
        echo "<script>alert('public function param(value) with ONE parameter')</script>";
        echo "<br><br>>value: ";
        foreach ($value as $v){
            echo $v;
        }
    }

    //param...
    public function param2($values){
        echo "<script>alert('public function param2(values) with TWO parameters')</script>";
        echo "<br><br>>>>values: ";
        $vals = func_get_args();
        foreach ($vals as $v){
            echo "<br>".$v;
        }
    }

}