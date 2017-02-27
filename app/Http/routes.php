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
Route::get('/',"AdminController@index");

//route to display dashboard
Route::get("/dashboard","AdminController@dashboard");
Route::get("/admin","AdminController@admin");

//login into the system
Route::post("/admin","AdminController@store");

//logout route
Route::get("/logout","AdminController@logout");

//All revenue collection route
Route::get("/all_collection","CollectionController@index");

//getting collections
Route::post("/all_collection","CollectionController@all_collection");

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

//Route to create admin user
Route::get("/users","UserController@index");

//delete a user 
Route::get("/users/{any}","UserController@delete_user");

//creating user
Route::post("/users","UserController@store");

//pos collection page
Route::get("/pos_collection","CollectionController@pos_collection");

//Ebills collection page
Route::get("/ebill_collection","CollectionController@ebill_collection");

//revenue heads
Route::get("/revenue_heads1","CollectionController@revenue_heads");

//Route onboarding the IGR
Route::get("/agencies","AgencyController@index");

//Route for adding agency on the platform
Route::post("/agencies","AgencyController@store");

//Route to delete agencies
Route::get("/agencies/{any}","AgencyController@delete_agency");

//Route to display LGA Page
Route::get("/lga","LgaController@index");

//Route storing lga
Route::post("/lga","LgaController@store");

//Route deleting lgas
Route::get("/lga/{any}","LgaController@delete_lga");

//adding revenue heads
Route::get("/revenue_heads","AgencyController@revenue_heads");

//route for adding up station
Route::get("/station","AgencyController@station");

//Post action status
Route::get("/pos","AgencyController@pos");

//Route viewing list of revenue heads under an mda
Route::get("/agency/{any}","AgencyController@view_head");

//Route displaying station
Route::get("/station","StationController@index");

//Route for adding station 
Route::post("/station","StationController@store");

//selecting station of a specific MDA
Route::get("/mda_station","StationController@mda_station");






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
	$api->post('revenue_heads','App\Http\Controllers\ApiController@revenue_heads');

	//api for verifying Invoice
	$api->post('invoice','App\Http\Controllers\ApiInvoiceController@invoice');

	//api to generate invoice
	$api->post('generate_invoice','App\Http\Controllers\ApiGenerateInvoiceController@generate_invoice');

	//Pos collection api
	$api->post('pos_collection','App\Http\Controllers\ApiPosCollectionController@pos_collection');

	//generating remittance
	$api->post('generate_remittance','App\Http\Controllers\ApiGenerateRemittance@generate_remittance');

	//login user
	$api->post('user_login','App\Http\Controllers\ApiController@user_login');

	//activation of pos
	$api->post('pos_activation','App\Http\Controllers\ApiController@pos_activation');

	//verification of tin
	$api->post("tin_verification","App\Http\Controllers\ApiTinController@verify_tin");

	//creating temporary tin
	$api->post("temporary_tin","App\Http\Controllers\ApiTinController@temporary_tin");

	//clearing remittance
	$api->post("clear_remittance","App\Http\Controllers\ApiGenerateRemittance@clear_remittance");


});
