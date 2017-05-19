<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Auth;
use JWTAuth;
use Validator;
use Image;
use Tymon\JWTAuth\Exceptions\JWTException;
use Dingo\Api\Routing\Helpers;
use App\User;
use App\Invoice;
use App\Revenuehead;
use App\Mda;
use App\Worker;
use App\Postable;
use App\Subhead;
use App\Remittance;
use App\Collection;
use Carbon\Carbon;
use App\Http\Requests;
use App\Http\Controllers\Controller;

class ApiController extends Controller
{
	use Helpers;

/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////API LOGIN
    //creating user token
    public function authentication(Request $request)
    {
    	$credentials = $request->only('email', 'password');

    	try{
    		if (! $token = JWTAuth::attempt($credentials)) {
    			return $this->response->errorUnauthorized();
    		}

    	} catch (JWTException $e) {
    		return $this->response->errorInternal();
    	}

    	return $this->response->array(compact('token'))->setStatusCode(200);
    }


///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////GETTING REVENUE HEADS
    //getting the list of revenue heads
    public function revenue_heads(Request $request)
    {
        //Token authentication
        $this->token_auth();

        //check if the parameter is missing
        if ($request->has("user_key")&&$request->has("mda_key")&&$request->has("pos_key")) {

            //checking if the user exist
            if (! $user = $this->user_check($request->input("user_key"))) {

               $message = "User does not exist";
               return $this->response->array(compact('message'))->setStatusCode(400); 
            }

            //checking if pos exist
            if (! $pos = $this->pos_check($request->input("pos_key"))) {
                $message = "Pos does not exist";
                return $this->response->array(compact('message'))->setStatusCode(400); 
            }

            //checking if the pos activated
            if ($pos->activation != 1) {
                $message = "Pos is not activated";
                return $this->response->array(compact('message'))->setStatusCode(400); 
            }

            //checking if mda exist
            if (! $check_mda = $this->mda_id($request->mda_key)) {
                $message = "MDA does not exist";
                return $this->response->array(compact('message'))->setStatusCode(400); 
            }

            //checking if user is assigned to the mda
            if ($pos->mda_id != $user->mda_id) {
                $message = "User is not assigned to MDA";
                return $this->response->array(compact('message'))->setStatusCode(400);
            }

            //getting the revenue heads assign to user
            $mda_id = $this->mda_id($request->input("mda_key"));
            if ($heads = Worker::where("worker_key", $request->user_key)->first()) {
                $sub_heads = array();

                //checking if subheads is assigned to user
                if (count($heads->subheads) > 0) {
                    foreach ($heads->subheads as $subhead) {

                            $subhead_details['subhead_code'] = $subhead->subhead_code;
                            $subhead_details['subhead_name'] = $subhead->subhead_name;
                            $subhead_details['subhead_key'] = $subhead->subhead_key;
                            $subhead_details['taxiable'] = $subhead->taxiable;
                            $subhead_details['amount'] = $subhead->amount;

                        array_push($sub_heads,$subhead_details);
                    }

                    return $this->response->array(compact('sub_heads'))->setStatusCode(200);
                }

                //response no subhead assign
                $message = "No subhead assigned to user";
                return $this->response->array(compact('message'))->setStatusCode(400);
            }

            $message = "User does not exist";
            return $this->response->array(compact('message'))->setStatusCode(400);
        }

        $message = "parameter missing";
        return $this->response->array(compact('message'))->setStatusCode(400);
    }
///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////


