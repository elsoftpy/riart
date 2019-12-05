<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Cabecera_encuesta;
use App\Encuestas_cargo;
use App\Detalle_encuesta;
use App\Rubro;
use App\Empresa;
use App\Cargo;
use App\Traits\PeriodosTrait;

class ReportController extends Controller
{
    use PeriodosTrait;
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $dbEmpresas = empresa::pluck('descripcion', 'id');
        $dbCargos = Cargo::pluck('descripcion', 'id');

        return view('admin.reportes.filter_empresas')->with('dbEmpresas', $dbEmpresas)
                                                     ->with('dbCargos', $dbCargos);

    }

    public function filterNiveles(){
        $rubro = Rubro::first()->id;
        $periodos = $this->getPeriodos($rubro);
        $rubros = $this->getRubros();
    	return view('admin.reportes.filter_niveles')->with('periodos', $periodos)
    								                ->with('rubros', $rubros);
    }

    public function filterCargos(){
        $rubro = Rubro::first()->id;
        $periodos = $this->getPeriodos($rubro);
        $rubros = $this->getRubros();
    	return view('admin.reportes.filter_cargos')->with('periodos', $periodos)
    								                ->with('rubros', $rubros);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        return redirect()->route('reportes.show', $id);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    public function getPeriodosEmpresa(Request $request){
        $id = $request->empresa_id;
        $periodos = Cabecera_encuesta::distinct('periodo')
                                     ->where('empresa_id', $id)
                                     ->pluck('periodo', 'periodo');
        return $periodos;                                
    }

}
