<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\User;

class ValidatiionController extends Controller
{
    //validating customerid in ussd
    public function index(Request $request)
    {
    	//test validation
    	$customer = User::where('email', $request->email)->first();

    	if ($customer == null) {
    		return Response(['error' => 'No record found'], 400);
    	}

    	return Response($customer, 200);
    }
}
