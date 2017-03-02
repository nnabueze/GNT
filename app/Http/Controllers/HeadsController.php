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
    	$igr = igr::with("mdas")->find(Auth::user()->igr_id);
    	$heads = array();
    	return view("heads.index",compact("sidebar",'igr'));
    }

    ///////////////////////////////////////////////////////////////////

    //getting head of a specific MDA
    public function heads(Request $request)
    {
    	//getting the parameter
    	$item = $request->only("station");

    	//getting all the existing MDA
    	$sidebar = "heads";
    	$igr = igr::with("mdas")->find(Auth::user()->igr_id);

    	//select station base on MDA
    	$heads = Revenuehead::where("mda_id",$item)->with("subheads")->get();
    	if (count($heads) > 0) {
    		
    		return view("heads.subhead",compact("heads","sidebar","igr"));
    	}

    	Session::flash("warning","Failed! No Revenue Head added.");
    	return Redirect::to("/revenue_heads");
    }

    /////////////////////////////////////////////////////////////////////////////

    //getting revenue heads for Lga and Mda
    public function heads_revenue()
    {
        $sidebar = "heads_revenue";
        $igr = igr::with("mdas")->find(Auth::user()->igr_id);
        $heads = array();
        return view("heads.heads_revenue",compact("sidebar",'igr'));
    }

    ////////////////////////////////////////////////////////////////////////////////

    //getting a specific head range
    public function heads_revenue_range(Request $request)
    {
        //getting the parameter
        $item = $request->only("station");

        //getting all the existing MDA
        $sidebar = "heads";
        $igr = igr::with("mdas")->find(Auth::user()->igr_id);

        //select station base on MDA
        $heads = Revenuehead::where("mda_id",$item)->with("subheads")->get();
        if (count($heads) > 0) {
            
            return view("heads.heads_revenue_range",compact("heads","sidebar","igr"));
        }

        Session::flash("warning","Failed! No Revenue Head added.");
        return Redirect::to("/revenue_heads");
    }
}
