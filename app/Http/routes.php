<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/


Route::get('/', function () {
    return view('welcome');
});



/////////////////////API'S ////////////////////////

$api = app('Dingo\Api\Routing\Router');

//open APi route
$api->version('v1',function($api){
    //creating user token
    $api->post('authenticate','App\Http\Controllers\ApiController@authentication');
    $api->post('hello',function(){
        return "hello";
    });
});

//protected Api route
$api->version('v1',['middleware'=>'api.auth'],function($api){

	//getting list of revenue heads
	$api->get('revenue_heads','App\Http\Controllers\ApiController@revenue_heads');

	//api for verifying Invoice
	$api->post('invoice','App\Http\Controllers\ApiController@invoice');

	//api to generate invoice
	$api->post('generate_invoice','App\Http\Controllers\ApiController@generate_invoice');
});
