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

class AgencyController extends Controller
{
   //protecting route
   public function __construct()
   {

   	$this->middleware('auth');

   }

   ///////////////////////////////////////////////////////////////////////

   //Displaying the page
   public function index()
   {
      $sidebar = "agancy";
      $igr = Igr::all();
      $mda = Mda::where("mda_category","state")->get();
   	return view("agency.index",compact("sidebar","igr","mda"));
   }

   /////////////////////////////////////////////////////////////////////////////////


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
         Session::flash("message","Successful! MDA Registered");

         return Redirect::back();
      }

      //return failed response
      Session::flash("warning","Failed! Unable to register MDA");
      return Redirect::back();
   }

   //////////////////////////////////////////////////////////////////////////////////////
   //deleting agency
   public function delete_agency($id)
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

   ///////////////////////////////////////////////////////////////////////////////////////

   //displaying and setting up the revenue heads
   public function revenue_heads()
   {
   	return view("agency.revenve_head");
   }

   ///////////////////////////////////////////////////////////////////////////////

   //displaying and add up station
   public function station()
   {
      $mdas = Igr::with("mdas.station")->find(Auth::user()->igr_id);
   	return view("agency.mda_station",compact("mdas"));
   }

   ////////////////////////////////////////////////////////////////////////////////

   //getting list of pos 
   public function pos()
   {
      $sidebar ="pos";
      $mda = Mda::all();
      $mda1 = Mda::all();
      $station = Station::all();
      $pos = array();
      return view("agency.pos",compact("sidebar","mda",'station',"mda1","pos"));
   }

   /////////////////////////////////////////////////////////////////////////////////////

   //view list of revenue heads under MDA
   public function view_head($id)
   {
      $mda = Mda::where("mda_key",$id)->first();
      $revenue = Revenuehead::where("mda_id",$mda->id)->get();
      return view("agency.mda_revenue",compact("revenue"));
   }

   ////////////////////////////////////////////////////////////////////////////////////

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
