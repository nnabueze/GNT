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
use App\Igr;
use Carbon\Carbon;
use App\Http\Requests;
use App\Http\Controllers\Controller;

class IgrMobileController extends Controller
{
    use Helpers;
    /////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////API LOGIN
    //Login User
    public function authentication(Request $request)
    {
    	$credentials = $request->only('email', 'password');

        $data = array();

        try{
            if (! $token = JWTAuth::attempt($credentials)) {
                return $this->response->errorUnauthorized();
            }  

        } catch (JWTException $e) {
              return $this->response->errorInternal();
        }

        if (Auth::attempt(['email' => $request->input("email"), 'password' => $request->input("password")])) {

            $firstday_last_date = date('Y-m-d', strtotime('first day of last month'));
            $lastday_last_date = date('Y-m-d', strtotime('last day of last month'));
            $firstday_curent_date = date('Y-m-d', strtotime(date('Y-m-1')));
            $yestarday = date('Y-m-d',strtotime("-1 days"));
            $today = date('Y-m-d',time());

            $igr = Igr::with("mdas")->find(Auth::user()->igr_id);

            $data['last_month'] = 0;
            $data['current_month'] = 0;
            $data['yestarday'] = 0;
            $data['today'] = 0;

            foreach ($igr->mdas as $mda) {

                $last_months = Collection::where("mda_id",$mda->id)->whereDate("created_at",">=", $firstday_last_date)
                                ->whereDate("created_at","<=",$lastday_last_date)->get();

                if (count($last_months) > 0) {
                    foreach ($last_months as $collection) {

                        $data['last_month'] += $collection->amount;
                    }
                }
                

                $current_months = Collection::where("mda_id",$mda->id)->whereDate("created_at",">=", $firstday_curent_date)->get();
                if (count($current_months) > 0) {
                    foreach ($current_months as $current_month) {

                        $data['current_month'] += $current_month->amount;
                    }
                }



                $yestarday_date = Collection::where("mda_id",$mda->id)->whereDate("created_at","=", $yestarday)->get();
                if (count($yestarday_date) > 0) {
                    foreach ($yestarday_date as $yestarday_date) {

                        $data['yestarday'] += $yestarday_date->amount;
                    }
                }

                $today_date = Collection::where("mda_id",$mda->id)->whereDate("created_at",">=", $today)->get();
                if (count($today_date) > 0) {
                    foreach ($today_date as $today_date) {

                        $data['today'] += $today_date->amount;
                    }
                }
            }
        }

        $data['token'] = $token;
        $data['igr_key'] = $igr->igr_key;
        $data['state_name'] = $igr->state_name;
        $data['logo'] = $igr->logo;
        $data['billerId'] = Auth::user()->igr_id;

        return $this->response->array(compact('data'))->setStatusCode(200);
    }

    public function getMdas(Request $request){

        //Token authentication
        $this->token_auth();

        if ( ! $request->has("billerId")) {
            $message = "parameter missing";
            return $this->response->array(compact('message'))->setStatusCode(400);
        }

            $igr = Igr::with("mdas")->find($request->input("billerId"));

            $info = array();
            $today = date('Y-m-d',time());    
            

            foreach ($igr->mdas as $mda) {

                $data['amount'] = 0;
                $data["name"] = "";

                $today_date = Collection::where("mda_id",$mda->id)->whereDate("created_at",">=", $today)->get();
                if (count($today_date) > 0) {
                    foreach ($today_date as $today_date) {

                        $data['amount']+= $today_date->amount;
                    }
                }
                $data["name"] = $mda->mda_name;
                array_push($info, $data);
            }

        return $this->response->array(compact('info'))->setStatusCode(200);


    }

