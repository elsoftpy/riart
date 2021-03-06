<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Ficha_dato;
use App\Cabecera_encuesta;
use App\Rubro;
use App\Traits\PeriodosTrait;
use Carbon\Carbon;
use flash;
use DB;

class FichasController extends Controller
{
    use PeriodosTrait;

    public function index(){
    	$dbData = Ficha_dato::get();
    	return view('fichas.list')->with('dbData', $dbData);
    }

    public function create(){
		$rubro = Rubro::first()->id;
        $periodos = $this->getPeriodos($rubro);
        $rubros = $this->getRubros();
    	return view('fichas.create')->with('periodos', $periodos)
    								->with('rubros', $rubros);
    }

    public function store(Request $request){
    	$dbData = new Ficha_dato($request->all());
        $dbData->activo = 0;
    	$dbData->save();
    	return redirect()->route('admin_ficha.index');
    }

     public function show($id)
    {
        //
    }

    public function edit($id){
        $dbData = Ficha_dato::find($id);
        $rubro = Rubro::find($dbData->rubro_id);
        $periodos = $this->getPeriodos($rubro->id);
        $rubros = $this->getRubros();
        //dd($periodos, $dbData);
        return view('fichas.edit')->with('dbData', $dbData)
                                    ->with('periodos', $periodos)
    								->with('rubros', $rubros);
    }

    public function update(Request $request, $id){

        if($request->activo){
            $activo = 1;
            $fichas = Ficha_dato::where('rubro_id', $request->rubro_id)->get();
            foreach($fichas as $ficha){
                $ficha->activo = 0;
                $ficha->save();
            }
        }else{
            $activo = 0;
        }
        $dbData = Ficha_dato::find($id);
        $dbData->fill($request->all());
        $dbData->activo = $activo;
    	$dbData->save();
    	return redirect()->route('admin_ficha.index');
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

    public function countEmergentes(Request $request){
        $rubro = $request->rubro_id;
        $periodo = $request->periodo;
        $results = DB::select( DB::raw(
            "SELECT count(distinct cargo_id) cargos
               FROM encuestas_cargos e 
              WHERE cabecera_encuesta_id in ( select id 
                                                from cabecera_encuestas
                                               where rubro_id = :rubro
                                                 and periodo = :periodo)"), 
            array('rubro' => $rubro, 'periodo' => $periodo));
            //return $results;
            if($results){
                $count = $results[0]->cargos;
            }else{
                $count = 0;
            }
            return $count;
    }

}
