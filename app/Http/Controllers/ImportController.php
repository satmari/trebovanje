<?php namespace App\Http\Controllers;


use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Redirect;
use Maatwebsite\Excel\Facades\Excel;
use Request;

use App\trans_color;
use App\trans_item;
use App\trans_size;
use App\User;
use DB;

class ImportController extends Controller {

	public function index()
	{
		//
		return view('import.index');
	}
	
	public function postImportItems(Request $request) {
	    $getSheetName = Excel::load(Request::file('file1'))->getSheetNames();
	    
	    foreach($getSheetName as $sheetName)
	    {
	        Excel::filter('chunk')->selectSheets($sheetName)->load(Request::file('file1'))->chunk(50, function ($reader)
	            
	            {
	                $readerarray = $reader->toArray();
	                var_dump($readerarray);
	                foreach($readerarray as $row)
	                {
						$bulk = new trans_item;
						$bulk->item = $row['item'];
						$bulk->item_t = $row['leaders_translation'];
						$bulk->std_qty = $row['standard_qty'];
						$bulk->std_uom = $row['uom'];

						// $bulk->created_at = mktime(12, 12, 12, 01, 01, 2017);
						// $bulk->updated_at = mktime(12, 12, 12, 01, 01, 2017);
												
						$bulk->save();
	                }
	            });
	    }
		return redirect('/');
	}
	public function postImportColors(Request $request) {
	    $getSheetName = Excel::load(Request::file('file2'))->getSheetNames();
	    
	    foreach($getSheetName as $sheetName)
	    {
	        
	        Excel::filter('chunk')->selectSheets($sheetName)->load(Request::file('file2'))->chunk(50, function ($reader)
	            
	            {
	                $readerarray = $reader->toArray();
	                //var_dump($readerarray);
	                foreach($readerarray as $row)
	                {
	                	
						$bulk = new trans_color;
						$bulk->color = $row['color'];
						$bulk->color_t = $row['leaders_translation'];
						
						// $bulk->created_at = date(2017-01-01);
						// $bulk->updated_at = date(2017-01-01);
												
						$bulk->save();
						
	                }
	            });
	    }
		return redirect('/');
	}
	public function postImportSizes(Request $request) {
	    $getSheetName = Excel::load(Request::file('file3'))->getSheetNames();
	    
	    foreach($getSheetName as $sheetName)
	    {
	       
	        Excel::filter('chunk')->selectSheets($sheetName)->load(Request::file('file3'))->chunk(50, function ($reader)
	            
	            {
	                $readerarray = $reader->toArray();
	                //var_dump($readerarray);
	                foreach($readerarray as $row)
	                {
	                	
						$bulk = new trans_size;
						$bulk->size = $row['dimension'];
						$bulk->size_t = $row['leaders_translation'];
						
						// $bulk->created_at = date(2017-01-01);
						// $bulk->updated_at = date(2017-01-01);
												
						$bulk->save();
						
	                }
	            });
	    }
		return redirect('/');
	}

	public function postImportUpdatePass() {
	    
	    
	    
	    $sql = DB::connection('sqlsrv')->select(DB::raw("SELECT * FROM users"));

	    for ($i=0; $i < count($sql) ; $i++) { 
	    	
	    	// dd($sql[$i]->password);

	    	$password = bcrypt($sql[$i]->name);
	    	// dd($password);

			$sql2 = DB::connection('sqlsrv')->select(DB::raw("
					SET NOCOUNT ON;
					UPDATE [trebovanje].[dbo].[users]
					SET password = '".$password."'
					WHERE name = '".$sql[$i]->name."';
					SELECT TOP 1 [id] FROM [trebovanje].[dbo].[users];
				"));	    	

	    }

		return redirect('/');
	}
	
}


