<?php

namespace App\Http\Controllers;

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


        switch ($json['Step']) {
            case "2":
                $item = $this->create_tin($json);
                return $item;
            break;
            case "1":
                $item = $this->non_tax($json);
                return $item;

            break;
            case "4":
                $item = $this->step_4($json);
                return $item;

            break;
            case "5":
                $item = $this->step_5($json);
                return $item;

            break;
            case "#":

            break;
            default:

        }
    }

    /////////////////////////////////////////////////////////////////////////////////////////////////////////////

    //creating a temporary tin
    private function create_tin($param)
    {
        //getting all the param
        $data['BillerID'] = $param['BillerID'];
        $data['BillerName'] = $param['BillerName'];
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

            if ($param['Param'][$i]['Key'] == "address") {
                $data['address'] = $param['Param'][$i]['Value'];
            }
        }

        //check if the biller exist
        if (!$data['igr_id'] = $this->igr_id($data['BillerID'])) {
         
            $message = "Biller does not exist";
            $code = '401';
            $error = $this->error_response($message, $code, $param['Step']);
            return $error;

        }

        //checking if the parameter are set
        if (empty($data['address']) || empty($data['name']) || empty($data['phone']) || empty($data['BillerID'])) {

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
            $tin['NextStep'] = 3;
            $tin['ResponseCode'] = 200;
            if ($tem_tin->email) {

                $tin['email'] = $tem_tin->email;
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
        //getting parameter
        $data['BillerID'] = $param['BillerID'];
        $data['subhead_id'] = $param['HeadID'];
        $data['Refcode'] = $param['Refcode'];
        for ($i=0; $i <count($param['Param']) ; $i++) { 

            if ($param['Param'][$i]['Key'] == "start") {
                $data['start_date'] = $param['Param'][$i]['Value'];
            }

            if ($param['Param'][$i]['Key'] == "end") {
                $data['end_date'] = $param['Param'][$i]['Value'];
            }

            if ($param['Param'][$i]['Key'] == "amount") {
                $data['amount'] = $param['Param'][$i]['Value'];
            }
        }

        //checking for parameter
        if (empty($data['subhead_id']) || empty($data['start_date']) || empty($data['end_date']) || empty($data['amount'])) {

            $message = "Parameter missing";
            $code = '401';
            $error = $this->error_response($message, $code, $param['Step']);
            return $error;
        }

        //updating record
        if ($collection = Collection::where("collection_key", $data['Refcode'])->first()) {
            $collection->update(['start_date' => $data['start_date'],
                "end_date"=>$data['end_date'],"amount"=>$data['amount'],"subhead_id"=>$data['subhead_id']]);
/*            print_r($collection);
            die;*/

            
            $item['refcode'] = $collection->collection_key;
            $item['name'] = $collection->name;
            $item['payerID'] = $collection->payer_id;
            $item['phone'] = $collection->phone;
         
                $item['mda'] = $this->mda_name($collection->mda_id);

            $item['subhead'] = $this->subhead($data['subhead_id']);
            $item['period'] = $data['start_date']."-". $data['end_date'];
            $item['amount'] = $data['amount'];
           

            $content = view('xml.step_5', compact('item'));

            return response($content, 200)
                ->header('Content-Type', 'application/xml');
        }

        //return response
        $message = "Unable to record data";
        $code = '401';
        $error = $this->error_response($message, $code, $param['Step']);
        return $error;
    }



    //////////////////////////////////////////////////////////////////////////////////////////////////////////////

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
    private function error_response($message, $code, $step)
    {
        
        $response['NextStep'] = $step;
        $response['ResponseCode'] = $code;
        $response['ErrorMessage'] = $message;

        $formatter = Formatter::make($response, Formatter::ARR);
        $car  = $formatter->toXml();

        return response($car, 400)
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

    ///////////////////////////////////////////////////////////////////////////////////////////////////////////////

    //getting mda name
    private function subhead($mda_key)
    {
        if ($mda = Subhead::where("id",$mda_key)->first()) {
                # code...
            return $mda->subhead_name;
        }
    }
}
