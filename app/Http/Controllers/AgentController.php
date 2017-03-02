<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Auth;
use Session;
use Redirect;
use App\Igr;
use App\Mda;
use App\Subhead;
use App\Revenuehead;
use App\Http\Requests;
use App\Http\Controllers\Controller;

class AgentController extends Controller
{
    //protecting route
    public function __construct()
    {

    	$this->middleware('auth');

    }

    /////////////////////////////////////////////////////////////////////////////////////////

    //displaying agent page
    public function index()
    {
    	$sidebar = "agent";
    	$igr = Igr::with('mdas')->find(Auth::user()->igr_id);

    	return view("agent.index",compact("sidebar","igr"));
    }

    ///////////////////////////////////////////////////////////////////////////////////////

    //getting list of heads under an MDA
    public function agent_mda($id)
    {
    	$subhead = Subhead::where("mda_id",$id)->get();

    	echo  json_encode($subhead);
    }

    //////////////////////////////////////////////////////////////////////////////////////////

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
}
