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
use App\Tin;
use App\Subhead;
use App\Remittance;
use App\Collection;
use Carbon\Carbon;
use App\Http\Requests;
use App\Http\Controllers\Controller;

class ApiTinController extends Controller
{
	use Helpers;

   //verifying tin
	public function verify_tin(Request $request)
	{
   	//Token authentication
		$this->token_auth();

		if ($request->has("user_key")&&$request->has("tin")&&$request->has("pos_key")) {

   		//check if user and pos key is right
			if (!$user = $this->user_check($request->input("user_key"))) {
				$message = "User does not exist";
				return $this->response->array(compact('message'))->setStatusCode(401);
			}

			if (!$pos = $this->pos_check($request->input("pos_key"))) {
				$message = "Pos key does not exist";
				return $this->response->array(compact('message'))->setStatusCode(401);
			}

   		//check if user is assigned to mda 
			if ($pos->mda_id != $user->mda_id) {
				$message = "User is not assigned to MDA";
				return $this->response->array(compact('message'))->setStatusCode(401);
			}

   		//verify pin

			if ($tin = Tin::where('tin_no', $request->input("tin"))->orWhere('temporary_tin', $request->input("tin")) ->first()) {

        	//return information
				$tin_details["name"] = $tin->name;
				$tin_details["email"] = $tin->email;
				$tin_details["address"] = $tin->address;
				$tin_details["tin_no"] = $tin->tin_no;
				$tin_details["temporary_tin"] = $tin->temporary_tin;
				$tin_details["phone"] = $tin->phone;

				return $this->response->array(compact('tin_details'))->setStatusCode(200);
			}

			return $this->response->errorNotFound();


		}

		$message = "parameter missing";
		return $this->response->array(compact('message'))->setStatusCode(400);
	}
///////////////////////////////////////////////////////////////////////////////////////////////////Creating tempoarary tin

   //creating temporary tin
	public function temporary_tin(Request $request)
	{

   	//Token authentication
		$this->token_auth();

   	//check if the user and pos key is posed
		if ($request->has("user_key")&&$request->has("pos_key")) {

   			//check if user and pos key is right
			if (!$user = $this->user_check($request->input("user_key"))) {
				$message = "User does not exist";
				return $this->response->array(compact('message'))->setStatusCode(401);
			}

			if (!$pos = $this->pos_check($request->input("pos_key"))) {
				$message = "Pos key does not exist";
				return $this->response->array(compact('message'))->setStatusCode(401);
			}

   			//check if user is assigned to mda 
			if ($pos->mda_id != $user->mda_id) {
				$message = "User is not assigned to MDA";
				return $this->response->array(compact('message'))->setStatusCode(401);
			}

			//check if phone number exist
			if ($check_tin = Tin::where("phone",$request->phone)->first()) {
				$message = "Phone already exist on the platform.";
				return $this->response->array(compact('message'))->setStatusCode(400);
			}

   			//getting igr id
			$igr = Mda::with("igr")->where("id",$pos->mda_id)->first();
			$request['igr_id'] = $igr->id;
			$request['temporary_tin'] = $this->random_number(11);
			$request['tin_key'] = str_random(15);

			//check if the temporary tin exist
			if (! $tin_tempoary = Tin::where("temporary_tin", $request['temporary_tin'])->first()) {
				
				$tem_tin = Tin::create($request->all());
				$tin['tin'] = 	$tem_tin->temporary_tin;
				$tin['name'] = 	$tem_tin->name;
				$tin['phone'] = 	$tem_tin->phone;
				$tin['date'] = 	$tem_tin->created_at;

				return $this->response->array(compact('tin'))->setStatusCode(200);
			}

			$message = "unable to generate Tin try again.";
			return $this->response->array(compact('message'))->setStatusCode(400);

		}

		$message = "parameter missing";
		return $this->response->array(compact('message'))->setStatusCode(400);

	}

 /////////////////////////////////////////////////////////////////////////////////////////////////Private Class

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
