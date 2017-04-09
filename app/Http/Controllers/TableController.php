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

class TableController extends Controller {

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
				h.color as colorfg,
				h.size as sizefg,
				h.module,
				h.leader,
				h.status,
				h.po,
				h.flash,
				h.so,
				h.first_time,
				l.id as lineid,
				l.item,
				l.color,
				l.size,
				l.hu,
				h.comment,
				l.created_at
				
			  FROM [trebovanje].[dbo].[request_line] as l
			  JOIN [trebovanje].[dbo].[request_header] as h ON l.request_header_id = h.id
			  WHERE l.deleted = 0 AND h.created_at >= DATEADD(day,-7,GETDATE()) AND h.module = '".$module."'
			  ORDER BY h.created_at desc
			  "));

			return view('Table.indexhistory', compact('data'));
		    
		}

		/*if ($user->is('admin')) { */
		    // if user has at least one role
		    $data = DB::connection('sqlsrv')->select(DB::raw("SELECT
				h.id,
				h.name,
				h.style as stylefg,
				h.color as colorfg,
				h.size as sizefg,
				h.module,
				h.leader,
				h.status,
				h.po,
				h.flash,
				h.so,
				h.first_time,
				l.id as lineid,
				l.item,
				l.color,
				l.size,
				l.hu,
				h.comment,
				l.created_at

			  FROM [trebovanje].[dbo].[request_line] as l
			  JOIN [trebovanje].[dbo].[request_header] as h ON l.request_header_id = h.id
			  WHERE l.deleted = 0 AND h.created_at >= DATEADD(day,-15,GETDATE()) 
			  ORDER BY h.created_at desc
			  "));

			return view('Table.index', compact('data'));
		// }
	}

	public function request_lines($id) {

		$data = DB::connection('sqlsrv')->select(DB::raw("SELECT
				h.id,
				h.name,
				h.style as stylefg,
				h.color as colorfg,
				h.size as sizefg,
				h.module,
				h.leader,
				h.status,
				h.po,
				h.flash,
				h.so,
				h.first_time,
				l.id as lineid,
				l.item,
				l.color,
				l.size,
				l.hu,
				h.comment,
				l.created_at

			  FROM [trebovanje].[dbo].[request_line] as l
			  JOIN [trebovanje].[dbo].[request_header] as h ON l.request_header_id = h.id
			  WHERE l.deleted = 0 AND h.id = '".$id."' 
			  ORDER BY h.created_at desc
			  "));

		return view('Table.index', compact('data'));


	}

	public function indexso() {
		//
		$data = DB::connection('sqlsrv')->select(DB::raw("SELECT
			h.id,
			h.name,
			h.style as stylefg,
			h.color as colorfg,
			h.size as sizefg,
			h.module,
			h.leader,
			h.status,
			h.po,
			h.flash,
			h.so,
			h.comment,
			h.first_time,
			h.created_at

		  FROM [trebovanje].[dbo].[request_header] as h
		  WHERE h.deleted = 0 AND h.created_at >= DATEADD(day,-15,GETDATE()) 
		  ORDER BY h.created_at desc
		  "));

		return view('Table.indexso', compact('data'));
	}

	public function indexall() {
		//
		$data = DB::connection('sqlsrv')->select(DB::raw("SELECT
				h.id,
				h.name,
				h.style as stylefg,
				h.color as colorfg,
				h.size as sizefg,
				h.module,
				h.leader,
				h.status,
				h.po,
				h.flash,
				h.so,
				h.first_time,
				l.id as lineid,
				l.item,
				l.color,
				l.size,
				l.hu,
				h.comment,
				l.created_at

			  FROM [trebovanje].[dbo].[request_line] as l
			  JOIN [trebovanje].[dbo].[request_header] as h ON l.request_header_id = h.id
			  WHERE l.deleted = 0 
			  ORDER BY h.created_at desc
			  "));

			return view('Table.index', compact('data'));
	}

	public function indexsoall() {
		//
		$data = DB::connection('sqlsrv')->select(DB::raw("SELECT
			h.id,
			h.name,
			h.style as stylefg,
			h.color as colorfg,
			h.size as sizefg,
			h.module,
			h.leader,
			h.status,
			h.po,
			h.flash,
			h.so,
			h.comment,
			h.first_time,
			h.created_at

		  FROM [trebovanje].[dbo].[request_header] as h
		  WHERE h.deleted = 0 
		  ORDER BY h.created_at desc
		  "));

		return view('Table.indexso', compact('data'));
	}

	public function indexsotoday() {
		//
		$data = DB::connection('sqlsrv')->select(DB::raw("SELECT
			h.id,
			h.name,
			h.style as stylefg,
			h.color as colorfg,
			h.size as sizefg,
			h.module,
			h.leader,
			h.status,
			h.po,
			h.flash,
			h.so,
			h.comment,
			h.first_time,
			h.created_at

		  FROM [trebovanje].[dbo].[request_header] as h
		  WHERE h.deleted = 0 AND h.created_at BETWEEN CAST(GETDATE() AS DATE) AND DATEADD(DAY, 1, CAST(GETDATE() AS DATE))
		  ORDER BY h.created_at desc
		  "));

		return view('Table.indexsotoday', compact('data'));
	}

	public function toprint() {
		//
		$data = DB::connection('sqlsrv')->select(DB::raw("SELECT
			h.id,
			h.name,
			h.style as stylefg,
			h.color as colorfg,
			h.size as sizefg,
			h.module,
			h.leader,
			h.status,
			h.po,
			h.flash,
			h.so,
			h.comment,
			h.first_time,
			h.created_at

		  FROM [trebovanje].[dbo].[request_header] as h
		  WHERE h.status = 'TO PRINT' AND h.deleted = 0
		  ORDER BY h.created_at desc
		  "));

		// return view('Table.index', compact('data'));
		return view('Table.indexso_to_print', compact('data'));
	}

	public function tocreate() {
		//
		$data = DB::connection('sqlsrv')->select(DB::raw("SELECT
			h.id,
			h.name,
			h.style as stylefg,
			h.color as colorfg,
			h.size as sizefg,
			h.module,
			h.leader,
			h.status,
			h.po,
			h.flash,
			h.so,
			h.comment,
			h.first_time,
			h.created_at

		  FROM [trebovanje].[dbo].[request_header] as h
		  WHERE h.status = 'TO CREATE' AND h.deleted = 0
		  ORDER BY h.created_at desc
		  "));

		// return view('Table.index', compact('data'));
		return view('Table.indexso_to_create', compact('data'));
	}

	public function last_used() {
		//
		$data = DB::connection('sqlsrv')->select(DB::raw("SELECT	
		
			h.id, 
			h.so,
			h.po,
			h.module,
			h.leader,
			h.status,
			h.updated_at,
			h.created_at
			
		  FROM [trebovanje].[dbo].[request_header] as h
		  WHERE h.deleted = 0
		  ORDER BY h.updated_at asc
		  "));

		// dd($data);

		if (isset($data[0])) {
			for ($i=0; $i < count($data); $i++) { 

				$so = DB::connection('sqlsrv3')->select(DB::raw("SELECT		   
				   p.[No_] as so,
				   /*l.[Item No_] as item,*/
				   /*l.[Shortcut Dimension 2 Code],*/
				   /*(RIGHT(l.[Shortcut Dimension 2 Code],5)) as po,*/
				   /*l.[PfsHorizontal Component] as size,*/
				   /*l.[PfsVertical Component] as color,*/
				   /*p.[Description],*/
				   p.[WMS Status] as sowmsstatus
				   
					FROM [Gordon_LIVE].[dbo].[GORDON\$Prod_ Order Line] as l
					JOIN [Gordon_LIVE].[dbo].[GORDON\$Production Order] as p ON p.[No_] = l.[Prod_ Order No_]
					WHERE p.[No_] = '".$data[$i]->so."'
				"));
				// var_dump($so[0]->so);
				// dd($so[0]->sowmsstatus);

				if (isset($so[0])) {
					if 	($so[0]->sowmsstatus == '0') {
						$sowmsstatus = "Open";
					} elseif ($so[0]->sowmsstatus == '1') {
						$sowmsstatus = "Close";
					}
				} else {
					$sowmsstatus = NULL;	
				}

				$table = RequestHeader::findOrFail($data[$i]->id);
		
				try {		
					
					$table->sowmsstatus = $sowmsstatus;
					$table->save();
				}
				catch (\Illuminate\Database\QueryException $e) {
					return view('Table.error');			
				}
			}

		} else {

		}

		/*
		$data = DB::connection('sqlsrv')->select(DB::raw("SELECT	
			
			DISTINCT h.so,
			h.po,
			h.flash,
			h.module,
			h.leader,
			h.status,
			h.updated_at,
			h.sowmsstatus
			
		  FROM [trebovanje].[dbo].[request_header] as h
		  WHERE h.deleted = 0 AND sowmsstatus = 'Open'
		  ORDER BY h.updated_at asc
		  "));
		*/
		
		$data = DB::connection('sqlsrv')->select(DB::raw("SELECT	
		h.so,
		h.po,
		h.flash,
		h.module,
		h.status,
		MAX (h.updated_at) updated,
		h.sowmsstatus
		FROM [trebovanje].[dbo].[request_header] as h
		WHERE h.deleted = 0 AND h.sowmsstatus = 'Open' 
		GROUP BY 
		h.so,
		h.po,
		h.flash,
		h.module,
		h.status,
		h.sowmsstatus
		ORDER BY updated asc
		"));

		// dd($data);
		// return view('Table.index', compact('data'));
		return view('Table.indexso_last_update', compact('data'));
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
			h.name,
			h.style as stylefg,
			h.color as colorfg,
			h.size as sizefg,
			h.module,
			h.leader,
			h.status,
			h.po,
			h.flash,
			h.so,
			h.first_time,
			l.item,
			l.item_t,
			l.color,
			l.color_t,
			l.size,
			l.size_t,
			l.hu,
			l.uom,
			h.comment,
			l.created_at
		  FROM [trebovanje].[dbo].[request_line] as l
		  JOIN [trebovanje].[dbo].[request_header] as h ON l.request_header_id = h.id
		  WHERE l.deleted = '0' AND h.id = '".$id."'"));

		// dd($data);
		// dd($data[0]->name);
		if (isset($data[0]->name)) {

		} else {
			$message = "Greska, za jedan on zahteva iz modula nema linija, vrovatno je linija obrisana rucno.";
			echo "<script type='text/javascript'>alert('$message');</script>";
		}


		if (isset($data[0]->so)) {
			$so = $data[0]->so;
		} else {
			$so = "";
		}

		for ($i=0; $i < 15 ; $i++) { 
			if (isset($data[$i]->item)) {
				${"item_{$i}"}=$data[$i]->item;	
			} else {
				${"item_{$i}"}="";	
			} 
			if (isset($data[$i]->item_t)) {
				${"item_t_{$i}"}=$data[$i]->item_t;	
			} else {
				${"item_t_{$i}"}="";	
			} 
			if (isset($data[$i]->size)) {
				${"size_{$i}"}=$data[$i]->size;	
			}  else {
				${"size_{$i}"}="";	
			} 
			if (isset($data[$i]->size_t)) {
				${"size_t_{$i}"}=$data[$i]->size_t;	
			}  else {
				${"size_t_{$i}"}="";	
			} 
			if (isset($data[$i]->color)) {
				${"color_{$i}"}=$data[$i]->color;
			} else {
				${"color_{$i}"}="";
			} 
			if (isset($data[$i]->color_t)) {
				${"color_t_{$i}"}=$data[$i]->color_t;
			} else {
				${"color_t_{$i}"}="";
			} 
			if (isset($data[$i]->uom)) {
				${"uom_{$i}"}=$data[$i]->uom;
			} else {
				${"uom_{$i}"}="";
			}
			if (isset($data[$i]->hu)) {
				${"hu_{$i}"}=$data[$i]->hu;
			} else {
				${"hu_{$i}"}="";
			}
			
		}
		// dd($data[0]->name);

		//Record temp_print
		// try {
			$table = new temp_print;

			$table->name = $data[0]->name;

			$table->so = $so;
			$table->po = $data[0]->po;
			$table->flash = $data[0]->flash;
			$table->first_time = $data[0]->first_time;
			$table->printer = $printer_name;

			$table->stylefg = $data[0]->stylefg;
			$table->colorfg = $data[0]->colorfg;
			$table->sizefg = $data[0]->sizefg;

			$table->module = $data[0]->module;
            $table->leader = $data[0]->leader;
            $table->comment = $data[0]->comment;

			$table->item_0 = $item_0;
			$table->item_t_0 = $item_t_0;
			$table->size_0 = $size_0;
			$table->size_t_0 = $size_t_0;
			$table->color_0 = $color_0;
			$table->color_t_0 = $color_t_0;
			$table->uom_0 = $uom_0;
			$table->hu_0 = $hu_0;

			$table->item_1 = $item_1;
			$table->item_t_1 = $item_t_1;
			$table->size_1 = $size_1;
			$table->size_t_1 = $size_t_1;
			$table->color_1 = $color_1;
			$table->color_t_1 = $color_t_1;
			$table->uom_1 = $uom_1;
			$table->hu_1 = $hu_1;

			$table->item_2 = $item_2;
			$table->item_t_2 = $item_t_2;
			$table->size_2 = $size_2;
			$table->size_t_2 = $size_t_2;
			$table->color_2 = $color_2;
			$table->color_t_2 = $color_t_2;
			$table->uom_2 = $uom_2;
			$table->hu_2 = $hu_2;

			$table->item_3 = $item_3;
			$table->item_t_3 = $item_t_3;
			$table->size_3 = $size_3;
			$table->size_t_3 = $size_t_3;
			$table->color_3 = $color_3;
			$table->color_t_3 = $color_t_3;
			$table->uom_3 = $uom_3;
			$table->hu_3 = $hu_3;

			$table->item_4 = $item_4;
			$table->item_t_4 = $item_t_4;
			$table->size_4 = $size_4;
			$table->size_t_4 = $size_t_4;
			$table->color_4 = $color_4;
			$table->color_t_4 = $color_t_4;
			$table->uom_4 = $uom_4;
			$table->hu_4 = $hu_4;

			$table->item_5 = $item_5;
			$table->item_t_5 = $item_t_5;
			$table->size_5 = $size_5;
			$table->size_t_5 = $size_t_5;
			$table->color_5 = $color_5;
			$table->color_t_5 = $color_t_5;
			$table->uom_5 = $uom_5;
			$table->hu_5 = $hu_5;

			$table->item_6 = $item_6;
			$table->item_t_6 = $item_t_6;
			$table->size_6 = $size_6;
			$table->size_t_6 = $size_t_6;
			$table->color_6 = $color_6;
			$table->color_t_6 = $color_t_6;
			$table->uom_6 = $uom_6;
			$table->hu_6 = $hu_6;

			$table->item_7 = $item_7;
			$table->item_t_7 = $item_t_7;
			$table->size_7 = $size_7;
			$table->size_t_7 = $size_t_7;
			$table->color_7 = $color_7;
			$table->color_t_7 = $color_t_7;
			$table->uom_7 = $uom_7;
			$table->hu_7 = $hu_7;

			$table->item_8 = $item_8;
			$table->item_t_8 = $item_t_8;
			$table->size_8 = $size_8;
			$table->size_t_8 = $size_t_8;
			$table->color_8 = $color_8;
			$table->color_t_8 = $color_t_8;
			$table->uom_8 = $uom_8;
			$table->hu_8 = $hu_8;

			$table->item_9 = $item_9;
			$table->item_t_9 = $item_t_9;
			$table->size_9 = $size_9;
			$table->size_t_9 = $size_t_9;
			$table->color_9 = $color_9;
			$table->color_t_9 = $color_t_9;
			$table->uom_9 = $uom_9;
			$table->hu_9 = $hu_9;

			$table->item_10 = $item_10;
			$table->item_t_10 = $item_t_10;
			$table->size_10 = $size_10;
			$table->size_t_10 = $size_t_10;
			$table->color_10 = $color_10;
			$table->color_t_10 = $color_t_10;
			$table->uom_10 = $uom_10;
			$table->hu_10 = $hu_10;

			$table->item_11 = $item_11;
			$table->item_t_11 = $item_t_11;
			$table->size_11 = $size_11;
			$table->size_t_11 = $size_t_11;
			$table->color_11 = $color_11;
			$table->color_t_11 = $color_t_11;
			$table->uom_11 = $uom_11;
			$table->hu_11 = $hu_11;

			$table->item_12 = $item_12;
			$table->item_t_12 = $item_t_12;
			$table->size_12 = $size_12;
			$table->size_t_12 = $size_t_12;
			$table->color_12 = $color_12;
			$table->color_t_12 = $color_t_12;
			$table->uom_12 = $uom_12;
			$table->hu_12 = $hu_12;

			$table->item_13 = $item_13;
			$table->item_t_13 = $item_t_13;
			$table->size_13 = $size_13;
			$table->size_t_13 = $size_t_13;
			$table->color_13 = $color_13;
			$table->color_t_13 = $color_t_13;
			$table->uom_13 = $uom_13;
			$table->hu_13 = $hu_13;

			$table->item_14 = $item_14;
			$table->item_t_14 = $item_t_14;
			$table->size_14 = $size_14;
			$table->size_t_14 = $size_t_14;
			$table->color_14 = $color_14;
			$table->color_t_14 = $color_t_14;
			$table->uom_14 = $uom_14;
			$table->hu_14 = $hu_14;

			$table->save();
			
		// }
		// catch (\Illuminate\Database\QueryException $e) {
		// 	$msg = "Problem to save in temp_print";
		// 	return view('Request.error',compact('msg'));
		// }

		try {
			$header = RequestHeader::findOrFail($id);
			$header->status = "PRINTED";
			$header->save();
		}
			catch (\Illuminate\Database\QueryException $e) {
				$msg = "Problem to print header";
				return view('Table.error',compact('msg'));
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
				so,
				module
				FROM [trebovanje].[dbo].[request_header]
			  	WHERE deleted = '0' AND status = 'TO PRINT'
			  	ORDER BY module asc
			  "));

		

		for ($d=0; $d < count($main); $d++) { 
			
			$data = DB::connection('sqlsrv')->select(DB::raw("SELECT
				h.id,
				h.name,
				h.style as stylefg,
				h.color as colorfg,
				h.size as sizefg,
				h.module,
				h.leader,
				h.status,
				h.po,
				h.flash,
				h.so,
				h.first_time,
				l.item,
				l.item_t,
				l.color,
				l.color_t,
				l.size,
				l.size_t,
				l.hu,
				l.uom,
				h.comment,
				l.created_at
			  FROM [trebovanje].[dbo].[request_line] as l
			  JOIN [trebovanje].[dbo].[request_header] as h ON l.request_header_id = h.id
			  WHERE l.deleted = '0' AND h.status = 'TO PRINT' AND h.id = '".$main[$d]->id."'
			  ORDER BY h.module asc
			  "));

			// dd($data);
			// dd($data[0]->name);
			if (isset($data[0]->name)) {

			} else {
				$message = "Greska, za jedan on zahteva iz modula nema linija, vrovatno je linija obrisana rucno.";
				echo "<script type='text/javascript'>alert('$message');</script>";
			}
			
			if (isset($data[0]->so)) {
				$so = $data[0]->so;
			} else {
				$so = "";
			}

			for ($i=0; $i < 15 ; $i++) { 
				if (isset($data[$i]->item)) {
					${"item_{$i}"}=$data[$i]->item;	
				} else {
					${"item_{$i}"}="";	
				} 
				if (isset($data[$i]->item_t)) {
					${"item_t_{$i}"}=$data[$i]->item_t;	
				} else {
					${"item_t_{$i}"}="";	
				} 
				if (isset($data[$i]->size)) {
					${"size_{$i}"}=$data[$i]->size;	
				}  else {
					${"size_{$i}"}="";	
				} 
				if (isset($data[$i]->size_t)) {
					${"size_t_{$i}"}=$data[$i]->size_t;	
				}  else {
					${"size_t_{$i}"}="";	
				} 
				if (isset($data[$i]->color)) {
					${"color_{$i}"}=$data[$i]->color;
				} else {
					${"color_{$i}"}="";
				} 
				if (isset($data[$i]->color_t)) {
					${"color_t_{$i}"}=$data[$i]->color_t;
				} else {
					${"color_t_{$i}"}="";
				} 
				if (isset($data[$i]->uom)) {
					${"uom_{$i}"}=$data[$i]->uom;
				} else {
					${"uom_{$i}"}="";
				}
				if (isset($data[$i]->hu)) {
					${"hu_{$i}"}=$data[$i]->hu;
				} else {
					${"hu_{$i}"}="";
				}
			}

			//Record temp_print
			try {
				$table = new temp_print;

				$table->name = $data[0]->name;

				$table->so = $so;
				$table->po = $data[0]->po;
				$table->flash = $data[0]->flash;
				$table->first_time = $data[0]->first_time;
				$table->printer = $printer_name;

				$table->stylefg = $data[0]->stylefg;
				$table->colorfg = $data[0]->colorfg;
				$table->sizefg = $data[0]->sizefg;

				$table->module = $data[0]->module;
	            $table->leader = $data[0]->leader;
	            $table->comment = $data[0]->comment;

				$table->item_0 = $item_0;
				$table->item_t_0 = $item_t_0;
				$table->size_0 = $size_0;
				$table->size_t_0 = $size_t_0;
				$table->color_0 = $color_0;
				$table->color_t_0 = $color_t_0;
				$table->uom_0 = $uom_0;
				$table->hu_0 = $hu_0;

				$table->item_1 = $item_1;
				$table->item_t_1 = $item_t_1;
				$table->size_1 = $size_1;
				$table->size_t_1 = $size_t_1;
				$table->color_1 = $color_1;
				$table->color_t_1 = $color_t_1;
				$table->uom_1 = $uom_1;
				$table->hu_1 = $hu_1;

				$table->item_2 = $item_2;
				$table->item_t_2 = $item_t_2;
				$table->size_2 = $size_2;
				$table->size_t_2 = $size_t_2;
				$table->color_2 = $color_2;
				$table->color_t_2 = $color_t_2;
				$table->uom_2 = $uom_2;
				$table->hu_2 = $hu_2;

				$table->item_3 = $item_3;
				$table->item_t_3 = $item_t_3;
				$table->size_3 = $size_3;
				$table->size_t_3 = $size_t_3;
				$table->color_3 = $color_3;
				$table->color_t_3 = $color_t_3;
				$table->uom_3 = $uom_3;
				$table->hu_3 = $hu_3;

				$table->item_4 = $item_4;
				$table->item_t_4 = $item_t_4;
				$table->size_4 = $size_4;
				$table->size_t_4 = $size_t_4;
				$table->color_4 = $color_4;
				$table->color_t_4 = $color_t_4;
				$table->uom_4 = $uom_4;
				$table->hu_4 = $hu_4;

				$table->item_5 = $item_5;
				$table->item_t_5 = $item_t_5;
				$table->size_5 = $size_5;
				$table->size_t_5 = $size_t_5;
				$table->color_5 = $color_5;
				$table->color_t_5 = $color_t_5;
				$table->uom_5 = $uom_5;
				$table->hu_5 = $hu_5;

				$table->item_6 = $item_6;
				$table->item_t_6 = $item_t_6;
				$table->size_6 = $size_6;
				$table->size_t_6 = $size_t_6;
				$table->color_6 = $color_6;
				$table->color_t_6 = $color_t_6;
				$table->uom_6 = $uom_6;
				$table->hu_6 = $hu_6;

				$table->item_7 = $item_7;
				$table->item_t_7 = $item_t_7;
				$table->size_7 = $size_7;
				$table->size_t_7 = $size_t_7;
				$table->color_7 = $color_7;
				$table->color_t_7 = $color_t_7;
				$table->uom_7 = $uom_7;
				$table->hu_7 = $hu_7;

				$table->item_8 = $item_8;
				$table->item_t_8 = $item_t_8;
				$table->size_8 = $size_8;
				$table->size_t_8 = $size_t_8;
				$table->color_8 = $color_8;
				$table->color_t_8 = $color_t_8;
				$table->uom_8 = $uom_8;
				$table->hu_8 = $hu_8;

				$table->item_9 = $item_9;
				$table->item_t_9 = $item_t_9;
				$table->size_9 = $size_9;
				$table->size_t_9 = $size_t_9;
				$table->color_9 = $color_9;
				$table->color_t_9 = $color_t_9;
				$table->uom_9 = $uom_9;
				$table->hu_9 = $hu_9;

				$table->item_10 = $item_10;
				$table->item_t_10 = $item_t_10;
				$table->size_10 = $size_10;
				$table->size_t_10 = $size_t_10;
				$table->color_10 = $color_10;
				$table->color_t_10 = $color_t_10;
				$table->uom_10 = $uom_10;
				$table->hu_10 = $hu_10;

				$table->item_11 = $item_11;
				$table->item_t_11 = $item_t_11;
				$table->size_11 = $size_11;
				$table->size_t_11 = $size_t_11;
				$table->color_11 = $color_11;
				$table->color_t_11 = $color_t_11;
				$table->uom_11 = $uom_11;
				$table->hu_11 = $hu_11;

				$table->item_12 = $item_12;
				$table->item_t_12 = $item_t_12;
				$table->size_12 = $size_12;
				$table->size_t_12 = $size_t_12;
				$table->color_12 = $color_12;
				$table->color_t_12 = $color_t_12;
				$table->uom_12 = $uom_12;
				$table->hu_12 = $hu_12;

				$table->item_13 = $item_13;
				$table->item_t_13 = $item_t_13;
				$table->size_13 = $size_13;
				$table->size_t_13 = $size_t_13;
				$table->color_13 = $color_13;
				$table->color_t_13 = $color_t_13;
				$table->uom_13 = $uom_13;
				$table->hu_13 = $hu_13;

				$table->item_14 = $item_14;
				$table->item_t_14 = $item_t_14;
				$table->size_14 = $size_14;
				$table->size_t_14 = $size_t_14;
				$table->color_14 = $color_14;
				$table->color_t_14 = $color_t_14;
				$table->uom_14 = $uom_14;
				$table->hu_14 = $hu_14;

				$table->save();
				
			}
			catch (\Illuminate\Database\QueryException $e) {
				$msg = "Problem to save in temp_print";
				return view('Request.error',compact('msg'));
			}

			
			try {
				$header = RequestHeader::findOrFail($main[$d]->id);
				$header->status = "PRINTED";
				$header->save();
			}
				catch (\Illuminate\Database\QueryException $e) {
					$msg = "Problem to print header";
					return view('Table.error',compact('msg'));
			}


		}
		
		return Redirect::to('/');
	}

	public function delete_header($id)	{
		
		try {

		$header = RequestHeader::findOrFail($id);
		$header->status = "DELETED";
		$header->deleted = 1;
		$header->save();
		
		}
			catch (\Illuminate\Database\QueryException $e) {
				$msg = "Problem to delete header";
				return view('Table.error',compact('msg'));
		}

		$lines = DB::connection('sqlsrv')->select(DB::raw("SELECT id FROM request_line WHERE request_header_id = '".$id."'"));

		foreach ($lines as $line) {

			try {
				$l = RequestLine::findOrFail($line->id);
				$l->deleted = 1;
				$l->save();
				// $l->delete();
			}
				catch (\Illuminate\Database\QueryException $e) {
					$msg = "Problem to delete line";
					return view('Table.error',compact('msg'));
			}
		}

		return Redirect::to('/tableso');
	}

	public function delete_line($id) {
		try {

		$line = RequestLine::findOrFail($id);
		$line->deleted = 1;
		$line->save();
		// $line->delete();

		}
		catch (\Illuminate\Database\QueryException $e) {
			$msg = "Problem to delete line";
			return view('Table.error',compact('msg'));
		}
		return Redirect::to('/table');
	}

	public function edit_header($id) {

		$data = RequestHeader::findOrFail($id);		
		return view('Table.edit_header', compact('data'));
	}

	public function update_header($id, Request $request) {

		//
		$this->validate($request, ['first'=>'required']);
		$input = $request->all(); 

		$table = RequestHeader::findOrFail($id);		
		//dd($input);

		try {		
			
			$table->first_time = $input['first'];
			$table->save();
		}
		catch (\Illuminate\Database\QueryException $e) {
			return view('Table.error');			
		}
		
		return Redirect::to('/');
	}

}


