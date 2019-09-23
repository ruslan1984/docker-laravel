<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;

class OrderController extends Controller
{
    const orderTable='order';
    const servicesTable='services';

    public static function index(){

        $data=[
            "address_from"=>  "",
            "address_to"=>  "",
            "coordinares_from"=>  "",
            "coordinares_to"=>  "",
            "date"  =>  "",
            "name"  =>  "",
            "phone" =>  "",
            "more_services"=>  ""
        ];
        $errors=[];
        $errors["address_from"]="";
        $errors["address_to"]="";
        $errors["coordinares_from"]="";
        $errors["coordinares_to"]="";
        $errors["date"]="";
        $errors["name"]="";
        $errors["phone"]="";
        $errors["adr_from"]="";
        $errors["adr_to"]="";
        return self::showOrderPage($data,$errors);
    }
    public static function showOrderPage($data=[],$errors=[]){
        $services = DB::table(self::servicesTable)->select(['code','name'])->get();
        return  view('order')->with(['data'=>$data,'services'=>$services,'errors'=>$errors]);
    }
    public static function getList(){
        $list = DB::table(self::orderTable)->select()->get();
        return  view('orderList')->with(['list'=>$list]);
    }
    public static function order(Request $request){
        $data   =   self::setData($request);
        $errors =   self::setErrors($request);
        if(self::emprtyErrors($errors)){
            DB::table(self::orderTable)->insert($data);
            return 'Спасибо за заказ';
        }else {
            return  self::showOrderPage($data, $errors);
        }
    }

    public static function setData(Request $request){
        $date=date('Y-m-d H:i:s');
        $setDate="";
        if(!empty($request['date'])) {
            $setDate = date_create_from_format('d.m.Y H:i', $request['date']);
        }
        return [
            "created_at"    =>  $date,
            "updated_at"    =>  $date,
            "address_from"  =>  $request['address_from'],
            "address_to"    =>  $request['address_to'],
            "coordinares_from"  =>  $request['coordinares_from'],
            "coordinares_to"    =>  $request['coordinares_to'],
            "date"  =>  $setDate,
            "name"  =>  $request['name'],
            "phone" =>  $request['phone'],
            "more_services" =>  json_encode($request['more_services'])
        ];
    }
    public static function setErrors(Request $request) {
        $errors=[];

        $errors["address_from"]="";
        $errors["address_to"]="";
        $errors["coordinares_from"]="";
        $errors["coordinares_to"]="";
        $errors["date"]="";
        $errors["name"]="";
        $errors["phone"]="";
        $errors["adr_form"]="";
        $errors["adr_to"]="";

        if(empty($request["address_from"])){
            $errors["address_from"]="Не указан адрес";
        }
        if(empty($request[ "address_to"])){
            $errors["address_to"]="Не указан адрес";
        }
        if(empty($request["coordinares_from"])){
            $errors["coordinares_from"]="Адрес указан не полностью";
        }
        if(empty($request["coordinares_to"])){
            $errors["coordinares_to"]="Адрес указан не полностью";
        }
        if(empty($request["date"])){
            $errors["date"]="Дата не указана";
        }
        if(empty($request["name"])){
            $errors["name"]="Имя не указано";
        }
        if(empty($request["phone"])){
            $errors["phone"]="Не указан телефон";
        }else{

        }

        if(!empty($errors['address_from'])){
            $errors["adr_from"]=$errors['address_from'];
        }elseif(!empty($errors['coordinares_from'])){
            $errors["adr_from"]=$errors['coordinares_from'];
        }else{
            $errors["adr_from"]="";
        }

        if(!empty($errors['address_to'])){
            $errors["adr_to"]=$errors['address_to'];
        }elseif(!empty($errors['coordinares_to'])){
            $errors["adr_to"]=$errors['coordinares_to'];
        }else{
            $errors["adr_to"]="";
        }
        return $errors;
    }
    public static function emprtyErrors($error){
        foreach ($error as $item){
            if(!empty($item)) return false;
        }
        return true;
    }
    public static function submit(Request $request){
        if($request['submit']){
            return self::order($request);
        }
    }
}
