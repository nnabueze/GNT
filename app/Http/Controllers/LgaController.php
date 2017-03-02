<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Auth;
use Session;
use Redirect;
use App\Igr;
use App\Mda;
use App\Revenuehead;
use App\Http\Requests;
use App\Http\Controllers\Controller;

class LgaController extends Controller
{
    //protecting route
    public function __construct()
    {

    	$this->middleware('auth');

    }

    ///////////////////////////////////////////////////////////////////////////////////////

    //Displaying the page
    public function index()
    {
       $sidebar = "lga";
       $igr = Igr::with('mdas')->find(Auth::user()->igr_id);
       
    	return view("lga.index",compact("sidebar","igr"));
    }

    ////////////////////////////////////////////////////////////////////////////////////////////
    //adding agency on the platform
    public function store(Request $request)
    {
       //validate check if the request parameter is posted
       $this->validate($request, [
           'mda_name' => 'required|min:3',
           'mda_category' => 'required',
       ]);

       //generating MDA code
       $request['mda_key'] ="M" .$this->random_number(10);
       $request['igr_id'] =$request->input("igr");

       //insert the record into db
       if ($mda = Mda::create($request->all())) {

          //return the right response
          Session::flash("message","Successful! LGA Registered");

          return Redirect::back();
       }

       //return failed response
       Session::flash("warning","Failed! Unable to register LGA");
       return Redirect::back();
    }

    ////////////////////////////////////////////////////////////////////////////////////////////////

    //deleting lga
    public function delete_lga($id)
    {
       //checking user right
       if ( ! Auth::user()->hasRole('Superadmin')) {

          Session::flash("warning","You don't have the right to delete MDA");
          return Redirect::back();
       }

       //deleting the mda
       if ($mda = Mda::where("mda_key",$id)->first()) {
          $mda->delete();

          Session::flash("message","Successful! Mda deleted");
          return Redirect::back();
       }

       Session::flash("warning","Failed! Mda not deleted");
       return Redirect::back();

    }

    //////////////////////////////////////////////////////////////////////////////////////////////////

    //generating random digit number
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
    /////////////////////////////////////////////////////////////////////////////////
}


