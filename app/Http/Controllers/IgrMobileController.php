<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

class IgrMobileController extends Controller
{
    /////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////API LOGIN
    //Login User
    public function authentication(Request $request)
    {
    	$credentials = $request->only('email', 'password');
        return $this->response->array(compact('yes'))->setStatusCode(200);

    	try{
    		if (! Auth::attempt(['email' => $request->input("email"), 'password' => $request->input("password")])) {
    			return $this->response->errorUnauthorized();
    		}

    	} catch (JWTException $e) {
    		return $this->response->errorInternal();
    	}

    	return $this->response->array(compact('token'))->setStatusCode(200);
    }
}
