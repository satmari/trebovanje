<?php namespace App\Http\Controllers;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Database\Eloquent\ModelNotFoundException as ModelNotFoundException;
use Illuminate\Database\QueryException as QueryException;
use App\Exceptions\Handler;

use Illuminate\Http\Request;
//use Gbrock\Table\Facades\Table;

use App\trans_color;
use App\trans_item;
use App\trans_size;
use App\RequestHeader;
use App\RequestLine;
use DB;

use App\User;
use Bican\Roles\Models\Role;
use Bican\Roles\Models\Permission;
use Illuminate\Support\Facades\Redirect;
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

		if ($user->is('admin')) { 
		    return Redirect::to('/tableso');
		}
		if ($user->is('magacin')) { 
		    return Redirect::to('/tableso');
		}
		if ($user->is('modul')) { 
			return view('Request.index');   
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

    	$module_line = substr($module, 0, 1);
    	$module_name = substr($module, 1, 3);

    	// var_dump($module_line);
    	// var_dump($module_name);

		$so = DB::connection('sqlsrv3')->select(DB::raw("
			SELECT /*l.[Prod_ Order No_],*/
			   /*l.[Status],*/
			   p.[No_] as so,
			   l.[Item No_] as item,
			   /*l.[Shortcut Dimension 2 Code],*/
			   (RIGHT(l.[Shortcut Dimension 2 Code],5)) as po,
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

		$components = DB::connection('sqlsrv3')->select(DB::raw("
			SELECT 
		      c.[Item No_] as item
		      /*,c.[Variant Code] */
		      ,c.[PfsVertical Component] as color
		      ,c.[PfsHorizontal Component] as size 
		      /*,c.[Area Code]*/
		      ,l.[Item No_] as itemfg
		      ,l.[PfsHorizontal Component] as sizefg
			  ,l.[PfsVertical Component] as colorfg
			  /*,RIGHT(c.[Shortcut Dimension 2 Code],5) as po*/
			  ,c.[Shortcut Dimension 2 Code] as po
			  ,c.[Unit of Measure Code] as uom
			  ,b.[Barcode No_] as hu

			  /*  ,c.*  ,l.*  */
			  
			  FROM [Gordon_LIVE].[dbo].[GORDON\$Prod_ Order Component] as c
			  JOIN [Gordon_LIVE].[dbo].[GORDON\$Prod_ Order Line] as l ON c.[Prod_ Order No_] = l.[Prod_ Order No_] AND c.[Prod_ Order Line No_] = l.[Line No_]
			  INNER JOIN [Gordon_LIVE].[dbo].[GORDON\$Barcode] as b ON c.[Item No_] = b.[No_] AND c.[Variant Code] = b.[Variant Code] AND b.[Barcode No_] like 'NOT%'
			  WHERE l.[Status] = '0'  AND c.[Prod_ Order No_] like '".$so."' 
			  "));

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
			
			$item_t = DB::connection('sqlsrv')->select(DB::raw("SELECT item, item_t FROM trans_items WHERE item = '".$components[$i]->item."'"));
			$size_t = DB::connection('sqlsrv')->select(DB::raw("SELECT size, size_t FROM trans_sizes WHERE size = '".$components[$i]->size."'"));
			$color_t = DB::connection('sqlsrv')->select(DB::raw("SELECT color, color_t FROM trans_colors WHERE color = '".$components[$i]->color."'"));

			// dd($item_t[0]->item_t);
			// dd($size_t[0]->size_t);
			// dd($color_t[0]->color_t);

			// Item Missing
			if (!isset($item_t[0]->item)) {
				$msg = 'Item '.$components[$i]->item.' not exist in translation table!';
				return view('Request.error',compact('msg'));
			}
			if (!isset($size_t[0]->size)) {
				$msg = 'Size '.$components[$i]->size.' not exist in translation table!';
				return view('Request.error',compact('msg'));
			}
			if (!isset($color_t[0]->color)) {
				$msg = 'Color '.$components[$i]->color.' not exist in translation table!';
				return view('Request.error',compact('msg'));
			}

			// Translation is missing 
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
			
			if (($item_t_new == 'Care Label') OR ($item_t_new == 'Barkod') OR ($item_t_new == 'Fabric')) {
			  continue;
			}

			array_push($newarray, array(
		        "item" => $components[$i]->item, 
		        "item_t" => $item_t_new,
		        "size" => $components[$i]->size,
		        "size_t" => $size_t_new,
		        "color" => $components[$i]->color,
		        "color_t" => $color_t_new,
		        "uom" => $components[$i]->uom,
		        "hu" => $components[$i]->hu
		    ));

		}

		// dd($newarray);

		return view('Request.createtrebcon', compact('leader','so','po','itemfg','colorfg','sizefg','newarray'));
	}

	public function newso(Request $request)
	{
		$leader = Session::get('leader');
		return view('Request.createnewso', compact('leader'));
	}

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
			  /*,RIGHT(c.[Shortcut Dimension 2 Code],5) as po*/
			  ,c.[Shortcut Dimension 2 Code] as po
			  ,c.[Unit of Measure Code] as uom
			  ,b.[Barcode No_] as hu

			  /*  ,c.*  ,l.*  */
			  
			  FROM [Gordon_LIVE].[dbo].[GORDON\$Prod_ Order Component] as c
			  JOIN [Gordon_LIVE].[dbo].[GORDON\$Prod_ Order Line] as l ON c.[Prod_ Order No_] = l.[Prod_ Order No_] AND c.[Prod_ Order Line No_] = l.[Line No_]
			  INNER JOIN [Gordon_LIVE].[dbo].[GORDON\$Barcode] as b ON c.[Item No_] = b.[No_] AND c.[Variant Code] = b.[Variant Code] AND b.[Barcode No_] like 'NOT%'
			  WHERE l.[Status] = '3' AND c.[Prod_ Order No_] like '%".$po."' AND l.[PfsHorizontal Component] = '".$size."'
			  "));

		if ($components) {
			// dd($components);
			$po = $components[0]->po;
			$itemfg = $components[0]->itemfg;
			$colorfg = $components[0]->colorfg;
			$sizefg = $components[0]->sizefg;
		} else {
			$msg = 'There is no open PO with that size!!! ';
			return view('Request.error',compact('msg'));
		}
		


		$newarray = [];

		for ($i=0; $i < count($components); $i++) { 

			// dd($components[$i]->item);
			// dd($components[$i]->size);
			// dd($components[$i]->color);
			
			$item_t = DB::connection('sqlsrv')->select(DB::raw("SELECT item,item_t FROM trans_items WHERE item = '".$components[$i]->item."'"));
			$size_t = DB::connection('sqlsrv')->select(DB::raw("SELECT size,size_t FROM trans_sizes WHERE size = '".$components[$i]->size."'"));
			$color_t = DB::connection('sqlsrv')->select(DB::raw("SELECT color,color_t FROM trans_colors WHERE color = '".$components[$i]->color."'"));

			// dd($item_t[0]->item_t);
			// dd($size_t[0]->size_t);
			// dd($color_t[0]->color_t);

			if (($item_t[0]->item_t == 'Care Label') OR ($item_t[0]->item_t == 'Barkod') OR ($item_t[0]->item_t == 'Fabric')) {
			  continue;
			}

			if ((is_null($size_t[0]->size_t)) OR ($size_t[0]->size_t == '')) {
				$size_t_new = $size_t[0]->size_t;
			} else {
				$size_t_new = $size_t[0]->size_t;
			}

			if ((is_null($color_t[0]->color_t)) OR ($color_t[0]->color_t == '')) {
				$color_t_new = $color_t[0]->color_t;
			} else {
				$color_t_new = $color_t[0]->color_t;
			}

			// if (($item_t[0]->item_t == 'Kuma') OR ($item_t[0]->item_t == 'Elan')) {
			// 	$color_t_new = $color_t[0]->color_t;
			// } else {
			// 	$color_t_new = '';
			// }

			array_push($newarray, array(
		        "item" => $components[$i]->item, 
		        "item_t" => $item_t[0]->item_t, 
		        "size" => $components[$i]->size,
		        "size_t" => $size_t_new,
		        "color" => $components[$i]->color,
		        "color_t" => $color_t_new,
		        "uom" => $components[$i]->uom,
		        "hu" => $components[$i]->hu
		    ));

		}

		// dd($newarray);

		return view('Request.createtrebcon', compact('leader','so','po','itemfg','colorfg','sizefg','newarray'));
	}

	public function requeststoretreb(Request $request)
	{
		// $this->validate($request, ['']);
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

		$so = $input['so']; 
		$po = $input['po'];
		$itemfg = $input['itemfg'];
		$colorfg = $input['colorfg'];
		$sizefg = $input['sizefg'];
		$items = $input['items'];
		// dd($items);

		if ($so == '') {
			$status = 'TO CREATE';
		} else {
			$status = 'TO PRINT';	
		}
		

		if (isset($input['comment'])) {
			$comment = $input['comment'];
		} else {
			$comment = '';
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
			$table->deleted = 0;

			$table->comment = $comment;
			$table->save();
			
		}
		catch (\Illuminate\Database\QueryException $e) {
			$msg = "Problem to save in RequestHeader";
			return view('Request.error',compact('msg'));
		}
		
		// dd($table->id);
	
		// Record Line
		foreach ($items as $line) {
			
			list($item, $item_t, $color, $color_t, $size, $size_t, $uom, $hu) = explode('#', $line);
			// dd($line);

			try {
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

				$table2->deleted = 0;
				$table2->save();
				
			}
			catch (\Illuminate\Database\QueryException $e) {
				$msg = "Problem to save in RequestLine";
				return view('Request.error',compact('msg'));
			}
		}

		return Redirect::to('/');
	}

}