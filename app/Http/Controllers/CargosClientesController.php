<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Encuestas_cargo;
use App\Cabecera_encuesta;
use App\Detalle_encuesta;
use App\Area;
use App\Aseguradora;
use App\Nivel;
use App\Autos_modelo;
use App\Autos_marca;
use App\Zona;
use Auth;
use flash;
class CargosClientesController extends Controller
{
    public function index(){
        $dbData = Encuestas_cargo::where('');
    	return view('cargos_clientes.list')->with('dbData', $dbData);
    }

    public function create(){
		$dbNivel = Nivel::get()->pluck('descripcion', 'id');
		$dbAseguradora = Aseguradora::get()->pluck('descripcion', 'id');
		$dbZona = Zona::get()->pluck('descripcion', 'id');
        $dbMarca = Autos_marca::get()->pluck('descripcion', 'id');
        $idMarca =$dbMarca->keys()->first();
        $dbModelo = Autos_modelo::where('autos_marca_id', $idMarca)->get()->pluck('descripcion', 'id');
        $dbArea = Area::get()->pluck('descripcion', 'id');
    	return view('cargos_clientes.create')->with('dbNivel', $dbNivel)
    								->with('dbArea', $dbArea)
                                    ->with('dbMarca', $dbMarca)
                                    ->with('dbModelo', $dbModelo)
                                    ->with('dbZona', $dbZona)
    								->with('dbAseguradora', $dbAseguradora);
    }

    public function store(Request $request){
        //dd($request->server('HTTP_ACCEPT_LANGUAGE'));

        if(Auth::user()->is_admin){
            $id = Auth::user()->empresa_id;    // cambiar para permitir crear empleado desde consultora
        }else{
            $id = Auth::user()->empresa_id;    
        }
        
        
        $dbEncuesta = Cabecera_encuesta::where('empresa_id', $id)->whereRaw('id = (select max(id) from cabecera_encuestas where empresa_id = '. $id.')')->first();
        
        $dbCargo = new Encuestas_cargo();
        $dbCargo->descripcion = $request->descripcion;
        $dbCargo->cabecera_encuesta_id = $dbEncuesta->id;
        $dbCargo->save();
        $fields = collect();
        foreach ($request->all() as $key => $value) {
            if(($key !== "area_id" || $key !== "nivel_id" || $key !== "aseguradora_id" || $key !== "autos_marca_id" || $key !==  "autos_modelo_id" || $key !== "zona_id") && $value == ""){
                $value = 0;
            }

            $posComa = strpos($value, ",");
            $posPunto = strpos($value, ".");

            if ($posComa){
                $real = str_replace("%", "", str_replace(",", "", $value));         // quita los caracteres de formato numérico

            }elseif ($posPunto) {
                $real = str_replace("%", "", str_replace(".", "", $value));         // quita los caracteres de formato numérico
            }else{
                $real = str_replace("%", "", $value);
            }
            
            
            $fields->put($key,$real);
        }
        
        if(!$fields->has("car_company")){
            $fields->put("car_company", 0);
        }else{
            if($fields["car_company"] == "S"){
                $fields["car_company"] = 1;
            }else{
                $fields["car_company"] = 0;
            }
        }


        if(!$fields->has("tarjeta_flota")){
            $fields->put("tarjeta_flota", 0);
        }else{
            if($fields["tarjeta_flota"] == "S"){
                $fields["tarjeta_flota"] = 1;
            }else{
                $fields["tarjeta_flota"] = 0;
            }
        }

        if(!$fields->has("tipo_clase_idioma")){
            $fields->put("tipo_clase_idioma", "N");
        }

        if(!$fields->has("condicion_ocupante")){
            $fields->put("condicion_ocupante", "L");
        }
        $dbData = new Detalle_encuesta($fields->toArray());

        $dbData->cabecera_encuesta_id = $dbEncuesta->id;
        $dbData->encuestas_cargo_id = $dbCargo->id;
        
    	$dbData->save();
    	return redirect()->route('cargos_clientes.show', $id);
    }

