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
use Carbon\Carbon;
use App\Activity;
use App\Farmer;
use App\Worker;
use App\Scheme;
use App\Dealer;
use App\Group;
use App\Report;
use App\Invoice;
use App\Receipt;
use App\Http\Requests;
use App\Http\Controllers\Controller;

class ApiController extends Controller
{
	use Helpers;

    //creating user token
    public function authentication(Request $request)
    {
    	$credentials = $request->only('email', 'password');

    	try{
    		if (! $token = JWTAuth::attempt($credentials)) {
    			return $this->response->errorUnauthorized();
    		}

    	} catch (JWTException $e) {
    		return $this->response->errorInternal();
    	}

    	return $this->response->array(compact('token'))->setStatusCode(200);
    }

    // GET Farmers verification
    public function farmer_verification(Request $request)
    {
        //Token authentication
        $this->token_auth();

        //validating Farmers identification
        $validator = Validator::make($request->only('id','scheme_key','group_key'),[
            'id'=>'alpha_num|max:20|min:20|required',
            'scheme_key'=>'alpha_num|max:20|min:20!required',
            'group_key'=>'alpha_num|max:20|min:20|required'
            ]);

        //checking if validation failed
        if ($validator->fails()) {
            return $this->response->errorBadRequest();
        }

        //$identification = $request->only('id','scheme_key');
        $farmer = Farmer::where('key', $request->input('id'))->first();
        $scheme = Scheme::where('key', $request->input('scheme_key'))->with('activities')->first();
        

        if ( $farmer && $scheme) {

            //checking if farmer is assign
            if ($farmer->assign == 0) {
                return $this->response->error('farmer have not been assign to any group',404);
            }
            $activity = array();
            $farmer_activity = array();
            $farmer = $farmer->toArray();
            //check if farmer have collected input activity
            foreach($scheme->activities as $value){
                $value = $value->toArray();
                $activity = $value;

                //check if farmer have collected from dealer
                $check_dealer = Invoice::where('farmer_key',$farmer['key'])->where('activity_key',$value['key'])->first();
                
                if ($check_dealer) {

                    $activity['dealer_key'] = $check_dealer->dealer_key;
                    $activity['dealer_mark'] = 'yes';

                }else{
                    //$activity['dealer_key'] = $check_dealer->dealer_key;
                    $activity['dealer_mark'] = 'no';
                }

                //check if farmer have been verified by worker
                $check_worker = Receipt::where('farmer_key',$farmer['key'])->where('activity_key',$value['key'])->first();
                if ($check_worker) {

                    $activity['worker_mark'] = 'yes';

                }else{

                    $activity['worker_mark'] = 'no';
                }
                array_push($farmer_activity,$activity);
            //return  $activity;
            }
            $farmer['activity'] = $farmer_activity;

            //check if farmer is within worker group
            if ( ! $check = $this->check_group($request)) {
               return $this->response->error('farmer does not exist in group',404);
           }

           return $this->response->array(compact('farmer'))->setStatusCode(200);
       }

       return $this->response->errorNotFound();

   }

  //worker confirming input after the dealer gives out input
   public function worker_confirmation(Request $request)
   {
    //Token authentication
    $this->token_auth();

    //validating Farmers identification
    $validator = Validator::make($request->only('farmer_key','scheme_key','activity_key','worker_key','dealer_key'),[
        'farmer_key'=>'alpha_num|max:20|min:20|required',
        'scheme_key'=>'alpha_num|max:20|min:20!required',
        'activity_key'=>'alpha_num|max:20|min:20|required',
        'worker_key'=>'alpha_num|max:20|min:20|required',
        'dealer_key'=>'alpha_num|max:20|min:20|required',
        ]);

    //checking if validation failed
    if ($validator->fails()) {
        return $this->response->errorBadRequest();
    }

    //check if the record exist in invoice table
    $invoice = Invoice::where('farmer_key',$request->input('farmer_key'))->where('activity_key',$request->input('activity_key'))->first();
    if (!$invoice) {
        return $this->response->error('Input not collected',404);
    }

    //getting farmer name
    $request['farmer_name'] = $this->farmer_name($request->input('farmer_key'));
    $request['scheme_name'] = $this->scheme_name($request->input('scheme_key'));
    $request['activity_name'] = $this->activity_name($request->input('activity_key'));
    $request['dealer_name'] = $this->dealer_name($request->input('dealer_key'));
    $request['worker_name'] = $this->worker_name($request->input('worker_key'));
    $request['quantity'] = $invoice->quantity;

    if ($receipt= Receipt::create($request->all())) {
        $message = "Input confirmed";
        return $this->response->array(compact('message'))->setStatusCode(200);
    }
    return $this->response->error('Unable to confirm input',404);
}


