<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Auth;
use JWTAuth;
use Validator;
use Image;
use Response;
use Tymon\JWTAuth\Exceptions\JWTException;
use Dingo\Api\Routing\Helpers;
use App\User;
use App\Invoice;
use App\Revenuehead;
use App\Mda;
use App\Worker;
use App\Postable;
use App\Subhead;
use App\Tin;
use App\Igr;
use DB;
use App\Remittance;
use App\Collection;
use Carbon\Carbon;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use SoapBox\Formatter\Formatter;

class IgrEbillsApiController extends Controller
{
	use Helpers;

    //Getting ebills biller details
    public function index(Request $request)
    {
        $jsonString = $request->getContent();
        $formatter = Formatter::make($jsonString, Formatter::XML);
        $json  = $formatter->toArray();

        $json_en = json_encode($json);

        //loging ebils request
        DB::table('ebilslogs')->insert(
            ['log' => $json_en]
        );



        //looping through param to check if page is passed
        for ($i=0; $i <count($json['Param']) ; $i++) {

            if ($json['Param'][$i]['Key'] == "page") {
                 $param['page'] = $json['Param'][$i]['Value'];
            }
        }

        //checking if step is set
        if (!isset($json['Step'])) {
           $message = "current step missing";
           $code = '401';
           $error = $this->error_response($message, $code, $json['Step']);
           return $error;
        }

        //checking if page is set
        if (!isset($param['page'])) {
           $message = "current Page no missing";
           $code = '401';
           $error = $this->error_response($message, $code, $json['Step']);
           return $error;
        }

        //creating refoce or temperateary tin
        if ($json['Step'] == 1 && $param['page'] == 1) {
            $item = $this->create_tin($json);
            return $item;
        }

        //non tax collection
        if ($json['Step'] == 2 && $param['page'] == 4) {
            $item = $this->step_5($json);
            return $item;
        }

        //tax collection validation
        if ($json['Step'] == 1 && $param['page'] == 6) {
            $item = $this->tax($json);
            return $item;
        }

        //tax collection
        if ($json['Step'] == 2 && $param['page'] == 8) {
            $item = $this->step_9($json);
            return $item;
        }

        //Invoice collection 
        if ($json['Step'] == 1 && $param['page'] == 10) {
            $item = $this->invoice($json);
            return $item;
        }

        //remittance collection
        if ($json['Step'] == 1 && $param['page'] == 12) {
            $item = $this->remittance($json);
            return $item;
        }


        $message = "Invalid Step and page passed";
        $code = '401';
        $error = $this->error_response($message, $code, $json['Step']);
        return $error;


    }

    /////////////////////////////////////////////////////////////////////////////////////////////////////////////

