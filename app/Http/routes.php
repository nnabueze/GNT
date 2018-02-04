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


//GNT
Route::get('/','CentralPayController@index');

Route::get('pay','CentralPayController@pay');
Route::get('pay/cancel','CentralPayController@cancel');
Route::post('pay/response','CentralPayController@response');
Route::get('pay/success','CentralPayController@success');
Route::post('pay/notification','NotificationController@centralpay');
Route::get('pay/test','CentralpayController@test');











/////////////////////API'S ////////////////////////

$api = app('Dingo\Api\Routing\Router');

//open APi route
$api->version('v1',function($api){
    //creating user token
    $api->post('authenticate','App\Http\Controllers\ApiController@authentication');


});

//protected Api route
$api->version('v1',['middleware'=>'api.auth'],function($api){

Route::post('/mcash/notification','NotificationController@mcash');
Route::post('/ussd/notification','NotificationController@ussd');
Route::post('/ussd/validation','ValidatiionController@index');

});