    // POST Api to login workers
public function login_worker(Request $request)
{
        //Token authentication
    $this->token_auth();
    $validator = Validator::make($request->only('email','password'),['email'=>'email']);

        //checking if validation failed
    if ($validator->fails()) {
        return $this->response->errorBadRequest();
    }

        //check if worker is able to login
    if (Auth::attempt(['email' => $request->email, 'password' => $request->password])) {

            //getting login worker details via email
        $worker = Worker::where('email', Auth::user()->email)->with('scheme','groups')->first();
            //return $worker;
            //check if worker is found
        if (!$worker) {
            return $this->response->errorNotFound();
        }

            //check if worker is assigned
        if( $worker->assign != 1){
            return $this->response->error('Not assigned',404);
        }
            //return worker details
        return $this->response->array(compact('worker'))->setStatusCode(200);
    }

    return $this->response->error('Unable to login',404);
}

    //GET scheme Verification
public function scheme_verification(Request $request)
{
        //Token authentication
    $this->token_auth();
    $validator = Validator::make($request->only('id'),['id'=>'alpha_num|max:20|min:20']);
        //checking if validation failed
    if ($validator->fails()) {
        return $this->response->errorBadRequest();
    }
    $identification = $request->only('id');
    $scheme = Scheme::where('key',$identification)->with('dealers','activities')->first();
    if (!$scheme) {
        return $this->response->errorNotFound();
    }

    return $this->response->array(compact('scheme'))->setStatusCode(200);
}

    //GET Dealer Verification
public function dealer_verification(Request $request)
{
        //Token authentication
    $this->token_auth();
    $validator = Validator::make($request->only('id'),['id'=>'alpha_num|max:20|min:20']);
        //checking if validation failed
    if ($validator->fails()) {
        return $this->response->errorBadRequest();
    }

    $dealer = Dealer::where('key',$request->only('id'))->with('activities')->first();
    if (!$dealer) {
        return $this->response->errorNotFound();
    }

    return $this->response->array(compact('dealer'))->setStatusCode(200);
}

    //POST Rport API
public function report(Request $request)
{
        //Token authentication
    $this->token_auth();
    $validator = Validator::make($request->all(),[
        'key_farmer'=>'alpha_num|max:20|min:20|required',
        'key_worker'=>'alpha_num|max:20|min:20|required',
        'key_activity'=>'alpha_num|max:20|min:20|required',
        'key_scheme'=>'alpha_num|max:20|min:20|required',
        'description'=>'required',
        'key_group'=>'alpha_num|max:20|min:20|required'
        ]);
        //checking if validation failed
    if ($validator->fails()) {
        return $this->response->errorBadRequest();
    }

         //generating key for report
    $request['key'] = str_random(20);

        //check if report key exist
    if (!$group = Group::where('key', $request->input('key_group'))->first()) {
        return $this->response->error('Wrong group passed',400);
    }

    //updating image
    $request['image'] = $this->image_upload($request);

    //inserting a report
    $report =Report::create($request->all());
    if ($report) {

        //attach report to group
        $report->groups()->attach($group->id);
        $report->save();

        return $this->response->created();
    }
    return $this->response->error('something went wrong',400);
}

    //get farmer wihin workers group
public function group_farmer(Request $request)
{
        //Token authentication
    $this->token_auth();


        //check if group exist
    $group = Group::where('key',$request->input('id'))->with('farmers','dealers')->first();

    if (!$group) {
        return $this->response->errorNotFound();
    }

    $farmer_dealer = array();
    //return only farmers in a group
    $farmer_dealer['farmers'] = $group->farmers;
    $farmer_dealer['dealers'] = $group->dealers;

    return $this->response->array(compact('farmer_dealer'))->setStatusCode(200);
}

