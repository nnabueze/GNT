<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Auth;
use App\Igr;
use App\Mda;
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

   //Displaying the page
   public function index()
   {
      //getting the list of MDA under an igr
      $mdas = Igr::with("mdas.revenue")->find(Auth::user()->igr_id);
   	return view("agency.index",compact("mdas"));
   }

   //displaying and setting up the revenue heads
   public function revenue_heads()
   {
   	return view("agency.revenve_head");
   }

   //displaying and add up station
   public function station()
   {
      $mdas = Igr::with("mdas.station")->find(Auth::user()->igr_id);
   	return view("agency.mda_station",compact("mdas"));
   }

   //getting list of pos 
   public function pos()
   {
      return view("agency.pos");
   }

   //view list of revenue heads under MDA
   public function view_head($id)
   {
      $mda = Mda::where("mda_key",$id)->first();
      $revenue = Revenuehead::where("mda_id",$mda->id)->get();
      return view("agency.mda_revenue",compact("revenue"));
   }

}