    //creating a temporary tin
    private function create_tin($param)
    {
        //getting all the param
        $data['BillerID'] = $param['BillerID'];
        $data['BillerName'] = $param['BillerName'];
        for ($i=0; $i <count($param['Param']) ; $i++) {

            if ($param['Param'][$i]['Key'] == "ercasBillerId") {
                 $data['ercasBillerId'] = $param['Param'][$i]['Value'];
             } 

            if ($param['Param'][$i]['Key'] == "name") {
                $data['name'] = $param['Param'][$i]['Value'];
            }

            if ($param['Param'][$i]['Key'] == "phone") {
                $data['phone'] = $param['Param'][$i]['Value'];
            }

            if ($param['Param'][$i]['Key'] == "email") {
                $data['email'] = $param['Param'][$i]['Value'];
            }

            if ($param['Param'][$i]['Key'] == "address") {
                $data['address'] = $param['Param'][$i]['Value'];
            }
        }

        //check if the biller exist
        if (!$data['igr_id'] = $this->igr_id($data['ercasBillerId'])) {
         
            $message = "Biller does not exist";
            $code = '401';
            $error = $this->error_response($message, $code, $param['Step']);
            return $error;

        }

        //checking if the parameter are set
        if (empty($data['address']) || empty($data['name']) || empty($data['phone']) || empty($data['ercasBillerId'])) {

            $message = "Parameter missing";
            $code = '401';
            $error = $this->error_response($message, $code, $param['Step']);
            return $error;
        }

        //check if the phone number exist
        if ($phone = Tin::where("phone", $data['phone'])->first()) {

            $message = "Phone number already exist";
            $code = '401';
            $error = $this->error_response($message, $code, $param['Step']);
            return $error;
        }

        //checking if number is 11digit
        if (strlen((string)$data['phone']) != 11) {

           $message = "Phone number must be 11 digit";
           $code = '401';
           $error = $this->error_response($message, $code, $param['Step']);
           return $error;
        }

        //avoiding error email is empty
        if (empty($data['email'])) {
            $data['email'] = null;
        }

        //generate random number and temperary tin
        $data['temporary_tin'] = $this->random_number(11);
        $data['tin_key'] = str_random(15);

        //checking if generated temporary tin exist
        if (! $tin_tempoary = Tin::where("temporary_tin", $data['temporary_tin'])->first()) {
            
            $tem_tin = Tin::create($data);
            $tin['refcode'] =   $tem_tin->temporary_tin;
            $tin['name'] =  $tem_tin->name;
            $tin['phone'] = $tem_tin->phone;
            $tin['address'] = $tem_tin->address;
            $tin['NextStep'] = 2;
            $tin['ResponseCode'] = "00";

            for ($i=0; $i <count($param['Param']) ; $i++) { 

                if ($param['Param'][$i]['Key'] == "email") {
                    $data['email_1'] = $param['Param'][$i]['Value'];
                }
            }
            if (isset($data['email_1'])) {

                $tin['email'] = $data['email_1'];
            }
            
            $content = view('xml.create_tin', compact('tin'));

            return response($content, 200)
                ->header('Content-Type', 'application/xml');
        }

        //return error response
        $message = "Unable to register";
        $code = '401';
        $error = $this->error_response($message, $code, $param['Step']);
        return $error;

    }

    /////////////////////////////////////////////////////////////////////////////////////////////////////////////

    //PAYMENT OF NON TAX
    private function non_tax($param)
    {
        //getting parameter
        $BillerID = $param['BillerID'];

        //check the parmeter is missing
        if (empty($BillerID)) {

            $message = "Parameter missing";
            $code = '401';
            $error = $this->error_response($message, $code, $param['Step']);
            return $error;
        }


        //check if biller exist
        if (!$igr = Igr::with("mdas")->where("igr_key", $BillerID)->first()) {

            $message = "Bileer does not exist";
            $code = '401';
            $error = $this->error_response($message, $code, $param['Step']);
            return $error;
        }

        //return response
        $mda['mda'] = $igr->mdas;
        $mda['NextStep'] = 4;
        $tin['ResponseCode'] = "00";

        $content = view('xml.list_mda', compact('mda'));

        return response($content, 200)
            ->header('Content-Type', 'application/xml');

    }

    /////////////////////////////////////////////////////////////////////////////////////////////////////////////

    //Non Tax step 4
    private function step_4($param)
    {

        //getting params
        $data['mda_id'] = $param['MdaID'];
        for ($i=0; $i <count($param['Param']) ; $i++) { 

            if ($param['Param'][$i]['Key'] == "name") {
                $data['name'] = $param['Param'][$i]['Value'];
            }

            if ($param['Param'][$i]['Key'] == "phone") {
                $data['phone'] = $param['Param'][$i]['Value'];
            }

            if ($param['Param'][$i]['Key'] == "email") {
                $data['email'] = $param['Param'][$i]['Value'];
            }

            if ($param['Param'][$i]['Key'] == "payer_id") {
                $data['payer_id'] = $param['Param'][$i]['Value'];
            }
        }

        //check if param is missing
        if (empty($data['payer_id']) || empty($data['name']) || empty($data['phone']) || empty($data['mda_id'])) {

            $message = "Parameter missing";
            $code = '401';
            $error = $this->error_response($message, $code, $param['Step']);
            return $error;
        }

        //checking if mda exist
        if (!$mda = Mda::find($data['mda_id'])) {

            $message = "Mda does not exist";
            $code = '401';
            $error = $this->error_response($message, $code, $param['Step']);
            return $error;
        }

        //generating refcode and random number
        $data['collection_key'] = $this->random_number(11);
        $data['collection_type'] = "ebills";

        //inserting record
        if ($collection = Collection::create($data)) {
            $item['subheads'] = $mda->subheads;
            $item['NextStep'] = 5;
            $item['refcode'] = $collection->collection_key;
            $tin['ResponseCode'] = "00";

            $content = view('xml.subhead_list', compact('item'));

            return response($content, 200)
                ->header('Content-Type', 'application/xml');
        }

        $message = "Unable to validate record";
        $code = '401';
        $error = $this->error_response($message, $code, $param['Step']);
        return $error;
    }



