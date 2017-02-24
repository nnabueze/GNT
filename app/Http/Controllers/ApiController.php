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

class ApiController extends Controller
{
	use Helpers;

/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////API LOGIN
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


///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////GETTING REVENUE HEADS
    //getting the list of revenue heads
    public function revenue_heads(Request $request)
    {
        //Token authentication
        $this->token_auth();

        //check if the parameter is missing
        if ($request->has("user_key")&&$request->has("mda_key")&&$request->has("pos_key")) {

            //checking if the user exist
            if (! $user = $this->user_check($request->input("user_key"))) {

               $message = "User does not exist";
               return $this->response->array(compact('message'))->setStatusCode(400); 
            }

            //checking if pos exist
            if (! $pos = $this->pos_check($request->input("pos_key"))) {
                $message = "Pos does not exist";
                return $this->response->array(compact('message'))->setStatusCode(400); 
            }

            //checking if the pos activated
            if ($pos->activation != 1) {
                $message = "Pos is not activated";
                return $this->response->array(compact('message'))->setStatusCode(400); 
            }

            //checking if user is assigned to the mda
            if ($pos->mda_id != $user->mda_id) {
                $message = "User is not assigned to MDA";
                return $this->response->array(compact('message'))->setStatusCode(400);
            }

            //getting the revenue heads
            $mda_id = $this->mda_id($request->input("mda_key"));
            if ($heads = Revenuehead::with("subheads")->where("mda_id",$mda_id)->get()) {
                $revenue_heads = array();

                foreach ($heads as $head) {
                    $item = array();

                    $item['revenueheads_key'] = $head->revenueheads_key;
                    $item['revenue_code'] = $head->revenue_code;
                    $item['revenue_name'] = $head->revenue_name;
                    $item['amount'] = $head->amount;
                    $item['taxiable'] = $head->taxiable;
                    if ($head->sub_heads == 1) {
                        $item['subheads_details'] =array();
                         //getting the subheads
                        foreach($head->subheads as $subhead){
                            $subhead_details['subhead_code'] = $subhead->subhead_code;
                            $subhead_details['subhead_name'] = $subhead->subhead_name;
                            $subhead_details['subhead_key'] = $subhead->subhead_key;
                            $subhead_details['taxiable'] = $subhead->taxiable;
                            $subhead_details['amount'] = $subhead->amount;

                            array_push($item['subheads_details'], $subhead_details);
                        }
                    }

                    array_push($revenue_heads, $item);
                }
              /*  print_r($revenue_heads);
                die;*/

                return $this->response->array(compact('revenue_heads'))->setStatusCode(200);
            }

            $message = "No Revenue Head Listed";
            return $this->response->array(compact('message'))->setStatusCode(400);
        }

        $message = "parameter missing";
        return $this->response->array(compact('message'))->setStatusCode(400);
    }
///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////



