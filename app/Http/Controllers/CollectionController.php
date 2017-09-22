<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Redirect;
use Auth;
use Session;
use App\Mda;
use App\Percentage;
use App\Igr;
use App\Collection;
use App\Revenuehead;
use App\Remittance;
use App\Invoice;
use App\Subhead;
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

        $percent_array = array();
        $info = array();
        $total_amount = 0;

        $today = date('Y-m-d',time());

        foreach ($igr->mdas as $mda) {
            $info['transaction_id'] = "";
            $info['payer_name'] = "";
            $info['head_subhead'] = "";
            $info['transaction_detail'] = "";
            $info['pos_user'] = "";
            $info['collection_point'] = "";
            $info['amount'] = "";
            $info['channel'] = "";
            $info['date'] = "";

            $collections = Collection::where("mda_id",$mda->id)->whereDate("created_at",">=", $today)->get(); 

            if (count($collections) > 0) {
                foreach ($collections as $collection) {
                    $info['transaction_id'] = $collection->collection_key;
                    $info['payer_name'] = $collection->name;

                    if($collection->collection_type == "pos"){
                        $info['head_subhead'] = $collection->subhead->subhead_code;
                        $info['transaction_detail'] = $collection->subhead->subhead_name;
                        $info['pos_user'] = $collection->worker->worker_name; 
                        $info['collection_point'] = $collection->station->station_name;
                    } else{
                        $info['head_subhead'] = $collection->subhead->subhead_code;
                        $info['transaction_detail'] = $collection->subhead->subhead_name;
                        $info['pos_user'] = "A/C"; 
                        $info['collection_point'] = "ERCASPay";
                    }
                    $info['channel'] = $collection->collection_type;
                    $info['amount'] = $collection->amount;
                    $info['date'] = $collection->created_at;

                    $total_amount += $collection->amount;


                    array_push($percent_array, $info);
                }
            }
            
        }
        return view("collection.index",compact("igr","sidebar","percent_array","total_amount"));
    }

    ///////////////////////////////////////////////////////////////////////////////////////////////////////////

    //show all the collection
    public function all_collection(Request $request)
    {

        //getting list of mdas
        $igr = Igr::with("mdas")->find(Auth::user()->igr_id);

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

        if (count($collections) > 0) {

            $total_amount = 0;

            //getting the total sum for the selected MDA
            foreach ($collections as $collection) {
                $total_amount += $collection->amount;
            }

            return view("collection.all",compact("igr","sidebar","collections","mda_name","total_amount"));
        }

        Session::flash("warning","Failed! No result found.");
        return Redirect::to("/all_collection");
    }

    /////////////////////////////////////////////////////////////////////////////////////////////////////////////

    //ebills
    public function ebill_collection()
    {
       
        $sidebar = "ebill_collection";


        $igr = Igr::with("mdas")->find(Auth::user()->igr_id);

        $percent_array = array();
        $info = array();
        $total_amount = 0;

        foreach ($igr->mdas as $mda) {
            $info['transaction_id'] = "";
            $info['payer_name'] = "";
            $info['head_subhead'] = "";
            $info['transaction_detail'] = "";
            $info['pos_user'] = "";
            $info['collection_point'] = "";
            $info['amount'] = "";
            $info['channel'] = "";
            $info['date'] = "";

                //checking if the MDA belong to state

                $collections = Collection::where("mda_id",$mda->id)->get();

                if (count($collections) > 0) {
                    foreach ($collections as $collection) {
                        if ($collection->collection_type == "ebills") {
                            $info['transaction_id'] = $collection->collection_key;
                            $info['payer_name'] = $collection->name;

                            if($collection->collection_type == "pos"){
                                $info['head_subhead'] = $collection->subhead->subhead_code;
                                $info['transaction_detail'] = $collection->subhead->subhead_name;
                                $info['pos_user'] = $collection->worker->worker_name; 
                                $info['collection_point'] = $collection->station->station_name;
                            } else{
                                $info['head_subhead'] = $collection->subhead->subhead_code;
                                $info['transaction_detail'] = $collection->subhead->subhead_name;
                                $info['pos_user'] = "A/C"; 
                                $info['collection_point'] = "ERCASPay";
                            }
                            $info['channel'] = $collection->collection_type;
                            $info['amount'] = $collection->amount;
                            $info['date'] = $collection->created_at;

                            $total_amount += $collection->amount;


                            array_push($percent_array, $info);
                        }
                    }
                }
        }

        $mda = Mda::where("igr_id",Auth::user()->igr_id)->get();
        return view("collection.ebills_collection",compact("igr","sidebar","percent_array","total_amount","mda"));


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
        $collections = Collection::where("collection_type","ebills")->where("mda_id",$mda_id)->whereDate('created_at',">=",$start_date )->whereDate('created_at',"<=",$end_date )->get();

        //getting the name of the search MDA
        $mda1 = Mda::find($mda_id);
        $mda_name = $mda1->mda_name;

        //select station base on MDA
        
        if (count($collections) > 0) {

            $total_amount = 0;

            //getting the total sum for the selected MDA
            foreach ($collections as $collection) {
                $total_amount += $collection->amount;
            }

            return view("collection.ebill_range",compact("mda","sidebar","collections","mda_name","total_amount"));
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
        $sidebar = "agency";
        $igr = Igr::with("mdas")->find(Auth::user()->igr_id);

        $percent_array = array();
        $info = array();
        $total_amount = 0;

        foreach ($igr->mdas as $mda) {
            $info['transaction_id'] = "";
            $info['payer_name'] = "";
            $info['head_subhead'] = "";
            $info['transaction_detail'] = "";
            $info['pos_user'] = "";
            $info['collection_point'] = "";
            $info['amount'] = "";
            $info['channel'] = "";
            $info['date'] = "";

                //checking if the MDA belong to state
            if ($mda->mda_category == "state") {

                $collections = Collection::where("mda_id",$mda->id)->get();

                if (count($collections) > 0) {
                    foreach ($collections as $collection) {
                        $info['transaction_id'] = $collection->collection_key;
                        $info['payer_name'] = $collection->name;

                        if($collection->collection_type == "pos"){
                            $info['head_subhead'] = $collection->subhead->subhead_code;
                            $info['transaction_detail'] = $collection->subhead->subhead_name;
                            $info['pos_user'] = $collection->worker->worker_name; 
                            $info['collection_point'] = $collection->station->station_name;
                        } else{
                            $info['head_subhead'] = $collection->subhead->subhead_code;
                            $info['transaction_detail'] = $collection->subhead->subhead_name;
                            $info['pos_user'] = "A/C"; 
                            $info['collection_point'] = "ERCASPay";
                        }
                        $info['channel'] = $collection->collection_type;
                        $info['amount'] = $collection->amount;
                        $info['date'] = $collection->created_at;

                        $total_amount += $collection->amount;


                        array_push($percent_array, $info);
                    }
                }
            }

        }

        $mda = Mda::where("mda_category","state")->where("igr_id",Auth::user()->igr_id)->get();
        return view("collection.agency",compact("igr","sidebar","percent_array","total_amount","mda"));

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
        $mda1 = Mda::find($mda_id);
        $mda_name = $mda1->mda_name;

        //select station base on MDA
        
        if (count($collections) > 0) {
            $total_amount = 0;

            //getting the total sum for the selected MDA
            foreach ($collections as $collection) {
                $total_amount += $collection->amount;
            }

            return view("collection.angency_range",compact("igr","sidebar","collections","mda_name","total_amount"));
        }

        Session::flash("warning","Failed! No result found.");
        return Redirect::to("/agency_collection");
    }

    //////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    //lga collection 
    public function lga_collection()
    {
       $sidebar = "lga_collection";

       $igr = Igr::with("mdas")->find(Auth::user()->igr_id);

       $percent_array = array();
       $info = array();
       $total_amount = 0;

       foreach ($igr->mdas as $mda) {
           $info['transaction_id'] = "";
           $info['payer_name'] = "";
           $info['head_subhead'] = "";
           $info['transaction_detail'] = "";
           $info['pos_user'] = "";
           $info['collection_point'] = "";
           $info['amount'] = "";
           $info['channel'] = "";
           $info['date'] = "";

               //checking if the MDA belong to state
           if ($mda->mda_category == "lga") {

               $collections = Collection::where("mda_id",$mda->id)->get();

               if (count($collections) > 0) {
                   foreach ($collections as $collection) {
                       $info['transaction_id'] = $collection->collection_key;
                       $info['payer_name'] = $collection->name;

                       if($collection->collection_type == "pos"){
                           $info['head_subhead'] = $collection->subhead->subhead_code;
                           $info['transaction_detail'] = $collection->subhead->subhead_name;
                           $info['pos_user'] = $collection->worker->worker_name; 
                           $info['collection_point'] = $collection->station->station_name;
                       } else{
                           $info['head_subhead'] = $collection->subhead->subhead_code;
                           $info['transaction_detail'] = $collection->subhead->subhead_name;
                           $info['pos_user'] = "A/C"; 
                           $info['collection_point'] = "ERCASPay";
                       }
                       $info['channel'] = $collection->collection_type;
                       $info['amount'] = $collection->amount;
                       $info['date'] = $collection->created_at;

                       $total_amount += $collection->amount;


                       array_push($percent_array, $info);
                   }
               }
           }

       }

       $mda = Mda::where("mda_category","lga")->where("igr_id",Auth::user()->igr_id)->get();
       return view("collection.lga",compact("igr","sidebar","percent_array","total_amount","mda"));
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

    $mda1 = Mda::find($mda_id);
    $mda_name = $mda1->mda_name;

        //select station base on MDA

    if (count($collections) > 0) {

        $total_amount = 0;

        //getting the total sum for the selected MDA
        foreach ($collections as $collection) {
            $total_amount += $collection->amount;
        }
        return view("collection.lga_range",compact("mda","sidebar","collections","mda_name","total_amount"));
    }

    Session::flash("warning","Failed! No result found.");
    return Redirect::to("/lga_collection");
}

    ///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

    //getting all collections by POS
public function pos_collection()
{
    $sidebar = "pos_collection";

    $igr = Igr::with("mdas")->find(Auth::user()->igr_id);

    $percent_array = array();
    $info = array();
    $total_amount = 0;

    foreach ($igr->mdas as $mda) {
        $info['transaction_id'] = "";
        $info['payer_name'] = "";
        $info['head_subhead'] = "";
        $info['transaction_detail'] = "";
        $info['pos_user'] = "";
        $info['collection_point'] = "";
        $info['amount'] = "";
        $info['channel'] = "";
        $info['date'] = "";

            //checking if the MDA belong to state

            $collections = Collection::where("mda_id",$mda->id)->get();

            if (count($collections) > 0) {
                foreach ($collections as $collection) {
                    if ($collection->collection_type == "pos") {
                        $info['transaction_id'] = $collection->collection_key;
                        $info['payer_name'] = $collection->name;

                        if($collection->collection_type == "pos"){
                            $info['head_subhead'] = $collection->subhead->subhead_code;
                            $info['transaction_detail'] = $collection->subhead->subhead_name;
                            $info['pos_user'] = $collection->worker->worker_name; 
                            $info['collection_point'] = $collection->station->station_name;
                        } else{
                            $info['head_subhead'] = $collection->subhead->subhead_code;
                            $info['transaction_detail'] = $collection->subhead->subhead_name;
                            $info['pos_user'] = "A/C"; 
                            $info['collection_point'] = "ERCASPay";
                        }
                        $info['channel'] = $collection->collection_type;
                        $info['amount'] = $collection->amount;
                        $info['date'] = $collection->created_at;

                        $total_amount += $collection->amount;


                        array_push($percent_array, $info);
                    }
                }
            }
    }

    $mda = Mda::where("igr_id",Auth::user()->igr_id)->get();
    return view("collection.pos_collection",compact("igr","sidebar","percent_array","total_amount","mda"));
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

    $mda1 = Mda::find($mda_id);
    $mda_name = $mda1->mda_name;

        //select station base on MDA

    if (count($collections) > 0) {

        $total_amount = 0;

        //getting the total sum for the selected MDA
        foreach ($collections as $collection) {
            $total_amount += $collection->amount;
        }

        return view("collection.pos_range",compact("mda","sidebar","collections","mda_name","total_amount"));
    }

    Session::flash("warning","Failed! No result found.");
    return Redirect::to("/pos_collection");
}

    //////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    //percentage collection
public function percentage()
{
    $sidebar = "percentage";
    $igr = Igr::with("mdas")->find(Auth::user()->igr_id);
    $percent_array = array();
    return view("collection.percentage",compact("igr","sidebar","percent_array"));
}

    ///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    //showing percentage collection


public function percentage_report(Request $request)
{
        //echo "string";die;

        //getting list of mdas
    $igr = Igr::with("mdas")->find(Auth::user()->igr_id);

        //getting all the request
    $mda_id = $request->input("mda");
    $start_date = $request->input("startdate");
    $end_date = $request->input("enddate");

    $sidebar = "percentage";
    $collection = array();

        //getting subheads under an MDA
    $mda_subheads = Mda::find($mda_id);

    $percent_array = array();
    $info = array();
    $agency_total =0;
    $gov_total = 0;
    $amount_total =0;

    foreach($mda_subheads->subheads as $subhead){
        $info['amount'] = 0;
        $info['gov_amount'] = 0;
        $info['agency_amount'] = 0;
        $info['subhead'] = "";

            //casting subhead id to int
        $id = (int) $subhead->id;

            //getting percentage collection of mda subheads within the date range
        $collections = Percentage::where("mda_id",$mda_id)->where("subhead_id",$id)->whereDate('created_at',">=",$start_date )->whereDate('created_at',"<=",$end_date )->get();

            //check for subhead that have payment within date range
        if (count($collections) > 0) {
            foreach ($collections as $collection) {

                $info['amount'] += $collection->amount;
                $info['gov_amount'] += $collection->gov_amount;
                $info['agency_amount'] += $collection->agency_amount;

            }

            $agency_total = $agency_total + $info['agency_amount'];
            $gov_total = $gov_total + $info['gov_amount'];
            $amount_total = $amount_total + $info['amount'];
            $info['subhead'] = $this->subhead_name($id);

            array_push($percent_array, $info);
        }

    }

        //getting the name of the search MDA
    $mda = Mda::find($mda_id);
    $mda_name = $mda->mda_name;

        //select station base on MDA

    if (count($percent_array) > 0) {            

        return view("collection.percentage_report",compact("igr","sidebar","percent_array","mda_name","agency_total","gov_total","amount_total"));
    }

    Session::flash("warning","Failed! No result found.");
    return Redirect::to("/percentage");
}

    //getting name of a subhead
private function subhead_name($id)
{
    $subhead = Subhead::find($id);

    return $subhead->subhead_name;
}

    //getting list of remittance 
public function list_remittance()
{
    $sidebar = "remittance";
    $igr = Igr::with("mdas")->find(Auth::user()->igr_id);
    $remittance = array();
    return view("remittance_invoice.remittance",compact("igr","sidebar","remittance"));
}

//getting list of remittance for staff
public function s_list_remittance()
{
    $sidebar = "remittance";
    $remittances = Remittance::where("mda_id",Auth::user()->mda_id)->get();
    return view("remittance_invoice.s_remittance",compact("sidebar","remittances"));
}

    //viewing remittance with date range
public function remittance_view(Request $request)
{
        //getting list of mdas
    $igr = Igr::with("mdas")->find(Auth::user()->igr_id);

        //getting all the request
    $mda_id = $request->input("mda");
    $start_date = $request->input("startdate");
    $end_date = $request->input("enddate");

    $sidebar = "remittance";

        //getting collection within the date range
    $remittances = Remittance::where("mda_id",$mda_id)->whereDate('created_at',">=",$start_date )->whereDate('created_at',"<=",$end_date )->get();

        //getting the name of the search MDA
    $mda = Mda::find($mda_id);
    $mda_name = $mda->mda_name;

        //select station base on MDA

    if (count($remittances) > 0) {

        return view("remittance_invoice.remittance_view",compact("igr","sidebar","remittances","mda_name"));
    }

    Session::flash("warning","Failed! No result found.");
    return Redirect::to("/list_remittance");
}

//getting range of staff remittance
public function s_remittance_view(Request $request)
{


        //getting all the request
    $mda_id = Auth::user()->mda_id;
    $start_date = $request->input("startdate");
    $end_date = $request->input("enddate");

    $sidebar = "remittance";

        //getting collection within the date range
    $remittances = Remittance::where("mda_id",$mda_id)->whereDate('created_at',">=",$start_date )->whereDate('created_at',"<=",$end_date )->get();

        //getting the name of the search MDA
    $mda = Mda::find($mda_id);
    $mda_name = $mda->mda_name;

        //select station base on MDA

    if (count($remittances) > 0) {

        return view("remittance_invoice.s_remittance_view",compact("sidebar","remittances","mda_name"));
    }

    Session::flash("warning","Failed! No result found.");
    return Redirect::to("/list_remittance");
}

    //getting list of invoice 
public function list_invoice()
{
    $sidebar = "invoice";
    $igr = Igr::with("mdas")->find(Auth::user()->igr_id);
    $remittance = array();
    return view("remittance_invoice.invoice",compact("igr","sidebar","remittance"));
}

    //viewing remittance with date range
public function invoice_view(Request $request)
{
        //getting list of mdas
    $igr = Igr::with("mdas")->find(Auth::user()->igr_id);

        //getting all the request
    $mda_id = $request->input("mda");
    $start_date = $request->input("startdate");
    $end_date = $request->input("enddate");

    $sidebar = "invoice";

        //getting collection within the date range
    $invoice = Invoice::where("mda_id",$mda_id)->whereDate('created_at',">=",$start_date )->whereDate('created_at',"<=",$end_date )->get();

        //getting the name of the search MDA
    $mda = Mda::find($mda_id);
    $mda_name = $mda->mda_name;

        //select station base on MDA

    if (count($invoice) > 0) {

        return view("remittance_invoice.invoice_view",compact("igr","sidebar","invoice","mda_name"));
    }

    Session::flash("warning","Failed! No result found.");
    return Redirect::to("/list_remittance");
}
}
