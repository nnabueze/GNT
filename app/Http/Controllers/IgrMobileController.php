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

class IgrMobileController extends Controller
{
    /////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////API LOGIN
    //Login User
    public function authentication(Request $request)
    {
    	$credentials = $request->only('email', 'password');

    	try{
    		if (! Auth::attempt(['email' => $request->input("email"), 'password' => $request->input("password")])) {
    			return $this->response->errorUnauthorized();
    		}

    	} catch (JWTException $e) {
    		return $this->response->errorInternal();
    	}
    	
        return $this->response->array(compact('token'))->setStatusCode(200);
    }

/*        public function authentication(Request $request)
    {

            if (! Auth::attempt(['email' => $request->input("email"), 'password' => $request->input("password")])) {
                return $this->response->errorUnauthorized();
            }

        return $request->input("password");
        //return $this->response->array(compact('token'))->setStatusCode(200);
    }*/
}
