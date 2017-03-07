<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Requests\UserRequest;
use App\User;
use App\Role;
use App\Igr;
use App\Mda;
use Auth;
use Redirect;
use Session;
use Hash;
use Input;


class UserController extends Controller
{
    public function __construct()
    {

        $this->middleware('auth');

    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        $mdas = Mda::all();
        $igrs = Igr::all();
        $roles = Role::all();
        $users = User::where("igr_id",Auth::user()->igr_id)->paginate(4);
        $sidebar = "user_sidebar";
        return view('user.index',compact("mdas","roles",'users','sidebar','igrs'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //checking if email exist
          $user = User::where('email',$request->input('email'))->first();
          if ($user) {
              Session::flash('warning', 'Failed! Email already exist');
              return Redirect::back();
          }


          //check if igr is selected
          if (empty($request->input('igr_id'))) {
             Session::flash('warning', 'Failed! select MDA');
             return Redirect::back();
          }

          //check if role is attached
          if (empty($request->input('role'))) {
             Session::flash('warning', 'Failed! select a role');
             return Redirect::back();
          }

          //checking if mda is selected
          if (empty($request->input('mda_id'))) {
             
             foreach($request->input('role') as $role){
                $role_check = Role::find($role);
                if ($role_check->name == "Staff") {
                    Session::flash('warning', 'Failed! Select MDA, if role is Staff');
                    return Redirect::back();
                }
             }
          }

          //hashing password
          $request['password'] = Hash::make($request->input('password'));
          
        if ($user = User::create($request->all())) {

            $user->attachRole($request->input('role'));

            Session::flash('message', 'Success! Account have been created');
            return Redirect::back();
        }
        

        Session::flash('warning', 'Failed! Unable to create account');
        return Redirect::back();
    }

    //////////////////////////////////////////////////////////////////////////////////////////////////////

    //getting lidt of mads when igr id is passed
    public function list_mda()
    {
        $id = Input::get('option');
        $mda = Igr::with("mdas")->find($id);
        return $mda->mdas->lists('mda_name', 'id');
    }


    //////////////////////////////////////////////////////////////////////////////////////////////////////

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
        $title = 'Fermers Connect: Users Page';
        $roles = Role::all();
        $users = User::with('roles')->paginate(20);
        $user1 = User::where('id',$id)->with('roles')->first();
        //dd($user1);
        return view('user.show',compact('title','user1','users','roles'));

    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
        echo "updating user";
        die;
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(UserRequest $request, $id)
    {
        //
        $user = User::find($id);
        if ($user->update($request->all())) {
            $user->roles->sync($request->input('role'));
            Session::flash('message', 'Success! Account have been updated');
            return Redirect::to('/users');
        }else{
           Session::flash('warning', 'Failed! User not updated');
           return Redirect::to('/users'); 
        }
       /* echo"<pre>";
        print_r($request->all());
        echo"</pre>";*/
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    $user = User::where('id',$id)->with('roles')->first();
    foreach($user->roles as $role){
        if ($role['slug'] == 'superadmin') {
           Session::flash('warning', 'Failed! Unable to delete a User');
           return Redirect::back();
        }
    }
    $user->delete();
    Session::flash('message', 'Success! You have deleted a User');
    return Redirect::back();
    }

    public function delete_user($id)
    {
        //checking user right
        if ( ! Auth::user()->hasRole('Superadmin')) {

           Session::flash("warning","You don't have the right to delete MDA");
           return Redirect::back();
        }

        //deleting the mda
        if ($igr = User::where("id",$id)->first()) {
           $igr->delete();

           Session::flash("message","Successful! User deleted");
           return Redirect::back();
        }

        Session::flash("warning","Failed! User not deleted");
        return Redirect::back();
    }

    //activating a USER
    public function status(Request $request)
    {
        $user = User::where('id',$request->input('id'))->first();

        switch($user->status){
            case "suspend":
                $user->status = 'active';
                $user->save();
                return Redirect::to('users');
            case "active":
                $user->status = 'suspend';
                $user->save();
                return Redirect::to('users');
            default:
                $user->status = 'active';
                $user->save();
                return Redirect::to('users');
        }
    }
}
