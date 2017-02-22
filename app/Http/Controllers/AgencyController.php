<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

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
   	return view("agency.index");
   }

   //displaying and setting up the revenue heads
   public function revenue_heads()
   {
   	return view("agency.revenve_head");
   }

   //displaying and add up station
   public function station()
   {
   	return view("agency.station");
   }

   //getting list of pos 
   public function pos()
   {
      return view("agency.pos");
   }

}
