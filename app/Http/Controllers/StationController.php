<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Auth;
use Session;
use Redirect;
use App\Igr;
use App\Mda;
use App\Station;
use App\Postable;
use App\Revenuehead;
use App\Http\Requests;
use App\Http\Controllers\Controller;

class StationController extends Controller
{
    //

    //protecting route
    public function __construct()
    {

    	$this->middleware('auth');

    }

    //////////////////////////////////////////////////////////////////////////////


    //Displaying the page
    public function index()
    {
       $sidebar = "station";
       $igr = Igr::with("mdas")->find(Auth::user()->igr_id);
       $station="";
    	return view("station.index",compact("sidebar","igr","station"));
    }

    ////////////////////////////////////////////////////////////////////////////////

    //adding station on the platform
    public function store(Request $request)
    {
    	//validate the parameter
    	$this->validate($request, [
    	    'station_name' => 'required|min:3',
    	    'mda_id' => 'required',
    	]);

    	//generate a station code
    	$request['station_key'] = $this->random_number(5);

    	//insert into db
    	if ($station = Station::create($request->all())) {
    		
    		Session::flash("message","Successful! Station add.");
    		return Redirect::back();
    	}

    	//return response
    	Session::flash("message","Successful! Station add.");
    	return Redirect::back();
    }

    /////////////////////////////////////////////////////////////////////////////////

    //selecting the station of a specific MDA
    public function mda_station(Request $request)
    {
    	//getting the parameter
    	$item = $request->only("station");

    	//getting all the existing MDA
    	$sidebar = "station";
        $igr = Igr::with("mdas")->find(Auth::user()->igr_id);

    	//select station base on MDA
    	$station = Station::where("mda_id",$item)->get();
    	if (count($station) > 0) {
    		
    		return view("station.index",compact("station","sidebar","igr"));
    	}

    	Session::flash("warning","Failed! No station added.");
    	return Redirect::to("/station");

    	//return station 
    }

    ////////////////////////////////////////////////////////////////////////////////////

    //deleting station
    public function delete_station($id)
    {
    	//checking user right
    	if ( ! Auth::user()->hasRole('Superadmin')) {

    	   Session::flash("warning","You don't have the right to delete MDA");
    	   return Redirect::back();
    	}

    	//deleting the mda
    	if ($mda = Station::where("station_key",$id)->first()) {
    	   $mda->delete();

    	   Session::flash("message","Successful! Station deleted");
    	   return Redirect::back();
    	}

    	Session::flash("warning","Failed! Station not deleted");
    	return Redirect::back();
    }

    ////////////////////////////////////////////////////////////////////////////////////


    //getting lis of pos assigned to a perticuar mda
    public function mda_pos(Request $request)
    {
        //getting the parameter
        $item = $request->only("station");

        //getting all the existing MDA
        $sidebar = "station";
        $mda = Mda::all();
        $mda1 = Mda::all();

        //select station base on MDA
        $pos = Postable::where("mda_id",$item)->get();
        if (count($pos) > 0) {
            
            return view("station.pos",compact("pos","sidebar","mda","mda1"));
        }

        Session::flash("warning","Failed! No POS added.");
        return Redirect::to("/pos");
    }
    ////////////////////////////////////////////////////////////////////////////////////////

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