    //getting monthly remittance
    public function getRemittanceStatus(Request $request){
        $this->token_auth();

        if ( ! $request->has("billerId")) {
            $message = "parameter missing";
            return $this->response->array(compact('message'))->setStatusCode(400);
        }

        $firstday_last_date = date('Y-m-d', strtotime('first day of last month'));
        $lastday_last_date = date('Y-m-d', strtotime('last day of last month'));
        $firstday_curent_date = date('Y-m-d', strtotime(date('Y-m-1')));

        $igr = Igr::with("mdas")->find(Auth::user()->igr_id);

        $data['last_month_remitted'] = 0;
        $data['current_month_remitted'] = 0;
        $data['last_month'] = 0;
        $data['current_month'] = 0;

        foreach ($igr->mdas as $mda) {

            $last_months = Remittance::where("mda_id",$mda->id)->where("remittance_status",1)->whereDate("created_at",">=", $firstday_last_date)
                            ->whereDate("created_at","<=",$lastday_last_date)->get();

            if (count($last_months) > 0) {
                foreach ($last_months as $collection) {

                    $data['last_month_remitted'] += $collection->amount;
                }
            }
            

            $current_months = Remittance::where("mda_id",$mda->id)->where("remittance_status",1)->whereDate("created_at",">=", $firstday_curent_date)->get();
            if (count($current_months) > 0) {
                foreach ($current_months as $current_month) {

                    $data['current_month_remitted'] += $current_month->amount;
                }
            }



            $yestarday_date = Remittance::where("mda_id",$mda->id)->where("remittance_status",0)->whereDate("created_at",">=", $firstday_last_date)
                            ->whereDate("created_at","<=",$lastday_last_date)->get();
            if (count($yestarday_date) > 0) {
                foreach ($yestarday_date as $yestarday_date) {

                    $data['last_month'] += $yestarday_date->amount;
                }
            }

            $today_date = Remittance::where("mda_id",$mda->id)->where("remittance_status",0)->whereDate("created_at",">=", $firstday_curent_date)->get();
            if (count($today_date) > 0) {
                foreach ($today_date as $today_date) {

                    $data['current_month'] += $today_date->amount;
                }
            }
        }

        return $this->response->array(compact('data'))->setStatusCode(200);
    }

    //getting pos collection
    public function getPosCollection(Request $request){

         if ( ! $request->has("billerId")) {
            $message = "parameter missing";
            return $this->response->array(compact('message'))->setStatusCode(400);
        }

        $firstday_last_date = date('Y-m-d', strtotime('first day of last month'));
        $lastday_last_date = date('Y-m-d', strtotime('last day of last month'));
        $firstday_curent_date = date('Y-m-d', strtotime(date('Y-m-1')));
        $yestarday = date('Y-m-d',strtotime("-1 days"));
        $today = date('Y-m-d',time());

        $igr = Igr::with("mdas")->find(Auth::user()->igr_id);

        $data['last_month'] = 0;
        $data['current_month'] = 0;
        $data['yestarday'] = 0;
        $data['today'] = 0;

        foreach ($igr->mdas as $mda) {

            $last_months = Collection::where("mda_id",$mda->id)->where("collection_type","pos")->whereDate("created_at",">=", $firstday_last_date)
                            ->whereDate("created_at","<=",$lastday_last_date)->get();

            if (count($last_months) > 0) {
                foreach ($last_months as $collection) {

                    $data['last_month'] += $collection->amount;
                }
            }
            

            $current_months = Collection::where("mda_id",$mda->id)->where("collection_type","pos")->whereDate("created_at",">=", $firstday_curent_date)->get();
            if (count($current_months) > 0) {
                foreach ($current_months as $current_month) {

                    $data['current_month'] += $current_month->amount;
                }
            }



            $yestarday_date = Collection::where("mda_id",$mda->id)->where("collection_type","pos")->whereDate("created_at","=", $yestarday)->get();
            if (count($yestarday_date) > 0) {
                foreach ($yestarday_date as $yestarday_date) {

                    $data['yestarday'] += $yestarday_date->amount;
                }
            }

            $today_date = Collection::where("mda_id",$mda->id)->where("collection_type","pos")->whereDate("created_at",">=", $today)->get();
            if (count($today_date) > 0) {
                foreach ($today_date as $today_date) {

                    $data['today'] += $today_date->amount;
                }
            }
        }

        return $this->response->array(compact('data'))->setStatusCode(200);
    }

