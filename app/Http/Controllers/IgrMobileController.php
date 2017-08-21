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

                $last_months = Collection::where("mda_id",$mda->id)->where("created_at",">=", $firstday_last_date)
                                ->where("created_at","<=",$lastday_last_date)->get();

                if (count($last_months) > 0) {
                    foreach ($last_months as $collection) {

                        $data['last_month'] += $collection->amount;
                    }
                }
                

                $current_months = Collection::where("mda_id",$mda->id)->where("created_at",">=", $firstday_curent_date)->get();
                if (count($current_months) > 0) {
                    foreach ($current_months as $current_month) {

                        $data['current_month'] += $current_month->amount;
                    }
                }



                $yestarday_date = Collection::where("mda_id",$mda->id)->where("created_at","=", $yestarday)->get();
                if (count($yestarday_date) > 0) {
                    foreach ($yestarday_date as $yestarday_date) {

                        $data['yestarday'] += $yestarday_date->amount;
                    }
                }
                print_r($data['yestarday']);die;

                $today_date = Collection::where("mda_id",$mda->id)->where("created_at","=", $today)->get();
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

        return $this->response->array(compact('data'))->setStatusCode(200);
    }
}
