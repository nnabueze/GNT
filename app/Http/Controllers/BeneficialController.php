<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Igr;
use App\Beneficial;
use Session;
use Redirect;
use Auth;
use App\Http\Requests;
use App\Http\Controllers\Controller;

class BeneficialController extends Controller
{
	//protecting route
	public function __construct()
	{

		$this->middleware('auth');

	}

    //displaying all agency acount with in an igr
    public function index()
    {
    	$sidebar = "beneficiaries";
    	$igr = Igr::find(Auth::user()->igr_id);
    	$beneficial = Beneficial::where("igr_id",Auth::user()->igr_id)->get();

    	return view("beneficial.index",compact('sidebar','igr','beneficial'));
    }

    //storing Beneficial
    public function Beneficial(Request $request)
    {
    	$this->validate($request, [
    	    'mda_id' => 'required',
    	    'account_no' => 'required|numeric',
    	    'bank_code' => 'required|numeric',
    	    'notification_no' => 'required|numeric',
    	    'bank_name' => 'required',
    	]);

    	//check if the account number exist
    	if ($account_check = Beneficial::where("mda_id",$request->input("mda_id"))->where("account_no",$request->input("account_no"))->first()) {
    		Session::flash("warning","Failed! Account number already exist");
    		return Redirect::back();
    	}

    	//generating Beneficial key
    	$request['beneficial_key'] = $this->random_number(10);

    	//stroing account number
    	if ($account = Beneficial::create($request->all())) {

    		Session::flash("message","Successful! Account created");
    		return Redirect::back();
    	}

    	Session::flash("warning","Failed! Unable to create account");
    	return Redirect::back();
    }

    private function random_number($size = 5)
    {
    	$random_number='';
    	$count=0;
    	while ($count < $size ) 
    	{
    		$random_digit = mt_rand(0, 9);
    		$random_number .= $random_digit;
    		$count++;
    	}
    	return $random_number;  
    }
}
