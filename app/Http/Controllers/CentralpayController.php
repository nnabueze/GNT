<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use DB;
use Session;
use Validator;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Notification;
//////////////////////////////
//Design By Eze 08035400839 //
//                          //
/////////////////////////////

class CentralpayController extends Controller
{

     //displaying form for testing purpose
    public function index()
    {
    	return view('pay.centralpay');
    }

    public function pay(Request $request)
    {
    	
        //Assigning Mandatary post data to array
        $params = $this->get_mandatary_param($request);

        //check if NIBSS Merchant id exist
        $users = $this->getSecretId($params['merchant_id']);

        //saving param from customer
        $customerParam = $this->saveParam($params);

    	

        //Attaching NIBSS Merchant ID
        $params['merchant_id'] = $users->merchantId;
        $params['hash'] = $this->hash_make($request, $users->secretKey, $params);
    	$query_string = $this->str_query($params);
         

    	$ch = curl_init();
    	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
    	curl_setopt($ch, CURLOPT_URL,'https://staging.nibss-plc.com.ng/CentralPayPlus/pay?'.$query_string);     
    	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    	curl_setopt($ch, CURLOPT_FOLLOWLOCATION, false);
    	curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
    	curl_setopt($ch, CURLOPT_HEADER, 0);

    	$repos = curl_exec ($ch);
    	$redirectURL = curl_getinfo($ch, CURLINFO_EFFECTIVE_URL);
    	curl_close ($ch);
    	return redirect($redirectURL);
    }

        //return URL
    public function response(Request $request)
    {
        //check if item is return from NIBSS
        if (!$request->input('merchant_id')) {

            //check if NIBSS Merchant id exist
            $users = $this->getSecretId('NIBSS0000000045');

            return redirect($users->cancel_url);
        }

        //checking the status of the transaction
        $params = $request->all();
        $params['hash'] = $this->hash_make_response($request);
        $query_string = $this->str_query($params);

        $url = 'https://staging.nibss-plc.com.ng/CentralPayPlus/merchantTransQueryJSON?'.$query_string;
        $response = $this->curlInfo($url);

        $response['Amount'] = $this->kobo_to_naira($response['Currency'], $response['Amount']);
        

        if ($response['ResponseCode'] == '000') {

            
            //updating record
            $customer_details = $this->updateRecord($response,'success');
            //echo "yes";die;
            //echo "<pre>";print_r($response);die;

            $users = $this->getSecretId($response['MerchantId']);

            //post notification to client
            //$notification = $this->notify($users->notification_url, $response);

            $redirect_url = $users->response_url;

            return redirect($redirect_url);
        }else{

            //updating record
            $customer_details = $this->updateRecord($response, 'failed');
            //echo "no";die;
             //echo "<pre>";print_r($response);die;


            $users = $this->getSecretId($response['MerchantId']);

            //post notification to client
            //$notification = $this->notify($users->notification_url, $response);

            $redirect_url = $users->response_url;

            return redirect($redirect_url);

        }


            
    }

