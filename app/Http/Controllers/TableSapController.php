<?php namespace App\Http\Controllers;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Database\Eloquent\ModelNotFoundException as ModelNotFoundException;
use Illuminate\Database\QueryException as QueryException;
use App\Exceptions\Handler;

use Illuminate\Http\Request;
//use Gbrock\Table\Facades\Table;
use Illuminate\Support\Facades\Redirect;

// use App\trans_color;
// use App\trans_item;
// use App\trans_size;
use App\temp_print_sap;
use App\RequestHeaderSap;
use App\RequestLineSap;
use DB;

use App\User;
use Bican\Roles\Models\Role;
use Bican\Roles\Models\Permission;
use Auth;

use Session;
use Validator;

class TableSapController extends Controller {

	public function __construct()
	{
		$this->middleware('auth');
	}

	public function index() {
		//
		$user = User::find(Auth::id());


		if ($user->is('modul')) { 
		    // if user has at least one role
		    $module = Auth::user()->name;
		    $data = DB::connection('sqlsrv')->select(DB::raw("SELECT
				h.id,
				h.name,
				h.style as stylefg,
				--h.color as colorfg,
				--h.size as sizefg,
				h.module,
				h.leader,
				h.status,
				h.po,
				h.flash,
				h.approval,
				h.wc,
				--h.so,
				h.first_time,
				l.id as lineid,
				--l.item,
				l.material,
				--l.color,
				--l.size,
				--l.std_qty,
				l.description,
				l.standard_qty,
				--l.std_uom,
				l.uom,
				l.uom_desc,
				l.tpa,
				--l.hu,
				h.comment,
				l.created_at,
				l.updated_at
				
			  FROM [trebovanje].[dbo].[request_line_sap] as l
			  JOIN [trebovanje].[dbo].[request_header_sap] as h ON l.request_header_id = h.id
			  WHERE l.deleted = 0 AND h.created_at >= DATEADD(day,-7,GETDATE()) AND h.module = '".$module."'
			  ORDER BY h.created_at desc
			  "));

			return view('TableSap.indexhistory', compact('data'));
		    
		}

		/*if ($user->is('admin')) { */
		    // if user has at least one role
		    $data = DB::connection('sqlsrv')->select(DB::raw("SELECT
				h.id,
				h.name,
				h.style as stylefg,
				--h.color as colorfg,
				--h.size as sizefg,
				h.module,
				h.leader,
				h.status,
				h.po,
				h.flash,
				h.approval,
				h.wc,
				--h.so,
				h.first_time,
				l.id as lineid,
				--l.item,
				l.material,
				--l.color,
				--l.size,
				--l.std_qty,
				l.description,
				l.standard_qty,
				--l.std_uom,
				l.uom,
				l.uom_desc,
				l.tpa,
				--l.hu,
				h.comment,
				l.created_at,
				l.updated_at
				
			  FROM [trebovanje].[dbo].[request_line_sap] as l
			  JOIN [trebovanje].[dbo].[request_header_sap] as h ON l.request_header_id = h.id
			  WHERE l.deleted = 0 AND h.created_at >= DATEADD(day,-7,GETDATE()) 
			  ORDER BY h.created_at desc
			  "));

			return view('TableSap.index', compact('data'));
		// }
	}

	public function indexso() {
		//
		$data = DB::connection('sqlsrv')->select(DB::raw("SELECT
			h.id,
			h.name,
			h.style as stylefg,
			--h.color as colorfg,
			--h.size as sizefg,
			h.module,
			h.leader,
			h.status,
			h.po,
			h.flash,
			h.approval,
			h.wc,
			--h.so,
			h.comment,
			h.first_time,
			h.created_at,
			h.updated_at

		  FROM [trebovanje].[dbo].[request_header_sap] as h
		  WHERE h.deleted = 0 AND h.created_at >= DATEADD(day,-15,GETDATE()) 
		  ORDER BY h.created_at desc
		  "));

		return view('TableSap.indexso', compact('data'));
	}

	public function indexsotoday() {
		//
		$data = DB::connection('sqlsrv')->select(DB::raw("SELECT
			h.id,
			h.name,
			h.style as stylefg,
			--h.color as colorfg,
			--h.size as sizefg,
			h.module,
			h.leader,
			h.status,
			h.po,
			h.flash,
			h.approval,
			h.wc,
			--h.so,
			h.comment,
			h.first_time,
			h.created_at,
			h.updated_at

		  FROM [trebovanje].[dbo].[request_header_sap] as h
		  WHERE (h.deleted = 0) AND
		  	(h.created_at BETWEEN CAST(GETDATE() AS DATE) AND DATEADD(DAY, 1, CAST(GETDATE() AS DATE))) AND
			((SUBSTRING(h.module, 0, 3) != 'K-') AND (SUBSTRING(h.module, 0, 3) != 'Z-')) AND
			((h.module != 'WC01_K') AND (h.module != 'WC01_S'))  AND
			((h.module != 'WC02M_K') AND (h.module != 'WC02M_S'))
		  ORDER BY h.created_at desc
		  "));

		return view('TableSap.indexsotoday', compact('data'));
	}

	public function indexsotodayk() {
		//
		$data = DB::connection('sqlsrv')->select(DB::raw("SELECT
			h.id,
			h.name,
			h.style as stylefg,
			--h.color as colorfg,
			--h.size as sizefg,
			h.module,
			h.leader,
			h.status,
			h.po,
			h.flash,
			h.approval,
			h.wc,
			--h.so,
			h.comment,
			h.first_time,
			h.created_at,
			h.updated_at

		  FROM [trebovanje].[dbo].[request_header_sap] as h
		  WHERE (h.deleted = 0) AND 
		  	(h.created_at BETWEEN CAST(GETDATE() AS DATE) AND DATEADD(DAY, 1, CAST(GETDATE() AS DATE))) AND 
			((SUBSTRING(h.module, 0, 3) = 'K-') OR (h.module = 'WC01_K') OR (h.module = 'WC02M_K'))
		  ORDER BY h.created_at desc
		  "));

		return view('TableSap.indexsotoday', compact('data'));
	}

	public function indexsotodays() {
		//
		$data = DB::connection('sqlsrv')->select(DB::raw("SELECT
			h.id,
			h.name,
			h.style as stylefg,
			--h.color as colorfg,
			--h.size as sizefg,
			h.module,
			h.leader,
			h.status,
			h.po,
			h.flash,
			h.approval,
			h.wc,
			--h.so,
			h.comment,
			h.first_time,
			h.created_at,
			h.updated_at

		  FROM [trebovanje].[dbo].[request_header_sap] as h
		  WHERE (h.deleted = 0) AND 
		  	(h.created_at BETWEEN CAST(GETDATE() AS DATE) AND DATEADD(DAY, 1, CAST(GETDATE() AS DATE))) AND 
		  	((SUBSTRING(h.module, 0, 3) = 'Z-') OR (h.module = 'WC01_S') OR (h.module = 'WC02M_S'))
		  ORDER BY h.created_at desc
		  "));

		return view('TableSap.indexsotoday', compact('data'));
	}

	public function toprint() {
		//
		$data = DB::connection('sqlsrv')->select(DB::raw("SELECT
			h.id,
			h.name,
			h.style as stylefg,
			--h.color as colorfg,
			--h.size as sizefg,
			h.module,
			h.leader,
			h.status,
			h.po,
			h.flash,
			h.approval,
			h.wc,
			--h.so,
			h.comment,
			h.first_time,
			h.created_at,
			h.updated_at

		  FROM [trebovanje].[dbo].[request_header_sap] as h
		  WHERE h.deleted = 0 AND h.status = 'TO PRINT'
		  ORDER BY h.created_at desc
		  "));

		// return view('Table.index', compact('data'));
		return view('TableSap.indexso_to_print', compact('data'));
	}

	public function indexall() {
		//
		$data = DB::connection('sqlsrv')->select(DB::raw("SELECT
				h.id,
				h.name,
				h.style as stylefg,
				--h.color as colorfg,
				--h.size as sizefg,
				h.module,
				h.leader,
				h.status,
				h.po,
				h.flash,
				h.approval,
				h.wc,
				--h.so,
				h.first_time,
				l.id as lineid,
				--l.item,
				l.material,
				--l.color,
				--l.size,
				--l.std_qty,
				l.description,
				l.standard_qty,
				--l.std_uom,
				l.uom,
				l.uom_desc,
				l.tpa,
				--l.hu,
				h.comment,
				l.created_at,
				l.updated_at
				
			  FROM [trebovanje].[dbo].[request_line_sap] as l
			  JOIN [trebovanje].[dbo].[request_header_sap] as h ON l.request_header_id = h.id
			  WHERE l.deleted = 0 
			  ORDER BY h.created_at desc
			  "));

			return view('TableSap.index', compact('data'));
	}

	public function indexsoall() {
		//
		$data = DB::connection('sqlsrv')->select(DB::raw("SELECT
			h.id,
			h.name,
			h.style as stylefg,
			--h.color as colorfg,
			--h.size as sizefg,
			h.module,
			h.leader,
			h.status,
			h.po,
			h.flash,
			h.approval,
			h.wc,
			--h.so,
			h.comment,
			h.first_time,
			h.created_at,
			h.updated_at

		  FROM [trebovanje].[dbo].[request_header_sap] as h
		  WHERE h.deleted = 0 
		  ORDER BY h.created_at desc
		  "));

		return view('TableSap.indexso', compact('data'));
	}

	public function request_lines($id) {

		$data = DB::connection('sqlsrv')->select(DB::raw("SELECT
				h.id,
				h.name,
				h.style as stylefg,
				--h.color as colorfg,
				--h.size as sizefg,
				h.module,
				h.leader,
				h.status,
				h.po,
				h.flash,
				h.approval,
				h.wc,
				--h.so,
				h.first_time,
				l.id as lineid,
				--l.item,
				l.material,
				--l.color,
				--l.size,
				--l.std_qty,
				l.description,
				l.standard_qty,
				--l.std_uom,
				l.uom,
				l.uom_desc,
				l.tpa,
				--l.hu,
				h.comment,
				l.created_at,
				l.updated_at
				
			  FROM [trebovanje].[dbo].[request_line_sap] as l
			  JOIN [trebovanje].[dbo].[request_header_sap] as h ON l.request_header_id = h.id
			  WHERE l.deleted = 0 AND h.id = '".$id."'
			  ORDER BY h.created_at desc
			  "));

		return view('TableSap.index', compact('data'));
	}

// PRINTING
	public function printrequest($id, Request $request) {

		$printer_name = Session::get('printer_name');
		
		// dd($printer_name);
		if ($printer_name != NULL) {
			// $printer = $printer_name;
		} else {
			return view('printer');
		}

		// dd($id);
		$data = DB::connection('sqlsrv')->select(DB::raw("SELECT
				h.id,
				h.name,
				h.style as stylefg,
				--h.color as colorfg,
				--h.size as sizefg,
				h.module,
				h.leader,
				h.status,
				h.po,
				h.flash,
				h.approval,
				h.wc,
				--h.so,
				h.first_time,
				l.id as lineid,
				--l.item,
				l.material,
				--l.color,
				--l.size,
				--l.std_qty,
				l.description,
				l.standard_qty,
				--l.std_uom,
				l.uom,
				l.uom_desc,
				l.tpa,
				--l.hu,
				h.comment,
				l.created_at,
				l.updated_at
				
			  FROM [trebovanje].[dbo].[request_line_sap] as l
			  JOIN [trebovanje].[dbo].[request_header_sap] as h ON l.request_header_id = h.id
			  WHERE l.deleted = 0 AND h.id = '".$id."'
		  "));

		// dd($data);
		// dd($data[0]->name);
		if (isset($data[0]->name)) {

		} else {
			$message = "Greska, za jedan on zahteva iz modula nema linija, vrovatno je linija obrisana rucno.";
			echo "<script type='text/javascript'>alert('$message');</script>";
		}


		// if (isset($data[0]->so)) {
			// $so = $data[0]->so;
		// } else {
			$so = "";
		// }

		for ($i=0; $i < 15 ; $i++) { 

			if (isset($data[$i]->material)) {
				${"item_{$i}"}=$data[$i]->material;	
			} else {
				${"item_{$i}"}="";	
			} 
			if (isset($data[$i]->uom)) {
				${"uom_{$i}"}=$data[$i]->uom;	
			}  else {
				${"uom_{$i}"}="";	
			}
			if (isset($data[$i]->description)) {
				${"item_t_{$i}"}=$data[$i]->description;	
			} else {
				${"item_t_{$i}"}="";	
			} 
			if (isset($data[$i]->standard_qty)) {
				${"std_qty_{$i}"}=$data[$i]->standard_qty;	
			}  else {
				${"std_qty_{$i}"}="";	
			} 
			if (isset($data[$i]->uom_desc)) {
				${"std_uom_{$i}"}=$data[$i]->uom_desc;
			} else {
				${"std_uom_{$i}"}="";
			}
			if (isset($data[$i]->tpa)) {
				${"tpa_{$i}"}=$data[$i]->tpa;
			} else {
				${"tpa_{$i}"}="";
			} 
			
		}
		// dd($data[0]->name);

		//Record temp_print
		try {
			$table = new temp_print_sap;

			$table->name = $data[0]->name;

			// $table->so = $so;
			$table->po = $data[0]->po;
			$table->flash = $data[0]->flash;
			$table->approval = $data[0]->approval;
			$table->first_time = $data[0]->first_time;
			$table->printer = $printer_name;

			$table->stylefg = $data[0]->stylefg;
			// $table->colorfg = $data[0]->colorfg;
			// $table->sizefg = $data[0]->sizefg;

			$table->module = $data[0]->module;
			$table->module_pk = $data[0]->module."-PK";
            $table->leader = $data[0]->leader;
            $table->comment = $data[0]->comment;

            $table->wc = $data[0]->wc;

			$table->item_0 = $item_0;
			$table->uom_0 = $uom_0;
			$table->item_t_0 = $item_t_0;
			$table->std_qty_0 = $std_qty_0;
			$table->std_uom_0 = $std_uom_0;
			$table->tpa_0 = $tpa_0;
			
			$table->item_1 = $item_1;
			$table->uom_1 = $uom_1;
			$table->item_t_1 = $item_t_1;
			$table->std_qty_1 = $std_qty_1;
			$table->std_uom_1 = $std_uom_1;
			$table->tpa_1 = $tpa_1;

			$table->item_2 = $item_2;
			$table->uom_2 = $uom_2;
			$table->item_t_2 = $item_t_2;
			$table->std_qty_2 = $std_qty_2;
			$table->std_uom_2 = $std_uom_2;
			$table->tpa_2 = $tpa_2;

			$table->item_3 = $item_3;
			$table->uom_3 = $uom_3;
			$table->item_t_3 = $item_t_3;
			$table->std_qty_3 = $std_qty_3;
			$table->std_uom_3 = $std_uom_3;
			$table->tpa_3 = $tpa_3;

			$table->item_4 = $item_4;
			$table->uom_4 = $uom_4;
			$table->item_t_4 = $item_t_4;
			$table->std_qty_4 = $std_qty_4;
			$table->std_uom_4 = $std_uom_4;
			$table->tpa_4 = $tpa_4;

			$table->item_5 = $item_5;
			$table->uom_5 = $uom_5;
			$table->item_t_5 = $item_t_5;
			$table->std_qty_5 = $std_qty_5;
			$table->std_uom_5 = $std_uom_5;
			$table->tpa_5 = $tpa_5;

			$table->item_6 = $item_6;
			$table->uom_6 = $uom_6;
			$table->item_t_6 = $item_t_6;
			$table->std_qty_6 = $std_qty_6;
			$table->std_uom_6 = $std_uom_6;
			$table->tpa_6 = $tpa_6;

			$table->item_7 = $item_7;
			$table->uom_7 = $uom_7;
			$table->item_t_7 = $item_t_7;
			$table->std_qty_7 = $std_qty_7;
			$table->std_uom_7 = $std_uom_7;
			$table->tpa_7 = $tpa_7;

			$table->item_8 = $item_8;
			$table->uom_8 = $uom_8;
			$table->item_t_8 = $item_t_8;
			$table->std_qty_8 = $std_qty_8;
			$table->std_uom_8 = $std_uom_8;
			$table->tpa_8 = $tpa_8;

			$table->item_9 = $item_9;
			$table->uom_9 = $uom_9;
			$table->item_t_9 = $item_t_9;
			$table->std_qty_9 = $std_qty_9;
			$table->std_uom_9 = $std_uom_9;
			$table->tpa_9 = $tpa_9;

			$table->item_10 = $item_10;
			$table->uom_10 = $uom_10;
			$table->item_t_10 = $item_t_10;
			$table->std_qty_10 = $std_qty_10;
			$table->std_uom_10 = $std_uom_10;
			$table->tpa_10 = $tpa_10;

			$table->item_11 = $item_11;
			$table->uom_11 = $uom_11;
			$table->item_t_11 = $item_t_11;
			$table->std_qty_11 = $std_qty_11;
			$table->std_uom_11 = $std_uom_11;
			$table->tpa_11 = $tpa_11;

			$table->item_12 = $item_12;
			$table->uom_12 = $uom_12;
			$table->item_t_12 = $item_t_12;
			$table->std_qty_12 = $std_qty_12;
			$table->std_uom_12 = $std_uom_12;
			$table->tpa_12 = $tpa_12;

			$table->item_13 = $item_13;
			$table->uom_13 = $uom_13;
			$table->item_t_13 = $item_t_13;
			$table->std_qty_13 = $std_qty_13;
			$table->std_uom_13 = $std_uom_13;
			$table->tpa_13 = $tpa_13;

			$table->item_14 = $item_14;
			$table->uom_14 = $uom_14;
			$table->item_t_14 = $item_t_14;
			$table->std_qty_14 = $std_qty_14;
			$table->std_uom_14 = $std_uom_14;
			$table->tpa_14 = $tpa_14;

			$table->save();
			
		}
		catch (\Illuminate\Database\QueryException $e) {
			$msg = "Problem to save in temp_print_sap";
			return view('RequestSap.error',compact('msg'));
		}

		try {
			$header = RequestHeaderSap::findOrFail($id);
			$header->status = "PRINTED";
			$header->save();
		}
			catch (\Illuminate\Database\QueryException $e) {
				$msg = "Problem to save header sap";
				return view('TableSap.error',compact('msg'));
		}
		
		return Redirect::to('/');
	}

	public function printall (Request $request) {

		$printer_name = Session::get('printer_name');
		
		// dd($printer_name);
		if ($printer_name != NULL) {
			// $printer = $printer_name;
		} else {
			return view('printer');
		}

		$main = DB::connection('sqlsrv')->select(DB::raw("SELECT
				id,
				--so,
				module
				FROM [trebovanje].[dbo].[request_header_sap]
			  		WHERE deleted = '0' AND 
			  		status = 'TO PRINT' AND 
			  		((SUBSTRING(module, 0, 3) != 'K-') AND (SUBSTRING(module, 0, 3) != 'Z-')) AND
			  		((module != 'WC01_K') AND (module != 'WC01_S')) AND 
			  		((module != 'WC02M_K') AND (module != 'WC02M_S'))
			  	ORDER BY module asc
			  "));
		// dd($main);

		for ($d=0; $d < count($main); $d++) { 
			
			$data = DB::connection('sqlsrv')->select(DB::raw("SELECT
				h.id,
				h.name,
				h.style as stylefg,
				--h.color as colorfg,
				--h.size as sizefg,
				h.module,
				h.leader,
				h.status,
				h.po,
				h.flash,
				h.approval,
				h.wc,
				--h.so,
				h.first_time,
				l.id as lineid,
				--l.item,
				l.material,
				--l.color,
				--l.size,
				--l.std_qty,
				l.description,
				l.standard_qty,
				--l.std_uom,
				l.uom,
				l.uom_desc,
				l.tpa,
				--l.hu,
				h.comment,
				l.created_at,
				l.updated_at
				
			  FROM [trebovanje].[dbo].[request_line_sap] as l
			  JOIN [trebovanje].[dbo].[request_header_sap] as h ON l.request_header_id = h.id
			  WHERE l.deleted = '0' AND 
			  h.status = 'TO PRINT' AND 
			  h.id = '".$main[$d]->id."' AND
			  ((SUBSTRING(h.module, 0, 3) != 'K-') AND (SUBSTRING(h.module, 0, 3) != 'Z-')) AND 
			  ((h.module != 'WC01_K') AND (h.module != 'WC01_S')) AND 
			  ((h.module != 'WC02M_K') AND (h.module != 'WC02M_S'))
			  ORDER BY h.module asc
			  "));
			// dd($data);

			// dd($data[0]->name);
			if (isset($data[0]->name)) {

			} else {
				$message = "Greska, za jedan on zahteva iz modula nema linija, vrovatno je linija obrisana rucno.";
				echo "<script type='text/javascript'>alert('$message');</script>";
			}
			
			// if (isset($data[0]->so)) {
				// $so = $data[0]->so;
			// } else {
				$so = "";
			// }

			for ($i=0; $i < 15 ; $i++) { 

				if (isset($data[$i]->material)) {
					${"item_{$i}"}=$data[$i]->material;	
				} else {
					${"item_{$i}"}="";	
				} 
				if (isset($data[$i]->uom)) {
					${"uom_{$i}"}=$data[$i]->uom;	
				}  else {
					${"uom_{$i}"}="";	
				}
				if (isset($data[$i]->description)) {
					${"item_t_{$i}"}=$data[$i]->description;	
				} else {
					${"item_t_{$i}"}="";	
				} 
				if (isset($data[$i]->standard_qty)) {
					${"std_qty_{$i}"}=$data[$i]->standard_qty;	
				}  else {
					${"std_qty_{$i}"}="";	
				} 
				if (isset($data[$i]->uom_desc)) {
					${"std_uom_{$i}"}=$data[$i]->uom_desc;
				} else {
					${"std_uom_{$i}"}="";
				} 
				if (isset($data[$i]->tpa)) {
					${"tpa_{$i}"}=$data[$i]->tpa;
				} else {
					${"tpa_{$i}"}="";
				} 
				
			}

			try {
				$table = new temp_print_sap;

				$table->name = $data[0]->name;

				// $table->so = $so;
				$table->po = $data[0]->po;
				$table->flash = $data[0]->flash;
				$table->approval = $data[0]->approval;
				$table->first_time = $data[0]->first_time;
				$table->printer = $printer_name;

				$table->stylefg = $data[0]->stylefg;
				// $table->colorfg = $data[0]->colorfg;
				// $table->sizefg = $data[0]->sizefg;

				$table->module = $data[0]->module;
				$table->module_pk = $data[0]->module."-PK";
	            $table->leader = $data[0]->leader;
	            $table->comment = $data[0]->comment;

	            $table->wc = $data[0]->wc;

				$table->item_0 = $item_0;
				$table->uom_0 = $uom_0;
				$table->item_t_0 = $item_t_0;
				$table->std_qty_0 = $std_qty_0;
				$table->std_uom_0 = $std_uom_0;
				$table->tpa_0 = $tpa_0;

				$table->item_1 = $item_1;
				$table->uom_1 = $uom_1;
				$table->item_t_1 = $item_t_1;
				$table->std_qty_1 = $std_qty_1;
				$table->std_uom_1 = $std_uom_1;
				$table->tpa_1 = $tpa_1;

				$table->item_2 = $item_2;
				$table->uom_2 = $uom_2;
				$table->item_t_2 = $item_t_2;
				$table->std_qty_2 = $std_qty_2;
				$table->std_uom_2 = $std_uom_2;
				$table->tpa_2 = $tpa_2;

				$table->item_3 = $item_3;
				$table->uom_3 = $uom_3;
				$table->item_t_3 = $item_t_3;
				$table->std_qty_3 = $std_qty_3;
				$table->std_uom_3 = $std_uom_3;
				$table->tpa_3 = $tpa_3;

				$table->item_4 = $item_4;
				$table->uom_4 = $uom_4;
				$table->item_t_4 = $item_t_4;
				$table->std_qty_4 = $std_qty_4;
				$table->std_uom_4 = $std_uom_4;
				$table->tpa_4 = $tpa_4;

				$table->item_5 = $item_5;
				$table->uom_5 = $uom_5;
				$table->item_t_5 = $item_t_5;
				$table->std_qty_5 = $std_qty_5;
				$table->std_uom_5 = $std_uom_5;
				$table->tpa_5 = $tpa_5;

				$table->item_6 = $item_6;
				$table->uom_6 = $uom_6;
				$table->item_t_6 = $item_t_6;
				$table->std_qty_6 = $std_qty_6;
				$table->std_uom_6 = $std_uom_6;
				$table->tpa_6 = $tpa_6;

				$table->item_7 = $item_7;
				$table->uom_7 = $uom_7;
				$table->item_t_7 = $item_t_7;
				$table->std_qty_7 = $std_qty_7;
				$table->std_uom_7 = $std_uom_7;
				$table->tpa_7 = $tpa_7;

				$table->item_8 = $item_8;
				$table->uom_8 = $uom_8;
				$table->item_t_8 = $item_t_8;
				$table->std_qty_8 = $std_qty_8;
				$table->std_uom_8 = $std_uom_8;
				$table->tpa_8 = $tpa_8;

				$table->item_9 = $item_9;
				$table->uom_9 = $uom_9;
				$table->item_t_9 = $item_t_9;
				$table->std_qty_9 = $std_qty_9;
				$table->std_uom_9 = $std_uom_9;
				$table->tpa_9 = $tpa_9;

				$table->item_10 = $item_10;
				$table->uom_10 = $uom_10;
				$table->item_t_10 = $item_t_10;
				$table->std_qty_10 = $std_qty_10;
				$table->std_uom_10 = $std_uom_10;
				$table->tpa_10 = $tpa_10;

				$table->item_11 = $item_11;
				$table->uom_11 = $uom_11;
				$table->item_t_11 = $item_t_11;
				$table->std_qty_11 = $std_qty_11;
				$table->std_uom_11 = $std_uom_11;
				$table->tpa_11 = $tpa_11;

				$table->item_12 = $item_12;
				$table->uom_12 = $uom_12;
				$table->item_t_12 = $item_t_12;
				$table->std_qty_12 = $std_qty_12;
				$table->std_uom_12 = $std_uom_12;
				$table->tpa_12 = $tpa_12;

				$table->item_13 = $item_13;
				$table->uom_13 = $uom_13;
				$table->item_t_13 = $item_t_13;
				$table->std_qty_13 = $std_qty_13;
				$table->std_uom_13 = $std_uom_13;
				$table->tpa_13 = $tpa_13;

				$table->item_14 = $item_14;
				$table->uom_14 = $uom_14;
				$table->item_t_14 = $item_t_14;
				$table->std_qty_14 = $std_qty_14;
				$table->std_uom_14 = $std_uom_14;
				$table->tpa_14 = $tpa_14;

				$table->save();
				
			}
			catch (\Illuminate\Database\QueryException $e) {
				$msg = "Problem to save in temp_print_sap";
				return view('RequestSap.error',compact('msg'));
			}

			
			try {
				$header = RequestHeaderSap::findOrFail($main[$d]->id);
				$header->status = "PRINTED";
				$header->save();
			}
				catch (\Illuminate\Database\QueryException $e) {
					$msg = "Problem to print header sap";
					return view('TableSap.error',compact('msg'));
			}


		}
		
		return Redirect::to('/');
	}

	public function printallk (Request $request) {

		$printer_name = Session::get('printer_name');
		
		// dd($printer_name);
		if ($printer_name != NULL) {
			// $printer = $printer_name;
		} else {
			return view('printer');
		}

		$main = DB::connection('sqlsrv')->select(DB::raw("SELECT
				id,
				--so,
				module
				FROM [trebovanje].[dbo].[request_header_sap]
			  	WHERE deleted = '0' AND 
			  	status = 'TO PRINT' AND 
			  	((SUBSTRING(module, 0, 3) = 'K-') OR (module = 'WC01_K') OR (module = 'WC02M_K'))
			  	ORDER BY module asc
			  "));
		// dd($main);

		for ($d=0; $d < count($main); $d++) { 
			
			$data = DB::connection('sqlsrv')->select(DB::raw("SELECT
				h.id,
				h.name,
				h.style as stylefg,
				--h.color as colorfg,
				--h.size as sizefg,
				h.module,
				h.leader,
				h.status,
				h.po,
				h.flash,
				h.approval,
				h.wc,
				--h.so,
				h.first_time,
				l.id as lineid,
				--l.item,
				l.material,
				--l.color,
				--l.size,
				--l.std_qty,
				l.description,
				l.standard_qty,
				--l.std_uom,
				l.uom,
				l.uom_desc,
				l.tpa,
				--l.hu,
				h.comment,
				l.created_at,
				l.updated_at
				
			  FROM [trebovanje].[dbo].[request_line_sap] as l
			  JOIN [trebovanje].[dbo].[request_header_sap] as h ON l.request_header_id = h.id
			  WHERE l.deleted = 0 AND 
			  h.status = 'TO PRINT' AND 
			  h.id = '".$main[$d]->id."' AND ((SUBSTRING(h.module, 0, 3) = 'K-') OR (h.module = 'WC01_K') OR (h.module = 'WC02M_K'))
			  ORDER BY h.module asc
			  "));
			// dd($data);

			// dd($data[0]->name);
			if (isset($data[0]->name)) {

			} else {
				$message = "Greska, za jedan on zahteva iz modula nema linija, vrovatno je linija obrisana rucno.";
				echo "<script type='text/javascript'>alert('$message');</script>";
			}
			
			// if (isset($data[0]->so)) {
				// $so = $data[0]->so;
			// } else {
				$so = "";
			// }

			for ($i=0; $i < 15 ; $i++) { 

				if (isset($data[$i]->material)) {
					${"item_{$i}"}=$data[$i]->material;	
				} else {
					${"item_{$i}"}="";	
				} 
				if (isset($data[$i]->uom)) {
					${"uom_{$i}"}=$data[$i]->uom;	
				}  else {
					${"uom_{$i}"}="";	
				}
				if (isset($data[$i]->description)) {
					${"item_t_{$i}"}=$data[$i]->description;	
				} else {
					${"item_t_{$i}"}="";	
				} 
				if (isset($data[$i]->standard_qty)) {
					${"std_qty_{$i}"}=$data[$i]->standard_qty;	
				}  else {
					${"std_qty_{$i}"}="";	
				} 
				if (isset($data[$i]->uom_desc)) {
					${"std_uom_{$i}"}=$data[$i]->uom_desc;
				} else {
					${"std_uom_{$i}"}="";
				}
				if (isset($data[$i]->tpa)) {
					${"tpa_{$i}"}=$data[$i]->tpa;
				} else {
					${"tpa_{$i}"}="";
				} 
				
			}

			try {
				$table = new temp_print_sap;

				$table->name = $data[0]->name;

				// $table->so = $so;
				$table->po = $data[0]->po;
				$table->flash = $data[0]->flash;
				$table->approval = $data[0]->approval;
				$table->first_time = $data[0]->first_time;
				$table->printer = $printer_name;

				$table->stylefg = $data[0]->stylefg;
				// $table->colorfg = $data[0]->colorfg;
				// $table->sizefg = $data[0]->sizefg;

				$table->module = $data[0]->module;
				$table->module_pk = $data[0]->module."-PK";
	            $table->leader = $data[0]->leader;
	            $table->comment = $data[0]->comment;

	            $table->wc = $data[0]->wc;

				$table->item_0 = $item_0;
				$table->uom_0 = $uom_0;
				$table->item_t_0 = $item_t_0;
				$table->std_qty_0 = $std_qty_0;
				$table->std_uom_0 = $std_uom_0;
				$table->tpa_0 = $tpa_0;
				
				$table->item_1 = $item_1;
				$table->uom_1 = $uom_1;
				$table->item_t_1 = $item_t_1;
				$table->std_qty_1 = $std_qty_1;
				$table->std_uom_1 = $std_uom_1;
				$table->tpa_1 = $tpa_1;

				$table->item_2 = $item_2;
				$table->uom_2 = $uom_2;
				$table->item_t_2 = $item_t_2;
				$table->std_qty_2 = $std_qty_2;
				$table->std_uom_2 = $std_uom_2;
				$table->tpa_2 = $tpa_2;

				$table->item_3 = $item_3;
				$table->uom_3 = $uom_3;
				$table->item_t_3 = $item_t_3;
				$table->std_qty_3 = $std_qty_3;
				$table->std_uom_3 = $std_uom_3;
				$table->tpa_3 = $tpa_3;

				$table->item_4 = $item_4;
				$table->uom_4 = $uom_4;
				$table->item_t_4 = $item_t_4;
				$table->std_qty_4 = $std_qty_4;
				$table->std_uom_4 = $std_uom_4;
				$table->tpa_4 = $tpa_4;

				$table->item_5 = $item_5;
				$table->uom_5 = $uom_5;
				$table->item_t_5 = $item_t_5;
				$table->std_qty_5 = $std_qty_5;
				$table->std_uom_5 = $std_uom_5;
				$table->tpa_5 = $tpa_5;

				$table->item_6 = $item_6;
				$table->uom_6 = $uom_6;
				$table->item_t_6 = $item_t_6;
				$table->std_qty_6 = $std_qty_6;
				$table->std_uom_6 = $std_uom_6;
				$table->tpa_6 = $tpa_6;

				$table->item_7 = $item_7;
				$table->uom_7 = $uom_7;
				$table->item_t_7 = $item_t_7;
				$table->std_qty_7 = $std_qty_7;
				$table->std_uom_7 = $std_uom_7;
				$table->tpa_7 = $tpa_7;

				$table->item_8 = $item_8;
				$table->uom_8 = $uom_8;
				$table->item_t_8 = $item_t_8;
				$table->std_qty_8 = $std_qty_8;
				$table->std_uom_8 = $std_uom_8;
				$table->tpa_8 = $tpa_8;

				$table->item_9 = $item_9;
				$table->uom_9 = $uom_9;
				$table->item_t_9 = $item_t_9;
				$table->std_qty_9 = $std_qty_9;
				$table->std_uom_9 = $std_uom_9;
				$table->tpa_9 = $tpa_9;

				$table->item_10 = $item_10;
				$table->uom_10 = $uom_10;
				$table->item_t_10 = $item_t_10;
				$table->std_qty_10 = $std_qty_10;
				$table->std_uom_10 = $std_uom_10;
				$table->tpa_10 = $tpa_10;

				$table->item_11 = $item_11;
				$table->uom_11 = $uom_11;
				$table->item_t_11 = $item_t_11;
				$table->std_qty_11 = $std_qty_11;
				$table->std_uom_11 = $std_uom_11;
				$table->tpa_11 = $tpa_11;

				$table->item_12 = $item_12;
				$table->uom_12 = $uom_12;
				$table->item_t_12 = $item_t_12;
				$table->std_qty_12 = $std_qty_12;
				$table->std_uom_12 = $std_uom_12;
				$table->tpa_12 = $tpa_12;

				$table->item_13 = $item_13;
				$table->uom_13 = $uom_13;
				$table->item_t_13 = $item_t_13;
				$table->std_qty_13 = $std_qty_13;
				$table->std_uom_13 = $std_uom_13;
				$table->tpa_13 = $tpa_13;

				$table->item_14 = $item_14;
				$table->uom_14 = $uom_14;
				$table->item_t_14 = $item_t_14;
				$table->std_qty_14 = $std_qty_14;
				$table->std_uom_14 = $std_uom_14;
				$table->tpa_14 = $tpa_14;
			
				$table->save();
				
			}
			catch (\Illuminate\Database\QueryException $e) {
				$msg = "Problem to save in temp_print_sap";
				return view('RequestSap.error',compact('msg'));
			}

			
			try {
				$header = RequestHeaderSap::findOrFail($main[$d]->id);
				$header->status = "PRINTED";
				$header->save();
			}
				catch (\Illuminate\Database\QueryException $e) {
					$msg = "Problem to print header sap";
					return view('TableSap.error',compact('msg'));
			}

		}
		
		return Redirect::to('/');
	}

	public function printalls (Request $request) {

		$printer_name = Session::get('printer_name');
		
		// dd($printer_name);
		if ($printer_name != NULL) {
			// $printer = $printer_name;
		} else {
			return view('printer');
		}

		$main = DB::connection('sqlsrv')->select(DB::raw("SELECT
				id,
				--so,
				module
				FROM [trebovanje].[dbo].[request_header_sap]
			  	WHERE deleted = '0' AND 
			  	status = 'TO PRINT' AND 
			  	((SUBSTRING(module, 0, 3) = 'Z-') OR (module = 'WC01_S') OR (module = 'WC02M_S'))
			  	ORDER BY module asc
			  "));
		// dd($main);
	

		for ($d=0; $d < count($main); $d++) { 
			
			$data = DB::connection('sqlsrv')->select(DB::raw("SELECT
				h.id,
				h.name,
				h.style as stylefg,
				--h.color as colorfg,
				--h.size as sizefg,
				h.module,
				h.leader,
				h.status,
				h.po,
				h.flash,
				h.approval,
				h.wc,
				--h.so,
				h.first_time,
				l.id as lineid,
				--l.item,
				l.material,
				--l.color,
				--l.size,
				--l.std_qty,
				l.description,
				l.standard_qty,
				--l.std_uom,
				l.uom,
				l.uom_desc,
				l.tpa,
				--l.hu,
				h.comment,
				l.created_at,
				l.updated_at
				
			  FROM [trebovanje].[dbo].[request_line_sap] as l
			  JOIN [trebovanje].[dbo].[request_header_sap] as h ON l.request_header_id = h.id
			  WHERE l.deleted = 0 AND
			  	h.status = 'TO PRINT' AND 
			  	h.id = '".$main[$d]->id."' AND ((SUBSTRING(h.module, 0, 3) = 'Z-') OR (h.module = 'WC01_S') OR (h.module = 'WC02M_S'))
			  ORDER BY h.module asc
			  "));
			// dd($data);

			// dd($data[0]->name);
			if (isset($data[0]->name)) {

			} else {
				$message = "Greska, za jedan on zahteva iz modula nema linija, vrovatno je linija obrisana rucno.";
				echo "<script type='text/javascript'>alert('$message');</script>";
			}
			
			// if (isset($data[0]->so)) {
				// $so = $data[0]->so;
			// } else {
				$so = "";
			// }

			for ($i=0; $i < 15 ; $i++) { 

				if (isset($data[$i]->material)) {
					${"item_{$i}"}=$data[$i]->material;	
				} else {
					${"item_{$i}"}="";	
				} 
				if (isset($data[$i]->uom)) {
					${"uom_{$i}"}=$data[$i]->uom;	
				}  else {
					${"uom_{$i}"}="";	
				}
				if (isset($data[$i]->description)) {
					${"item_t_{$i}"}=$data[$i]->description;	
				} else {
					${"item_t_{$i}"}="";	
				} 
				if (isset($data[$i]->standard_qty)) {
					${"std_qty_{$i}"}=$data[$i]->standard_qty;	
				}  else {
					${"std_qty_{$i}"}="";	
				} 
				if (isset($data[$i]->uom_desc)) {
					${"std_uom_{$i}"}=$data[$i]->uom_desc;
				} else {
					${"std_uom_{$i}"}="";
				}
				if (isset($data[$i]->tpa)) {
					${"tpa_{$i}"}=$data[$i]->tpa;
				} else {
					${"tpa_{$i}"}="";
				} 
				
			}

			try {
				$table = new temp_print_sap;

				$table->name = $data[0]->name;

				// $table->so = $so;
				$table->po = $data[0]->po;
				$table->flash = $data[0]->flash;
				$table->approval = $data[0]->approval;
				$table->first_time = $data[0]->first_time;
				$table->printer = $printer_name;

				$table->stylefg = $data[0]->stylefg;
				// $table->colorfg = $data[0]->colorfg;
				// $table->sizefg = $data[0]->sizefg;

				$table->module = $data[0]->module;
				$table->module_pk = $data[0]->module."-PK";
	            $table->leader = $data[0]->leader;
	            $table->comment = $data[0]->comment;

	            $table->wc = $data[0]->wc;

				$table->item_0 = $item_0;
				$table->uom_0 = $uom_0;
				$table->item_t_0 = $item_t_0;
				$table->std_qty_0 = $std_qty_0;
				$table->std_uom_0 = $std_uom_0;
				$table->tpa_0 = $tpa_0;
				
				$table->item_1 = $item_1;
				$table->uom_1 = $uom_1;
				$table->item_t_1 = $item_t_1;
				$table->std_qty_1 = $std_qty_1;
				$table->std_uom_1 = $std_uom_1;
				$table->tpa_1 = $tpa_1;

				$table->item_2 = $item_2;
				$table->uom_2 = $uom_2;
				$table->item_t_2 = $item_t_2;
				$table->std_qty_2 = $std_qty_2;
				$table->std_uom_2 = $std_uom_2;
				$table->tpa_2 = $tpa_2;

				$table->item_3 = $item_3;
				$table->uom_3 = $uom_3;
				$table->item_t_3 = $item_t_3;
				$table->std_qty_3 = $std_qty_3;
				$table->std_uom_3 = $std_uom_3;
				$table->tpa_3 = $tpa_3;

				$table->item_4 = $item_4;
				$table->uom_4 = $uom_4;
				$table->item_t_4 = $item_t_4;
				$table->std_qty_4 = $std_qty_4;
				$table->std_uom_4 = $std_uom_4;
				$table->tpa_4 = $tpa_4;

				$table->item_5 = $item_5;
				$table->uom_5 = $uom_5;
				$table->item_t_5 = $item_t_5;
				$table->std_qty_5 = $std_qty_5;
				$table->std_uom_5 = $std_uom_5;
				$table->tpa_5 = $tpa_5;

				$table->item_6 = $item_6;
				$table->uom_6 = $uom_6;
				$table->item_t_6 = $item_t_6;
				$table->std_qty_6 = $std_qty_6;
				$table->std_uom_6 = $std_uom_6;
				$table->tpa_6 = $tpa_6;

				$table->item_7 = $item_7;
				$table->uom_7 = $uom_7;
				$table->item_t_7 = $item_t_7;
				$table->std_qty_7 = $std_qty_7;
				$table->std_uom_7 = $std_uom_7;
				$table->tpa_7 = $tpa_7;

				$table->item_8 = $item_8;
				$table->uom_8 = $uom_8;
				$table->item_t_8 = $item_t_8;
				$table->std_qty_8 = $std_qty_8;
				$table->std_uom_8 = $std_uom_8;
				$table->tpa_8 = $tpa_8;

				$table->item_9 = $item_9;
				$table->uom_9 = $uom_9;
				$table->item_t_9 = $item_t_9;
				$table->std_qty_9 = $std_qty_9;
				$table->std_uom_9 = $std_uom_9;
				$table->tpa_9 = $tpa_9;

				$table->item_10 = $item_10;
				$table->uom_10 = $uom_10;
				$table->item_t_10 = $item_t_10;
				$table->std_qty_10 = $std_qty_10;
				$table->std_uom_10 = $std_uom_10;
				$table->tpa_10 = $tpa_10;

				$table->item_11 = $item_11;
				$table->uom_11 = $uom_11;
				$table->item_t_11 = $item_t_11;
				$table->std_qty_11 = $std_qty_11;
				$table->std_uom_11 = $std_uom_11;
				$table->tpa_11 = $tpa_11;

				$table->item_12 = $item_12;
				$table->uom_12 = $uom_12;
				$table->item_t_12 = $item_t_12;
				$table->std_qty_12 = $std_qty_12;
				$table->std_uom_12 = $std_uom_12;
				$table->tpa_12 = $tpa_12;

				$table->item_13 = $item_13;
				$table->uom_13 = $uom_13;
				$table->item_t_13 = $item_t_13;
				$table->std_qty_13 = $std_qty_13;
				$table->std_uom_13 = $std_uom_13;
				$table->tpa_13 = $tpa_13;

				$table->item_14 = $item_14;
				$table->uom_14 = $uom_14;
				$table->item_t_14 = $item_t_14;
				$table->std_qty_14 = $std_qty_14;
				$table->std_uom_14 = $std_uom_14;
				$table->tpa_14 = $tpa_14;
			
				$table->save();
				
			}
			catch (\Illuminate\Database\QueryException $e) {
				$msg = "Problem to save in temp_print_sap";
				return view('RequestSap.error',compact('msg'));
			}

			
			try {
				$header = RequestHeaderSap::findOrFail($main[$d]->id);
				$header->status = "PRINTED";
				$header->save();
			}
				catch (\Illuminate\Database\QueryException $e) {
					$msg = "Problem to print header sap";
					return view('TableSap.error',compact('msg'));
			}
		}
		
		return Redirect::to('/');
	}

// DELETE $ EDIT
	public function delete_header($id)	{
		
		try {

		$header = RequestHeaderSap::findOrFail($id);
		$header->status = "DELETED";
		$header->deleted = 1;
		$header->save();
		
		}
			catch (\Illuminate\Database\QueryException $e) {
				$msg = "Problem to delete header sap";
				return view('TableSap.error',compact('msg'));
		}

		$lines = DB::connection('sqlsrv')->select(DB::raw("SELECT id FROM request_line_sap WHERE request_header_id = '".$id."'"));

		foreach ($lines as $line) {

			try {
				$l = RequestLineSap::findOrFail($line->id);
				$l->deleted = 1;
				$l->save();
				// $l->delete();
			}
				catch (\Illuminate\Database\QueryException $e) {
					$msg = "Problem to delete line sap";
					return view('TableSap.error',compact('msg'));
			}
		}

		return Redirect::to('/');
	}

	public function delete_line($id) {
		try {

		$line = RequestLineSap::findOrFail($id);
		$line->deleted = 1;
		$line->save();
		// $line->delete();

		}
		catch (\Illuminate\Database\QueryException $e) {
			$msg = "Problem to delete line sap";
			return view('TableSap.error',compact('msg'));
		}

		$header_line_id = $line->request_header_id;
		$lines = DB::connection('sqlsrv')->select(DB::raw("SELECT id FROM request_line_sap WHERE request_header_id = '".$header_line_id."' AND deleted = 0"));

		if (!isset($lines[0]->id)) {

			try {

			$header = RequestHeaderSap::findOrFail($header_line_id);
			$header->status = "DELETED";
			$header->deleted = 1;
			$header->save();
			
			}
				catch (\Illuminate\Database\QueryException $e) {
					$msg = "Problem to delete header sap";
					return view('TableSap.error',compact('msg'));
			}
		}

		return Redirect::to('/');
	}

	public function edit_header($id) {

		$data = RequestHeaderSap::findOrFail($id);		
		return view('TableSap.edit_header', compact('data'));
	}

	public function update_header($id, Request $request) {

		//
		$this->validate($request, ['first'=>'required']);
		$input = $request->all(); 

		$table = RequestHeaderSap::findOrFail($id);		
		//dd($input);

		try {		
			
			$table->first_time = $input['first'];
			$table->save();
		}
		catch (\Illuminate\Database\QueryException $e) {
			return view('TableSap.error');			
		}
		
		return Redirect::to('/');
	}


}