    //////////////////////////////////////////////////////////////////////////////////////////////////////////////

    //non tax step 5
    private function step_5($param)
    {
        //getting param
        $data['BillerID'] = $param['BillerID'];
        for ($i=0; $i <count($param['Param']) ; $i++) { 

            if ($param['Param'][$i]['Key'] == "ercasBillerId") {
                $data['ercasBillerId'] = $param['Param'][$i]['Value'];
            }

            if ($param['Param'][$i]['Key'] == "name") {
                $data['name'] = $param['Param'][$i]['Value'];
            }

            if ($param['Param'][$i]['Key'] == "phone") {
                $data['phone'] = $param['Param'][$i]['Value'];
            }

            if ($param['Param'][$i]['Key'] == "mda") {
                $data['mda'] = $param['Param'][$i]['Value'];
            }

            if ($param['Param'][$i]['Key'] == "subhead") {
                $data['subhead'] = $param['Param'][$i]['Value'];
            }

            if ($param['Param'][$i]['Key'] == "startdate") {
                $data['start_date'] = $param['Param'][$i]['Value'];
            }

            if ($param['Param'][$i]['Key'] == "enddate") {
                $data['end_date'] = $param['Param'][$i]['Value'];
            }

            if ($param['Param'][$i]['Key'] == "amount") {
                $data['amount'] = $param['Param'][$i]['Value'];
            }

            if ($param['Param'][$i]['Key'] == "payerid") {
                $data['payerid'] = $param['Param'][$i]['Value'];
            }

            if ($param['Param'][$i]['Key'] == "lga") {
                $data['lga'] = $param['Param'][$i]['Value'];
            }

        }

        //assigning lga varible to mda
        if (isset($data['lga'])) {
            $data['mda'] = $data['lga'];
        }

        //checking missing param
        if (empty($data['ercasBillerId']) || empty($data['payerid']) || empty($data['start_date']) || empty($data['end_date']) || empty($data['amount'])
            || empty($data['name']) || empty($data['phone']) || empty($data['mda']) || empty($data['subhead'])) {

            $message = "Parameter missing";
            $code = '401';
            $error = $this->error_response($message, $code, $param['Step']);
            return $error;
        }

        //validation
        $data['igr_id'] = $this->igr_id($data['ercasBillerId']);
        $data['mda_id'] = $this->mda_id($data['mda']);
        $data['subhead_id'] = $this->subhead_id($data['subhead']);
        

        //checking if mda, igr and subhead exist
        if (empty($data['igr_id']) || empty($data['mda_id']) || empty($data['subhead_id'])) {

            $message = "Mda or Subhead does not exist";
            $code = '401';
            $error = $this->error_response($message, $code, $param['Step']);
            return $error;
        }


        $data['mda_name'] = $this->mda_name($data['mda_id']);
        $data['mda_category'] = $this->mda_category($data['mda']);
        $data['subhead_name'] = $this->subhead($data['subhead_id']);
        
        //checking if MDA belong to biller
        if (!$mda = Mda::where("igr_id",$data['igr_id'])->find($data['mda_id'])) {
            $message = "Mda does not belong to biller";
            $code = '401';
            $error = $this->error_response($message, $code, $param['Step']);
            return $error;
        }

        //genrating random number
        $data['collection_key'] = $this->random_number(11);
        $data['collection_type'] = "ebills";
        $data['tax'] = 0;
        $data['NextStep'] = 3;
        $data['ResponseCode'] = "00";

            $content = view('xml.tax_collection', compact('data'));

            return response($content, 200)
                ->header('Content-Type', 'application/xml');
    }



