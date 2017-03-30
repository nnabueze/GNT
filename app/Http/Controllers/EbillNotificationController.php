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
use App\Ebillcollection;
use App\Remittancenotification;
use App\Invoicenotification;
use Carbon\Carbon;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use SoapBox\Formatter\Formatter;

class EbillNotificationController extends Controller
{
    public function index(Request $request)
    {
    	$jsonString = $request->getContent();
    	$formatter = Formatter::make($jsonString, Formatter::XML);
    	$json  = $formatter->toArray();

        $json_en = json_encode($json);

        //loging ebils request
        DB::table('ebilsnotificationlogs')->insert(
            ['log' => $json_en]
        );

         $json = $this->param_value($json);

    	//checking notification for collection
    	if (isset($json['tax'])) {

    		$this->collection($json);

    	}

    	//checking notification for remittance
    	if (isset($json['Remittance'])) {
    		
    		$this->remittance($json);
    	}

    	//checking notification for invoice
    	if (isset($json['Invoice'])) {
    		$this->invoice($json);
    	}
    }

    /////////////////////////////////////////////////////////////////////////////////

    //collection api
    private function collection($param)
    {
    	 

    	    if (isset($param['collection_key'])) {
    	        $data['collection_key'] = $param['collection_key'];
    	    }

            if (isset($param['Tin'])) {
                $data['Tin'] = $param['Tin'];
            }

            if (isset($param['collection_type'])) {
                $data['collection_type'] = $param['collection_type'];
            }


            if (isset($param['tax'])) {
                $data['tax'] = $param['tax'];
            }


            if (isset($param['Tin'])) {
                $data['Tin'] = $param['Tin'];
            }

            if (isset($param['name'])) {
                $data['name'] = $param['name'];
            }

    	    if (isset($param['phone'])) {
    	        $data['phone'] = $param['phone'];
    	    }

    	    if (isset($param['mda'])) {
    	        $data['mda'] = $param['mda'];
    	    }

            if (isset($param['lga'])) {
                $data['mda'] = $param['lga'];
            }

    	    if (isset($param['subhead'])) {
    	        $data['subhead'] = $param['subhead'];
    	    }

    	    if (isset($param['period'])) {
    	        $data['period'] = $param['period'];
    	    }

    	    if (isset($param['amount'])) {
    	        $data['amount'] = $param['amount'];
    	    }

            if (isset($param['payer_id'])) {
                $data['payer_id'] = $param['payer_id'];
            }

            if (isset($param['mda_key'])) {
                $data['mda_key'] = $param['mda_key'];
            }

            if (isset($param['subhead_key'])) {
                $data['subhead_key'] = $param['subhead_key'];
            }

            if (isset($param['ercasBillerId'])) {
                $data['ercasBillerId'] = $param['ercasBillerId'];
            }
    	
        $data['igr_id'] = $this->igr_id($data['ercasBillerId']);

        $data['mda_id'] = $this->mda_id($data['mda_key']);
        $data['subhead_id'] = $this->subhead_id($data['subhead_key']);

    	$date_info = explode("/", $data['period']);
    	$data['start_date'] = $date_info[0];
    	$data['end_date'] = $date_info[1];
    	$data['SessionID'] = $param['SessionID'];
    	$data['SourceBankCode'] = $param['SourceBankCode'];
    	$data['DestinationBankCode'] = $param['DestinationBankCode'];

        if ($data['tax'] == 1) {
            $data['payer_id'] = $data['Tin'];
        }


        if ($collection = Collection::create($data)) {

            //insert ebills collection
            $ebillcollection = Ebillcollection::create($data);

            $message = "00";

            $content = view('xml.notification', compact('message'));

            return response($content, 200)
                ->header('Content-Type', 'application/xml');
        }

        $message = 401;
    	
        $content = view('xml.notification_error', compact('message'));

        return response($content, 401)
            ->header('Content-Type', 'application/xml');

 




    }

    /////////////////////////////////////////////////////////////////////////////////

    //notification for remittance
    private function remittance($param)
    {
    	
   


                if (isset($param['name'])) {
                    $data['name'] = $param['name'];
                }

                if (isset($param['phone'])) {
                    $data['phone'] = $param['phone'];
                }

                if (isset($param['mda'])) {
                    $data['mda'] = $param['mda'];
                }


                if (isset($param['amount'])) {
                    $data['amount'] = $param['amount'];
                }

                if (isset($param['mda_key'])) {
                    $data['mda_key'] = $param['mda_key'];
                }

                if (isset($param['Remittance'])) {
                    $data['remittance_key'] = $param['Remittance'];
                }

                if (isset($param['ercasBillerId'])) {
                    $data['ercasBillerId'] = $param['ercasBillerId'];
                }

                if (isset($param['refcode'])) {
                    $data['refcode'] = $param['refcode'];
                }
                

        $data['igr_id'] = $this->igr_id($data['ercasBillerId']);

        $data['mda_id'] = $this->mda_id($param['mda_key']);

    	$data['SessionID'] = $param['SessionID'];
    	$data['SourceBankCode'] = $param['SourceBankCode'];
    	$data['DestinationBankCode'] = $param['DestinationBankCode'];



    	//insert ebills remittance notification table
    	if ($ebillcollection = Remittancenotification::create($data)) {
    		$remittance = Remittance::where("remittance_key", $data['remittance_key'])->first();

    		$remittance->update(['remittance_status'=>1]);

            $message = "00";

            $content = view('xml.notification', compact('message'));

            return response($content, 200)
                ->header('Content-Type', 'application/xml');
    	}

        $message = 401;
        
        $content = view('xml.notification_error', compact('message'));

        return response($content, 401)
            ->header('Content-Type', 'application/xml');
    }

