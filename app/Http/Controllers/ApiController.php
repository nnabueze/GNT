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
        
        $result = $invoice->remittance->toArray();
        
        if ($result) {

            $invoice_receipt['invoice_no'] = $result['collection_key'];
            $invoice_receipt['amount'] = $result['amount'];
            $invoice_receipt['name'] = $result['name'];
            $invoice_receipt['email'] = $result['email'];
            $invoice_receipt['start_date'] = $result['start_date'];
            $invoice_receipt['end_date'] = $result['end_date'];

            return $this->response->array(compact('invoice_receipt'))->setStatusCode(200);
        }

        return $this->response->error('Invoice not remitted',404);
    }


}
