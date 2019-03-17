<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\beneficio_cabecera_encuesta;
use App\Rubro;
use App\beneficios_periodo;
use App\Traits\PeriodosBeneficiosTrait;
use Carbon\Carbon;
use flash;
use DB;

class BeneficiosPeriodosController extends Controller
{
    use PeriodosBeneficiosTrait;

    public function index(){
    	$dbData = Beneficios_periodo::get();
    	return view('beneficios_periodos.list')->with('dbData', $dbData);
    }

    public function create(){
		$rubro = Rubro::first()->id;
        $periodos = $this->getPeriodos($rubro);
        $rubros = $this->getRubros();
    	return view('beneficios_periodos.create')->with('periodos', $periodos)
    								->with('rubros', $rubros);
    }

    public function store(Request $request){
    	$dbData = new Beneficios_periodo($request->all());
        $dbData->activo = 0;
    	$dbData->save();
    	return redirect()->route('periodos_activos.index');
    }

     public function show($id)
    {
        //
    }

    public function edit($id){
        $dbData = Beneficios_periodo::find($id);
        $rubro = Rubro::find($dbData->rubro_id);
        $periodos = $this->getPeriodos($rubro->id);
        $rubros = $this->getRubros();
        //dd($periodos, $dbData);
        return view('beneficios_periodos.edit')->with('dbData', $dbData)
                                    ->with('periodos', $periodos)
    								->with('rubros', $rubros);
    }

    public function update(Request $request, $id){

        if($request->activo){
            $activo = 1;
            $beneficios_periodos = Beneficios_periodo::where('rubro_id', $request->rubro_id)->get();
            foreach($beneficios_periodos as $ficha){
                $ficha->activo = 0;
                $ficha->save();
            }
        }else{
            $activo = 0;
        }
        $dbData = Beneficios_periodo::find($id);
        $dbData->fill($request->all());
        $dbData->activo = $activo;
    	$dbData->save();
    	return redirect()->route('periodos_activos.index');
    }

    public function destroy($id){
		$dbData = Cargo::find($id);
        $dbData->delete();            

		return redirect()->route('cargos.index');    	
    }

    public function getDetalle(Request $request){
        $cargo = Cargo::find($request->id);
        return $cargo->detalle;
    }

    public function getPeriodosAjax(Request $request){
        $periodos = $this->getPeriodos($request->rubro_id);
        return $periodos;
    }
}
