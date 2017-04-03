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
use App\Tin;
use App\Registration;
use App\Subhead;
use App\Remittance;
use App\Collection;
use Carbon\Carbon;
use App\Http\Requests;
use App\Http\Controllers\Controller;

class ApiRegistrationController extends Controller
{
	use Helpers;

    //Registration of via mobile app
    public function details(Request $request)
    {
    	//Token authentication
    	$this->token_auth();

    	//check if the parameters are ok
    	if ($request->has("name") && $request->has("bank_name") && $request->has("account_number")
    		&& $request->has("account_name") && $request->has("phone_no")) {

    		if ($register = Registration::create($request->all())) {
    			
    			$message = "Successfully Created";
    			return $this->response->array(compact('message'))->setStatusCode(200);
    		}

    		
    	}

    $message = "Parameter Missing";
    return $this->response->array(compact('message'))->setStatusCode(401);

    }

    /////////////////////////////////////////////////////////////////////////////////////////////////////

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
}
