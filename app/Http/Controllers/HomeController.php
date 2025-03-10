<?php namespace App\Http\Controllers;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Database\Eloquent\ModelNotFoundException as ModelNotFoundException;
use Illuminate\Database\QueryException as QueryException;
use App\Exceptions\Handler;

use Illuminate\Http\Request;
//use Gbrock\Table\Facades\Table;
use Illuminate\Support\Facades\Redirect;


use DB;

use App\User;
use Bican\Roles\Models\Role;
use Bican\Roles\Models\Permission;
use Auth;

use Session;
use Validator;

class HomeController extends Controller {

	public function __construct()
	{
		// $this->middleware('auth');
	}

	public function index()
	{	
		// $user = User::find(Auth::id());
		// if (Auth::check())
		// {
		//     // $userId = Auth::user()->id;
		//     // $module = Auth::user()->name;
		//     // var_dump($module);
		// }

		// if ($user->is('admin')) { 
		//     var_dump("admin");
		// }
		
		// if ($user->is('modul')) { 
		// 	var_dump("modul");
		// }

	    // dd($module);

		return Redirect::to('/');
		// return view('/');
	}

	public function printer()
	{	
		
		// return Redirect::to('/');
		return view('printer');
	}

	public function printer_set(Request $request)
	{	
		$this->validate($request, ['printer_name'=>'required']);

		$p = $request->all();
		$printer_name = $p['printer_name'];

		Session::set('printer_name', $printer_name );

		return redirect('/');
	}



}
