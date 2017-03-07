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
use App\Ebillremittance;
use App\Collection;
use Carbon\Carbon;
use App\Http\Requests;
use App\Http\Controllers\Controller;

class ApiGenerateRemittance extends Controller
{
    use Helpers;


        //generating remittance
    public function generate_remittance(Request $request)
    {
        //Token authentication
        $this->token_auth();

        if ($request->has('user_id') && $request->has('mda')&& $request->has('pos_key')) {

            $worker = $this->worker_id($request->input("user_id"));
            $mda = $this->mda_id($request->input("mda"));

            if (!$pos =$this->pos_check($request->input("pos_key"))) {
            	$message = "invalid pos key";
            	return $this->response->array(compact('message'))->setStatusCode(400);
            }

            //checking if user is assign to the MDA
            if ($pos->mda_id != $mda) {
            	$message = "User not assigned to MDA";
            	return $this->response->array(compact('message'))->setStatusCode(400);
            }

                //check if worker and mda passed exist            
            if (empty($worker) || empty($mda)) {

                return $this->response->errorNotFound();
            }

            $collections = Collection::where("worker_id",$worker)
            ->where("mda_id",$mda)
            ->where("collection_type","pos")
            ->where("remittance_id",0)
            ->get();

                //check if there any collection that have not been remited
            if (count($collections) > 0) {

                $request['remittance_key'] = str_random(15);
                $request['amount'] = $collections->sum("amount");
                $request['mda_id'] = $mda;
                $request['worker_id'] = $worker;

                    //insert remittance 
                if (!$remit= Remittance::where("worker_id",$worker)->where("remittance_status",0)->first()) {

                	$remittance = Remittance::create($request->all());

                    //updateing collection table with remittance_id
                    Collection::where('worker_id',$worker)
                    ->where('mda_id',$mda)
                    ->update(['remittance_id'=>$remittance->id,'collection_status'=>1]);


                    $remittance_receipt['remittance_no'] = $remittance->remittance_key;
                    $remittance_receipt['mda'] = $remittance->mda->mda_name;
                    $remittance_receipt['pos_user'] = $remittance->worker->worker_name;
                    $remittance_receipt['amount'] = $remittance->amount;
                    $remittance_receipt['remittance_status'] = $remittance->remittance_status;

                    return $this->response->array(compact('remittance_receipt'))->setStatusCode(200);
                }else{

                	$remittance_receipt['remittance_no'] = $remit->remittance_key;
                	$remittance_receipt['mda'] = $remit->mda->mda_name;
                	$remittance_receipt['pos_user'] = $remit->worker->worker_name;
                	$remittance_receipt['amount'] = $remit->amount;
                	$remittance_receipt['remittance_status'] = $remit->remittance_status;

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
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

    //clear remittance
    public function clear_remittance(Request $request)
    {
        //Token authentication
        $this->token_auth();

        //check if request has the parameter
        if ($request->has('user_key') && $request->has('refcode')&& $request->has('pos_key')) {
            
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

            //check if the reference code exist
            if ($remit = Ebillremittance::where("refcode",$request->input('refcode'))->first()) {

                //update the remittance status to 1
                if ($remit_status = Remittance::where("remittance_key",$remit->remittance_code)->first()) {

                    //checking if remittance have been cleared before
                   $remit_status->update(['remittance_status' => 1]);

                   $message ="Remittance cleared!";

                   //return response
                   return $this->response->array(compact("message"))->setStatusCode(200);
                }
                

                $message = "remittance code does not exist";
                return $this->response->array(compact('message'))->setStatusCode(400);
            }

            
            $message = "Invalid Refence code";
            return $this->response->array(compact('message'))->setStatusCode(400);
            
        }

        $message = "parameter missing";
        return $this->response->array(compact('message'))->setStatusCode(400);

    }


//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

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
