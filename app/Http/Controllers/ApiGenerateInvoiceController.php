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
use App\Worker;
use App\Postable;
use App\Subhead;
use App\Remittance;
use App\Collection;
use Carbon\Carbon;
use App\Http\Requests;
use App\Http\Controllers\Controller;

class ApiGenerateInvoiceController extends Controller
{
    use Helpers;

    /////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////GENERATE INVOICE
        //genarating invoice
        public function generate_invoice(Request $request)
        {
            //Token authentication
            $this->token_auth();
            
            //validation incoming request
            if ($request->has('name') && $request->has('phone')&&$request->has('payer_id')&&$request->has('mda')&&$request->has('amount')&&$request->has('user_key')&&$request->has('start_date')&&$request->has('end_date')&&$request->has('pos_key')) {

                $request['invoice_key'] = str_random(15);
            $request['mda_id'] = $this->mda_id($request->input("mda"));
            $request['revenuehead_id'] = $this->revenue_id($request->input("revenue_head"));
            $request['worker_id'] = $this->worker_id($request->input('user_key'));

            if ($request->input("subhead")) {

                $request['subhead_id'] = $this->subhead_id($request->input("subhead"));
            }

            //checking for mda and revenue head
            if (empty($request['mda_id'])) {
                $message = "invalid Mda";
                return $this->response->array(compact('message'))->setStatusCode(400);
            }

            //checking for workers id
            if (empty($request['worker_id'])) {

             $message = "invalid User id";
             return $this->response->array(compact('message'))->setStatusCode(400);
         }

            //checking for revenue head

            //checking if subhead is valid and exist
         if ($request->has('subhead') && empty($request['subhead_id'])) {

            $message = "invalid sub-head";
            return $this->response->array(compact('message'))->setStatusCode(400);
        }

        //checking if user is assigned to mda 
        if (!$pos = $this->pos_check($request->input("pos_key"))) {
            $message = "invalid pos key";
            return $this->response->array(compact('message'))->setStatusCode(400);
        }

        if ($pos->mda_id != $request['mda_id']) {
            $message = "User not assigned to MDA";
            return $this->response->array(compact('message'))->setStatusCode(400);
        }

        //checking if user has a pending remittance
        if ($remittance =Remittance::where("worker_id",$request['worker_id'])->where("remittance_status",0)->first()) {
            $message = "Remittance required";
            return $this->response->array(compact('message'))->setStatusCode(400);
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
        $invoice_receipt['pos_key'] = $invoice->pos_key;

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

    $message = "parameter missing";
    return $this->response->array(compact('message'))->setStatusCode(400);

    }

//////////////////////////////////////////////////////////////////////////////////////////////////////////////
    //getting mda random key
    private function mda_key($id)
    {
        if ($mda = Mda::where("id",$id)->first()) {
                # code...
            return $mda->mda_key;
        }
    }

    //getting mda increment id
    private function mda_id($mda_key)
    {
        if ($mda = Mda::where("mda_key",$mda_key)->first()) {
                # code...
            return $mda->id;
        }
    }

        //getting user increment id
    private function worker_id($worker_key)
    {
        if ($worker = Worker::where("worker_key",$worker_key)->first()) {
            return $worker->id;
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

    //token Authentication
    private function token_auth()
    {
                //Token authentication
        $user = JWTAuth::parseToken()->authenticate();
        try{
            if (! $user ) {
                return $this->response->errorUnauthorized();
            } 
        } catch (\Tymon\JWTAuth\Exceptions\JWTException $e) {
            return $this->response->error('something went wrong');
        }

    }

    //pos check
    private function pos_check($pos_key)
    {
        $pos_check ="";
        if ($pos_check = Postable::where("pos_key",$pos_key)->first()) {
            return $pos_check;
        }
        return $pos_check;
    }

    //user check
    private function user_check($user_id)
    {
        $pos_check ="";
        if ($pos_check = Worker::where("worker_key",$user_id)->first()) {
            return $pos_check;
        }
        return $pos_check;
    }
}