    //login pos user api
public function user_login(Request $request)
{
    //Token authentication
    $this->token_auth();
    
    if ($request->has("phone")&&$request->has("pin")&&$request->has("pos_key")) {
        $user_login = Worker::where("phone",$request->input("phone"))
        ->where("pin",$request->input("pin"))
        ->first();

        //check if the user exist
    if (! $user_login) {
          return $this->response->errorNotFound();
    }

        //checking if user is assigned to MDA
      if ($user_login->mda_id == 0) {
        $message = "User not assigned to MDA";
        return $this->response->array(compact('message'))->setStatusCode(401);
    }

    //check if pos is not found
    $pos_check = $this->pos_check($request->input("pos_key"));
    if (empty($pos_check)) {

        return $this->response->errorNotFound();
    }

    if ($pos_check->activation != 1) {
      $message = "POS not activated";
      return $this->response->array(compact('message'))->setStatusCode(401);
    }

    //check if user is assigned to mda
    if($user_login->mda_id != $pos_check->mda_id){

        $message = "User does not belong to the MDA";
        return $this->response->array(compact('message'))->setStatusCode(401);
    }

    //check if firebase id is included
    if ($request->has("firebase_id")) {
        
        //update firbase_id
        $user_login->firebase_id = $request->input("firebase_id");
        $user_login->save();

    }


    //return user credentials
    $credential["user_id"] = $user_login->worker_key;
    $credential["name"] = $user_login->worker_name;
    $credential["phone"] = $user_login->phone;
    $credential["email"] = $user_login->email;
    $credential["pos_key"] = $pos_check->pos_key;
    $credential["user_limit"] = $pos_check->user_limit;

    $credential["mda_name"] = $user_login->mda->mda_name;
    $credential["mda_id"] = $user_login->mda->mda_key;

    $credential["pin"] = $user_login->pin;

    return $this->response->array(compact('credential'))->setStatusCode(200);
}

$message = "parameter missing";
return $this->response->array(compact('message'))->setStatusCode(400);
}

//activation of pos
public function pos_activation(Request $request)
{

    //Token authentication
    $this->token_auth();

    //get the incoming parameter
    $pos_code = $request->only("activation_code");

    //check if the parameter exist and activated
    $pos_activation = Postable::where("activation_code",$pos_code)->first();
    if ($pos_activation->activation == "1") {
     $message = "Pos Already activated";
     return $this->response->array(compact('message'))->setStatusCode(401);
 }

    //Activating the pos by updating
 $pos_activation->activation = 1;
 $pos_activation->save();

    //return pos details
 $pos_details['pos_key'] = $pos_activation->pos_key;
 $pos_details['pos_imei'] = $pos_activation->pos_imei;
 $pos_details['name'] = $pos_activation->name;
 $pos_details['activation'] = $pos_activation->activation;
 $pos_details['mda_key'] = $this->mda_key($pos_activation->mda_id);

 return $this->response->array(compact('pos_details'))->setStatusCode(200);
}



/////////////////////////////////////////////////private class

//getting mda random key
private function mda_key($id)
{
    if ($mda = Mda::where("id",$id)->first()) {
            # code...
        return $mda->mda_key;
    }
}

//getting mda increment id
private function mda_id($mda_key)
{
    if ($mda = Mda::where("mda_key",$mda_key)->first()) {
            # code...
        return $mda->id;
    }
}

    //getting user increment id
private function worker_id($worker_key)
{
    if ($worker = Worker::where("worker_key",$worker_key)->first()) {
        return $worker->id;
    }
}


    //revenue head
private function revenue_id($revenue_key)
{
    if ($revenue = Revenuehead::where("revenueheads_key",$revenue_key)->first()) {
            # code...
        return $revenue->id;
    }
}

    //subhead
private function subhead_id($subhead_key)
{
    if ($subhead = Subhead::where("subhead_key",$subhead_key)->first()) {
            # code...
        return  $subhead->id;
    }
}

//token Authentication
private function token_auth()
{
            //Token authentication
    $user = JWTAuth::parseToken()->authenticate();
    try{
        if (! $user ) {
            return $this->response->errorUnauthorized();
        } 
    } catch (\Tymon\JWTAuth\Exceptions\JWTException $e) {
        return $this->response->error('something went wrong');
    }

}

//pos check
private function pos_check($pos_key)
{
    $pos_check ="";
    if ($pos_check = Postable::where("pos_key",$pos_key)->first()) {
        return $pos_check;
    }
    return $pos_check;
}

//user check
private function user_check($user_id)
{
    $pos_check ="";
    if ($pos_check = Worker::where("worker_key",$user_id)->first()) {
        return $pos_check;
    }
    return $pos_check;
}

//////////////////////////////////////////////////////////////////////////////Private class end

}
