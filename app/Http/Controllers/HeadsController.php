<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Auth;
use Session;
use Redirect;
use App\Igr;
use App\Mda;
use App\Station;
use App\Revenuehead;
use App\Http\Requests;
use App\Http\Controllers\Controller;

class HeadsController extends Controller
{
    //protecting route
    public function __construct()
    {

    	$this->middleware('auth');

    }

    //////////////////////////////////////////////////////////////

    //displaying index page
    public function index()
    {
    	$sidebar = "heads";
    	$mda1 = Mda::all();
    	$heads = array();

    	return view("heads.index",compact("sidebar",'mda1','heads'));
    }

    ///////////////////////////////////////////////////////////////////

    //getting head of a specific MDA
    public function heads(Request $request)
    {
    	//getting the parameter
    	$item = $request->only("station");

    	//getting all the existing MDA
    	$sidebar = "heads";
    	$mda = Mda::all();
    	$mda1 = Mda::all();

    	//select station base on MDA
    	$heads = Revenuehead::where("mda_id",$item)->with("subheads")->get();
    	if (count($heads) > 0) {
    		
    		return view("heads.subhead",compact("heads","sidebar","mda","mda1"));
    	}

    	Session::flash("warning","Failed! No Revenue Head added.");
    	return Redirect::to("/revenue_heads");
    }
}
