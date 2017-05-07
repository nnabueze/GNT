<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Auth;
use Session;
use Redirect;
use App\Igr;
use App\Mda;
use Input;
use App\Station;
use App\Revenuehead;
use App\Subhead;
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
    	$igr = igr::with("mdas.revenue")->find(Auth::user()->igr_id);

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

    	if ($heads) {
    		
    		return view("heads.subhead",compact("heads","sidebar","igr"));
    	}

    	Session::flash("warning","Failed! No Revenue Head added.");
    	return Redirect::to("/revenue_heads");
    }

    //////////////////////////////////////////////////////////////////////////////

    //editing subheads
    public function revenue_heads_edit($id)
    {
       
        //checking the key exist
        if ($subhead = Subhead::where("subhead_key", $id)->first()) {
            
            $sidebar = "heads";
            return view("heads.edit_subhead", compact("sidebar","subhead"));
        }

        Session::flash("warning","Failed! Subhead does not exist.");
        return Redirect::back();
    }

    /////////////////////////////////////////////////////////////////////////////
    //deleting subheads
    public function revenue_heads_delete($id)
    {

        //checking user is a super admin
        //checking user right
        if ( ! Auth::user()->hasRole('Superadmin')) {

           Session::flash("warning","You don't have the right to delete MDA");
           return Redirect::back();
        }

        //checking if the key exist
        if ($subhead = Subhead::where("subhead_key",$id)->first()) {

            //deleting subhead
            $subhead->delete();
            
            //return response
            Session::flash("message","Successful! Subhead deleted");
            return Redirect::back();
        }

        //return response
        Session::flash("warning","Failed! Unable to delete subhead");
        return Redirect::back();
    }

    /////////////////////////////////////////////////////////////////////////////

    //storing edited subhead
    public function revenue_heads_store(Request $request)
    {
        //validate in put fields
        $this->validate($request, [
                'head_code' => 'required',
                'head' => 'required',
                'subhead_code' => 'required',
                'subhead' => 'required',
                'amount' => 'numeric',
                'gov' => 'numeric',
                'agency' => 'numeric',
            ]);

        //check if the subhead exist
        if ($subhead = Subhead::find($request->id)) {
            $subhead->update(["subhead_code"=>$request->subhead_code,"subhead_name"=>$request->subhead,"amount"=>$request->amount,"gov"=>$request->gov,
                "agency"=>$request->agency]);

            //selecting the heads
            $heads = Revenuehead::find($request->head_id);
            $heads->update(["revenue_code"=>$request->head_code,"revenue_name"=>$request->head]);

            Session::flash("message","Successful! Subhead updated");
            return Redirect::to("revenue_heads");
        }

        Session::flash("warning","Failed! Unable to update Subhead");
        return Redirect::back();
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
        $sidebar = "heads_revenue";
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

    /////////////////////////////////////////////////////////////////////////////////////////////////////

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

    ////////////////////////////////////////////////////////////////////////////////////////////

    //adding heads on the platform
    public function add_heads(Request $request)
    {
        //validation
        $this->validate($request, [
            'revenue_name' => 'required|min:2',
            'mda_id' => 'required',
            'revenue_code' => 'required'
        ]);

        //generate random key and code
        $request['revenueheads_key'] = "RH".$this->random_number(11);

        if ($revenue = Revenuehead::create($request->all())) {
            
            Session::flash("message","Successful! Revenue head added");
            return Redirect::back();
        }
       
       Session::flash("warning","Fail! Unable to add revenue head");
       return Redirect::back();


    }

    /////////////////////////////////////////////////////////////////////////////////////////////

    //adding subhead
    public function add_subhead(Request $request)
    {
        //validation
        $this->validate($request, [
            'subhead_name' => 'required|min:2',
            'subhead_code' => 'required',
            'revenuehead_id' => 'required',
            'amount' => 'numeric',
            'gov'=>'numeric',
            'gency'=>'numeric'
        ]);

        //generate random key
        $request['subhead_key'] = "SH".$this->random_number(5).$this->random_number(8);

        //check if gov is not empty
        if (! empty($request->gov)) {
           $request['agency'] = 100 - $request->gov;
        }

        //check if inserting was succesful
        if ($subhead = Subhead::create($request->all())) {
            
            Session::flash("message","Successful! Subhead head added");
            return Redirect::back();
        }

        //return response
        Session::flash("warning","Failed! Unable to add subhead");
        return Redirect::back();
    }

    //////////////////////////////////////////////////////////////////////////////////////////////

    //Route for getting list of heads when pass mda id
    public function list_heads()
    {
        $id = Input::get('option');
        $mda = Mda::with("revenue")->find($id);
        return $mda->revenue->lists('revenue_name', 'id');
    }

    /////////////////////////////////////////////////////////////////////////////////////////////////
    //Route for getting list of subheads
    public function list_subheads()
    {
        $id = Input::get('option');
        $mda = Mda::with("revenue")->find($id);
        return $mda->subheads->lists('subhead_name', 'id');
    }

    /////////////////////////////////////////////////////////////////////////////////////////////////

    //generating random numbers
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

    ///////////////////////////////////////////////////////////////////////////////////////////////



}
