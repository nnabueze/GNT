<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Auth;
use JWTAuth;
use Validator;
use Image;
use Response;
use Tymon\JWTAuth\Exceptions\JWTException;
use Dingo\Api\Routing\Helpers;
use App\User;
use App\Invoice;
use App\Revenuehead;
use App\Mda;
use App\Worker;
use App\Postable;
use App\Subhead;
use App\Tin;
use App\Igr;
use App\Remittance;
use App\Collection;
use App\ebillcollection;
use App\Remittancenotification;
use App\Invoicenotification;
use Carbon\Carbon;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use SoapBox\Formatter\Formatter;

class EbillNotificationController extends Controller
{
    public function index(Request $request)
    {
    	$jsonString = $request->getContent();
    	$formatter = Formatter::make($jsonString, Formatter::XML);
    	$json  = $formatter->toArray();

    	//checking notification for collection
    	if (isset($json['tax'])) {

    		$this->collection($json);

    	}

    	//checking notification for remittance
    	if (isset($json['Remittance'])) {
    		
    		$this->remittance($json);
    	}

    	//checking notification for invoice
    	if (isset($json['Invoice'])) {
    		$this->invoice($json);
    	}
    }

    /////////////////////////////////////////////////////////////////////////////////

    //collection api
    private function collection($param)
    {
    	$data['collection_key'] = $param['Refcode'];
    	$data['Tin'] = $param['Tin'];
    	$data['collection_type'] = $param['collection_type'];
    	$data['tax'] = $param['tax'];
    	$data['igr_id'] = $this->igr_id($param['BillerID']);
    	$data['mda_id'] = $this->mda_id($param['Mda_key']);
    	$data['subhead_id'] = $this->subhead_id($param['subhead_key']);

    	for ($i=0; $i <count($param['Param']) ; $i++) { 

    	    if ($param['Param'][$i]['Key'] == "name") {
    	        $data['name'] = $param['Param'][$i]['Value'];
    	    }

    	    if ($param['Param'][$i]['Key'] == "phone") {
    	        $data['phone'] = $param['Param'][$i]['Value'];
    	    }

    	    if ($param['Param'][$i]['Key'] == "mda") {
    	        $data['mda'] = $param['Param'][$i]['Value'];
    	    }

    	    if ($param['Param'][$i]['Key'] == "subhead") {
    	        $data['subhead'] = $param['Param'][$i]['Value'];
    	    }

    	    if ($param['Param'][$i]['Key'] == "period") {
    	        $data['period'] = $param['Param'][$i]['Value'];
    	    }

    	    if ($param['Param'][$i]['Key'] == "amount") {
    	        $data['amount'] = $param['Param'][$i]['Value'];
    	    }
    	}

    	$date_info = explode("/", $data['period']);
    	$data['start_date'] = $date_info[0];
    	$data['end_date'] = $date_info[1];
    	$data['SessionID'] = $param['SessionID'];
    	$data['SourceBankCode'] = $param['SourceBankCode'];
    	$data['DestinationBankCode'] = $param['DestinationBankCode'];


    	//inserting into collection
    	$collection = Collection::create($data);

    	//insert ebills collection
    	$ebillcollection = Ebillcollection::create($data);




    }

    /////////////////////////////////////////////////////////////////////////////////

    //notification for remittance
    private function remittance($param)
    {
    	$data['remittance_key'] = $param['Remittance'];
    	$data['igr_id'] = $this->igr_id($param['BillerID']);
    	$data['mda_id'] = $this->mda_id($param['Mda_key']);

    	for ($i=0; $i <count($param['Param']) ; $i++) { 

    	    if ($param['Param'][$i]['Key'] == "name") {
    	        $data['name'] = $param['Param'][$i]['Value'];
    	    }

    	    if ($param['Param'][$i]['Key'] == "phone") {
    	        $data['phone'] = $param['Param'][$i]['Value'];
    	    }

    	    if ($param['Param'][$i]['Key'] == "mda") {
    	        $data['mda'] = $param['Param'][$i]['Value'];
    	    }

    	    if ($param['Param'][$i]['Key'] == "amount") {
    	        $data['amount'] = $param['Param'][$i]['Value'];
    	    }
    	}

    	$data['SessionID'] = $param['SessionID'];
    	$data['SourceBankCode'] = $param['SourceBankCode'];
    	$data['DestinationBankCode'] = $param['DestinationBankCode'];



    	//insert ebills remittance notification table
    	if ($ebillcollection = Remittancenotification::create($data)) {
    		$remittance = Remittance::where("remittance_key", $data['remittance_key'])->first();

    		$remittance->update(['remittance_status'=>1]);
    	}
    }

    /////////////////////////////////////////////////////////////////////////////////
    //invoice notifcation
    private function invoice($param)
    {
    	$data['invoice_key'] = $param['Invoice'];
    	$data['igr_id'] = $this->igr_id($param['BillerID']);
    	$data['mda_id'] = $this->mda_id($param['Mda_key']);
    	$data['subhead_id'] = $this->subhead_id($param['subhead_key']);

    	for ($i=0; $i <count($param['Param']) ; $i++) { 

    	    if ($param['Param'][$i]['Key'] == "name") {
    	        $data['name'] = $param['Param'][$i]['Value'];
    	    }

    	    if ($param['Param'][$i]['Key'] == "phone") {
    	        $data['phone'] = $param['Param'][$i]['Value'];
    	    }

    	    if ($param['Param'][$i]['Key'] == "mda") {
    	        $data['mda'] = $param['Param'][$i]['Value'];
    	    }

    	    if ($param['Param'][$i]['Key'] == "subhead") {
    	        $data['subhead'] = $param['Param'][$i]['Value'];
    	    }

    	    if ($param['Param'][$i]['Key'] == "amount") {
    	        $data['amount'] = $param['Param'][$i]['Value'];
    	    }
    	}

    	$data['SessionID'] = $param['SessionID'];
    	$data['SourceBankCode'] = $param['SourceBankCode'];
    	$data['DestinationBankCode'] = $param['DestinationBankCode'];


    	//insert ebills remittance notification table
    	if ($invoice = Invoicenotification::create($data)) {
    		$remittance = Invoice::where("invoice_key", $data['invoice_key'])->first();

    		$remittance->update(['invoice_status'=>1]);
    	}
    }

    /////////////////////////////////////////////////////////////////////////////////

    //getting biller(IGR) serial id
    private function igr_id($igr_key)
    {
        if ($igr = Igr::where("igr_key",$igr_key)->first()) {
                   
            return $igr->id;
        }
    }

    //////////////////////////////////////////////////////////////////////////////////

    //getting mda id
    private function mda_id($mda_key)
    {
        if ($mda = Mda::where("mda_key",$mda_key)->first()) {
                # code...
            return $mda->id;
        }
    }

    ////////////////////////////////////////////////////////////////////////////////////

    //getting mda name
    private function subhead_id($mda_key)
    {
        if ($mda = Subhead::where("subhead_key",$mda_key)->first()) {
                # code...
            return $mda->id;
        }
    }
}
