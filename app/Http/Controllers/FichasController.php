<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Ficha_dato;
use App\Cabecera_encuesta;
use App\Rubro;
use App\Traits\PeriodosTrait;
use flash;

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

    	$dbData->save();
    	return redirect()->route('admin_ficha.index');
    }

     public function show($id)
    {
        //
    }

    public function edit($id){
        $dbData = Ficha_dato::find($id);
        $rubro = Rubro::get()->first()->id;
        $periodos = $this->getPeriodos($rubro);
        $rubros = $this->getRubros();
        return view('fichas.create')->with('dbData', $dbData)
                                    ->with('periodos', $periodos)
    								->with('rubros', $rubros);
    }

    public function update(Request $request, $id){

    	$dbData = new Ficha_dato($request->all());

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


}