    //verifying invoice Number
    public function invoice(Request $request)
    {
        //Token authentication
        $this->token_auth();

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




//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////


    //post collections via 
public function pos_collection(Request $request)
{

    //Token authentication
    $this->token_auth();

        //validating request
    if ($request->has('name') && $request->has('phone')&&$request->has('payer_id')&&$request->has('mda')&&$request->has('revenue_head')
        &&$request->has('amount')&&$request->has('user_id')&&$request->has('start_date')&&$request->has('end_date')) {

            //generating for collect and getting mda auto incremental id.
        $request['collection_key'] = str_random(15);
    $request['mda_id'] = $this->mda_id($request->input("mda"));
    $request['revenuehead_id'] = $this->revenue_id($request->input("revenue_head"));
    $request['worker_id'] = $this->worker_id($request->input("user_id"));
    $request['collection_type'] = "pos";
    if ($request->input("subhead")) {

        $request['subhead_id'] = $this->subhead_id($request->input("subhead"));
    }

            //check if worker and mda passed exist            
    if (empty($request['mda_id']) || empty($request['revenuehead_id']) || empty($request['worker_id']) ) {

        return $this->response->errorNotFound();
    }

            //checking for user limit

            //checking for uses remittance status

            //inserting records
    if (! $collection = Collection::create($request->all())) {
        $message = "unable to insert record";
        return $this->response->array(compact('message'))->setStatusCode(400);
    }

    $collection_receipt['collection_key'] = $collection->collection_key;
    $collection_receipt['name'] = $collection->name;
    $collection_receipt['email'] = $collection->email;
    $collection_receipt['phone'] = $collection->phone;
    $collection_receipt['amount'] = $collection->amount;
    $collection_receipt['start_date'] = $collection->start_date;
    $collection_receipt['end_date'] = $collection->end_date;
    $collection_receipt['collection_type'] = $collection->collection_type;
    $collection_receipt['payer_id'] = $collection->payer_id;
    $collection_receipt['email'] = $collection->email;
    $collection_receipt['phone'] = $collection->phone;
    $collection_receipt['user'] = $collection->worker->worker_name;

            //checking if invoice is assigned to mda
    if ($collection->mda) {
        $collection_receipt['mda'] = $collection->mda->mda_name;
    }

            //checking if collection is assign to revenue head
    if ($collection->revenuehead) {
        $collection_receipt['revenue_head'] = $collection->revenuehead->revenue_name;
    }

            //checking if collection is assign to subhead
    if ($collection->subhead) {
        $collection_receipt['sub_head'] = $collection->subhead->subhead_name;
    }

    return $this->response->array(compact('collection_receipt'))->setStatusCode(200);


}

$message = "parameter missing";
return $this->response->array(compact('message'))->setStatusCode(400);


}

    //generating remittance
public function generate_remittance(Request $request)
{
    //Token authentication
    $this->token_auth();

    if ($request->has('user_id') && $request->has('mda')) {

        $worker = $this->worker_id($request->input("user_id"));
        $mda = $this->mda_id($request->input("mda"));

            //check if worker and mda passed exist            
        if (empty($worker) || empty($mda)) {

            return $this->response->errorNotFound();
        }

        $collections = Collection::where("worker_id",$worker)
        ->where("mda_id",$mda)
        ->where("collection_type","pos")
        ->where("collection_status",0)
        ->get();

            //check if there any collection that have not been remited
        if (count($collections) > 0) {

            $amount = "";
            foreach ($collections as $collection) {
                $amount += $collection->amount;
            }

            $request['remittance_key'] = str_random(15);
            $request['amount'] = $amount;
            $request['mda_id'] = $mda;
            $request['worker_id'] = $worker;

                //insert remittance 
            if ($remittance = Remittance::create($request->all())) {

                    //updateing collection table with remittance_id
                Collection::where('worker_id',$worker)
                ->where('mda_id',$mda)
                ->update(['remittance_id'=>$remittance->id,'collection_status'=>1]);


                $remittance_receipt['remittance_no'] = $remittance->remittance_key;
                $remittance_receipt['mda'] = $remittance->mda->mda_name;
                $remittance_receipt['user'] = $remittance->worker->worker_name;
                $remittance_receipt['amount'] = $remittance->amount;
                $remittance_receipt['remittance_status'] = $remittance->remittance_status;

                return $this->response->array(compact('remittance_receipt'))->setStatusCode(200);
            }

            $message = "unable to generate remittance";
            return $this->response->array(compact('message'))->setStatusCode(400);

        }

        $message = "No generate remittance";
        return $this->response->array(compact('message'))->setStatusCode(400);
    }

    $message = "parameter missing";
    return $this->response->array(compact('message'))->setStatusCode(400);
}

    //login pos user api
public function user_login(Request $request)
{
    //Token authentication
    $this->token_auth();
    
    if ($request->has("phone")&&$request->has("pin")&&$request->has("pos_key")) {
        $user_login = Worker::where("phone",$request->input("phone"))
        ->where("pin",$request->input("pin"))
        ->first();

        //check if the user exist
    if (! $user_login) {
          return $this->response->errorNotFound();
    }

        //checking if user is assigned to MDA
      if ($user_login->mda_id == 0) {
        $message = "User not assigned to MDA";
        return $this->response->array(compact('message'))->setStatusCode(401);
    }

    //check if pos is not found
    $pos_check = $this->pos_check($request->input("pos_key"));
    if (empty($pos_check)) {

        return $this->response->errorNotFound();
    }

    if ($pos_check->activation != 1) {
      $message = "POS not activated";
      return $this->response->array(compact('message'))->setStatusCode(401);
    }

    //check if user is assigned to mda
    if($user_login->mda_id != $pos_check->mda_id){

        $message = "User does not belong to the MDA";
        return $this->response->array(compact('message'))->setStatusCode(401);
    }


    //return user credentials
    $credential["user_id"] = $user_login->worker_key;
    $credential["name"] = $user_login->worker_name;
    $credential["phone"] = $user_login->phone;
    $credential["email"] = $user_login->email;
    $credential["pos_key"] = $pos_check->pos_key;

    $credential["mda_name"] = $user_login->mda->mda_name;
    $credential["mda_id"] = $user_login->mda->mda_key;

    $credential["pin"] = $user_login->pin;

    return $this->response->array(compact('credential'))->setStatusCode(200);
}

$message = "parameter missing";
return $this->response->array(compact('message'))->setStatusCode(400);
}

//activation of pos
public function pos_activation(Request $request)
{

    //Token authentication
    $this->token_auth();

    //get the incoming parameter
    $pos_code = $request->only("activation_code");

    //check if the parameter exist and activated
    $pos_activation = Postable::where("activation_code",$pos_code)->first();
    if ($pos_activation->activation == "1") {
     $message = "Pos Already activated";
     return $this->response->array(compact('message'))->setStatusCode(401);
 }

    //Activating the pos by updating
 $pos_activation->activation = 1;
 $pos_activation->save();

    //return pos details
 $pos_details['pos_key'] = $pos_activation->pos_key;
 $pos_details['pos_imei'] = $pos_activation->pos_imei;
 $pos_details['name'] = $pos_activation->name;
 $pos_details['activation'] = $pos_activation->activation;
 $pos_details['mda_key'] = $this->mda_key($pos_activation->mda_id);

 return $this->response->array(compact('pos_details'))->setStatusCode(200);
}



/////////////////////////////////////////////////private class

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

//////////////////////////////////////////////////////////////////////////////Private class end

}
