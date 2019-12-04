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

class RefreshController extends Controller {

	public function so_refresh()
	{
		//

		try {	
			
		$request = DB::connection('sqlsrv')->select(DB::raw("SELECT id,po,size,module FROM request_header WHERE so = '' AND deleted = 0 "));
		// dd($request[0]->po);

		for ($i=0; $i < count($request); $i++) { 

			$module = $request[$i]->module;
			$crtica = substr($module, 1, 1);

			if ($crtica == "-") {

				$module_line = substr($module, 0, 1);
	    		$module_name = substr($module, 2, 3);
	    		
	    		$module = $module_line." ".$module_name;

	    		Session::set('module', $module);	

			} else {

				$module_line = substr($module, 0, 1);
	    		$module_name = substr($module, 1, 3);
	    		
	    		$module = $module_line." ".$module_name;

	    		Session::set('module', $module);	
			}

			try {			
			$so = DB::connection('sqlsrv3')->select(DB::raw("
			SELECT /*l.[Prod_ Order No_],*/
			   /*l.[Status],*/
			   p.[No_] as so,
			   l.[Item No_] as item,
			   /*l.[Shortcut Dimension 2 Code],*/
			   (RIGHT(l.[Shortcut Dimension 2 Code],6)) as po,
			   l.[PfsHorizontal Component] as size,
			   l.[PfsVertical Component] as color
			   /*p.[Description],*/
			   /*p.[WMS Status]*/
		   
			FROM [Gordon_LIVE].[dbo].[GORDON\$Prod_ Order Line] as l
			JOIN [Gordon_LIVE].[dbo].[GORDON\$Production Order] as p ON p.[No_] = l.[Prod_ Order No_]
			WHERE p.[Status] = '0' AND  p.[No_] like 'SO%' AND p.[Description] like '%".$module_line."%".$module_name."%' AND p.[WMS Status] = '0' 
			AND l.[Shortcut Dimension 2 Code] = '".$request[$i]->po."' AND l.[PfsHorizontal Component] = '".$request[$i]->size."'
			"));
			}
			catch (\Illuminate\Database\QueryException $e) {
				$msg = "Problem to connect to Nav, try again.";
				return view('Request.error',compact('msg'));
			}			

			// dd($so);

			if (isset($so[1]->so)) {
				// dd("More than one SO for can be set for ".$request[$i]->po. " and size ".$request[$i]->size);
				$msg = 'More than one SO can be applied for '.$request[$i]->po.' and size '.$request[$i]->size.' ,please check and correct SO in Navision!';
				return view('Request.error',compact('msg'));
			}

			if (isset($so[0]->so)) {

				try {
					$table = RequestHeader::findOrFail($request[$i]->id);
					
					$table->so = $so[0]->so;
					$table->status = "TO PRINT";
					$table->save();
					
				}
				catch (\Illuminate\Database\QueryException $e) {
					$msg = "Problem to save in RequestHeader";
					return view('Request.error',compact('msg'));
				}
			}
		}

		return Redirect::to('/');
		
		}
			catch (\Illuminate\Database\QueryException $e) {
			return Redirect::to('/so_refresh');
			
		}
	}

	public function hu_refresh()
	{
		//

		try {	
			
		$request = DB::connection('sqlsrv')->select(DB::raw("SELECT id, request_header_id, item, size, color FROM request_line WHERE hu = '' AND deleted = 0 "));
		// dd($request[0]->id);
		// dd(count($request));

		for ($i=0; $i < count($request); $i++) { 

			// $request[$i]->item;

			// try {			
			$hu_search = DB::connection('sqlsrv3')->select(DB::raw("
				SELECT TOP 1 [HU No_] as hu
				FROM [Gordon_LIVE].[dbo].[GORDON\$Handling Unit]
				WHERE  [Item No_] = '".$request[$i]->item."' AND [PfsVertical Component] = '".$request[$i]->color."' AND [PfsHorizontal Component] = '".$request[$i]->size."'
				"));
			// }
			// catch (\Illuminate\Database\QueryException $e) {
			// 	$msg = "Problem to connect to Nav, try again.";
			// 	return view('Request.error',compact('msg'));
			// }			

			// dd($hu_search);
			// dd(isset($hu_search[0]->hu));

			if (isset($hu_search[0]->hu)) {

				// try {
					$table = RequestLine::findOrFail($request[$i]->id);
					
					$table->hu = $hu_search[0]->hu;
					$table->save();
					
				// }
				// catch (\Illuminate\Database\QueryException $e) {
				// 	$msg = "Problem to save in RequestHeader";
				// 	return view('Request.error',compact('msg'));
				// }
			} else {

			}
		}

		return Redirect::to('/');

		}
			catch (\Illuminate\Database\QueryException $e) {
			return Redirect::to('/hu_refresh');
			
		}
	}

}
