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

class RequestController extends Controller {

	public function __construct()
	{
		$this->middleware('auth');
	}

	public function index()
	{
		//
		$user = User::find(Auth::id());

		// if ($user->is('admin')) { 
		//     return Redirect::to('/tablesotoday');
		// }
		// if ($user->is('magacin')) { 
		//     return Redirect::to('/tablesotoday');
		// }
		// if ($user->is('modul')) { 
		// 	return view('Request.index');   
		// }
		
		if ($user->is('admin')) { 
		    return Redirect::to('/tablesotodaysap');
		}
		if ($user->is('magacin')) { 
		    return Redirect::to('/tablesotodaysap');
		}
		if ($user->is('modul')) { 
			return view('RequestSap.index');   
		}

		// return view('Request.index');
		return Redirect::to('/');
	}

	public function createtreb(Request $request)
	{
		$this->validate($request, ['pin'=>'required|min:4|max:5']);
		$forminput = $request->all(); 

		$pin = $forminput['pin'];

		$inteosleaders = DB::connection('sqlsrv2')->select(DB::raw("SELECT Name FROM BdkCLZG.dbo.WEA_PersData WHERE (Func = 23) and (FlgAct = 1) and (PinCode = ".$pin.")"));
		/*
		$inteosleaders = DB::connection('sqlsrv2')->select(DB::raw("SELECT 
			Name 
		FROM [BdkCLZG].[dbo].[WEA_PersData] 
		WHERE (Func = 23) and (FlgAct = 1) and (PinCode = ".$pin.")
		UNION ALL
		SELECT 
			Name 
		FROM [SBT-SQLDB01P\\INTEOSKKA].[BdkCLZKKA].[dbo].[WEA_PersData]
		WHERE (Func = 23) and (FlgAct = 1) and (PinCode = ".$pin.")"));
		*/

		if (empty($inteosleaders)) {
			$msg = 'LineLeader with this PIN not exist';
		    return view('Request.error',compact('msg'));
		
		} else {
			foreach ($inteosleaders as $row) {
    			$leader = $row->Name;
    			Session::set('leader', $leader);		
    		}

    		if (Auth::check())
			{
			    $userId = Auth::user()->id;
			    $module = Auth::user()->name;
			} else {
				$msg = 'Modul is not autenticated';
				return view('Request.error',compact('msg'));
			}
    	} 

    	$leader = Session::get('leader');
		// dd($leader);

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

    	// var_dump($module_line);
    	// var_dump($module_name);

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
			"));

		// dd($so);

		return view('Request.createtreb', compact('leader','so'));
	}