     public function show($id)
    {
        $dbEncuesta = Cabecera_encuesta::where('empresa_id', $id)
                                       ->whereRaw('id = (select max(id) from cabecera_encuestas where empresa_id = '. $id.')')
                                       ->first();

        if($dbEncuesta){
            $dbData = Encuestas_cargo::where('cabecera_encuesta_id', $dbEncuesta->id)
                                     ->get();

            $dbDetalle = Detalle_encuesta::where('cabecera_encuesta_id', $dbEncuesta->id)
                                         ->get();
        }else{
            $dbData = null;
            $dbDetalle = null;
        }

        $dbEmpresa = $dbEncuesta->empresa->descripcion;
        $dbPeriodo = $dbEncuesta->periodo;

   
        return view('cargos_clientes.list') ->with('dbData', $dbData)
                                            ->with('dbEmpresa', $dbEmpresa)
                                            ->with('dbPeriodo', $dbPeriodo)
                                            ->with('dbDetalle', $dbDetalle);

    }

    public function edit($id){
    	$dbData = Encuestas_cargo::find($id);
        $dbDetalle = Detalle_encuesta::where('Encuestas_cargo_id', $dbData->id)->first();
        $dbNivel = Nivel::get()->pluck('descripcion', 'id');
        $dbAseguradora = Aseguradora::get()->pluck('descripcion', 'id');
        $dbZona = Zona::get()->pluck('descripcion', 'id');
        $dbMarca = Autos_marca::get()->pluck('descripcion', 'id');
        $idMarca =$dbDetalle->autos_marca_id;
        $dbModelo = Autos_modelo::where('autos_marca_id', $idMarca)->get()->pluck('descripcion', 'id');
        $dbArea = Area::get()->pluck('descripcion', 'id');

        if($dbData->cabeceraEncuestas->finalizada == "N"){
            $view = 'cargos_clientes.edit';
        }else{
            $view = 'cargos_clientes.sheet';
        }
        
        return view($view)->with('dbData', $dbData) 
                                           ->with('dbDetalle', $dbDetalle)
                                           ->with('dbNivel', $dbNivel)
                                           ->with('dbArea', $dbArea)
                                           ->with('dbMarca', $dbMarca)
                                           ->with('dbModelo', $dbModelo)
                                           ->with('dbZona', $dbZona)
                                           ->with('dbAseguradora', $dbAseguradora);;
    }

    public function update(Request $request, $id){
        
    	$dbData = Encuestas_cargo::find($id);
        $dbDetalle = Detalle_encuesta::find($dbData->DetalleEncuestas->id);
    	$fields = collect();
        foreach ($request->all() as $key => $value) {
            if(($key !== "area_id" || $key !== "nivel_id" || $key !== "aseguradora_id" || $key !== "autos_marca_id" || $key !==  "autos_modelo_id" || $key !== "zona_id") && $value == ""){
                $value = 0;
            }

            $real = str_replace("%", "", str_replace(".", "", $value));         // quita los caracteres de formato numérico

            $fields->put($key,$real);
        }
        
        if(!$fields->has("car_company")){
            $fields->put("car_company", 0);
        }else{
            if($fields["car_company"] == "S"){
                $fields["car_company"] = 1;
            }else{
                $fields["car_company"] = 0;
            }
        }


        if(!$fields->has("tarjeta_flota")){
            $fields->put("tarjeta_flota", 0);
        }else{
            if($fields["tarjeta_flota"] == "S"){
                $fields["tarjeta_flota"] = 1;
            }else{
                $fields["tarjeta_flota"] = 0;
            }
        }

        if(!$fields->has("tipo_clase_idioma")){
            $fields->put("tipo_clase_idioma", "N");
        }

        if(!$fields->has("condicion_ocupante")){
            $fields->put("condicion_ocupante", "L");
        }
        $dbDetalle->fill($fields->toArray());
        
        $dbData->descripcion = $request->descripcion;
        
         //dd($dbDetalle);
        if($request->excluir){
            $dbData->incluir = 0;
        }else{
            $dbData->incluir = 1;
        };

        if($request->es_contrato_periodo){
            $dbData->es_contrato_periodo = 1;
        }else{
            $dbData->es_contrato_periodo = 0;
        }


    	$dbData->save();
        $dbDetalle->save();
        $empresa = $dbDetalle->cabeceraEncuesta->empresa_id;
        if(Auth::user()->is_admin){
		  return redirect()->route('encuestas_cargos.showHistory', $dbDetalle->cabeceraEncuesta->id);
        }else{
          return redirect()->route('cargos_clientes.show', $empresa);
        }
    }

    public function destroy($id){
		$dbData = Cargo::find($id);
        $dbFuncionario = $dbData->funcionario;

        if($dbFuncionario->count() > 0 ){
            Flash::elsoftMessage(3015, true);
        }else{
            Flash::elsoftMessage(2015, true);
            $dbData->delete();            
        }        
		return redirect()->route('cargos.index');    	
    }

}