    //getting ebills collection
    public function getEbills(Request $request){
         if ( ! $request->has("billerId")) {
            $message = "parameter missing";
            return $this->response->array(compact('message'))->setStatusCode(400);
        }

        $firstday_last_date = date('Y-m-d', strtotime('first day of last month'));
        $lastday_last_date = date('Y-m-d', strtotime('last day of last month'));
        $firstday_curent_date = date('Y-m-d', strtotime(date('Y-m-1')));
        $yestarday = date('Y-m-d',strtotime("-1 days"));
        $today = date('Y-m-d',time());

        $igr = Igr::with("mdas")->find(Auth::user()->igr_id);

        $data['last_month'] = 0;
        $data['current_month'] = 0;
        $data['yestarday'] = 0;
        $data['today'] = 0;

        foreach ($igr->mdas as $mda) {

            $last_months = Collection::where("mda_id",$mda->id)->where("collection_type","ebills")->whereDate("created_at",">=", $firstday_last_date)
                            ->whereDate("created_at","<=",$lastday_last_date)->get();

            if (count($last_months) > 0) {
                foreach ($last_months as $collection) {

                    $data['last_month'] += $collection->amount;
                }
            }
            

            $current_months = Collection::where("mda_id",$mda->id)->where("collection_type","ebills")->whereDate("created_at",">=", $firstday_curent_date)->get();
            if (count($current_months) > 0) {
                foreach ($current_months as $current_month) {

                    $data['current_month'] += $current_month->amount;
                }
            }



            $yestarday_date = Collection::where("mda_id",$mda->id)->where("collection_type","ebills")->whereDate("created_at","=", $yestarday)->get();
            if (count($yestarday_date) > 0) {
                foreach ($yestarday_date as $yestarday_date) {

                    $data['yestarday'] += $yestarday_date->amount;
                }
            }

            $today_date = Collection::where("mda_id",$mda->id)->where("collection_type","ebills")->whereDate("created_at",">=", $today)->get();
            if (count($today_date) > 0) {
                foreach ($today_date as $today_date) {

                    $data['today'] += $today_date->amount;
                }
            }
        }

        return $this->response->array(compact('data'))->setStatusCode(200);
    }

    //getting remittance
    public function getRemittance(Request $request){
        //Token authentication
        $this->token_auth();

        if ( ! $request->has("billerId")) {
            $message = "parameter missing";
            return $this->response->array(compact('message'))->setStatusCode(400);
        }

            $igr = Igr::with("mdas")->find($request->input("billerId"));

            $info = array();
            $start = new Carbon('first day of last month');            

            foreach ($igr->mdas as $mda) {

                $remittances = Remittance::with("Worker")->where("mda_id",$mda->id)->where("created_at",">=", $start)->orderBy('created_at', 'ASC')->get();
                if (count($remittances) > 0) {
                    foreach ($remittances as $remittance) {
                       $data['id'] = $remittance->remittance_key;
                       $data['amount'] = $remittance->amount;
                       $data['remiteDate'] = $remittance->remtted_date;
                       $data['genDate'] = $remittance->created_at;
                       $data['name'] = $remittance->worker->worker_name;
                       if ($remittance->remittance_status == 1) {
                           $data['status'] = "Remitted";
                       }else{
                            $data['status'] = "******";
                       }
                       array_push($info, $data); 
                    }                    
                }
                
            }

        return $this->response->array(compact('info'))->setStatusCode(200);
    }

    public function getInvoice(Request $request){
        //Token authentication
        $this->token_auth();

        if ( ! $request->has("billerId")) {
            $message = "parameter missing";
            return $this->response->array(compact('message'))->setStatusCode(400);
        }

            $igr = Igr::with("mdas")->find($request->input("billerId"));

            $info = array();
            $start = new Carbon('first day of last month');            

            foreach ($igr->mdas as $mda) {

                $invoices = Invoice::where("mda_id",$mda->id)->where("created_at",">=", $start)->orderBy('created_at', 'ASC')->get();
                if (count($invoices) > 0) {
                    foreach ($invoices as $invoice) {
                       $data['id'] = $invoice->invoice_key;
                       $data['amount'] = $invoice->amount;
                       if ($invoice->invoice_status == 1) {
                           $data['status'] = "Paid";
                       }else{
                            $data['status'] = "******";
                       }
                       array_push($info, $data); 
                    }                    
                }
                
            }

        return $this->response->array(compact('info'))->setStatusCode(200);
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
}