    //registering farmer via mobile app
public function farmer_reg(Request $request)
{
        //Token authentication
    $this->token_auth();

        //validating the parameter
    $validator = Validator::make($request->all(),[
        'fullname'=>'min:2|required',
        'gender'=>'required',
        'phone'=>'required',
        'state'=>'required',
        'lga'=>'required',
        'crop'=>'required'
        ]);
        //checking if validation failed
    if ($validator->fails()) {
        return $this->response->error('some fields are missing',400);
    }

        //check if name and phone number exist
    $check_farmer = Farmer::where('phone',$request->input('phone'))->first();
    if ($check_farmer) {
        return $this->response->error('Phone already exist',400);
    }

        //generate a random number
    $request['key'] = str_random(20);

        //insartinto db
    $farmer = Farmer::create($request->all());

    if (!$farmer) {
        return $this->response->error('Unable to register Faremer',400);
    }
    $message = 'Successfully created';
    return $this->response->array(compact('message'))->setStatusCode(200);
}


    //Retrieving worker report within the group
public function worker_report(Request $request)
{
        //Token authentication
    $this->token_auth();

    $worker = Worker::where('key',$request->input('id'))->with('groups')->first();

    if (!$worker) {
        return $this->response->errorNotFound();
    }

    $i= 1;
    foreach ($worker->groups as $value) {
     if ($i == 1) {
         $group_id = $value->id;

         break;
     }
 } 

         //getting group report
 $group = Group::where('id',$group_id)->with('reports')->first();
 $report = $group->reports;

 return $this->response->array(compact('report'))->setStatusCode(200);
}

    //getting all modules attached to group
public function group(Request $request)
{
        //Token authentication
    $this->token_auth();

    $group = Group::where("key",$request->input('id'))->with('farmers','workers')->first();
    if (!$group) {
        return $this->response->errorNotFound();
    }

    return $this->response->array(compact('group'))->setStatusCode(200);
}

    //Login api for all user
public function login(Request $request)
{

        //Token authentication
    $this->token_auth();

    $validator = Validator::make($request->only('email','password'),['email'=>'email']);

        //checking if validation failed
    if ($validator->fails()) {
        return $this->response->errorBadRequest();
    }

        //check if worker is able to login
    if (Auth::attempt(['email' => $request->email, 'password' => $request->password])) {

            //check the user type
        switch (Auth::User()->user) {
                case 2: //checkif user is s cheme
                $item1 = Scheme::with('farmers','workers','dealers')->find(Auth::user()->scheme_id);
                $item = Scheme::find(Auth::user()->scheme_id);
                $item['type'] = 'Scheme';
                $item['number_farmer'] = count($item1->farmers);
                $item['number_worker'] = count($item1->workers);
                $item['number_dealer'] = count($item1->dealers);

                //return "yes";

                    //return scheme details
                return $this->response->array(compact('item'))->setStatusCode(200);
                break;
                case 3: //check if user is worker

                    //getting login worker details via email
                $item = Worker::where('email', Auth::user()->email)->with('schemes')->first();
                $group = Group::with('farmers','dealers')->find($item->groups[0]['id']);
                $scheme = Scheme::with('activities')->find($item->schemes[0]['id']);
                $item['type'] = 'Worker';
                $item['number_farmer'] = count($group->farmers);
                $item['number_dealer'] = count($group->dealers);
                $item['number_activity'] = count($scheme->activities);

                //check if worker is assigned
                if( $item->assign != 1){
                    return $this->response->error('Worker have not been assigned to scheme',404);
                }

                   //return worker details
                return $this->response->array(compact('item'))->setStatusCode(200);
                break;
                case 4: //check if user is dealer
                $item = Dealer::where('company_email', Auth::user()->email)->with('schemes')->first();
                $group = Group::with('farmers','workers')->find($item->groups[0]['id']);
                //return $item->dealer_key;
                $supply = Receipt::where('dealer_key',$item->key)->get();
                $item['type'] = 'Dealer';
                $item['number_farmer'] = count($group->farmers);
                $item['number_worker'] = count($group->workers);
                $item['number_supply'] = count($supply);


                //check if worker is assigned
                if( $item->assign != 1){
                    return $this->response->error('Dealer have not been assigned to scheme',404);
                }

                    //return worker details
                return $this->response->array(compact('item'))->setStatusCode(200);
                break;

                default: //give a not found respond
                return $this->response->error('Admin does not have access',400);
            } 

        }
        return $this->response->error('User unable to login',404);
    }

