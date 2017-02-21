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

////////////////////////////////////////////////Admin Route
Route::get('/',"adminController@index");

//route to display dashboard
Route::get("/dashboard","adminController@dashboard");

//login into the system
Route::post("/admin","adminController@store");

//logout route
Route::get("/logout","adminController@logout");

//All revenue collection route
Route::get("/all_collection","CollectionController@index");

//route for dispalying permission page
Route::get("/permission","PermissionController@index");

//storing permission
Route::post("/permission","PermissionController@store");

//deleteeing permission
Route::get("/permission/{any}","PermissionController@permission_delete");

//route display and create role
Route::get("/role","RoleController@index");

//Route storing role
Route::post("/role","RoleController@store");

//deleting role
Route::get("/role/{any}","RoleController@role_delete");




/////////////////////API'S ////////////////////////

$api = app('Dingo\Api\Routing\Router');

//open APi route
$api->version('v1',function($api){
    //creating user token
    $api->post('authenticate','App\Http\Controllers\ApiController@authentication');
});

//protected Api route
$api->version('v1',['middleware'=>'api.auth'],function($api){

	//getting list of revenue heads
	$api->get('revenue_heads','App\Http\Controllers\ApiController@revenue_heads');

	//api for verifying Invoice
	$api->post('invoice','App\Http\Controllers\ApiController@invoice');

	//api to generate invoice
	$api->post('generate_invoice','App\Http\Controllers\ApiController@generate_invoice');

	//Pos collection api
	$api->post('pos_collection','App\Http\Controllers\ApiController@pos_collection');

	//generating remittance
	$api->post('generate_remittance','App\Http\Controllers\ApiController@generate_remittance');

	//login user
	$api->post('user_login','App\Http\Controllers\ApiController@user_login');


});
