<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/

//Route::get('/', 'WelcomeController@index');
// Route::get('/', 'HomeController@index');
Route::get('/', 'RequestController@index');

Route::get('home', 'HomeController@index');

Route::controllers([
	'auth' => 'Auth\AuthController',
	'password' => 'Auth\PasswordController',
]);

// Request
Route::get('/request', 'RequestController@index');
// Route::get('/requestcheck', 'RequestController@check');
Route::post('/requestcheck', 'RequestController@createtreb');
// Route::get('/requestselect', 'RequestController@select');
Route::get('/requestselect', 'RequestController@createtreb');
Route::get('/requestcreatetreb', 'RequestController@createtreb');
Route::get('/existingso/{so}', 'RequestController@existingso');
Route::post('/requeststoretreb', 'RequestController@requeststoretreb');
Route::get('/newso', 'RequestController@newso');
Route::post('/createnewso', 'RequestController@createnewso');

// Table
Route::get('/table', 'TableController@index');
Route::get('/tableso', 'TableController@indexso');
Route::get('/tabletoprint', 'TableController@toprint');
Route::get('/tabletocreate', 'TableController@tocreate');
Route::get('/last_used', 'TableController@last_used');

Route::get('/print/{id}', 'TableController@printrequest');
Route::get('/printall', 'TableController@printall');
Route::get('/delete_header/{id}', 'TableController@delete_header');
Route::get('/delete_line/{id}', 'TableController@delete_line');
Route::get('/edit_header/{id}', 'TableController@edit_header');
Route::post('/update_header/{id}', 'TableController@update_header');

// Import
Route::get('/import', 'ImportController@index');
Route::post('/import1', 'ImportController@postImportItems');
Route::post('/import2', 'ImportController@postImportColors');
Route::post('/import3', 'ImportController@postImportSizes');

//Trans Table
Route::get('/transitem', 'TransTableController@indexitem');
Route::get('/transsize', 'TransTableController@indexsize');
Route::get('/transcolor', 'TransTableController@indexcolor');

Route::get('/edit_trans_item/{id}', 'TransTableController@edit_item');
Route::get('/edit_trans_size/{id}', 'TransTableController@edit_size');
Route::get('/edit_trans_color/{id}', 'TransTableController@edit_color');

Route::post('/update_item/{id}', 'TransTableController@update_item');
Route::post('/update_size/{id}', 'TransTableController@update_size');
Route::post('/update_color/{id}', 'TransTableController@update_color');

Route::get('/refresh', 'RefreshController@index');


Route::any('getpodata', function() {
	$term = Input::get('term');

	// $data = DB::connection('sqlsrv')->table('pos')->distinct()->select('po')->where('po','LIKE', $term.'%')->where('closed_po','=','Open')->groupBy('po')->take(10)->get();
	$data = DB::connection('sqlsrv')->select(DB::raw("SELECT TOP 10 (RIGHT([No_],5)) as po FROM [Gordon_LIVE].[dbo].[GORDON\$Production Order] WHERE [Status] = '3' AND [No_] like '%".$term."%'"));
	// var_dump($data);
	foreach ($data as $v) {
		$retun_array[] = array('value' => $v->po);
	}
return Response::json($retun_array);
});


