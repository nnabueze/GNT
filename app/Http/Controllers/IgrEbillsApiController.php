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
    /*	print_r($json);
    	die;*/

    	$response['info'] = $json['BillerID'];
    	$response['car'] = "yes";
    	$response['look'] = "yes";
    	//$content = view('xml.biller', compact('response'));

    	$formatter = Formatter::make($json, Formatter::ARR);
    	$car  = $formatter->toXml();

    	return response($car, 400)
    	    ->header('Content-Type', 'application/xml');
    }
}