    /////////////////////////////////////////////////////////////////////////////////
    //invoice notifcation
    private function invoice($param)
    {
        
    	
    	


        if (isset($param['name'])) {
            $data['name'] = $param['name'];
        }

        if (isset($param['phone'])) {
            $data['phone'] = $param['phone'];
        }

        if (isset($param['mda'])) {
            $data['mda'] = $param['mda'];
        }


        if (isset($param['amount'])) {
            $data['amount'] = $param['amount'];
        }

        if (isset($param['mda_key'])) {
            $data['mda_key'] = $param['mda_key'];
        }

        if (isset($param['subhead'])) {
            $data['subhead'] = $param['subhead'];
        }

        if (isset($param['mda'])) {
            $data['mda'] = $param['mda'];
        }

        if (isset($param['Invoice'])) {
            $data['invoice_key'] = $param['Invoice'];
        }

        if (isset($param['ercasBillerId'])) {
            $data['ercasBillerId'] = $param['ercasBillerId'];
        }


        $data['igr_id'] = $this->igr_id($data['ercasBillerId']);
        

        $data['mda_id'] = $this->mda_id($param['mda_key']);
        $data['subhead_id'] = $this->subhead_id($param['subhead_key']);

    	$data['SessionID'] = $param['SessionID'];
    	$data['SourceBankCode'] = $param['SourceBankCode'];
    	$data['DestinationBankCode'] = $param['DestinationBankCode'];


    	//insert ebills remittance notification table
    	if ($invoice = Invoicenotification::create($data)) {
    		$remittance = Invoice::where("invoice_key", $data['invoice_key'])->first();

            $remittance->invoice_status = 1;
    		$remittance->save();

            $message = "00";

            $content = view('xml.notification', compact('message'));

            return response($content, 200)
                ->header('Content-Type', 'application/xml');
    	}

        $message = 401;
        
        $content = view('xml.notification_error', compact('message'));

        return response($content, 401)
            ->header('Content-Type', 'application/xml');
    }

    /////////////////////////////////////////////////////////////////////////////////

    //getting biller(IGR) serial id
    private function igr_id($igr_key)
    {
        if ($igr = Igr::where("igr_key",$igr_key)->first()) {
                   
            return $igr->id;
        }
    }

    //////////////////////////////////////////////////////////////////////////////////

    //getting mda id
    private function mda_id($mda_key)
    {
        if ($mda = Mda::where("mda_key",$mda_key)->first()) {
                # code...
            return $mda->id;
        }
    }

    ////////////////////////////////////////////////////////////////////////////////////

    //getting mda name
    private function subhead_id($mda_key)
    {
        if ($mda = Subhead::where("subhead_key",$mda_key)->first()) {
                # code...
            return $mda->id;
        }
    }

    /////////////////////////////////////////////////////////////////////////////////////
    //geting array value
    private function param_value($param)
    {
        
        $data['BillerID'] = $param['BillerID'];
        $data['SessionID'] = $param['SessionID'];
        $data['SourceBankCode'] = $param['SourceBankCode'];
        $data['DestinationBankCode'] = $param['DestinationBankCode'];

            for ($i=0; $i <count($param['Param']) ; $i++) { 

                if ($param['Param'][$i]['Key'] == "Refcode") {
                    $data['collection_key'] = $param['Param'][$i]['Value'];
                }

                if ($param['Param'][$i]['Key'] == "collection_type") {
                    $data['collection_type'] = $param['Param'][$i]['Value'];
                }


                if ($param['Param'][$i]['Key'] == "tax") {
                    $data['tax'] = $param['Param'][$i]['Value'];
                }


                if ($param['Param'][$i]['Key'] == "Tin") {
                    $data['Tin'] = $param['Param'][$i]['Value'];
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

                if ($param['Param'][$i]['Key'] == "lga") {
                    $data['lga'] = $param['Param'][$i]['Value'];
                }

                if ($param['Param'][$i]['Key'] == "subhead") {
                    $data['subhead'] = $param['Param'][$i]['Value'];
                }

                if ($param['Param'][$i]['Key'] == "period") {
                    $data['period'] = $param['Param'][$i]['Value'];
                }

                if ($param['Param'][$i]['Key'] == "amount") {
                    $data['amount'] = $param['Param'][$i]['Value'];
                }

                if ($param['Param'][$i]['Key'] == "payerid") {
                    $data['payer_id'] = $param['Param'][$i]['Value'];
                }

                if ($param['Param'][$i]['Key'] == "mda_key") {
                    $data['mda_key'] = $param['Param'][$i]['Value'];
                }

                if ($param['Param'][$i]['Key'] == "subhead_key") {
                    $data['subhead_key'] = $param['Param'][$i]['Value'];
                }

                if ($param['Param'][$i]['Key'] == "Remittance") {
                    $data['Remittance'] = $param['Param'][$i]['Value'];
                }

                if ($param['Param'][$i]['Key'] == "Invoice") {
                    $data['Invoice'] = $param['Param'][$i]['Value'];
                }

                if ($param['Param'][$i]['Key'] == "ercasBillerId") {
                    $data['ercasBillerId'] = $param['Param'][$i]['Value'];
                }

                if ($param['Param'][$i]['Key'] == "refcode") {
                    $data['refcode'] = $param['Param'][$i]['Value'];
                }
            }


            return $data;
    }
}
