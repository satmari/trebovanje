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
// use App\RequestHeader;
// use App\RequestLine;
use App\RequestHeaderSap;
use App\RequestLineSap;
use DB;

use App\User;
use Bican\Roles\Models\Role;
use Bican\Roles\Models\Permission;
use Auth;

use Session;
use Validator;

class RequestSapController extends Controller {

	public function __construct()
	{
		$this->middleware('auth');
	}

	public function index()
	{
		//
		$user = User::find(Auth::id());

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
				$msg = 'Line is not autenticated';
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
		
		//dd('test');
		$so = [];
		
		// dd($so);

		// return view('RequestSap.createtreb', compact('leader','so'));
		return view('RequestSap.createnewso', compact('leader'));
	}
	
	public function newso_sap()
	{
		$leader = Session::get('leader');
		return view('RequestSap.createnewso', compact('leader'));
	}

	// IF MODULE NEED NEW PROD ORDER
	public function createnewso_sap(Request $request)
	{
		$this->validate($request, ['po'=>'required']);
		$input = $request->all(); 
		// dd($input);

		$po = $input['po'];
		// dd($po);
		if ($input['size'] == "") {
			$msg = 'Odaberite velicinu. Size is missing. ';
			return view('RequestSap.error',compact('msg'));
		} else {

			$size = $input['size'];
		}
		
		if (Auth::check())
		{
		    $userId = Auth::user()->id;
		    $module = Auth::user()->name;
		} else {
			$msg = 'Line is not autenticated';
			return view('RequestSap.error',compact('msg'));
		}

		$leader = Session::get('leader');
		if (!isset($leader)) {
			$msg = 'LineLeader is not autenticated';
			return view('RequestSap.error',compact('msg'));
		}

		$so = "";
		// dd(substr($module,0,2));

		if ($module == "WC01") {
			// Preparation 
			// $components = DB::connection('sqlsrv')->select(DB::raw("SELECT * FROM sap_coois WHERE wc = 'WC01' AND po like '%".$po."'  "));
			$components = DB::connection('sqlsrv')->select(DB::raw("SELECT * FROM sap_coois WHERE wc = 'WC01' AND po like '%".$po."%' AND substring(fg,14,5) = '".$size."' "));

		} elseif ($module == 'WC01_K') {
			// Preparation Kikinda
			$components = DB::connection('sqlsrv')->select(DB::raw("SELECT * FROM sap_coois WHERE wc = 'WC01_K' AND po like '%".$po."%' AND substring(fg,14,5) = '".$size."' "));

		} elseif ($module == 'WC02M') {
			// Preparation Kikinda
			$components = DB::connection('sqlsrv')->select(DB::raw("SELECT * FROM sap_coois WHERE wc = 'WC02M' AND po like '%".$po."%' AND substring(fg,14,5) = '".$size."' "));

		} elseif ($module == 'WC02M_K') {
			// Preparation Kikinda
			$components = DB::connection('sqlsrv')->select(DB::raw("SELECT * FROM sap_coois WHERE wc = 'WC02M_K' AND po like '%".$po."%' AND substring(fg,14,5) = '".$size."' "));

		} elseif (substr($module, 0, 2) == 'S-') {
			// Lines 
			$components = DB::connection('sqlsrv')->select(DB::raw("SELECT * FROM sap_coois WHERE (wc = 'WC03I' OR wc = 'WC03O') AND po like '%".$po."%' AND substring(fg,14,5) = '".$size."' "));

		} elseif (substr($module, 0, 2) == 'K-') {
			// Lines Kikinda
			$components = DB::connection('sqlsrv')->select(DB::raw("SELECT * FROM sap_coois WHERE (wc = 'WC03I_K' OR wc = 'WC03O_K') AND po like '%".$po."%' AND substring(fg,14,5) = '".$size."' "));

		} else {
			$components = DB::connection('sqlsrv')->select(DB::raw("SELECT * FROM sap_coois WHERE po like '%".$po."%' AND substring(fg,14,5) = '".$size."' "));

		}
		// dd($components);
			
		if ($components) {
			// dd($components);
			// var_dump($material);
			// dd($standard_qty);
		} else {
			$msg = 'Can not find components in coois_sap table !!!';
			return view('RequestSap.error',compact('msg'));
		}
		
		$newarray = [];

		for ($i=0; $i < count($components); $i++) { 

			$po = $input['po'];
			$po_sap = $components[$i]->po;
			$fg = $components[$i]->fg;
			$activity = $components[$i]->activity;
			$wc = $components[$i]->wc;
			$material = $components[$i]->material;
			$tpa = $components[$i]->tpa;

			
			if (!isset($components[$i]->list) OR (is_null($components[$i]->list))) {
				$list = '';
			} else {
				$list = $components[$i]->list;
			}

			if (!isset($components[$i]->uom) OR (is_null($components[$i]->uom))) {
				$uom = '';
			} else {
				$uom = $components[$i]->uom;
			}

			if (!isset($components[$i]->description) OR (is_null($components[$i]->description))) {
				$description = '';
			} else {
				$description = $components[$i]->description;
			}

			if (!isset($components[$i]->standard_qty) OR (is_null($components[$i]->standard_qty))) {
				$standard_qty = $i;
			} else {
				$standard_qty = (int)$components[$i]->standard_qty;
			}

			if (!isset($components[$i]->uom_desc) OR (is_null($components[$i]->uom_desc))) {
				$uom_desc = '';
			} else {
				$uom_desc = $components[$i]->uom_desc;
			}

			if (!isset($components[$i]->tpa) OR (is_null($components[$i]->tpa))) {
				$tpa = '';
			} else {
				$tpa = $components[$i]->tpa;
			}

			// Exclude
			if ($material == 'CUT_PIECE_001') {
			  continue;
			}

			array_push($newarray, array(
		        "material" => $material,
		        "uom" => $uom,
		        "description" => $description,
		        "standard_qty" => $standard_qty,
		        "uom_desc" => $uom_desc,
		        "tpa" => $tpa,
		    ));
		}

		// dd($newarray);
		return view('RequestSap.createtrebcon', compact('leader','po','po_sap','fg','activity','wc','list','newarray'));
	}

