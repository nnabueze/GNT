<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Redirect;
use Auth;
use Session;
use App\Mda;
use App\Igr;
use App\Collection;
use App\Revenuehead;
use App\Http\Requests;
use App\Http\Controllers\Controller;

class CollectionController extends Controller
{
    //protecting theroute
    public function __construct()
    {

    	$this->middleware('auth');

    }

    /////////////////////////////////////////////////////////////////////////////////////////////////////

    //displaying allcollection from differentchannels
    public function index()
    {
        $sidebar = "all_collection";
        $igr = Igr::with("mdas")->find(Auth::user()->igr_id);
        $collection = array();
    	return view("collection.index",compact("igr","sidebar","collection"));
    }

    ///////////////////////////////////////////////////////////////////////////////////////////////////////////

    //show all the collection
    public function all_collection(Request $request)
    {

        //getting list of mdas
        $igr = Igr::with("mdas")->find(Auth::user()->igr_id);
        //print_r($igr); die;

        //getting all the request
        $mda_id = $request->input("mda");
        $start_date = $request->input("startdate");
        $end_date = $request->input("enddate");

        $sidebar = "all_collection";
        $collection = array();

        //getting collection within the date range
        $collections = Collection::where("mda_id",$mda_id)->whereDate('created_at',">=",$start_date )->whereDate('created_at',"<=",$end_date )->get();
        
        //getting the name of the search MDA
        $mda = Mda::find($mda_id);
        $mda_name = $mda->mda_name;

        //select station base on MDA

        if (count($collections) > 0) {
                
            return view("collection.all",compact("igr","sidebar","collections","mda_name"));
        }

            Session::flash("warning","Failed! No result found.");
            return Redirect::to("/all_collection");
    }

    /////////////////////////////////////////////////////////////////////////////////////////////////////////////

    //ebills
    public function ebill_collection()
    {
        $mda = Mda::where("igr_id",Auth::user()->igr_id)->get();
        $sidebar = "ebill_collection";
        $collection = array();
        return view("collection.ebills_collection",compact("mda",'sidebar',"collection"));
    }

    /////////////////////////////////////////////////////////////////////////////////////////////////////////////

    //get a specific mda collection
    public function ebill_collection_range(Request $request)
    {
        //getting list of mdas
        $mda = Mda::where("igr_id",Auth::user()->igr_id)->get();

        //getting all the request
        $mda_id = $request->input("mda");
        $start_date = $request->input("startdate");
        $end_date = $request->input("enddate");

        $sidebar = "ebill_collection";

        //getting collection within the date range
        $collections = Collection::where("collection_type","ebill")->where("mda_id",$mda_id)->whereDate('created_at',">=",$start_date )->whereDate('created_at',"<=",$end_date )->get();

        //select station base on MDA
        
        if (count($collections) > 0) {
                
            return view("collection.ebill_range",compact("mda","sidebar","collections"));
        }

            Session::flash("warning","Failed! No result found.");
            return Redirect::to("/ebill_collection");
    }

    /////////////////////////////////////////////////////////////////////////////////////////////////////////////

    //displaying revenue heads
    public function revenue_heads()
    {
        $revenue = Revenuehead::all();
        return view("collection.revenue_heads",compact('revenue'));
    }

    ///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

    //getting all agency collection
    public function agency_collection()
    {

        $mda = Mda::where("mda_category","state")->where("igr_id",Auth::user()->igr_id)->get();
        $sidebar = "agency";
        $collection = array();

        return view("collection.agency",compact("mda","sidebar","collection"));
    }

    //////////////////////////////////////////////////////////////////////////////////////////////////////////////
    //getting a specific collection range
    public function agency_collection_range(Request $request)
    {

        //getting list of mdas
        $mda = Mda::where("mda_category","state")->where("igr_id",Auth::user()->igr_id)->get();

        //getting all the request
        $mda_id = $request->input("mda");
        $start_date = $request->input("startdate");
        $end_date = $request->input("enddate");

        $sidebar = "agency";

        //getting collection within the date range
        $collections = Collection::where("mda_id",$mda_id)->whereDate('created_at',">=",$start_date )->whereDate('created_at',"<=",$end_date )->get();

        //getting the name of the search MDA
        $mda = Mda::find($mda_id);
        $mda_name = $mda->mda_name;

        //select station base on MDA
        
        if (count($collections) > 0) {
                
            return view("collection.angency_range",compact("igr","sidebar","collections","mda_name"));
        }

            Session::flash("warning","Failed! No result found.");
            return Redirect::to("/agency_collection");
    }

    //////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    //lga collection 
    public function lga_collection()
    {
       $mda = Mda::where("mda_category","lga")->where("igr_id",Auth::user()->igr_id)->get();
       $sidebar = "lga_collection";
       $collection = array();

       return view("collection.lga",compact("mda","sidebar","collection")); 
    }

    ////////////////////////////////////////////////////////////////////////////////////////////////////////
    //lga collection
    public function lga_collection_range(Request $request)
    {
        //getting list of mdas
        $mda = Mda::where("mda_category","lga")->where("igr_id",Auth::user()->igr_id)->get();

        //getting all the request
        $mda_id = $request->input("mda");
        $start_date = $request->input("startdate");
        $end_date = $request->input("enddate");

        $sidebar = "lga_collection";

        //getting collection within the date range
        $collections = Collection::where("mda_id",$mda_id)->whereDate('created_at',">=",$start_date )->whereDate('created_at',"<=",$end_date )->get();

        //select station base on MDA
        
        if (count($collections) > 0) {
                
            return view("collection.lga_range",compact("mda","sidebar","collections"));
        }

            Session::flash("warning","Failed! No result found.");
            return Redirect::to("/lga_collection");
    }

    ///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

    //getting all collections by POS
    public function pos_collection()
    {
        $mda = Mda::where("igr_id",Auth::user()->igr_id)->get();
        $sidebar = "pos_collection";
        $collection = array();
        return view("collection.pos_collection",compact("mda",'sidebar',"collection"));
    }

    //////////////////////////////////////////////////////////////////////////////////////////////////////////////
    //getting a specific pos colection
    public function pos_collection_range(Request $request)
    {
        //getting list of mdas
        $mda = Mda::where("igr_id",Auth::user()->igr_id)->get();

        //getting all the request
        $mda_id = $request->input("mda");
        $start_date = $request->input("startdate");
        $end_date = $request->input("enddate");

        $sidebar = "pos_collection";

        //getting collection within the date range
        $collections = Collection::where("collection_type","pos")->where("mda_id",$mda_id)->whereDate('created_at',">=",$start_date )->whereDate('created_at',"<=",$end_date )->get();

        //select station base on MDA
        
        if (count($collections) > 0) {
                
            return view("collection.pos_range",compact("mda","sidebar","collections"));
        }

            Session::flash("warning","Failed! No result found.");
            return Redirect::to("/pos_collection");
    }
}
