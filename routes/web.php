<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| This file is where you may define all of the routes that are handled
| by your application. Just tell Laravel the URIs it should respond
| to using a Closure or controller method. Build something great!
|
*/

Route::get('/', 'PollController@home')->name('home.page');
Route::get('/home', 'HomeController@index')->name('home');

Route::group(["middleware"=>"auth"], function(){
	
	
	Route::resource('empresas', 'EmpresasController');

	Route::post('cargos_detalle', "CargosController@getDetalle")->name('cargos.detalle');
	Route::resource('cargos', 'CargosController');

	Route::resource('cargos_clientes', 'CargosClientesController');

	Route::resource('homologacion', 'HomologacionController');

	Route::get('encuestas_cargos_hist/{id}', [
		'uses'=> 'EncuestasCargosController@showHistory', 
		'as'=> 'encuestas_cargos.showHistory'
	]);	
	
	Route::resource('encuestas_cargos', 'EncuestasCargosController');


	Route::get('encuestas_clone/{id}', [
		'uses'=> 'EncuestasController@clonePoll', 
		'as'=> 'encuestas.clone'
	]);
	Route::post('encuestas_new', [
		'uses'=> 'EncuestasController@storeNew', 
		'as'=> 'encuestas.storeNew'
	]);	
	Route::resource('encuestas', 'EncuestasController');


	Route::resource('usuarios', 'UsuariosController');

	//Route::get('encuestas_report/{id}', 'EncuestasController@report' )->name('encuestas.report');

	Route::post('preview', [
				'uses'=>'EncuestasController@preview',
				'as'=>'encuestas.preview'
	]);
	
	Route::get('encuestas/escalas', [
		'uses'=>'EncuestasController@getEscalas',
		'as'=>'encuestas.escalas'
	]);

	Route::resource('encuestas', 'EncuestasController');

	Route::post('reportes_cargo', 'ReporteController@cargoReport')->name('reportes.cargos');
	Route::post('reportes_cargo_niveles', 'ReporteController@getCargos')->name('reportes.getcargos');
	Route::get('reportes_filter/{id}', 'ReporteController@filter')->name('reportes.filter');
	Route::get('reportes_ficha/{id}', 'ReporteController@ficha')->name('reportes.ficha');
	Route::resource('reportes', 'ReporteController');

	Route::get('resultados', 'ReporteController@resultados')->name('resultados');
	Route::post('resultados_excel', 'ReporteController@resultadosExcel')->name('resultados.excel');
	Route::post('periodo', 'ReporteController@setSession')->name('periodo');

	Route::get('panel_empresas/{id}', 'ReporteController@panel')->name('reportes.panel');

});


Auth::routes();


