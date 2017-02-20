<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Auth;

use App\Http\Requests;
use App\Http\Controllers\Controller;

class AdminController extends Controller
{
	//protecting route
	public function __construct()
	{

		$this->middleware('auth', ['except' => [
		     'index','logout','store'
		 ]]);

	}

	//display login page
	public function index()
	{
		return view("admin.login");
	}

	//processing login parameter
	public function store(Request $request)
	{

		if (Auth::attempt(['email' => $request->email, 'password' => $request->password])) {

			return redirect()->intended('admin/dashboard');
		}
	}

    //Displaying dashboard page
	public function dashboard()
	{
		return view("admin/dashboard");
	}

	//logout from the system
	public function logout()
	{
		if (Auth::check()) {
		    Auth::logout();
		    return redirect('/admin');
		} else {
	        Auth::logout();
	        return redirect('/admin');
		}
	}

}
