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

//getting a specific collection record for an MDA
Route::post("/pos_collection","CollectionController@pos_collection_range");

//Ebills collection page
Route::get("/ebill_collection","CollectionController@ebill_collection");

//Route to select each mda specific collection
Route::post("/ebill_collection","CollectionController@ebill_collection_range");

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
//Route::get("/revenue_heads","AgencyController@revenue_heads");

//route for adding up station
/*Route::get("/station","AgencyController@station");*/

//Post action status
Route::get("/pos","AgencyController@pos");

//storing pos
Route::post("/pos","AgencyController@store_pos");

//Route viewing list of revenue heads under an mda
Route::get("/agency/{any}","AgencyController@view_head");

//Route displaying station
Route::get("/station","StationController@index");

//Route for adding station 
Route::post("/station","StationController@store");

//selecting station of a specific MDA
Route::get("/mda_station","StationController@mda_station");

//getting of pos under an MDA
Route::get("/mda_pos","StationController@mda_pos");


//deleting station
Route::get("/station/{any}","StationController@delete_station");

//Route to display agent
Route::get("/agent","AgentController@index");

//storing agent
Route::post("/agent","AgentController@agent");

//Route for getting list of suhheads under mda
Route::get("/agent_mda/{any}","AgentController@agent_mda");

//route for showing revenue heads and subheads
Route::get("/revenue_heads","HeadsController@index");

//Route for getting list of head of a specific MDA
Route::post("/revenue_heads","HeadsController@heads");

//editing subhead
Route::get("/revenue_heads/{any}/edit","HeadsController@revenue_heads_edit");

//storing the edit subhead
Route::post("/revenue_heads/store","HeadsController@revenue_heads_store");

//deleting remove heads
Route::get("/revenue_heads/{any}","HeadsController@revenue_heads_delete");

//Route for adding heads
Route::get("/add_heads","HeadsController@index");

//Route for storing head
Route::post("/add_heads","HeadsController@add_heads");

//getting the revenue heads for lag and Mda
Route::get("/heads_revenue","HeadsController@heads_revenue");

//getting a specific head
Route::post("/heads_revenue","HeadsController@heads_revenue_range");


//Route getting all collection by agency
Route::get("/agency_collection","CollectionController@agency_collection");

//Route for agency collection range
Route::post("/agency_collection","CollectionController@agency_collection_range");

//Route for LGA collection
Route::get("/lga_collection","CollectionController@lga_collection");

//route lga range collection
Route::post("/lga_collection","CollectionController@lga_collection_range");

//Route onbarding an  igr
Route::get("/igr","AdminController@igr");

//storing igr
Route::post("/igr","AdminController@igr_store");

//editing igr biller
Route::get("/igr/{any}/edit","AdminController@edit_igr");

//storing edited igr biller
Route::post("/igr/edit","AdminController@edit_igr_store");

//deleting igr
Route::get("/igr/{any}","AdminController@delete_igr");

//All collection route for staff role
Route::get("s_all_collection","HeadsController@s_all_collection");

//ebill collection for staff route
Route::get("e_ebill_collection","HeadsController@e_ebill_collection");

//route for staff ebills collection
Route::get("p_pos_collection","HeadsController@p_pos_collection");

//getting the collection base on date range for staff
Route::post("s_collection","HeadsController@s_collection");

//Route to add subhead
Route::get("/add_subhead","HeadsController@index");

//route storing subhead
Route::post("/add_subhead","HeadsController@add_subhead");

//getting a specific MDA pos users
Route::get('/pos_user',"AgentController@index");

//getting a specific Mda pos user
Route::post('/pos_user',"AgentController@pos_user");

//Deleting a pos user
Route::get("/pos_user/{any}","AgentController@delete_pos_user");

//route for changing password
Route::get("/change_password","AdminController@change_password");

//string password change
Route::post("/change_password","AdminController@change_password_store");

//Route for editing the profile
/*Route::get("/edit_profile","AdminController@edit_profile");
*/

//Route to get list of MDAs when pass igr id
Route::get("/list_mda","UserController@list_mda");

//Route to get list of revenue heads when pass mda id
Route::get("/list_heads","HeadsController@list_heads");

//displaying percentage report for gov and agency page
Route::get("/percentage","CollectionController@percentage");

//displaying percentage report
Route::post("/percentage_report","CollectionController@percentage_report");

//viewing list of remittance
Route::get("/list_remittance", "CollectionController@list_remittance");

//view remittance with date range
Route::post("/list_remittance", "CollectionController@remittance_view");










/////////////////////API'S ////////////////////////

$api = app('Dingo\Api\Routing\Router');

//open APi route
$api->version('v1',function($api){
    //creating user token
    $api->post('authenticate','App\Http\Controllers\ApiController@authentication');

    //ebillspay
    $api->post('igr_api','App\Http\Controllers\IgrEbillsApiController@index');

    //ebill notification
    $api->post('igr_api_notification','App\Http\Controllers\EbillNotificationController@index');
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

	//mobile registration
	$api->post("registration","App\Http\Controllers\ApiRegistrationController@details");


});
