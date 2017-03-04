<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Auth;
use Session;
use Redirect;
use App\Igr;
use App\Mda;
use App\Worker;
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
      $pos_user =array();

    	return view("agent.index",compact("sidebar","igr",'pos_user'));
    }

    ///////////////////////////////////////////////////////////////////////////////////////

    //getting list of heads under an MDA
    public function agent_mda($id)
    {
    	$subhead = Subhead::where("mda_id",$id)->get();

    	echo  json_encode($subhead);
    }

    //////////////////////////////////////////////////////////////////////////////////////////

    //storing agent
    public function agent(Request $request)
    {
      //validation
      $this->validate($request, [
          'worker_name' => 'required|min:3',
          'phone' => 'required|digits:11',
          'user_limit' => 'required',
          'mda_id' => 'required',
          'subhead' => 'required',
      ]);

      //checking the phone number exist
      if ($phone = Worker::where("phone",$request->input('phone'))->first()) {

        Session::flash("warning","Failed! Phone number already exist on the platform");
        return Redirect::back();
      }

      //generate random number
      $request['worker_key'] = 'AG'.$this->random_number(11);
      $request['pin'] = 'AG'.$this->random_number(6);

      //insert into database
      if ($worker = Worker::create($request->all())) {

        //Attaching worker and subheads
        $worker->subheads()->attach($request->input('subhead'));
        $worker->save();

        Session::flash("message","Successful! POS user added ");
        return Redirect::back();
        
      }

      Session::flash("warning","Failed! Unable to add Pos user");
      return Redirect::back();

    }

    ////////////////////////////////////////////////////////////////////////////////////////////

    //getting a specific MDA user
    public function pos_user(Request $request)
    {
        $sidebar = "agent";
        $igr = Igr::with('mdas')->find(Auth::user()->igr_id);
        $pos_user =Worker::where("mda_id",$request->input('station'))->get();

        return view("agent.index",compact("sidebar","igr",'pos_user'));
    }

    ////////////////////////////////////////////////////////////////////////////////////////////

    //deketeing a pos user
    public function delete_pos_user($id)
    {
      //checking if user have the right credentials
      if ( ! Auth::user()->hasRole('Superadmin')) {

         Session::flash("warning","You don't have the right to delete MDA");
         return Redirect::back();
      }

      if ($worker= Worker::where("worker_key",$id)->first()) {

          $worker->delete();

          Session::flash("message","Successful! User deleted");
          return Redirect::back();
      }

      Session::flash("warning","Failed! User not deleted");
      return Redirect::back();

    }

    /////////////////////////////////////////////////////////////////////////////////////////////

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
