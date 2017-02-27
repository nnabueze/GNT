<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Role;
use Session;
use Redirect;
use App\Permission;

use App\Http\Controllers\Controller;

class RoleController extends Controller
{
    //
   //protecting route
   public function __construct()
   {

   	$this->middleware('auth');

   }

   //displaying role
   public function index()
   {
   	$permissions = Permission::all();
   	$roles = Role::all();
      $sidebar = "role";
   	return view("role.index",compact("permissions","roles","sidebar"));
   }

   //storing role data
   public function store(Request $request)
   {


   	$this->validate($request,[
   	    'name' => 'required',
   	    'display_name' => 'required',
   	    ]);


   	if ($role = Role::create($request->all())) {
   	    $role->attachPermissions($request->input("permission"));
   	    Session::flash('message','Successful! Role Created');

   	    return Redirect::back();
   	}

   	Session::flash('warning','Failed! Role Not Created');
   	return Redirect::back();
   }


   //deleting role
   public function role_delete($id)
   {
   	if ($permission = Role::find($id)) {
   	   $permission->delete();

   	   Session::flash('message','Successful! Permission Deleted');
   	   return Redirect::back();
   	}

   	Session::flash('warning','Failed! Permission Not Deleted');
   	return Redirect::back();
   }
}
