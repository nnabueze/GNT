<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Auth;
use Redirect;
use Session;

use App\Http\Requests;
use App\Http\Controllers\Controller;

class AdminController extends Controller
{
	//protecting route
	public function __construct()
	{

		$this->middleware('auth', ['except' => [
		     'index','logout','store','admin'
		 ]]);

	}

	//display login page
	public function index()
	{

		if (Auth::user()) {
			return Redirect::to("/dashboard");
		}

		//setting the menu active
		$sidebar = "dashbaord";
		return view("admin.login",compact("sidebar"));
	}

	//processing login parameter
	public function store(Request $request)
	{

		if (Auth::attempt(['email' => $request->email, 'password' => $request->password])) {
			
			return redirect()->intended('/dashboard');
		}else{
			Session::flash("warning","Failed! Invalid login credentails");
			return Redirect::to("/");
		}
	}

    //Displaying dashboard page
	public function dashboard()
	{
		//setting the side bar
		$sidebar = "dashbaord";
		return view("admin/dashboard",compact("sidebar"));
	}

	//logout from the system
	public function logout()
	{
		if (Auth::check()) {
		    Auth::logout();
		    return redirect('/');
		} else {
	        Auth::logout();
	        return redirect('/');
		}
	}

	//redirecting to the homepage
	public function admin()
	{
		return Redirect::to("/");
	}

}
