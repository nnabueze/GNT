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

class Api2Controller extends Controller
{
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

	//////////////////////////////////////////////////////////////////////////////////////////

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

}
