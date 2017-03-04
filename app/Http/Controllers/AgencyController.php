<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Auth;
use Session;
use Redirect;
use App\Igr;
use App\Postable;
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
      $igr = Igr::with("mdas")->find(Auth::user()->igr_id);
   	return view("agency.index",compact("sidebar","igr"));
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
      $igr = Igr::with("mdas.station")->find(Auth::user()->igr_id);
      $station = Station::all();
      $pos = array();
      return view("agency.pos",compact("sidebar","igr",'station',"pos"));
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

   //storing pos
   public function store_pos(Request $request)
   {
      //validation
      $this->validate($request, [
          'pos_imei' => 'required',
          'name' => 'required',
          'mda_id' => 'required',
          'station_id' => 'required',
      ]);

      //checking if the imei exist
      if ($pos = Postable::where("pos_imei",$request->pos_imei)->first()) {
         Session::flash("warning","Failed! POS already exist ");
         return Redirect::back();
      }

      //generating the random keys
      $request['pos_key'] ="PO" .$this->random_number(11);
      $request['activation_code'] ="PO" .$this->random_number(6);

      //inserting record into the db
      if ($pos_details = Postable::create($request->all())) {

         Session::flash("message","Successful! POS added ");
         return Redirect::back();
      }

      Session::flash("warning","Failed! Unable to add POS ");
      return Redirect::back();
   }

   /////////////////////////////////////////////////////////////////////////////////////

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