        //posting data to merchant notification url
    private function notify($url, $data)
    {
        $data = json_encode($data);

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
        curl_setopt($ch, CURLOPT_URL,$url);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS,$data);  //Post Fields
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_NOSIGNAL, 1);
        curl_setopt($ch, CURLOPT_TIMEOUT_MS, 100);

        $headers = array();
        $headers[] = 'Content-Type: application/json';
        $headers[] = 'Accept: application/json';
        //$headers[] = 'Authorization: Bearer '.$token;
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

        $pin = curl_exec ($ch);
        curl_close ($ch);
    }

    //updating record
    public function updateRecord($response,$status)
    {
        $status1 = Notification::where('SessionID',$response['TransactionId'])->first();
        $status1->status = $status;
        $status1->ReferenceCode = $response['CPAYRef'];
        $status1->TransactionDate = $response['TransDate'];
        $status1->save();

        return $status;
    }

    public function curlInfo($url){
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
        curl_setopt($ch, CURLOPT_URL,$url);     
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($ch, CURLOPT_HEADER, 0);

        $repos = curl_exec ($ch);
        $redirectURL = curl_getinfo($ch, CURLINFO_EFFECTIVE_URL);
        curl_close ($ch);

        //decoding NIBSS JSON response
        $repos1 =  json_decode($repos, true);

        return $repos1;

    }

    public function saveParam($requestParam)
    {
        $data_array =[
        'SessionID' => $requestParam['transaction_id'],
        'PayerPhoneNumber' => $requestParam['phone'],
        'amount' => $this->kobo_to_naira($requestParam['currency'], $requestParam['amount']),
        'PayerName' => $requestParam['name'],
        'paymentType' => 'centralpay',
        ];

        //check if transaction exit
        $tranCheck = Notification::Where('SessionID',$requestParam['transaction_id'])->first();

        if ($tranCheck != null) {
            Session::flash('error','Encounter an Error, Transaction ID exist');
            return view('errors.503');
        }

        $customer = Notification::create($data_array);

        return $customer;
    }

    //generating Hash data for transaction status
    private function hash_make_response($request)
    {

        //checking if cpay_ref is returned
        if ($request->input('cpay_ref')) {
            $hash = $request->input('transaction_id').$request->input('cpay_ref').$request->input('merchant_id')."DD39CAB9976D86B31EB80B6F9560ABE0";
        }else{
            $hash = $request->input('transaction_id').$request->input('merchant_id')."DD39CAB9976D86B31EB80B6F9560ABE0";
        }
        return hash('sha256', $hash);
    }

    //test cancel url
    public function cancel(Request $request)
    {
        echo "<pre>";
        print_r($request->all());
        die;

        $customer_details = $this->updateRecord($request->all());

        $users = $this->getSecretId();

        //post notification to client
        //$notification = $this->notify($users->cancel_url, $response);

        $redirect_url = $users->cancel_url;

        return redirect($redirect_url);
    }

    //testing cancelation
    public function test()
    {
        echo "cancel transaction";
        die;

        $options = [
            'trace' => true,
            'cache_wsdl' => WSDL_CACHE_NONE
        ];

        $client = new \SoapClient("http://127.0.0.1:8080/mlottery_wgpe/wgpe/ServicePort", $options);

        
    }


    //test sucess url
    public function success(Request $request)
    {
        echo "<pre>";
        print_r($request->all());
        die;
    }

    //getting merchant id and secretkey
    public function getSecretId($merchant_id){
    	$users = DB::table("merchantid")->where("merchantId",$merchant_id)->first();

        return $users;
    }

    //geting all posted parameter
    private function get_mandatary_param($request)
    {
        $params = array();
        $params['merchant_id'] = $request->input('merchant_id');
        $params['product_id'] = $request->input('product_id');
        $params['product_description'] = $request->input('product_description');
        $params['amount'] = $this->naira_to_kobo($request->input('currency'), $request->input('amount'));
        $params['currency'] = $request->input('currency');
        $params['transaction_id'] = $request->input('transaction_id');
        $params['name'] = $request->input('name');
        $params['phone'] = $request->input('phone');
        $params['response_url'] = url('pay/response');
        $params['cancel_url'] = url('pay/cancel');

        //print_r($params);die;

        return $params;
    }

        //converting naira to kobo
    private function naira_to_kobo($currency, $naira)
    {
        $amount = "";
        if ($currency == '566') {
            $amount = $naira * 100;
        }else{
            $amount = $naira;
        }
        

        return $amount;
    }

        //converting kobo to naira
    private function kobo_to_naira($currency, $naira)
    {
        $amount = "";
        if ($currency == '566') {
            $amount = $naira / 100;
        }else{
            $amount = $naira;
        }
        

        return $amount;   
    }


    //generating Hash data to passed to NIBSS
    Private function hash_make($request, $param, $merchant)
    {
        /*print_r($request->input('response_url'));
        die;*/
    	$hash = $merchant['merchant_id'].$request->input('product_id').$request->input('product_description').$merchant['amount'].$request->input('currency').$request->input('transaction_id').$merchant['response_url'].$param;
    	return hash('sha256', $hash);
    }

    //Building a query from array
    private function str_query($params)
    {
    	$str = http_build_query($params);
    	return $str;
    }

}