    //getting list of Activity in a scheme
    Public function scheme_activity(Request $request)
    {
        //Token authentication
        $this->token_auth();

        //check if the group id passed exsit
        $scheme = Scheme::where('key',$request->input('id'))->with('activities')->first();

        if (!$scheme) {
            return $this->response->errorNotFound();
        }

        if (!$scheme->activities) {

            return $this->response->error('No activity within Scheme',404);
        }

        return $this->response->array(compact('scheme'))->setStatusCode(200);
    }


    //Geting list of dealers in a group
    public function group_dealer(Request $request)
    {
        //Token authentication
        $this->token_auth();

        $group = Group::where("key",$request->input('id'))->with("dealers")->first();
        if (!$group) {
         return $this->response->errorNotFound();
     }

        //check if any dealer have been assign to the group
     if (!$group->dealers) {
        $group_dealer = 'No assigned dealer in the group';
    }

    $group_dealers = $group->dealers;
    return $this->response->array(compact('group_dealers'))->setStatusCode(200);

}

//getting worker group and scheme by passing worker key
public function worker_group(Request $request)
{
    //Token authentication
    $this->token_auth();

    $worker = Worker::where('key',$request->input('key'))->with('groups','schemes')->first();

    if (!$worker) {
        return $this->response->errorNotFound();
    }
    $worker_group = array();

    //getting group
    foreach ($worker->groups as  $value) {
        $worker_group['group'] = $value;
    }

    //getting scheme
    foreach ($worker->schemes as $value) {
        $worker_group['scheme'] = $value;
    }

    return $this->response->array(compact('worker_group'))->setStatusCode(200);
}

//getting of farmers and dealers in workers group
public function worker_count(Request $request)
{
    //Token authentication
    $this->token_auth(); 

    $worker = Worker::where('key',$request->input('key'))->with('groups','schemes')->first();
    if (!$worker) {
        return $this->response->errorNotFound();
    }
    $group = Group::with('farmers','dealers')->find($worker->groups[0]['id']);
    $scheme = Scheme::with('activities')->find($worker->schemes[0]['id']);

    $worker_count = array();
    $worker_count['number_farmer'] = count($group->farmers);
    $worker_count['number_dealer'] = count($group->dealers);
    $worker_count['number_activity'] = count($scheme->activities);

    return $this->response->array(compact('worker_count'))->setStatusCode(200);

}

//getting activity report base on activity key passed
public function activity_report(Request $request)
{
    //Token authentication
    $this->token_auth();

    //validating Farmers identification
    $validator = Validator::make($request->only('activity_key'),[
        'activity_key'=>'alpha_num|max:20|min:20|required',
        ]);

    //checking if validation failed
    if ($validator->fails()) {
        return $this->response->errorBadRequest();
    }

    $activity = Receipt::where('activity_key',$request->input('activity_key'))->get();

    //return $this->response->array(compact('activity'))->setStatusCode(200);

    //check if report exist
    if (!$activity) {
        return $this->response->error('No Reprt for the activity',404);
    }

    $list_item = array();
    $i = 1;
    foreach ($activity as $value) {
        //get activity name
        $activity_name = Activity::where('key',$value->activity_key)->first();
        $list_item['activity_'. $i] = $activity_name->name;

        //getting dealer activity supplier dealer
        $dealer = Dealer::where('key',$value->dealer_key)->first();
        $list_item[$activity_name->name.'_dealer_'. $i] = $dealer->name_of_company;

        //getting dealer supply quantity
        $list_item[$dealer->name_of_company.'_quantity_'. $i] = count($value->dealer_key);
        $i++;
    }

    return $this->response->array(compact('list_item'))->setStatusCode(200);
}

//getting list of farmers within the scheme by passing scheme key
public function scheme_farmers(Request $request)
{
    //Token authentication
    $this->token_auth();

    $scheme = Scheme::where('key',$request->input('key'))->with('farmers')->first();

    if (!$scheme) {
        return $this->response->errorNotFound();
    }
    $farmers = $scheme->farmers;
    return $this->response->array(compact('farmers'))->setStatusCode(200);
}

//getting list of workers in a scheme
public function scheme_workers(Request $request)
{
    //Token authentication
    $this->token_auth();

    $scheme = Scheme::where('key',$request->input('key'))->with('workers')->first();

    if (!$scheme) {
        return $this->response->errorNotFound();
    }
    $workers = $scheme->workers;
    return $this->response->array(compact('workers'))->setStatusCode(200);

}

//getting list of dealers in a scheme
public function scheme_dealers(Request $request)
{
    //Token authentication
    $this->token_auth();

    $scheme = Scheme::where('key',$request->input('key'))->with('dealers')->first();

    if (!$scheme) {
        return $this->response->errorNotFound();
    }
    $dealers = $scheme->dealers;
    return $this->response->array(compact('dealers'))->setStatusCode(200);

}

//getting the number of farmers, worker and dealers
public function scheme_count(Request $request)
{
   //Token authentication
   $this->token_auth();
   $scheme = Scheme::where('key',$request->input('key'))->with('dealers','farmers','workers')->first(); 

   if (!$scheme) {
      return $this->response->errorNotFound();
  }

  $scheme_count= array();
  $scheme_count['number_farmer'] = count($scheme->farmers);
  $scheme_count['number_worker'] = count($scheme->workers);
  $scheme_count['number_dealer'] = count($scheme->dealers);

  return $this->response->array(compact('scheme_count'))->setStatusCode(200);


}

//get report of scheme by passing scheme key
public function scheme_report(Request $request)
{
    //Token authentication
    $this->token_auth();
    $scheme = Scheme::where('key',$request->input('key'))->first(); 

    if (!$scheme) {
       return $this->response->errorNotFound();
   }

   $report = array();


    //getting scheme report base on current month
    //$report_current_month = Receipt::where('scheme_key', $request->input('key'))->where('created_at', '>=', Carbon::now()->subMonth())->get();
   $report_current_month = Receipt::where('scheme_key', $request->input('key'))
   ->where('created_at', '>=', Carbon::now()->startOfMonth())
   ->where('created_at', '<=', Carbon::now()->endOfMonth())
   ->get();

   $report['current_month'] = count($report_current_month);

    //report base on current week
   $report_current_week = Receipt::where('scheme_key', $request->input('key'))
   ->where('created_at', '>=', Carbon::now()->startOfWeek())
   ->where('created_at', '<=', Carbon::now()->endOfWeek())
   ->get();

   $report['current_week'] = count($report_current_week);

    //getting report base on present day
   $report_current_day = Receipt::where('scheme_key', $request->input('key'))
   ->where('created_at', '>=', Carbon::now()->startOfDay())
   ->where('created_at', '<=', Carbon::now()->endOfDay())
   ->get();

   $report['current_day'] = count($report_current_day);

    //Getting total scheme report
   $report_total = Receipt::where('scheme_key', $request->input('key'))
   ->get();

   $report['current_total'] = count($report_total);



   return $this->response->array(compact('report'))->setStatusCode(200);

}

//getting report details base on time passed
public function scheme_reportDetails(Request $request)
{
    //Token authentication
    $this->token_auth();
    $scheme = Scheme::where('key',$request->input('key'))->with('activities')->first(); 

    //return $scheme;

    if (!$scheme) {
       return $this->response->errorNotFound();
   }

   if (!$request->input('report')) {
    return $this->response->errorBadRequest();
}
$report = array();
$item = array();
    //check the report time to deliver
switch ($request->input('report')) {
    case 'current_month':
    foreach ($scheme->activities as $value) {
        $count_report = Receipt::where('scheme_key', $request->input('key'))
        ->where('activity_key',$value->key)
        ->where('created_at', '>=', Carbon::now()->startOfMonth())
        ->where('created_at', '<=', Carbon::now()->endOfMonth())
        ->get();

        $item['name'] = $value->name;
        $item['count'] = count($count_report);

        array_push($report, $item);
    }
    break;
    case 'current_week':
    foreach ($scheme->activities as $value) {
        $count_report = Receipt::where('scheme_key', $request->input('key'))
        ->where('activity_key',$value->key)
        ->where('created_at', '>=', Carbon::now()->startOfWeek())
        ->where('created_at', '<=', Carbon::now()->endOfWeek())
        ->get();

        $item['name'] = $value->name;
        $item['count'] = count($count_report);

        array_push($report, $item);
    }
    break;
    case 'current_day':
    foreach ($scheme->activities as $value) {
        $count_report = Receipt::where('scheme_key', $request->input('key'))
        ->where('activity_key',$value->key)
        ->where('created_at', '>=', Carbon::now()->startOfDay())
        ->where('created_at', '<=', Carbon::now()->endOfDay())
        ->get();

        $item['name'] = $value->name;
        $item['count'] = count($count_report);

        array_push($report, $item);
    }
    break;

    case 'current_total':
    foreach ($scheme->activities as $value) {
        $count_report = Receipt::where('scheme_key', $request->input('key'))
        ->where('activity_key',$value->key)
        ->get();

        $item['name'] = $value->name;
        $item['count'] = count($count_report);

        array_push($report, $item);
    }
    break;

    default:
    return $this->response->error('No Report for the specified time',404);
}

return $this->response->array(compact('report'))->setStatusCode(200);
}

//getting number of dealers supply
public function dealer_supply(Request $request)
{
    $this->token_auth();

        //validating Farmers identification
    $validator = Validator::make($request->only('dealer_key'),[
        'dealer_key'=>'alpha_num|max:20|min:20|required'
        ]);

    //checking if validation failed
    if ($validator->fails()) {
        return $this->response->errorBadRequest();
    }

    //check if the dealer exist
    $dealer = Dealer::where('key', $request->input('dealer_key'))->with('activities')->first();

    if (! $dealer || ! $dealer->activities) {
      return $this->response->errorNotFound();
    }
    
        $supply = array();
        $item = array();
        foreach ($dealer->activities as $value) {
            $report = Receipt::where('activity_key', $value->key)->get();
            $item['activity_name'] = $value->name;
            $item['count'] = count($report);

            array_push($supply, $item);
        }
   return $this->response->array(compact('supply'))->setStatusCode(200);
}

//farmer Verification by dealer
public function farmer_dealerVerification(Request $request)
{
  $this->token_auth();

      //validating Farmers identification
  $validator = Validator::make($request->only('id','dealer_key','group_key'),[
      'id'=>'alpha_num|max:20|min:20|required',
      'dealer_key'=>'alpha_num|max:20|min:20!required',
      'group_key'=>'alpha_num|max:20|min:20!required',
      ]);

      //checking if validation failed
  if ($validator->fails()) {
      return $this->response->errorBadRequest();
  }

      //$identification = $request->only('id','dealer_key');
  $farmer = Farmer::where('key', $request->input('id'))->first();
  $dealer = Dealer::where('key', $request->input('dealer_key'))->with('activities')->first();


  if ( $farmer && $dealer) {
    $activity = array();
    $farmer_activity = array();

          //checking if farmer is assign
    if ($farmer->assign == 0) {
      return $this->response->error('farmer have not been assign to any group',404);
  }

          //check if farmer have collected input activity
  foreach($dealer->activities as $value){
    $activity = $value;
              //check if farmer have collected from dealer
    $check_dealer = Invoice::where('farmer_key',$farmer->key)->where('activity_key',$value->key)->first();

    if ($check_dealer) {

      $activity['dealer_key'] = $check_dealer->dealer_key;
      $activity['dealer_mark'] = 'yes';

  }else{

      $activity['dealer_mark'] = 'no';
  }

              //check if farmer have been verified by worker
  $check_worker = Receipt::where('farmer_key',$farmer->key)->where('activity_key',$value->key)->first();
  if ($check_worker) {

      $activity['worker_mark'] = 'yes';

  }else{

      $activity['worker_mark'] = 'no';
  }

  array_push($farmer_activity,$activity);

}

$farmer['activity'] = $farmer_activity;
          //check if farmer is within worker group
if ( ! $check = $this->check_group($request)) {
 return $this->response->error('farmer does not exist in group',404);
}

return $this->response->array(compact('farmer'))->setStatusCode(200);
}

return $this->response->errorNotFound();

}

//select list of farmers and workers by dealer
public function list_farmer_dealer(Request $request)
{
        //Token authentication
    $this->token_auth();


        //check if group exist
    $group = Group::where('key',$request->input('id'))->with('farmers','workers')->first();

    if (!$group) {
        return $this->response->errorNotFound();
    }

    $farmer_worker = array();
    //return only farmers in a group
    $farmer_worker['farmers'] = $group->farmers;
    $farmer_worker['workers'] = $group->workers;

    return $this->response->array(compact('farmer_worker'))->setStatusCode(200);
}

//getting farmers and workers count in dealers group
public function dealer_count(Request $request)
{
       //Token authentication
   $this->token_auth();
   $group = Group::where('key',$request->input('key'))->with('farmers','workers')->first(); 
   $dealer = Dealer::where('key', $request->input('dealer_key'))->first();

   if (!$group && !$dealer) {
      return $this->response->errorNotFound();
  }

  $supply = Receipt::where('dealer_key',$request->input('dealer_key'))->get();

  $group_count= array();
  $group_count['number_farmer'] = count($group->farmers);
  $group_count['number_worker'] = count($group->workers);
  $group_count['number_supply'] = count($supply);

  return $this->response->array(compact('group_count'))->setStatusCode(200);
}

//Dealr input confirmation 
public function dealer_confirmation(Request $request)
{
    //Token authentication
    $this->token_auth();

    //validating Farmers identification
    $validator = Validator::make($request->only('farmer_key','scheme_key','activity_key','dealer_key'),[
        'farmer_key'=>'alpha_num|max:20|min:20|required',
        'scheme_key'=>'alpha_num|max:20|min:20!required',
        'activity_key'=>'alpha_num|max:20|min:20|required',
        'dealer_key'=>'alpha_num|max:20|min:20|required',
        ]);

    //checking if validation failed
    if ($validator->fails()) {
        return $this->response->errorBadRequest();
    }

    //check if dealer exist
    $dealer = Dealer::where('key',$request->input('dealer_key'))->first();

    if (!$dealer) {
        return $this->response->errorNotFound();
    }

    //getting farmer name
    $request['farmer_name'] = $this->farmer_name($request->input('farmer_key'));
    $request['scheme_name'] = $this->scheme_name($request->input('scheme_key'));
    $request['activity_name'] = $this->activity_name($request->input('activity_key'));
    $request['dealer_name'] = $this->dealer_name($request->input('dealer_key'));

    if ($invoice= Invoice::create($request->all())) {
        $message = "Dealer Input confirmed";
        return $this->response->array(compact('message'))->setStatusCode(200);
    }
    return $this->response->error('Unable to confirm input',404);
}

//getting farmer details via phone number
public function farmer_phone(Request $request)
{
    //Token authentication
    $this->token_auth();

    $validator = Validator::make($request->only('farmer_phone'),[
        'farmer_phone'=>'numeric|required']);

    //checking if validation failed
    if ($validator->fails()) {
        return $this->response->errorBadRequest();
    }

    if ($farmer = Farmer::where('phone', $request->input('farmer_phone'))->first()) {
        return $this->response->array(compact('farmer'))->setStatusCode(200);
    }

    return $this->response->errorNotFound();
}

//getting number of group report
public function group_report(Request $request)
{
    //Token authentication
    $this->token_auth();

    //check if scheme exit
    
    if (!$scheme = Scheme::where('key', $request->input('key'))->with('groups')->first()) {
        return $this->response->errorNotFound();
    }
    $report = array();
    foreach ($scheme->groups as $value) {
       $group_report = Group::with('reports')->find($value->id);

       $group['key'] = $value->key;
       $group['name'] = $value->group_name;
       $group['no_report'] = count($group_report->reports);

       array_push($report,$group);
   }

   return $this->response->array(compact('report'))->setStatusCode(200);
}

//getting total number of report
public function worker_total_report(Request $request)
{
    //Token authentication
    $this->token_auth();

    //checking if groupexist
    if (!$group = Group::where('key',$request->input('key'))->with('workers')->first()) {
     return $this->response->errorNotFound();
 }
 $report = array();
 foreach ($group->workers as $value) {
   $worker_report = Report::where('key_worker',$value->key)->get();


   if ($worker_report) {
    $car['key'] = $value->key;
    $car['name'] = $value->first_name.' '.$value->last_name;
    $car['no_report'] = count($worker_report);

    array_push($report,$car);
}
}

if (count($report) < 1) {
    return $this->response->error('No report for the group',404);
}

return $this->response->array(compact('report'))->setStatusCode(200);

}

//geting farmer_worker report by passing worker
public function worker_farmer_report(Request $request)
{
    //Token authentication
    $this->token_auth();

    //checking if groupexist
    if (!$worker = Worker::where('key',$request->input('key'))->first()) {
     return $this->response->errorNotFound();
 }
 $report1 = array();
 $report = Report::where('key_worker', $request->input('key'))->get();
    //return  $report;
 foreach ( $report as $value) {
    $farmer = Farmer::where('key', $value->key_farmer)->first();
    if ($farmer) {
        $farmer1['name'] = $farmer['fullname'];
        $farmer1['phone'] = $farmer['phone'];
        $farmer1['key'] = $farmer['key'];
        $farmer1['report']=$value->description;
        array_push($report1, $farmer1);
    }
}
return $this->response->array(compact('report1'))->setStatusCode(200);
}

//downloading image 
public function image_download(Request $request)
{
  //Token authentication
  $this->token_auth();
  
  $validator = Validator::make($request->only('key','type'),[
      'key'=>'alpha_num|max:20|min:20|required',
      'type'=>'required'
      ]);

      //checking if validation failed
  if ($validator->fails()) {
      return $this->response->errorBadRequest();
  }

  switch ($request->input('type')) {
      case "farmer":
      if (!$farmer = Farmer::where('key',$request->input('key'))->first()) {
          return $this->response->errorNotFound();
      }
      $image['type'] = 'farmer';

      $image['image'] = $this->image($farmer->image);

      return $this->response->array(compact('image'))->setStatusCode(200);
      break;
      case "report":
      return $this->response->error('image not found',404);
      break;     
      default:
      return $this->response->error('Image not found',404);
  }
}


//checking if farmer exist in group
private function check_group($request)
{

    $group= Group::where('key',$request->input('group_key'))->first();
    $farmer = Farmer::where('key', $request->input('id'))->with('groups')->first();
    $farmer = $farmer->toArray();


    $check = false;
    foreach ($farmer['groups'] as $value) {
        if ($group->key == $value['key']) {
            $check = true;
            break;
        }
    }

    return $check;
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

//getting image
private function image($image)
{
    $localFileName  = public_path('/uploads/farmers/'.$image);
    $fileData = file_get_contents($localFileName);
    $ImgfileEncode = base64_encode($fileData);

    return $ImgfileEncode;
}


//uploading image
public function image_upload($request)
{
    $image = $request->file('file');
    $imgName = time().'.'.$image->getClientOriginalExtension();
    $destinationPath = public_path('uploads/report');
    //$destinationPath = 'uploads/report';
    $img = Image::make($image->getRealPath())->resize(150, 200)->save($destinationPath.'/'.$imgName);

    return $imgName;
}

//getting the name of famer
private function farmer_name($key)
{
    $farmer_name ="";
    $farmer = Farmer::where("key",$key)->first();
    if ($farmer) {
       $farmer_name = $farmer->fullname;
    }
    return $farmer_name;
}

//getting the name of dealer
private function dealer_name($key)
{
    $dealer_name ="";
    $dealer = Dealer::where("key",$key)->first();
    if ($dealer) {
       $dealer_name = $dealer->name_of_company;
    }
    return $dealer_name;
}

//getting the name of activity
private function activity_name($key)
{
    $activity_name ="";
    $activity = Activity::where("key",$key)->first();
    if ($activity) {
       $activity_name = $activity->name;
    }
    return $activity_name;
}

//getting the name of scheme
private function scheme_name($key)
{
    $scheme_name ="";
    $scheme = Scheme::where("key",$key)->first();
    if ($scheme) {
       $scheme_name = $scheme->name_of_scheme;
    }
    return $scheme_name;
}

//getting the name of worker
private function worker_name($key)
{
    $worker_name ="";
    $worker = Worker::where("key",$key)->first();
    if ($worker) {
       $worker_name = $worker->first_name." ".$worker->last_name;
    }
    return $worker_name;
}


}
