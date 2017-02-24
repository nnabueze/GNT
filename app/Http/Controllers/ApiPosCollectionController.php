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

class ApiPosCollectionController extends Controller
{
	use Helpers;

        //post collections via 
	public function pos_collection(Request $request)
	{

        //Token authentication
		$this->token_auth();

            //validating request
		if ($request->has('name') && $request->has('phone')&&$request->has('payer_id')&&$request->has('mda')&&$request->has('revenue_head')
			&&$request->has('amount')&&$request->has('user_key')&&$request->has('start_date')&&$request->has('end_date')&&$request->has('pos_key')
			&&$request->has('tax')) {

                //generating for collect and getting mda auto incremental id.
		$request['collection_key'] = str_random(15);
		$request['mda_id'] = $this->mda_id($request->input("mda"));
		$request['revenuehead_id'] = $this->revenue_id($request->input("revenue_head"));
		$request['worker_id'] = $this->worker_id($request->input("user_key"));
		$request['postable_id'] = $this->pos_id($request->input("pos_key"));
		$request['collection_type'] = "pos";
		if ($request->input("subhead")) {

			$request['subhead_id'] = $this->subhead_id($request->input("subhead"));
		}
				//checking the MDA paramter
		if (empty($request['mda_id'])) {
			$message = "Invalid MDA key";
			return $this->response->array(compact('message'))->setStatusCode(400);
		}

		//checking if the revenue is valid
		if (empty($request['revenuehead_id'])) {
			$message = "Invalid revenue key";
			return $this->response->array(compact('message'))->setStatusCode(400);
		}


         //check if worker and mda passed exist            
		if ( empty($request['worker_id']) ) {

			$message = "Invalid user key";
			return $this->response->array(compact('message'))->setStatusCode(400);
		}

		if (empty($request['postable_id'])) {
			$message = "Invalid pos key";
			return $this->response->array(compact('message'))->setStatusCode(400);
		}


				//check if user is assign to MDA
		if (!$pos = $this->pos_check($request->input("pos_key"))) {
			$message = "Invalid pos key";
			return $this->response->array(compact('message'))->setStatusCode(400);
		}

		if (!$user = $this->user_check($request->input("user_key"))) {
			$message = "Invalid user key";
			return $this->response->array(compact('message'))->setStatusCode(400);
		}



		if ($pos->mda_id != $user->mda_id) {
			$message = "User is not assign to mda";
			return $this->response->array(compact('message'))->setStatusCode(400);
		}


        //checking for user limit
		$limit = Collection::where("worker_id",$request['worker_id'])->where("remittance_id",0)->get();
		if ($limit->sum("amount") >= $user->user_limit) {

			$message = "collection Limit exceeded";
			return $this->response->array(compact('message'))->setStatusCode(400);
		}


        //checking for uses remittance status
        if ($remittance = Remittance::where("worker_id",$request['worker_id'])->where("remittance_status",0)->first()) {
        	$message = "Not remitted";
        	return $this->response->array(compact('message'))->setStatusCode(400);
        }

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
    /////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
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

//getting pos key
private function pos_id($mda_key)
{
    if ($mda = Postable::where("pos_key",$mda_key)->first()) {
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