	// IF MODULE ALREADY HAVE SIMULATE ORDER
	public function existingso($so, Request $request)
	{
		// dd($so);
		if (Auth::check())
		{
		    $userId = Auth::user()->id;
		    $module = Auth::user()->name;
		} else {
			$msg = 'Modul is not autenticated';
			return view('Request.error',compact('msg'));
		}

		$leader = Session::get('leader');
		if (!isset($leader)) {
			$msg = 'LineLeader is not autenticated';
			return view('Request.error',compact('msg'));
		}

		// Check if user alredy have request for that day and so
		$today_request = DB::connection('sqlsrv')->select(DB::raw("SELECT id
		  FROM [trebovanje].[dbo].[request_header]
		  WHERE deleted = 0 AND so = '".$so."' AND module = '".$module."' AND created_at BETWEEN CAST(GETDATE() AS DATE) AND DATEADD(DAY, 1, CAST(GETDATE() AS DATE))"));

		if (isset($today_request[0]->id)) {
			$warning = "PAZNJA: DANAS JE MODUL ".$module." VEC TREBOVAO OVU KOMESU + VELICINU!";
		}



		// Restrive info from Navision
		$components = DB::connection('sqlsrv3')->select(DB::raw("
			SELECT 
		      c.[Item No_] as item
		      /*c.[Quantity per] as qp*/
		      /*,c.[Variant Code] */
		      ,c.[PfsVertical Component] as color
		      ,c.[PfsHorizontal Component] as size 
		      /*,c.[Area Code]*/
		      ,l.[Item No_] as itemfg
		      ,l.[PfsHorizontal Component] as sizefg
			  ,l.[PfsVertical Component] as colorfg
			  /*,RIGHT(c.[Shortcut Dimension 2 Code],6) as po*/
			  ,c.[Shortcut Dimension 2 Code] as po
			  ,c.[Unit of Measure Code] as uom
			  ,b.[Barcode No_] as hu

			  /*  ,c.*  ,l.*  */
			  
			  FROM [Gordon_LIVE].[dbo].[GORDON\$Prod_ Order Component] as c
			  LEFT JOIN [Gordon_LIVE].[dbo].[GORDON\$Prod_ Order Line] as l ON c.[Prod_ Order No_] = l.[Prod_ Order No_] AND c.[Prod_ Order Line No_] = l.[Line No_]
			  RIGHT JOIN [Gordon_LIVE].[dbo].[GORDON\$Barcode] as b ON c.[Item No_] = b.[No_] AND c.[Variant Code] = b.[Variant Code] AND b.[Barcode No_] like 'NOT%'
			  WHERE c.[Area Code] != 'PREPARATION' AND c.[Quantity per] > 0 AND c.[Prod_ Order No_] like '".$so."' 
			  ORDER BY item asc
			  "));

		// 2017.11.25 bilo je dupliranih linija pa smo stavili [Prod_ Order No_] like umesto [Shortcut Dimension 2 Code] like

		// dd($components[]);
		// var_dump($components);

		$po = $components[0]->po;
		$itemfg = $components[0]->itemfg;
		$colorfg = $components[0]->colorfg;
		$sizefg = $components[0]->sizefg;
		
		$newarray = [];

		for ($i=0; $i < count($components); $i++) { 

			// dd($components[$i]->item);
			// dd($components[$i]->size);
			// dd($components[$i]->color);
			
			$item_t = DB::connection('sqlsrv')->select(DB::raw("SELECT * FROM trans_items WHERE item = '".$components[$i]->item."'"));
			$size_t = DB::connection('sqlsrv')->select(DB::raw("SELECT * FROM trans_sizes WHERE size = '".$components[$i]->size."'"));
			$color_t = DB::connection('sqlsrv')->select(DB::raw("SELECT * FROM trans_colors WHERE color = '".$components[$i]->color."'"));

			// dd($item_t[0]->item_t);
			// dd($size_t[0]->size_t);
			// dd($color_t[0]->color_t);

			// Item Missing
			if (!isset($item_t[0]->item)) {
				$msg = 'Item '.$components[$i]->item.' not exist in translation table! Call WH Planer.';
				return view('Request.error',compact('msg'));
			}
			if (!isset($size_t[0]->size)) {
				$msg = 'Size '.$components[$i]->size.' not exist in translation table! Call WH Planer.';
				return view('Request.error',compact('msg'));
			}
			if (!isset($color_t[0]->color)) {
				$msg = 'Color '.$components[$i]->color.' not exist in translation table! Call WH Planer.';
				return view('Request.error',compact('msg'));
			}

			// Test period
			if (is_null($item_t[0]->std_qty)) {
				$item_t[0]->std_qty = 0;
			}
			if (is_null($item_t[0]->std_qty)) {
				$msg = 'Standard quantity for item '.$components[$i]->item.' is not set in translation table! GORDON\.';
				return view('Request.error',compact('msg'));
			}

			// Translation Missing 
			if (!isset($item_t[0]->item_t) OR (is_null($item_t[0]->item_t))) {
				$item_t_new = '';
			} else {
				$item_t_new = $item_t[0]->item_t;
			}

			if (!isset($size_t[0]->size_t) OR (is_null($size_t[0]->size_t))) {
				$size_t_new = '';
			} else {
				$size_t_new = $size_t[0]->size_t;
			}

			if (!isset($color_t[0]->color_t) OR (is_null($color_t[0]->color_t))) {
				$color_t_new = '';
			} else {
				$color_t_new = $color_t[0]->color_t;
			}	
			
			// var_dump($item_t[0]->item_t);
			// var_dump($size_t[0]->size_t);
			// var_dump($color_t[0]->color_t); 
			
			if (($item_t_new == 'Care Label') OR ($item_t_new == 'Barkod') OR ($item_t_new == 'Fabric') OR ($components[$i]->item == 'AF0129')) {
			  continue;
			}

			// dd($item_t[0]->std_qty);

			array_push($newarray, array(
		        "item" => $components[$i]->item, 
		        "item_t" => $item_t_new,
		        "size" => $components[$i]->size,
		        "size_t" => $size_t_new,
		        "color" => $components[$i]->color,
		        "color_t" => $color_t_new,
		        "uom" => $components[$i]->uom,
		        "hu" => $components[$i]->hu,
		        "std_qty" => (int)($item_t[0]->std_qty),
		        "std_uom" => $item_t[0]->std_uom
		    ));

		}

		// dd($newarray);

		return view('Request.createtrebcon', compact('leader','so','po','itemfg','colorfg','sizefg','newarray','warning'));
	}

	// public function existingso1($so, Request $request)
	// {
	// 	// dd($so);
	// 	if (Auth::check())
	// 	{
	// 	    $userId = Auth::user()->id;
	// 	    $module = Auth::user()->name;
	// 	} else {
	// 		$msg = 'Modul is not autenticated';
	// 		return view('Request.error',compact('msg'));
	// 	}

	// 	$leader = Session::get('leader');
	// 	if (!isset($leader)) {
	// 		$msg = 'LineLeader is not autenticated';
	// 		return view('Request.error',compact('msg'));
	// 	}

	// 	$components = DB::connection('sqlsrv3')->select(DB::raw("
	// 		SELECT 
	// 	      c.[Item No_] as item
	// 	      /*c.[Quantity per] as qp*/
	// 	      /*,c.[Variant Code] */
	// 	      ,c.[PfsVertical Component] as color
	// 	      ,c.[PfsHorizontal Component] as size 
	// 	      /*,c.[Area Code]*/
	// 	      ,l.[Item No_] as itemfg
	// 	      ,l.[PfsHorizontal Component] as sizefg
	// 		  ,l.[PfsVertical Component] as colorfg
	// 		  /*,RIGHT(c.[Shortcut Dimension 2 Code],5) as po*/
	// 		  ,c.[Shortcut Dimension 2 Code] as po
	// 		  ,c.[Unit of Measure Code] as uom
	// 		  ,b.[Barcode No_] as hu

	// 		  /*  ,c.*  ,l.*  */
			  
	// 		  FROM [Gordon_LIVE].[dbo].[GORDON\$Prod_ Order Component] as c
	// 		  LEFT JOIN [Gordon_LIVE].[dbo].[GORDON\$Prod_ Order Line] as l ON c.[Prod_ Order No_] = l.[Prod_ Order No_] AND c.[Prod_ Order Line No_] = l.[Line No_]
	// 		  LEFT JOIN [Gordon_LIVE].[dbo].[GORDON\$Barcode] as b ON c.[Item No_] = b.[No_] AND c.[Variant Code] = b.[Variant Code] AND b.[Barcode No_] like 'NOT%'
	// 		  WHERE c.[Area Code] != 'PREPARATION' AND c.[Quantity per] > 0 AND c.[Prod_ Order No_] like '".$so."' 
	// 		  ORDER BY item asc
	// 		  "));

	// 	// dd($components[]);
	// 	// var_dump($components);

	// 	$po = $components[0]->po;
	// 	$itemfg = $components[0]->itemfg;
	// 	$colorfg = $components[0]->colorfg;
	// 	$sizefg = $components[0]->sizefg;
		
	// 	$newarray = [];

	// 	for ($i=0; $i < count($components); $i++) { 

	// 		// dd($components[$i]->item);
	// 		// dd($components[$i]->size);
	// 		// dd($components[$i]->color);
			
	// 		$item_t = DB::connection('sqlsrv')->select(DB::raw("SELECT * FROM trans_items WHERE item = '".$components[$i]->item."'"));
	// 		$size_t = DB::connection('sqlsrv')->select(DB::raw("SELECT * FROM trans_sizes WHERE size = '".$components[$i]->size."'"));
	// 		$color_t = DB::connection('sqlsrv')->select(DB::raw("SELECT * FROM trans_colors WHERE color = '".$components[$i]->color."'"));

	// 		// dd($item_t[0]->item_t);
	// 		// dd($size_t[0]->size_t);
	// 		// dd($color_t[0]->color_t);

	// 		// Item Missing
	// 		if (!isset($item_t[0]->item)) {
	// 			$msg = 'Item '.$components[$i]->item.' not exist in translation table! Call WH Planer.';
	// 			return view('Request.error',compact('msg'));
	// 		}
	// 		if (!isset($size_t[0]->size)) {
	// 			$msg = 'Size '.$components[$i]->size.' not exist in translation table! Call WH Planer.';
	// 			return view('Request.error',compact('msg'));
	// 		}
	// 		if (!isset($color_t[0]->color)) {
	// 			$msg = 'Color '.$components[$i]->color.' not exist in translation table! Call WH Planer.';
	// 			return view('Request.error',compact('msg'));
	// 		}

	// 		// Test period
	// 		if (is_null($item_t[0]->std_qty)) {
	// 			$item_t[0]->std_qty = 0;
	// 		}
	// 		if (is_null($item_t[0]->std_qty)) {
	// 			$msg = 'Standard quantity for item '.$components[$i]->item.' is not set in translation table! GORDON\.';
	// 			return view('Request.error',compact('msg'));
	// 		}

	// 		// Translation Missing 
	// 		if (!isset($item_t[0]->item_t) OR (is_null($item_t[0]->item_t))) {
	// 			$item_t_new = '';
	// 		} else {
	// 			$item_t_new = $item_t[0]->item_t;
	// 		}

	// 		if (!isset($size_t[0]->size_t) OR (is_null($size_t[0]->size_t))) {
	// 			$size_t_new = '';
	// 		} else {
	// 			$size_t_new = $size_t[0]->size_t;
	// 		}

	// 		if (!isset($color_t[0]->color_t) OR (is_null($color_t[0]->color_t))) {
	// 			$color_t_new = '';
	// 		} else {
	// 			$color_t_new = $color_t[0]->color_t;
	// 		}	
			
	// 		// var_dump($item_t[0]->item_t);
	// 		// var_dump($size_t[0]->size_t);
	// 		// var_dump($color_t[0]->color_t); 
			
	// 		if (($item_t_new == 'Care Label') OR ($item_t_new == 'Barkod') OR ($item_t_new == 'Fabric') OR ($components[$i]->item == 'AF0129')) {
	// 		  continue;
	// 		}

	// 		// dd($item_t[0]->std_qty);

	// 		array_push($newarray, array(
	// 	        "item" => $components[$i]->item, 
	// 	        "item_t" => $item_t_new,
	// 	        "size" => $components[$i]->size,
	// 	        "size_t" => $size_t_new,
	// 	        "color" => $components[$i]->color,
	// 	        "color_t" => $color_t_new,
	// 	        "uom" => $components[$i]->uom,
	// 	        "hu" => $components[$i]->hu,
	// 	        "std_qty" => (int)($item_t[0]->std_qty),
	// 	        "std_uom" => $item_t[0]->std_uom
	// 	    ));

	// 	}

	// 	// dd($newarray);

	// 	return view('Request.createtrebcon1', compact('leader','so','po','itemfg','colorfg','sizefg','newarray'));
	// }
	

	// IF MODULE NEED NEW PROD ORDER
	public function newso()
	{
		$leader = Session::get('leader');
		return view('Request.createnewso', compact('leader'));
	}

	// IF MODULE NEED NEW PROD ORDER
	public function createnewso(Request $request)
	{
		$this->validate($request, ['po'=>'required','size'=>'required']);
		$input = $request->all(); 
		// dd($input);

		if (Auth::check())
		{
		    $userId = Auth::user()->id;
		    $module = Auth::user()->name;
		} else {
			$msg = 'Modul is not autenticated';
			return view('Request.error',compact('msg'));
		}

		$leader = Session::get('leader');
		if (!isset($leader)) {
			$msg = 'LineLeader is not autenticated';
			return view('Request.error',compact('msg'));
		}

		$po = $input['po'];
		$so = "";
		$size = $input['size'];

		$components = DB::connection('sqlsrv3')->select(DB::raw("
			SELECT 
		      c.[Item No_] as item
		      ,c.[Variant Code]
		      ,c.[PfsVertical Component] as color
		      ,c.[PfsHorizontal Component] as size 
		      /*,c.[Area Code]*/
		      ,l.[Item No_] as itemfg
		      ,l.[PfsHorizontal Component] as sizefg
			  ,l.[PfsVertical Component] as colorfg
			  /*,RIGHT(c.[Shortcut Dimension 2 Code],6) as po*/
			  ,c.[Shortcut Dimension 2 Code] as po
			  ,c.[Unit of Measure Code] as uom
			  ,b.[Barcode No_] as hu

			  /*  ,c.*  ,l.*  */
			  
			  FROM [Gordon_LIVE].[dbo].[GORDON\$Prod_ Order Component] as c
			  LEFT JOIN [Gordon_LIVE].[dbo].[GORDON\$Prod_ Order Line] as l ON c.[Prod_ Order No_] = l.[Prod_ Order No_] AND c.[Prod_ Order Line No_] = l.[Line No_]
			  RIGHT JOIN [Gordon_LIVE].[dbo].[GORDON\$Barcode] as b ON c.[Item No_] = b.[No_] AND c.[Variant Code] = b.[Variant Code] AND b.[Barcode No_] like 'NOT%'
			  WHERE c.[Area Code] != 'PREPARATION' AND c.[Quantity per] > 0 AND c.[Prod_ Order No_] like '%".$po."' AND c.[Shortcut Dimension 2 Code] like '%".$po."'
			  AND l.[PfsHorizontal Component] = '".$size."' 
			  ORDER BY item asc
			  "));

			// 2017.11.30 bilo je dupliranih linija pa sam stavio [Prod_ Order No_] like umesto [Shortcut Dimension 2 Code] like
			// 2017.11.30 bilo je dupliranih linija pa sam stavio i [Prod_ Order No_] like i [Shortcut Dimension 2 Code] like

		if ($components) {
			// dd($components);
			$po = $components[0]->po;
			$itemfg = $components[0]->itemfg;
			$colorfg = $components[0]->colorfg;
			$sizefg = $components[0]->sizefg;
		} else {
			$msg = 'There is no open Komesa with that size!!! ';
			return view('Request.error',compact('msg'));
		}
		


		$newarray = [];

		// dd($components);

		for ($i=0; $i < count($components); $i++) { 

			// dd($components[$i]->item);
			// dd($components[$i]->size);
			// dd($components[$i]->color);
			
			$item_t = DB::connection('sqlsrv')->select(DB::raw("SELECT * FROM trans_items WHERE item = '".$components[$i]->item."'"));
			$size_t = DB::connection('sqlsrv')->select(DB::raw("SELECT * FROM trans_sizes WHERE size = '".$components[$i]->size."'"));
			$color_t = DB::connection('sqlsrv')->select(DB::raw("SELECT * FROM trans_colors WHERE color = '".$components[$i]->color."'"));

			// dd($item_t[0]->item_t);
			// dd($size_t[0]->size_t);
			// dd($color_t[0]->color_t);

			// Item Missing
			if (!isset($item_t[0]->item)) {
				$msg = 'Item '.$components[$i]->item.' not exist in translation table! Call WH Planer.';
				return view('Request.error',compact('msg'));
			}
			if (!isset($size_t[0]->size)) {
				$msg = 'Size '.$components[$i]->size.' not exist in translation table! Call WH Planer.';
				return view('Request.error',compact('msg'));
			}
			if (!isset($color_t[0]->color)) {
				$msg = 'Color '.$components[$i]->color.' not exist in translation table! Call WH Planer.';
				return view('Request.error',compact('msg'));
			}

			// dd($item_t[0]->std_qty);
			// Test period
			if (is_null($item_t[0]->std_qty)) {
				$item_t[0]->std_qty = 0;
			}
			if (is_null($item_t[0]->std_qty)) {
				$msg = 'Standard quantity for item '.$components[$i]->item.' is not set in translation table! GORDON\.';
				return view('Request.error',compact('msg'));
			}

			// Translation Missing 
			if (!isset($item_t[0]->item_t) OR (is_null($item_t[0]->item_t))) {
				$item_t_new = '';
			} else {
				$item_t_new = $item_t[0]->item_t;
			}

			if (!isset($size_t[0]->size_t) OR (is_null($size_t[0]->size_t))) {
				$size_t_new = '';
			} else {
				$size_t_new = $size_t[0]->size_t;
			}

			if (!isset($color_t[0]->color_t) OR (is_null($color_t[0]->color_t))) {
				$color_t_new = '';
			} else {
				$color_t_new = $color_t[0]->color_t;
			}
			

			if (($item_t[0]->item_t == 'Care Label') OR ($item_t[0]->item_t == 'Barkod') OR ($item_t[0]->item_t == 'Fabric') OR ($components[$i]->item == 'AF0129')) {
			  continue;
			}

			// if ((is_null($size_t[0]->size_t)) OR ($size_t[0]->size_t == '')) {
			// 	$size_t_new = $size_t[0]->size_t;
			// } else {
			// 	$size_t_new = $size_t[0]->size_t;
			// }

			// if ((is_null($color_t[0]->color_t)) OR ($color_t[0]->color_t == '')) {
			// 	$color_t_new = $color_t[0]->color_t;
			// } else {
			// 	$color_t_new = $color_t[0]->color_t;
			// }

			// var_dump($item_t[0]->std_qty);
			// dd(integer($item_t[0]->std_qty));

			array_push($newarray, array(
		        "item" => $components[$i]->item, 
		        "item_t" => $item_t[0]->item_t, 
		        "size" => $components[$i]->size,
		        "size_t" => $size_t_new,
		        "color" => $components[$i]->color,
		        "color_t" => $color_t_new,
		        "uom" => $components[$i]->uom,
		        "hu" => $components[$i]->hu,
		        "std_qty" => (int)($item_t[0]->std_qty),
		        "std_uom" => $item_t[0]->std_uom

		    ));

		}

		// dd($newarray);

		return view('Request.createtrebcon', compact('leader','so','po','itemfg','colorfg','sizefg','newarray'));
	}

	public function requeststoretreb(Request $request)
	{
		$this->validate($request, ['comment'=>'max:50']);
		$input = $request->all(); 
		// dd($input);

		$hidden = $input['hidden'];
		$items = $input['items'];
		$not_std_qty = $input['not_std_qty'];
		// dd(count($items));

		$array = array();
		$not_std_qty_array = array();

		for ($i=0; $i < count($items); $i++) {

			for ($e=0; $e < count($hidden); $e++) { 
					
				if ($items[$i] == $hidden[$e]){
					// dd($items[$i]);
					// dd($not_std_qty[$e]);
					// $stack = array($items[$i] => $not_std_qty[$e]);
					// array_push($stack_array, $items[$i] => $not_std_qty[$e]);

					// $array = array((int)$i => (int)$not_std_qty[$e]);

					$array = array($not_std_qty[$e]);
					array_push($not_std_qty_array, $array);
				}
			}
		}

		// dd($items);
		// dd($not_std_qty_array);
		// dd($input);

		if (Auth::check())
		{
		    $userId = Auth::user()->id;
		    $module = Auth::user()->name;
		} else {
			$msg = 'Modul is not autenticated';
			return view('Request.error',compact('msg'));
		}

		$leader = Session::get('leader');
		if (!isset($leader)) {
			$msg = 'LineLeader is not autenticated';
			return view('Request.error',compact('msg'));
		}

		if (!isset($input['items'])) {
			$msg = 'Izaberite odredjeni materijal!';
			return view('Request.error',compact('msg'));
		}

		$so = $input['so']; 
		$po = $input['po'];
		$itemfg = $input['itemfg'];
		$colorfg = $input['colorfg'];
		$sizefg = $input['sizefg'];
		// $std_uom = $input['std_uom'];

		$items = $input['items'];
		// dd($items);

		// $std_qty = $input['std_qty']; // Pogresno  -------------------------------------------------------------------
		// dd($input['not_std_qty'][0]);
		
		// $std_qty = $input['std_qty'];
		// dd($std_qty);

		if ($so == '') {
			$status = 'TO CREATE';
			$first_time = 'YES';
		} else {
			$status = 'TO PRINT';	
			$first_time = 'NO';
		}
		

		if (isset($input['comment'])) {
			$comment = $input['comment'];

		} else {
			$comment = '';
		}

		// Flash details
		$find_flash = DB::connection('sqlsrv3')->select(DB::raw("
			SELECT 
			[Cutting Prod_ Line] as flash
			FROM [Gordon_LIVE].[dbo].[GORDON\$Production Order] 
			WHERE Status = '3' AND [No_] like '%".$po."'
			  "));
		// dd($find_flash[0]->flash);

		if (isset($find_flash[0]->flash)) {
			$flash = $find_flash[0]->flash;
		} else {
			$flash = "";
		}
		
		
		//Record Header
		try {
			$table = new RequestHeader;

			$table->name = $module." ".date("Y-m-d H:i:s");
			$table->so = $input['so'];
			$table->po = $input['po'];
			
			$table->style = $itemfg;
			$table->color = $colorfg;
			$table->size = $sizefg;
			
			$table->module = $module;
			$table->leader = $leader;

			$table->status = $status;
			$table->first_time = $first_time;
			$table->deleted = 0;

			$table->comment = $comment;

			$table->flash = $flash;

			$table->save();
			
		}
		catch (\Illuminate\Database\QueryException $e) {
			$msg = "Problem to save in RequestHeader";
			return view('Request.error',compact('msg'));
		}
		
		// dd($table->id);
	
		// Record Line
		// foreach ($items as $line) {

		// dd($items);
		// dd($input['not_std_qty'][1]);

		// dd(count($items)); 					//3
		// dd(count($input['not_std_qty'])); 	//8

		// $array = array_diff($input['not_std_qty'], [0]);
		// dd($array);

		for ($i=0; $i < count($items); $i++) { 

			
						
			list($item, $item_t, $color, $color_t, $size, $size_t, $uom, $hu, $std_qty, $std_uom) = explode('#', $items[$i]);
			// dd($items);

			// Staro ------------
			// if isset()
			// $not_std_qty = $not_std_qty[$i];
			// $std_qty;
			// dd((int)($input['not_std_qty'][$i+1]));


			// if (($input['not_std_qty'][$i+1]) == 0) {
			// 	$qty_final = $std_qty;

			// 	// dd("NUla na ".$input['not_std_qty'][$i]);
			// } else {
			// 	$qty_final = $input['not_std_qty'][$i+1];
			// }
			//-----------

			if ($not_std_qty_array[$i][0] == "" ) {
				$qty_final = (int)$std_qty;
			} else {
				$qty_final = (int)$not_std_qty_array[$i][0];
			}

			// dd($qty_final);
			// dd($not_std_qty_array);
			// dd($not_std_qty_array[$i][0]);
			
			// $qty_final = $std_qty;

			// try {
				$table2 = new RequestLine;

				$table2->request_header_id = $table->id;

				$table2->item = $item;
				$table2->item_t = $item_t;
				
				$table2->size = $size;
				$table2->size_t = $size_t;
				
				$table2->color = $color;
				$table2->color_t = $color_t;

				$table2->hu = $hu;
				$table2->uom = $uom;

				$table2->std_qty = $qty_final;
				$table2->std_uom = $std_uom;

				$table2->deleted = 0;
				$table2->save();
				
			// }
			// catch (\Illuminate\Database\QueryException $e) {
			// 	$msg = "Problem to save in RequestLine";
			// 	return view('Request.error',compact('msg'));
			// }
		}

		return Redirect::to('/');
	}

	// public function requeststoretreb1(Request $request)
	// {
	// 	$this->validate($request, ['comment'=>'max:50']);
	// 	$input = $request->all();

	// 	$hidden = $input['hidden'];
	// 	$items = $input['items'];
	// 	$not_std_qty = $input['not_std_qty'];
	// 	// dd(count($items));

	// 	$array = array();
	// 	$not_std_qty_array = array();

	// 	for ($i=0; $i < count($items); $i++) {

	// 		for ($e=0; $e < count($hidden); $e++) { 
					
	// 			if ($items[$i] == $hidden[$e]){
	// 				// dd($items[$i]);
	// 				// dd($not_std_qty[$e]);
	// 				// $stack = array($items[$i] => $not_std_qty[$e]);
	// 				// array_push($stack_array, $items[$i] => $not_std_qty[$e]);

	// 				// $array = array((int)$i => (int)$not_std_qty[$e]);

	// 				$array = array($not_std_qty[$e]);
	// 				array_push($not_std_qty_array, $array);
	// 			}
	// 		}
	// 	}

	// 	// dd($items);
	// 	// dd($not_std_qty_array);
	// 	// dd($input);

	// 	if (Auth::check())
	// 	{
	// 	    $userId = Auth::user()->id;
	// 	    $module = Auth::user()->name;
	// 	} else {
	// 		$msg = 'Modul is not autenticated';
	// 		return view('Request.error',compact('msg'));
	// 	}

	// 	$leader = Session::get('leader');
	// 	if (!isset($leader)) {
	// 		$msg = 'LineLeader is not autenticated';
	// 		return view('Request.error',compact('msg'));
	// 	}

	// 	if (!isset($input['items'])) {
	// 		$msg = 'Izaberite odredjeni materijal!';
	// 		return view('Request.error',compact('msg'));
	// 	}

	// 	$so = $input['so']; 
	// 	$po = $input['po'];
	// 	$itemfg = $input['itemfg'];
	// 	$colorfg = $input['colorfg'];
	// 	$sizefg = $input['sizefg'];
	// 	// $std_uom = $input['std_uom'];

	// 	$items = $input['items'];
	// 	// dd($items);

	// 	// $std_qty = $input['std_qty']; // Pogresno  -------------------------------------------------------------------
	// 	// dd($input['not_std_qty'][0]);
		
	// 	// $std_qty = $input['std_qty'];
	// 	// dd($std_qty);

	// 	if ($so == '') {
	// 		$status = 'TO CREATE';
	// 		$first_time = 'YES';
	// 	} else {
	// 		$status = 'TO PRINT';	
	// 		$first_time = 'NO';
	// 	}
		

	// 	if (isset($input['comment'])) {
	// 		$comment = $input['comment'];

	// 	} else {
	// 		$comment = '';
	// 	}

	// 	// Flash details
	// 	$find_flash = DB::connection('sqlsrv3')->select(DB::raw("
	// 		SELECT 
	// 		[Cutting Prod_ Line] as flash
	// 		FROM [Gordon_LIVE].[dbo].[GORDON\$Production Order] 
	// 		WHERE Status = '3' AND [No_] like '%".$po."'
	// 		  "));
	// 	// dd($find_flash[0]->flash);

	// 	if (isset($find_flash[0]->flash)) {
	// 		$flash = $find_flash[0]->flash;
	// 	} else {
	// 		$flash = "";
	// 	}
		
		
	// 	//Record Header
	// 	try {
	// 		$table = new RequestHeader;

	// 		$table->name = $module." ".date("Y-m-d H:i:s");
	// 		$table->so = $input['so'];
	// 		$table->po = $input['po'];
			
	// 		$table->style = $itemfg;
	// 		$table->color = $colorfg;
	// 		$table->size = $sizefg;
			
	// 		$table->module = $module;
	// 		$table->leader = $leader;

	// 		$table->status = $status;
	// 		$table->first_time = $first_time;
	// 		$table->deleted = 0;

	// 		$table->comment = $comment;

	// 		$table->flash = $flash;

	// 		// $table->save();
			
	// 	}
	// 	catch (\Illuminate\Database\QueryException $e) {
	// 		$msg = "Problem to save in RequestHeader";
	// 		return view('Request.error',compact('msg'));
	// 	}
		
	// 	// dd($table->id);
	
	// 	// Record Line
	// 	// foreach ($items as $line) {

	// 	// dd($items);
	// 	// dd($input['not_std_qty'][1]);

	// 	// dd(count($items)); 					//3
	// 	// dd(count($input['not_std_qty'])); 	//8

	// 	// $array = array_diff($input['not_std_qty'], [0]);
	// 	// dd($array);

	// 	for ($i=0; $i < count($items); $i++) { 

			
						
	// 		list($item, $item_t, $color, $color_t, $size, $size_t, $uom, $hu, $std_qty, $std_uom) = explode('#', $items[$i]);
	// 		// dd($items);

	// 		// Staro ------------
	// 		// if isset()
	// 		// $not_std_qty = $not_std_qty[$i];
	// 		// $std_qty;
	// 		// dd((int)($input['not_std_qty'][$i+1]));


	// 		// if (($input['not_std_qty'][$i+1]) == 0) {
	// 		// 	$qty_final = $std_qty;

	// 		// 	// dd("NUla na ".$input['not_std_qty'][$i]);
	// 		// } else {
	// 		// 	$qty_final = $input['not_std_qty'][$i+1];
	// 		// }
	// 		//-----------

	// 		if ($not_std_qty_array[$i][0] == "" ) {
	// 			$qty_final = (int)$std_qty;
	// 		} else {
	// 			$qty_final = (int)$not_std_qty_array[$i][0];
	// 		}

	// 		// dd($qty_final);
	// 		// dd($not_std_qty_array);
	// 		// dd($not_std_qty_array[$i][0]);
	// 		$qty_final = $std_qty;

	// 		try {
	// 			$table2 = new RequestLine;

	// 			$table2->request_header_id = $table->id;

	// 			$table2->item = $item;
	// 			$table2->item_t = $item_t;
				
	// 			$table2->size = $size;
	// 			$table2->size_t = $size_t;
				
	// 			$table2->color = $color;
	// 			$table2->color_t = $color_t;

	// 			$table2->hu = $hu;
	// 			$table2->uom = $uom;

	// 			$table2->std_qty = $qty_final;
	// 			$table2->std_uom = $std_uom;

	// 			$table2->deleted = 0;
	// 			// $table2->save();
				
	// 		}
	// 		catch (\Illuminate\Database\QueryException $e) {
	// 			$msg = "Problem to save in RequestLine";
	// 			return view('Request.error',compact('msg'));
	// 		}
	// 	}

	// 	return Redirect::to('/');
	// }

}