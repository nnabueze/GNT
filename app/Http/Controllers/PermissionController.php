<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Permission;
use Redirect;
use Session;
use Validator;
use App\Http\Controllers\Controller;

class PermissionController extends Controller
{
    //
    //protecting route
    public function __construct()
    {

    	$this->middleware('auth');

    }

    //displaying permission page
    public function index()
    {
    	$permissions = Permission::all();
    	return view("permission.index",compact("permissions"));
    }

    //stroing permission
    public function store(Request $request)
    {
    	$this->validate($request,[
    	    'name' => 'required',
    	    'display_name' => 'required',
    	    ]);


    	if ($permission = Permission::create($request->all())) {
    	    Session::flash('message','Successful! Permission Created');

    	    return Redirect::back();
    	}

    	Session::flash('warning','Failed! Permission Not Created');
    	return Redirect::back();
    }

    //permission delete
    //deleting permission
    public function permission_delete($id)
    {
        
        if ($permission = Permission::find($id)) {
           $permission->delete();

           Session::flash('message','Successful! Permission Deleted');
           return Redirect::back();
        }

        Session::flash('warning','Failed! Permission Not Deleted');
        return Redirect::back();
    }

    ///////////////////////////////////////////////Private class
    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        return Validator::make($data, [
            'name' => 'required|max:255',
            'display_name' => 'required',
        ]);
    }
}