    //////////////////////////////////////////////////////////////////////////////////////////////////////////////
    //tax
    private function tax($param)
    {
        /*print_r($param);
        die;*/
        //getting param
        $BillerID = $param['BillerID'];

        for ($i=0; $i <count($param['Param']) ; $i++) { 

            if ($param['Param'][$i]['Key'] == "tin") {
                $tin = $param['Param'][$i]['Value'];
            }

            if ($param['Param'][$i]['Key'] == "ercasBillerId") {
                $biller = $param['Param'][$i]['Value'];
            }
        }

        //checkng missing param
        if (empty($tin) || empty($biller)) {

            $message = "Parameter missing";
            $code = '401';
            $error = $this->error_response($message, $code, $param['Step']);
            return $error;
        }

        //Getting biller auto incremental id
        $igr_id = $this->igr_id($biller);
        if (empty($igr_id)) {

            $message = "Biller does not exist";
            $code = '401';
            $error = $this->error_response($message, $code, $param['Step']);
            return $error;
        }

        //validating param
        if ($tin_detains = Tin::where("tin_no",$tin)->orwhere("temporary_tin",$tin)->where("igr_id",$igr_id)->first()) {
            $item['name'] = $tin_detains->name;
            $item['ercasBillerId'] = $biller;
            $item['NextStep'] = 2;
            $item['phone'] = $tin_detains->phone;
            $item['tin'] = $tin;
            $item['page'] = 8;
            $item['ResponseCode'] = "00";

            $content = view('xml.tax', compact('item'));

            return response($content, 200)
                ->header('Content-Type', 'application/xml');
           
        }

        //returning response
        $message = "Invalid Tin No";
        $code = '401';
        $error = $this->error_response($message, $code, $param['Step']);
        return $error;
    }
    /////////////////////////////////////////////////////////////////////////////////////////////////////////////
    
    //step_9 tax collection
    private function step_9($param)
    {
        //getting param
        $data['BillerID'] = $param['BillerID'];

        for ($i=0; $i <count($param['Param']) ; $i++) { 

            if ($param['Param'][$i]['Key'] == "name") {
                $data['name'] = $param['Param'][$i]['Value'];
            }

            if ($param['Param'][$i]['Key'] == "ercasBillerId") {
                $data['ercasBillerId'] = $param['Param'][$i]['Value'];
            }

            if ($param['Param'][$i]['Key'] == "tin") {
                $data['Tin'] = $param['Param'][$i]['Value'];
            }

            if ($param['Param'][$i]['Key'] == "lga") {
                $data['lga'] = $param['Param'][$i]['Value'];
            }

            if ($param['Param'][$i]['Key'] == "phone") {
                $data['phone'] = $param['Param'][$i]['Value'];
            }

            if ($param['Param'][$i]['Key'] == "mda") {
                $data['mda'] = $param['Param'][$i]['Value'];
            }

            if ($param['Param'][$i]['Key'] == "subhead") {
                $data['subhead'] = $param['Param'][$i]['Value'];
            }

            if ($param['Param'][$i]['Key'] == "startdate") {
                $data['start_date'] = $param['Param'][$i]['Value'];
            }

            if ($param['Param'][$i]['Key'] == "enddate") {
                $data['end_date'] = $param['Param'][$i]['Value'];
            }

            if ($param['Param'][$i]['Key'] == "email") {
                $data['email'] = $param['Param'][$i]['Value'];
            }

            if ($param['Param'][$i]['Key'] == "amount") {
                $data['amount'] = $param['Param'][$i]['Value'];
            }
        }

        if (isset($data['lga'])) {
            $data['mda'] = $data['lga'];
        }

        //checking missing param
        if (empty($data['ercasBillerId']) || empty($data['Tin']) || empty($data['start_date']) || empty($data['end_date']) || empty($data['amount'])
            || empty($data['name']) || empty($data['phone']) || empty($data['subhead']) || empty($data['mda'])) {

            $message = "Parameter missing";
            $code = '401';
            $error = $this->error_response($message, $code, $param['Step']);
            return $error;
        }

        //validation
        $data['igr_id'] = $this->igr_id($data['ercasBillerId']);
        if (isset($data['mda'])) {
            $data['mda_id'] = $this->mda_id($data['mda']);
        }else{
            $data['mda_id'] = $this->mda_id($data['lga']);
        }
        
        $data['subhead_id'] = $this->subhead_id($data['subhead']);
        

        //checking if mda, igr and subhead exist
        if (empty($data['igr_id']) || empty($data['mda_id']) || empty($data['subhead_id'])) {

            $message = "Mda or Subhead does not exist";
            $code = '401';
            $error = $this->error_response($message, $code, $param['Step']);
            return $error;
        }

        $data['mda_name'] = $this->mda_name($data['mda_id']);
        if (isset($data['mda'])) {
            $data['mda_category'] = $this->mda_category($data['mda']);
        }else{
            $data['mda_category'] = $this->mda_category($data['lga']);
        }
        
        $data['subhead_name'] = $this->subhead($data['subhead_id']);
        
        //checking if MDA belong to biller
        if (!$mda = Mda::where("igr_id",$data['igr_id'])->find($data['mda_id'])) {
            $message = "Mda does not belong to biller";
            $code = '401';
            $error = $this->error_response($message, $code, $param['Step']);
            return $error;
        }

        //genrating random number
        $data['collection_key'] = $this->random_number(11);
        $data['collection_type'] = "ebills";
        $data['tax'] = 1;
        $data['NextStep'] = 4;
        $data['ResponseCode'] = "00";

            $content = view('xml.tax_collection', compact('data'));

            return response($content, 200)
                ->header('Content-Type', 'application/xml');
      

    }

