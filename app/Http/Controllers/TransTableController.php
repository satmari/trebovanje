<?php namespace App\Http\Controllers;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Database\Eloquent\ModelNotFoundException as ModelNotFoundException;
use Illuminate\Database\QueryException as QueryException;
use App\Exceptions\Handler;

use Illuminate\Http\Request;
//use Gbrock\Table\Facades\Table;
use Illuminate\Support\Facades\Redirect;

use App\trans_color;
use App\trans_item;
use App\trans_size;
use App\temp_print;
use App\RequestHeader;
use App\RequestLine;
use DB;

use App\User;
use Bican\Roles\Models\Role;
use Bican\Roles\Models\Permission;
use Auth;

use Session;
use Validator;

class TransTableController extends Controller {

	public function indexitem()
	{
		//
		try {
			$data = DB::connection('sqlsrv')->select(DB::raw("SELECT * FROM trans_items ORDER BY item"));
			return view('TransTable.indexitem', compact('data'));
		}
		catch (\Illuminate\Database\QueryException $e) {
			$data = DB::connection('sqlsrv')->select(DB::raw("SELECT * FROM trans_items"));
			return view('TransTable.indexitem', compact('data'));
		}

	}
	public function indexsize()
	{
		//
		try {
			$data = DB::connection('sqlsrv')->select(DB::raw("SELECT * FROM trans_sizes ORDER BY size"));
			return view('TransTable.indexsize', compact('data'));
		}
		catch (\Illuminate\Database\QueryException $e) {
			$data = DB::connection('sqlsrv')->select(DB::raw("SELECT * FROM trans_sizes"));
			return view('TransTable.indexsize', compact('data'));
		}
	}
	public function indexcolor()
	{
		//
		try {
			$data = DB::connection('sqlsrv')->select(DB::raw("SELECT * FROM trans_colors ORDER BY color"));
			return view('TransTable.indexcolor', compact('data'));
		}
		catch (\Illuminate\Database\QueryException $e) {
			$data = DB::connection('sqlsrv')->select(DB::raw("SELECT * FROM trans_colors"));
			return view('TransTable.indexcolor', compact('data'));
		}
	}	

	public function edit_item($id)
	{
		$data = trans_item::findOrFail($id);		
		return view('TransTable.edititem', compact('data'));

	}
	public function update_item($id, Request $request) 
	{
		//
		$this->validate($request, ['trans'=>'required','std_qty'=>'required','std_uom'=>'required']);

		$table = trans_item::findOrFail($id);		
		
		$input = $request->all(); 
		//dd($input);

		try {		
			
			$table->item_t = $input['trans'];
			$table->std_qty = $input['std_qty'];
			$table->std_uom = $input['std_uom'];
			$table->save();
		}
		catch (\Illuminate\Database\QueryException $e) {
			return view('TransTable.error');			
		}
		
		return Redirect::to('/transitem');
	}

	public function edit_size($id)
	{
		$data = trans_size::findOrFail($id);		
		return view('TransTable.editsize', compact('data'));

	}
	public function update_size($id, Request $request) 
	{
		//
		$this->validate($request, ['trans'=>'required']);

		$table = trans_size::findOrFail($id);		
		
		$input = $request->all(); 
		//dd($input);

		try {		
			
			$table->size_t = $input['trans'];
			$table->save();
		}
		catch (\Illuminate\Database\QueryException $e) {
			return view('TransTable.error');			
		}
		
		return Redirect::to('/transsize');
	}

	public function edit_color($id)
	{
		$data = trans_color::findOrFail($id);		
		return view('TransTable.editcolor', compact('data'));

	}
	public function update_color($id, Request $request) 
	{
		//
		$this->validate($request, ['trans'=>'required']);

		$table = trans_color::findOrFail($id);		
		
		$input = $request->all(); 
		//dd($input);

		try {		
			
			$table->color_t = $input['trans'];
			$table->save();
		}
		catch (\Illuminate\Database\QueryException $e) {
			return view('TransTable.error');			
		}
		
		return Redirect::to('/transcolor');
	}

}
