<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Auth;
use App\Mda;
use App\Igr;
use App\Http\Requests;
use App\Http\Controllers\Controller;

class CollectionController extends Controller
{
    //protecting theroute
    public function __construct()
    {

    	$this->middleware('auth');

    }

    //displaying allcollection from differentchannels
    public function index()
    {
        $igr = Igr::with("mdas")->find(Auth::user()->igr_id);
      
    	return view("collection.index",compact("igr"));
    }

    //show all the collection
    public function all_collection()
    {
            $mda = Mda::with("collections")->find(Auth::user()->igr_id);
          print_r($mda);
          die;
            return view("collection.index",compact("mda"));
    }
}
