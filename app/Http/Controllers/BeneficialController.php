<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Igr;
use App\Percentage;
use App\Beneficial;
use App\History;
use Session;
use Redirect;
use Auth;
use DB;
use App\Http\Requests;
use App\Http\Controllers\Controller;

class BeneficialController extends Controller
{
	//protecting route
	public function __construct()
	{

		$this->middleware('auth');

	}

    //displaying all agency acount with in an igr
    public function index()
    {
    	$sidebar = "beneficiaries";
    	$igr = Igr::find(Auth::user()->igr_id);
    	$beneficial = Beneficial::where("igr_id",Auth::user()->igr_id)->get();

    	return view("beneficial.index",compact('sidebar','igr','beneficial'));
    }

    //storing Beneficial
    public function Beneficial(Request $request)
    {
    	$this->validate($request, [
    	    'mda_id' => 'required',
    	    'account_no' => 'required|numeric',
    	    'bank_code' => 'required|numeric',
    	    'narration' => 'required|max:20',
            'bank_name' => 'required',
    	    'account_name' => 'required',
    	]);

    	//check if the account number exist
    	if ($account_check = Beneficial::where("mda_id",$request->input("mda_id"))->where("account_no",$request->input("account_no"))->first()) {
    		Session::flash("warning","Failed! Account number already exist");
    		return Redirect::back();
    	}

    	//generating Beneficial key
    	$request['beneficial_key'] = $this->random_number(10);

    	//stroing account number
    	if ($account = Beneficial::create($request->all())) {

    		Session::flash("message","Successful! Account created");
    		return Redirect::back();
    	}

    	Session::flash("warning","Failed! Unable to create account");
    	return Redirect::back();
    }

    //displaying generating fundsweep page
    public function fundsweep()
    {
        $sidebar = "fundsweep";
        $percent_array = array();

        return view("beneficial.fundsweep",compact('sidebar','percent_array'));
    }

    //generating fundsweep 
    public function generate_fundsweep(Request $request)
    {
        //validaating input param
        $this->validate($request, [
            'enddate' => 'required',
            'startdate' => 'required',
          
        ]);


        //getting list of mdas
        $igr = Igr::find(Auth::user()->igr_id);

        //getting all the request
        $start_date = $request->input("startdate");
        $end_date = $request->input("enddate");

        $sidebar = "fundsweep";

        $percent_array = array();
        $info = array();

        //checking if sweep have been genarated before
/*        if ($fundsweep_check = History::whereDate('startdate',"=",$start_date )->whereDate('enddate',"=",$start_date )) {

            Session::flash("warning","Failed! Fundsweep already exist check history.");
            return Redirect::to("/fundsweep");
        }*/


        foreach ($igr->mdas as $mda) {

            $info['mda_name'] = null;
            $info['account_no'] = null;
            $info['bank_code'] = null;
            $info['bank_name'] = null;
            $info['agency_total'] = 0;

            foreach($mda->subheads as $subhead){

                $agency_amount = 0;

                //casting subhead id to int
                $id = (int) $subhead->id;
                $mda_id = $mda->id;

                //getting percentage collection of mda subheads within the date range
                $collections = Percentage::where("mda_id",$mda_id)->where("subhead_id",$id)->whereDate('created_at',">=",$start_date )->whereDate('created_at',"<=",$end_date )->get();

                //check for subhead that have payment within date range
                if (count($collections) > 0) {
                    foreach ($collections as $collection) {

                        $agency_amount += $collection->agency_amount;
                        
                    }

                    $info['agency_total'] = $info['agency_total'] + $agency_amount;

                    
                }

            }

            //getting account details for MDA.
            if ($account = Beneficial::where("mda_id",$mda->id)->first()) {

                $info['mda_name'] = $mda->mda_name;
                $info['account_no'] = $account->account_no;
                $info['bank_code'] = $account->bank_code;
                $info['bank_name'] = $account->bank_name;
                $info['mda_id'] = (int) $mda->id;

                array_push($percent_array, $info);
            }
            
         }
         /*echo"<pre>";print_r();die;*/

         //storing the fundsweep history
         $ran_number = $this->random_number(10);
         $gen_name = date('d F Y', strtotime($start_date))."-".date('d F Y', strtotime($end_date));
         $igr_id = date('d F Y', strtotime($start_date))."-".date('d F Y', strtotime($end_date));
         

        if (count($percent_array) > 0) {

            $insert_id = DB::table('histories')->insertGetId(['history_key' => $ran_number, 
                                                            'history_name' => $gen_name,
                                                            'startdate'=>$start_date,
                                                            'igr_id'=>$mda->igr->id,
                                                            'enddate'=>$end_date]);

            //storing generated fundsweep
            foreach ($percent_array as $sweep) {
               
                DB::table('fundsweeps')->insertGetId(['mda_id' => $sweep['mda_id'], 
                                                        'history_id' => $insert_id,'account_no'=>$sweep['account_no'],
                                                        'bank_code'=>$sweep['bank_code'],
                                                        'bank_name'=>$sweep['bank_name'],
                                                        'agency_total'=>$sweep['agency_total']]);
            }            
                
            return view("beneficial.generate_fundsweep",compact("sidebar",'percent_array'));
        }

            Session::flash("warning","Failed! No fundsweep generated for the selected date.");
            return Redirect::to("/fundsweep");

    }

    //viewing fundsweep history
    public function fundsweep_history()
    {
        $sidebar = "history";

        //getting biller id
        $history = History::where("igr_id",Auth::user()->igr_id)->get();

        return view("beneficial.fundsweep_history",compact("sidebar","history"));

    }

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