    ////////////////////////////////////////////////////////////////////////////////////////////////////////////

    //invoice payment
    private function invoice($param)
    {
        //getting parameters
        $data['BillerID'] = $param['BillerID'];

        for ($i=0; $i <count($param['Param']) ; $i++) { 

            if ($param['Param'][$i]['Key'] == "invoicenumber") {
                $data['Invoice'] = $param['Param'][$i]['Value'];
            }

            if ($param['Param'][$i]['Key'] == "ercasBillerId") {
                $data['ercasBillerId'] = $param['Param'][$i]['Value'];
            }
        }

        //checkinng for missing parameter
        if (empty($data['ercasBillerId']) || empty($data['Invoice'])) {

            $message = "Parameter missing";
            $code = '401';
            $error = $this->error_response($message, $code, $param['Step']);
            return $error;
        }

        if (!$biller = $this->igr_id($data['ercasBillerId'])) {

            $message = "Biller does not exist";
            $code = '401';
            $error = $this->error_response($message, $code, $param['Step']);
            return $error;
        }

        //validating
        if (!$invoice = Invoice::where("invoice_key", $data['Invoice'])->first()) {

            $message = "Invoice number does not exist";
            $code = '401';
            $error = $this->error_response($message, $code, $param['Step']);
            return $error;
        }

        //return response
        $data['NextStep'] = 2;
        $data['name'] = $invoice->name;
        $data['phone'] = $invoice->phone;
        $data['amount'] = $invoice->amount;
        $data['mda_name'] = $this->mda_name($invoice->mda_id);
        $data['mda_category'] = $this->mda_category($invoice->mda_id);
        $data['subhead_name'] = $this->subhead($invoice->subhead_id);
        $data['ResponseCode'] = "00";



        $data['mda'] = $this->mda_key($invoice->mda_id);
        $data['subhead'] = $this->subhead_key($invoice->subhead_id);

        $content = view('xml.invoice', compact('data'));

        return response($content, 200)
            ->header('Content-Type', 'application/xml');

    }


