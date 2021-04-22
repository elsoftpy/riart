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

// Languages
Route::get('lang/{lang}', ['as' => 'lang.switch', 'uses' => 'LanguageController@switchLang']);

Route::get('lang_report/{lang}', 'LanguageController@switchLangReport')->name('switch.lang.report');

Route::group(["middleware"=>"auth"], function(){
	
	
	Route::resource('empresas', 'EmpresasController');

	Route::post('cargos_detalle', "CargosController@getDetalle")->name('cargos.detalle');
	Route::post('cargos_excel', 'CargosController@excel')->name('cargos.excel');
	Route::resource('cargos', 'CargosController');

	Route::resource('cargos_clientes', 'CargosClientesController');

	Route::resource('homologacion', 'HomologacionController');

	Route::get('encuestas_cargos_hist/{id}', [
		'uses'=> 'EncuestasCargosController@showHistory', 
		'as'=> 'encuestas_cargos.showHistory'
	]);	

	Route::get('encuestas_cargos/cargos/{id}', 'EncuestasCargosController@getCargos')->name('encuestas_cargos.getCargos');
	
	Route::resource('encuestas_cargos', 'EncuestasCargosController');


	Route::get('encuestas_clone/{id}', [
		'uses'=> 'EncuestasController@clonePoll', 
		'as'=> 'encuestas.clone'
	]);

	Route::get('clone_banca_nacional', 'EncuestasController@cloneBancosNacionales')->name('clonar.bancos.nacionales');

	Route::post('clone_banca_nacional', 'EncuestasController@clonarBancosNacionales')->name('clonar.bancos.nacionales.action');

	Route::get('clone_bancard', 'EncuestasController@cloneBancard')->name('clonar.bancard');

	Route::post('clone_bancard', 'EncuestasController@clonarBancard')->name('clonar.bancardAction');

	Route::get('clone_puente', 'EncuestasController@clonePuente')->name('clonar.puente');

	Route::post('clone_puente', 'EncuestasController@clonarPuente')->name('clonar.puenteAction');

	Route::get('clone_industrial', 'EncuestasController@cloneIndustrial')->name('clonar.industrial');

	Route::post('clone_industrial', 'EncuestasController@clonarIndustrial')->name('clonar.industrialAction');

	Route::get('clone_cofco', 'CloneController@cloneCofcoForm')->name('clonar.cofco.form');
	Route::post('clone_cofco', 'CloneController@cloneCofco')->name('clonar.cofco');
	Route::get('clone_amx', 'CloneController@cloneAMXForm')->name('clonar.amx.form');
	Route::post('clone_amx', 'CloneController@cloneAMX')->name('clonar.amx');
	
	Route::post('encuestas_new', [
		'uses'=> 'EncuestasController@storeNew', 
		'as'=> 'encuestas.storeNew'
	]);	
	Route::resource('encuestas', 'EncuestasController');


	Route::resource('usuarios', 'UsuariosController');

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
	Route::get('cargos_club/{id}', 'ReporteController@showCargosClub')->name('reportes.cargosRubro');
	Route::get('cargos_lista/{id}', 'ReporteController@lista')->name('reportes.lista');
	Route::get('reportes_filter/{id}', 'ReporteController@filter')->name('reportes.filter');
	Route::get('reportes_ficha/{id}', 'ReporteController@ficha')->name('reportes.ficha');
	Route::get('reportes_conceptos/{id}', 'ReporteController@conceptos')->name('reportes.conceptos');
	Route::get('reportes_metodologia/{id}', 'ReporteController@metodologia')->name('reportes.metodologia');
	Route::post('reportes_cargo_excel', 'ReporteController@cargoReportExcel')->name('reportes.cargoExcel');
	Route::post('reportes_cargos_club_excel', 'ReporteController@cargoReportClubExcel')->name('reportes.cargosClubExcel');
	Route::post('reportes_cargos_club_especial', 'ReporteController@cargoReportClubEspecial')->name('reportes.cargosClubEspecial');	
	Route::resource('reportes', 'ReporteController');

	Route::post('import_export/download', 'ImportExportController@download')->name('import_export.download');
	Route::post('import_export/upload', 'ImportExportController@upload')->name('import_export.upload');
	Route::post('import_export/periodos', 'ImportExportController@getPeriodos')->name('import_export.periodos');
	Route::resource('import_export', 'ImportExportController');

	//file attachment
	Route::get('file_attachment', 'FileAttachmentController@index')->name('file_attachment');
	Route::post('file_attachment/upload', 'FileAttachmentController@upload')->name('file_attachment.upload');
	Route::get('file_attachment/download', 'FileAttachmentController@download')->name('file_attachment.download');
	Route::post('file_attachment/periodos', 'FileAttachmentController@getPeriodosAjax')->name('file_attachment.periodos');

	Route::get('resultados', 'ReporteController@resultados')->name('resultados');
	Route::post('resultados_excel', 'ReporteController@resultadosExcel')->name('resultados.excel');
	Route::post('periodo', 'ReporteController@setSession')->name('periodo');
	Route::post('periodo_especial', 'ReporteController@setSessionEspecial')->name('periodo_especial');

	Route::get('panel_empresas/{id}', 'ReporteController@panel')->name('reportes.panel');

	Route::post('autos_modelos', 'BeneficiosController@getModelos')->name('autos.modelos');

	//Beneficios 
	
	Route::post('periodos_activos/periodos_ajax', 'BeneficiosPeriodosController@getPeriodosAjax')->name('periodos_activos.periodos');
	Route::resource('periodos_activos', 'BeneficiosPeriodosController');

	Route::post('beneficios/reportes', 'BeneficiosController@report')->name('beneficios.reportes');

	Route::post('beneficios/reportes_ajax', 'BeneficiosController@getChartData')->name("beneficios.data");

	Route::get('beneficios_encuestas_clone/{id}', 'BeneficiosController@clonePoll')->name('beneficios.encuestas.clone');

	Route::post('beneficios/reportes_composicion', 'BeneficiosController@compositionReport')->name("beneficios.reportes.composicion");

	Route::resource('beneficios', 'BeneficiosController');

	Route::resource('beneficios_admin', 'BeneficiosAdminController');

	Route::resource('beneficios_preguntas', 'BeneficiosPreguntasController');

	Route::get('beneficios/panel_empresas/{id}', 'BeneficiosAdminController@panel')->name('beneficios.panel');
	
	Route::get('beneficios_admin_resultados', 'BeneficiosAdminController@resultados')->name('beneficios.admin.resultados');

	Route::post('beneficios_resultados_excel', 'BeneficiosAdminController@resultadosExcel')->name('beneficios.admin.resultados.excel');

	Route::get('beneficios_admin_conclusiones/create', 'BeneficiosAdminController@createConclusion')->name('beneficios.admin.conclusion');

	Route::post('beneficios_admin_conclusiones', 'BeneficiosAdminController@storeConclusion')->name('beneficios.admin.conclusion.store');	

	Route::post('beneficios_admin_conclusiones/get', 'BeneficiosAdminController@getConclusion')->name('beneficios.admin.conclusion.get');	
	
	Route::get('admin_reportes_filter', 'Admin\ReportController@index')->name('admin.reporte.filter');
	Route::get('admin_reportes_filter/niveles', 'Admin\ReportController@filterNiveles')->name('admin.reporte.filter.niveles');
	Route::get('admin_reportes_filter/cargos', 'Admin\ReportController@filterCargos')->name('admin.reporte.filter.cargos');
	Route::post('admin_reportes_filter/periodos', 'Admin\ReportController@getPeriodosEmpresa')->name('admin.reporte.filter.periodos');
	Route::post('admin_reportes_excel/niveles', 'ReporteController@nivelReportClubExcel')->name('reportes.nivelesClubExcel');	
	Route::get('admin_reportes_excel/update_table', 'Admin\ReportController@updateTable')->name('reportes.update_niveles_table');
	Route::post('admin_reportes_excel/cargos', 'ReporteController@cargosReportExcel')->name('reportes.cargosExcel');	
	
	Route::post('admin_ficha/contar_emergentes', 'FichasController@countEmergentes')->name('admin.ficha.contar');
	Route::resource('admin_ficha', 'FichasController');

	Route::resource('areas', 'AreasController');

	Route::resource('niveles', 'NivelesController');

	Route::resource('rubros', 'RubrosController');

	Route::resource('sub_rubros', 'SubRubrosController');

	Route::get('tempdir', function(){
		return sys_get_temp_dir (  );
	});


});


Auth::routes();
Route::get('reset', 'ResetPasswordController@showResetForm')->name('reset.form');
Route::post('reset', 'ResetPasswordController@resetPassword')->name('reset.action');
Route::get('generate', 'ResetPasswordController@generate')->name('generate');



