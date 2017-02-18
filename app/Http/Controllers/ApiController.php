<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Auth;
use JWTAuth;
use Validator;
use Image;
use Tymon\JWTAuth\Exceptions\JWTException;
use Dingo\Api\Routing\Helpers;
use App\User;
use App\Invoice;
use App\Revenuehead;
use App\Mda;
use App\Subhead;
use Carbon\Carbon;
use App\Http\Requests;
use App\Http\Controllers\Controller;

class ApiController extends Controller
{
	use Helpers;

    //creating user token
    public function authentication(Request $request)
    {
    	$credentials = $request->only('email', 'password');

    	try{
    		if (! $token = JWTAuth::attempt($credentials)) {
    			return $this->response->errorUnauthorized();
    		}

    	} catch (JWTException $e) {
    		return $this->response->errorInternal();
    	}

    	return $this->response->array(compact('token'))->setStatusCode(200);
    }

    //getting the list of revenue heads
    public function revenue_heads()
    {
        $heads = Revenuehead::all();

        $revenue_heads = array();
        foreach ($heads as $head) {
            $item['revenueheads_key'] = $head->revenueheads_key;
            $item['revenue_code'] = $head->revenue_code;
            $item['revenue_name'] = $head->revenue_name;
            $item['amount'] = $head->amount;

            array_push($revenue_heads, $item);
        }

        return $this->response->array(compact('revenue_heads'))->setStatusCode(200);
    }

    //verifying invoice Number
    public function invoice(Request $request)
    {
        $invoice = $request->only('invoice_id');
       
       //verify that invoice
        if (! $invoice = Invoice::where("invoice_key",$invoice)->first()) {
            return $this->response->errorNotFound();
        }


        //returning details of a specific inoice
        $invoice_receipt['invoice_no'] = $invoice->invoice_key;
        $invoice_receipt['name'] = $invoice->name;
        $invoice_receipt['email'] = $invoice->email;
        $invoice_receipt['phone'] = $invoice->phone;
        $invoice_receipt['amount'] = $invoice->amount;
        $invoice_receipt['start_date'] = $invoice->start_date;
        $invoice_receipt['end_date'] = $invoice->end_date;
        $invoice_receipt['invoice_status'] = $invoice->invoice_status;

        //checking if invoice is assigned to mda
        if ($invoice->mda) {
            $invoice_receipt['mda'] = $invoice->mda->mda_name;
        }

        //checking if invoice is assign to revenue head
        if ($invoice->revenuehead) {
            $invoice_receipt['revenue_head'] = $invoice->revenuehead->revenue_name;
        }

        //checking if invoice is assign to subhead
        if ($invoice->subhead) {
            $invoice_receipt['sub_head'] = $invoice->subhead->subhead_name;
        }

        return $this->response->array(compact('invoice_receipt'))->setStatusCode(200);

    }

    //genarating invoice
    public function generate_invoice(Request $request)
    {
        //validation incoming request
        if (empty($request->input('name')) || empty($request->input('phone'))||empty($request->input('payer_id'))||empty($request->input('mda'))||empty($request->input('revenue_head'))
            ||empty($request->input('amount'))||empty($request->input('user_id'))||empty($request->input('start_date'))||empty($request->input('end_date'))) {

            $message = "parameter missing";
            return $this->response->array(compact('message'))->setStatusCode(400);
        }

        $request['invoice_key'] = str_random(15);
        $request['mda_id'] = $this->mda_id($request->input("mda"));
        $request['revenuehead_id'] = $this->revenue_id($request->input("revenue_head"));
        if ($request->input("subhead")) {

            $request['subhead_id'] = $this->subhead_id($request->input("subhead"));
        }

        if (!$invoice = Invoice::create($request->all())) {
            $message = "unable to generate invoice";
            return $this->response->array(compact('message'))->setStatusCode(400);
        }

        //returning details of a specific inoice
        $invoice_receipt['invoice_no'] = $invoice->invoice_key;
        $invoice_receipt['name'] = $invoice->name;
        $invoice_receipt['email'] = $invoice->email;
        $invoice_receipt['phone'] = $invoice->phone;
        $invoice_receipt['amount'] = $invoice->amount;
        $invoice_receipt['start_date'] = $invoice->start_date;
        $invoice_receipt['end_date'] = $invoice->end_date;
        $invoice_receipt['invoice_status'] = $invoice->invoice_status;

        //checking if invoice is assigned to mda
        if ($invoice->mda) {
            $invoice_receipt['mda'] = $invoice->mda->mda_name;
        }

        //checking if invoice is assign to revenue head
        if ($invoice->revenuehead) {
            $invoice_receipt['revenue_head'] = $invoice->revenuehead->revenue_name;
        }

        //checking if invoice is assign to subhead
        if ($invoice->subhead) {
            $invoice_receipt['sub_head'] = $invoice->subhead->subhead_name;
        }

        return $this->response->array(compact('invoice_receipt'))->setStatusCode(200);

    }

    //getting mda increment id
    private function mda_id($mda_key)
    {
        if ($mda = Mda::where("mda_key",$mda_key)->first()) {
            # code...
            return $mda->id;
        }
    }

    //revenue head
    private function revenue_id($revenue_key)
    {
        if ($revenue = Revenuehead::where("revenueheads_key",$revenue_key)->first()) {
            # code...
        return $revenue->id;
        }
    }

    //subhead
    private function subhead_id($subhead_key)
    {
        if ($subhead = Subhead::where("subhead_key",$subhead_key)->first()) {
            # code...
        return  $subhead->id;
        }
    }

}
