<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Notification;
use Illuminate\Http\Response;

class NotificationController extends Controller
{
    //endpoint notification for mCash from Ercas
    public function mCash(Request $request)
    {
    	$request['paymentType'] = 'mcash';

        $check_sessionID = Notification::where("SessionID", $request->input('SessionID'))->first();

        if ($check_sessionID != null) {
            return Response(['error' => 'Record already exist'], 400);
        }

        $mcash = Notification::create($request->all());


    	return response('Ok', 200);

    }

    //enpoint notification for ussd
    public function ussd()
    {
    	$request['paymentType'] = 'ussd';

        $check_sessionID = Notification::where("SessionID", $request->input('SessionID'))->first();

        if ($check_sessionID != null) {
            return Response(['error' => 'Record already exist'], 400);
        }

        $mcash = Notification::create($request->all());


        return response('Ok', 200);
    }

    //endpoint notification for centralpay
    public function centralpay(Request $request)
    {
        $text = json_encode($request->all());
        $file = fopen(base_path('test.txt'),"w");
        fwrite($file,$text);
        fclose($file);
    	// $request['paymentType'] = 'centralpay';

     //    $check_sessionID = Notification::where("SessionID", $request->input('SessionID'))->first();

     //    if ($check_sessionID != null) {
     //        return Response(['error' => 'Record already exist'], 400);
     //    }

     //    $mcash = Notification::create($request->all());


     //    return response('Ok', 200);
    }
}
