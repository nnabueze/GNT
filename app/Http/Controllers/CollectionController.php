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
        $mda = Mda::with("collections")->find(Auth::user()->igr_id);
    	return view("collection.index",compact("igr","mda"));
    }

    //show all the collection
    public function all_collection()
    {
        $igr = Igr::with("mdas")->find(Auth::user()->igr_id);
            $mda = Mda::with("collections")->find(Auth::user()->igr_id);

            return view("collection.index",compact("mda","igr"));
    }

    //displaying collection by pos
    public function pos_collection()
    {
        return view("collection.pos_collection");
    }

    //ebills
    public function ebill_collection()
    {
        return view("collection.ebills_collection");
    }

    //displaying revenue heads
    public function revenue_heads()
    {
        return view("collection.revenue_heads");
    }
}