    ////////////////////////////////////////////////////////////////////////////////////////////////////////////
    //remittance 
        private function remittance($param)
    {
        //getting parameters
        $data['BillerID'] = $param['BillerID'];
        
        for ($i=0; $i <count($param['Param']) ; $i++) { 

            if ($param['Param'][$i]['Key'] == "Remittance") {
                $data['Remittance'] = $param['Param'][$i]['Value'];
            }

            if ($param['Param'][$i]['Key'] == "ercasBillerId") {
                $data['ercasBillerId'] = $param['Param'][$i]['Value'];
            }
        }

        //checkinng for missing parameter
        if (empty($data['ercasBillerId']) || empty($data['Remittance'])) {

            $message = "Parameter missing";
            $code = '401';
            $error = $this->error_response($message, $code, $param['Step']);
            return $error;
        }

        if (!$biller = $this->igr_id($data['ercasBillerId'])) {

            $message = "Biller does not exist";
            $code = '401';
            $error = $this->error_response($message, $code, $param['Step']);
            return $error;
        }

        //validating
        if (!$remittance = Remittance::where("remittance_key", $data['Remittance'])->first()) {

            $message = "remittance number does not exist";
            $code = '401';
            $error = $this->error_response($message, $code, $param['Step']);
            return $error;
        }

        //checking remittance code have been used
        if ($remittance->remittance_status == 1) {
            $message = "remittance code have been used";
            $code = '401';
            $error = $this->error_response($message, $code, $param['Step']);
            return $error;
        }

        //worker details
        $worker = $this->worker($remittance->worker_id);

        //genarating recode
        $data['refcode'] = $this->random_number(11);

        //return response
        $data['NextStep'] = 2;
        $data['name'] = $worker->worker_name;
        $data['phone'] = $worker->phone;
        $data['amount'] = $remittance->amount;
        $data['mda_name'] = $this->mda_name($remittance->mda_id);
        $data['mda_category'] = $this->mda_category($remittance->mda_id);
        $data['ResponseCode'] = "00";



        $data['mda'] = $this->mda_key($remittance->mda_id);

        $content = view('xml.remittance', compact('data'));

        return response($content, 200)
            ->header('Content-Type', 'application/xml');

    }

    ///////////////////////////////////////////////////////////////////////////////////////////////////////////

    //generating random number
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

    /////////////////////////////////////////////////////////////////////////////////////////////////////////////

    //getting biller(IGR) serial id
    private function igr_id($igr_key)
    {
        if ($igr = Igr::where("igr_key",$igr_key)->first()) {
                   
            return $igr->id;
        }
    }

    /////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    private function error_response($message, $code, $step=null)
    {
        
        $response['NextStep'] = $step;
        $response['ResponseCode'] = $code;
        $response['ResponseMessage'] = $message;

        $content = view('xml.error', compact('response'));

        return response($content, 200)
            ->header('Content-Type', 'application/xml');
    }

    //////////////////////////////////////////////////////////////////////////////////////////////////////////////

    //getting mda name
    private function mda_name($mda_key)
    {
        if ($mda = Mda::where("id",$mda_key)->first()) {
                # code...
            return $mda->mda_name;
        }
    }

    //////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    //getting mda key
    private function mda_key($mda_key)
    {
        if ($mda = Mda::where("id",$mda_key)->first()) {
                # code...
            return $mda->mda_key;
        }
    }

    //////////////////////////////////////////////////////////////////////////////////////////////////////////////////
        //getting mda category
    private function mda_category($mda_key)
    {
        if ($mda = Mda::where("mda_key",$mda_key)->first()) {
                # code...
            return $mda->mda_category;
        }
    }

    ///////////////////////////////////////////////////////////////////////////////////////////////////////////////
    //getting mda id
    private function mda_id($mda_key)
    {
        if ($mda = Mda::where("mda_key",$mda_key)->first()) {
                # code...
            return $mda->id;
        }
    }

    ///////////////////////////////////////////////////////////////////////////////////////////////////////////////

    //getting subhead name
    private function subhead($mda_key)
    {
        if ($mda = Subhead::where("id",$mda_key)->first()) {
                # code...
            return $mda->subhead_name;
        }
    }

    /////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    //getting subhead key
    private function subhead_key($mda_key)
    {
        if ($mda = Subhead::where("id",$mda_key)->first()) {
                # code...
            return $mda->subhead_key;
        }
    }

    //////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    //getting mda name
    private function subhead_id($mda_key)
    {
        if ($mda = Subhead::where("subhead_key",$mda_key)->first()) {
                # code...
            return $mda->id;
        }
    }

    //////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

    //getting worker details
    private function worker($mda_key)
    {
        if ($mda = Worker::where("id",$mda_key)->first()) {
                # code...
            return $mda;
        }
    }

    ////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

    //checking if subhead belong mda
    private function mda_head($subhead, $mda)
    {
        $sub = Subhead::find($subhead);

        if ($sub->mda_id == $mda) {
            return True;
        }

        return False;
    }



}
