<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Cabecera_encuesta;
use App\Detalle_encuesta;
use App\Encuestas_cargo;
use App\Cargo;
use App\Nivel;
use App\Aseguradora;
use App\Zona;
use App\Autos_marca;
use App\Autos_modelo;
use App\Area;
class EncuestasCargosController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
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
        ini_set('memory_limit', '1020M');
        $EncuestaId = Cabecera_encuesta::where('empresa_id', $id)->max('id');
        $dbData = Encuestas_cargo::where('cabecera_encuesta_id', $EncuestaId)->get();
        $dbCargos = Cargo::pluck('descripcion', 'id');
        $dbCargos->prepend("Elija una opción", "0");
        
        return view('encuestas_cargos.show')->with('dbData', $dbData)->with('dbCargos', $dbCargos)->with('id', $id);

    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function showHistory($id)
    {
        ini_set('memory_limit', '1020M');
        $dbData = Encuestas_cargo::where('cabecera_encuesta_id', $id)->get();
        $dbEncuesta = Cabecera_encuesta::find($id);
        $dbEmpresa = $dbEncuesta->empresa->descripcion;
        $dbPeriodo = $dbEncuesta->periodo;
        $dbCargos = Cargo::pluck('descripcion', 'id');
        $dbCargos->prepend("Elija una opción", "0");
        
        return view('encuestas_cargos.show')->with('dbData', $dbData)
                                            ->with('dbCargos', $dbCargos)
                                            ->with('dbEmpresa', $dbEmpresa)
                                            ->with('dbPeriodo', $dbPeriodo)
                                            ->with('id', $id);

    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {

        $dbNivel = Nivel::get()->pluck('descripcion', 'id');
        $dbAseguradora = Aseguradora::get()->pluck('descripcion', 'id');
        $dbZona = Zona::get()->pluck('descripcion', 'id');
        $dbMarca = Autos_marca::get()->pluck('descripcion', 'id');
        $marcaId = $dbMarca->keys()->first();
        $dbModelo = Autos_modelo::where('autos_marca_id', $marcaId)->pluck('descripcion', 'id');
        $dbArea = Area::get()->pluck('descripcion', 'id');
        return view('cargos_clientes.add')->with('dbNivel', $dbNivel)
                                          ->with('dbArea', $dbArea)
                                          ->with('dbMarca', $dbMarca)
                                          ->with('dbModelo', $dbModelo)
                                          ->with('dbZona', $dbZona)
                                          ->with('id', $id)
                                          ->with('dbAseguradora', $dbAseguradora);

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
        $dbEncuesta = Cabecera_encuesta::find($id);

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

        $dbData = Encuestas_cargo::where('cabecera_encuesta_id', $id)->get();
        $dbCargos = Cargo::pluck('descripcion', 'id');
        $dbCargos->prepend("Elija una opción", "0");

        return view('encuestas_cargos.show')->with('dbData', $dbData)->with('dbCargos', $dbCargos)->with('id', $id);
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

    public function addCargo($id){


    }

    public function addCargoStore(Request $request, $id){
        
    }


}