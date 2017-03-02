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
use App\Collection;
use App\Worker;
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
    	 $heads = Mda::where("id",$item)->first();
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
        $item = $request->only("mda");

        //getting all the existing MDA
        $sidebar = "heads";
        $igr = igr::with("mdas")->find(Auth::user()->igr_id);

        //select station base on MDA
        $heads = Mda::where("id",$item)->first();

        if (count($heads) > 0) {
            
            return view("heads.heads_revenue_range",compact("heads","sidebar","igr"));
        }

        Session::flash("warning","Failed! No Revenue Head added.");
        return Redirect::to("/revenue_heads");
    }

    ////////////////////////////////////////////////////////////////////////////////////////

    //All collection for staff role
    public function s_all_collection()
    {
        $sidebar = "s_all_collection";
        
        if ($collection = Collection::where("mda_id",Auth::user()->mda_id)->get()) {

           return view("heads.s_all_collection",compact("collection","sidebar"));
        }

        Session::flash("warning","Failed! No record found.");
        return Redirect::back();
    }

    ///////////////////////////////////////////////////////////////////////////////////////////////

    //ebills collection for staff
    public function e_ebill_collection()
    {

        $sidebar = "e_ebill_collection";
        
        if ($collection = Collection::where("mda_id",Auth::user()->mda_id)->where("collection_type","ebills")->get()) {
   

           return view("heads.s_all_collection",compact("collection","sidebar"));
        }

        Session::flash("warning","Failed! No record found.");
        return Redirect::back();
    }

    ////////////////////////////////////////////////////////////////////////////////////////////

    //pos collection for staff role
    public function p_pos_collection()
    {

        $sidebar = "p_pos_collection";
        
        if ($collection = Collection::where("mda_id",Auth::user()->mda_id)->where("collection_type","pos")->get()) {
    

           return view("heads.s_all_collection",compact("collection","sidebar"));
        }

        Session::flash("warning","Failed! No record found.");
        return Redirect::back();
    }

    //collection range for staff
    public function s_collection(Request $request)
    {

        //getting all the request
        $start_date = $request->input("startdate");
        $end_date = $request->input("enddate");

        $sidebar = "s_all_collection";
        $collection = array();

        //getting collection within the date range
        $collection = Collection::where("mda_id",Auth::user()->mda_id)->whereDate('created_at',">=",$start_date )->whereDate('created_at',"<=",$end_date )->get();

        //select station base on MDA
        
        if (count($collection) > 0) {
                
            return view("heads.s_all_collection",compact("sidebar","collection"));
        }

            Session::flash("warning","Failed! No result found.");
            return Redirect::to("/s_all_collection");
    }


}