	public function requeststoretreb_sap(Request $request)
	{
		$this->validate($request, ['comment'=>'max:200']);
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

		if (!isset($input['items'])) {
			$msg = 'Izaberite odredjeni materijal!';
			return view('Request.error',compact('msg'));
		}


		$hidden = $input['hidden'];
		$items = $input['items'];
		// dd($items);

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

		$po = $input['po'];
		$po_sap = $input['po_sap'];
		$itemfg = $input['fg'];
		$activity = $input['activity'];
		$wc = $input['wc'];
		$list = $input['list'];
		$leader= $input['leader'];
		
		$exist =  DB::connection('sqlsrv')->select(DB::raw("SELECT id FROM request_header_sap WHERE po_sap = '".$po_sap."' AND module = '".$module."' "));
		// dd($exist);

		if(isset($exist[0]->id)) {
			$first_time = 'NO';
		} else {
			$first_time = 'YES';
		}
		// dd($first_time);

		$check_skeda_posum = DB::connection('sqlsrv4')->select(DB::raw("SELECT skeda FROM [posummary].[dbo].[pro] WHERE pro = '".$po_sap."' "));
		if (isset($check_skeda_posum[0])) {
			$skeda = $check_skeda_posum[0]->skeda;
		} else {
			$skeda = '';
		}
		// dd($skeda);

		  /*
		  UPDATE t
		  SET t.skeda = p.skeda
		  FROM [trebovanje].[dbo].[request_header_sap] as t
		  JOIN [posummary].[dbo].[pro] as p ON p.pro = po_sap
		  */
		 
		$exist_skeda = DB::connection('sqlsrv')->select(DB::raw("SELECT id FROM request_header_sap WHERE skeda = '".$skeda."' AND module = '".$module."' "));
		// dd($exist);

		if (isset($exist_skeda[0]->id)) {
			$first_time_skeda = 'NO';
		} else {
			$first_time_skeda = 'YES';
		}

		$approval_int =  DB::connection('sqlsrv2')->select(DB::raw("SELECT [Approval] as approval FROM [BdkCLZG].[dbo].[CNF_PO] WHERE POnum like  '%".$po_sap."%' "));
		// dd($approval_int);
		if(isset($approval_int[0]->approval)) {
			$approval = $approval_int[0]->approval;
		} else {
			$approval = NULL;
		}
		// dd($first_time);

		$so = '';
		$status = 'TO PRINT';	
		
		if (isset($input['comment'])) {
			$comment = $input['comment'];

		} else {
			$comment = '';
		}
		
		// Flash details
		/*
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
		*/
		$flash = "no info";
		
		//Record Header
		try {
			$table = new RequestHeaderSap;

			$table->name = $module." ".date("Y-m-d H:i:s");
			// $table->so = $input['so'];
			$table->po = $input['po'];
			$table->po_sap = $input['po_sap'];
			
			$table->style = $itemfg;
			// $table->color = $colorfg;
			// $table->size = $sizefg;

			$table->activity = $activity;
			$table->wc = $wc;
			$table->list = $list;
			
			$table->module = $module;
			$table->leader = $leader;

			$table->status = $status;
			$table->first_time = $first_time_skeda;
			$table->deleted = 0;

			$table->comment = $comment;

			$table->flash = $flash;
			$table->approval = $approval;

			$table->skeda = $skeda;

			$table->save();
			
		}
		catch (\Illuminate\Database\QueryException $e) {
			$msg = "Problem to save in RequestHeaderSap";
			return view('RequestSap.error',compact('msg'));
		}


		//Record Line
		for ($i=0; $i < count($items); $i++) { 

			// dd($items[$i]);
			list($material, $uom, $description, $standard_qty, $uom_desc, $tpa) = explode('#', $items[$i]);
	
			if ($not_std_qty_array[$i][0] == "" ) {
				$qty_final = (int)$standard_qty;
			} else {
				$qty_final = (int)$not_std_qty_array[$i][0];
			}

			try {
				$table2 = new RequestLineSap;

				$table2->request_header_id = $table->id;

				$table2->material = $material;
				$table2->uom = $uom;
				$table2->description = $description;
				$table2->standard_qty = $qty_final;
				$table2->uom_desc = $uom_desc;
				$table2->tpa = $tpa;
				
				$table2->deleted = 0;
				$table2->save();
				
			}
			catch (\Illuminate\Database\QueryException $e) {
				$msg = "Problem to save in RequestLineSap";
				return view('RequestSap.error',compact('msg'));
			}
		}
		return Redirect::to('/');
	}	
}