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

class IgrEbillsApiController extends Controller
{
	use Helpers;

    //Getting ebills biller details
    public function index(Request $request)
    {

    	$response['info'] = $request->input("Message");
    	$response['car'] = "yes";
    	$response['look'] = "yes";
    	$content = view('xml.biller', compact('response'));

    	return response($content, 400)
    	    ->header('Content-Type', 'application/xml');
    }
}
