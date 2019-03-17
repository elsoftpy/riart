<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\beneficios_cabecera_encuesta;
use App\beneficios_pregunta;
use App\beneficios_opcion;
use App\beneficios_respuesta;
use App\Autos_marca;
use App\Autos_modelo;
use App\Aseguradora;
use App\beneficios_categoria;
use App\beneficios_item;
use App\beneficios_composicion_item;
use App\beneficios_conclusion_abierta;
use App\Color;
use App\Empresa;
use Carbon\Carbon;
use Hash;
use Auth;

class BeneficiosController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
  
        $dbData = beneficios_pregunta::paginate(15);
        return view('beneficios.list')->with('dbData', $dbData);
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

    public function clonePoll($id){
      $dbData = beneficios_cabecera_encuesta::find($id);

      return view('beneficios.create')->with('dbData', $dbData);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
      $periodo = $request->periodo;
      $id = $request->encuesta_id;
      $empresa = $request->empresa_id;
      $dbOriginal = beneficios_cabecera_encuesta::find($id);
      $dbNuevo = new beneficios_cabecera_encuesta();
      $dbNuevo->empresa_id = $empresa;
      $dbNuevo->rubro_id =  $dbOriginal->rubro_id;
      $dbNuevo->sub_rubro_id =  $dbOriginal->sub_rubro_id;
      $dbNuevo->cantidad_empleados =  $dbOriginal->cantidad_empleados;
      $dbNuevo->cantidad_sucursales =  $dbOriginal->cantidad_sucursales;
      $dbNuevo->periodo = $periodo;
      $dbNuevo->finalizada = 'N';
      $dbNuevo->save();

      $nuevoId = $dbNuevo->id;

      $oldDetalle = beneficios_respuesta::where('beneficios_cabecera_encuesta_id', $id)->get();

      foreach($oldDetalle as $detalle){
        $dbDetalle = new beneficios_respuesta();
        $dbDetalle->beneficios_cabecera_encuesta_id = $nuevoId;
        $dbDetalle->beneficios_pregunta_id = $detalle->beneficios_pregunta_id;
        $dbDetalle->beneficios_opcion_id = $detalle->beneficios_opcion_id;
        $dbDetalle->abierta = $detalle->abierta;
        $dbDetalle->save();
      }

      return redirect()->route('beneficios_admin.index');
      
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
      $request = \Request::all();
      if(count($request) > 0){
        if($request['multipage']){
          $tour = true;
        }else{
          $tour = false;
        }
      }else{
        $tour = false;
      }

        $beneficios = beneficios_pregunta::where('beneficio', 1)->get();
        $dbEmpresa = Auth::user()->empresa;
        $dbCategorias = beneficios_categoria::get();
        $dbRubro = $dbEmpresa->rubro_id;
        return view('beneficios_report.index')->with('beneficios', $beneficios)
                                              ->with('dbCategorias', $dbCategorias)
                                              ->with('dbRubro', $dbRubro)
                                              ->with('tour', $tour)
                                              ->with('dbEmpresa', $dbEmpresa);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        if(Auth::user()->is_admin){
          $dbData = beneficios_cabecera_encuesta::find($id);
        }else{
          $empresa = Empresa::find($id);  
          $dbData = beneficios_cabecera_encuesta::where('empresa_id', $id)
                                                ->orderBy('id', 'DESC')
                                                ->first();          
        }
        
        

        $now = Carbon::now();
        $rubro = $dbData->rubro_id;
        $dbDetalle = beneficios_pregunta::where('rubro_id', $rubro)
                                        ->orWhere('rubro_id', null)
                                        ->orderBy('orden')
                                        ->activa()
                                        ->get();
        $dbMarca = Autos_marca::pluck('descripcion', 'id');
        $opcioneses = collect();

        if($dbData->detalleBeneficio->where('beneficios_pregunta_id', 66)->first()){
          $marca = $dbData->detalleBeneficio->where('beneficios_pregunta_id', 66)->first()->beneficios_opcion_id;

        }else{
          $marca = 1;

        }

        $dbModelo = Autos_modelo::where('autos_marca_id', $marca)->pluck('descripcion', 'id');
        $dbAseguradora = Aseguradora::pluck('descripcion', 'id');
        
        return view('beneficios.complete')->with('dbData', $dbData)
                                          ->with('dbMarca', $dbMarca)
                                          ->with('dbModelo', $dbModelo)
                                          ->with('dbAseguradora', $dbAseguradora)
                                          ->with('dbDetalle', $dbDetalle);

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
        $respuestas = array_except($request->all(), ["_token", "_method", "submit", "checks"]);
        foreach ($respuestas as $key => $value) {
          $pregunta = beneficios_pregunta::find($key);
          switch ($pregunta->cerrada) {
            case 'S':
              if($pregunta->multiple){
                foreach ($value as $opcion) {

                  $remove = explode("_", $opcion);

                  if($remove[0] == "remove"){
                    $respuesta = beneficios_respuesta::where('beneficios_pregunta_id', $pregunta->id)
                                                     ->where('beneficios_cabecera_encuesta_id', $id)
                                                     ->where('beneficios_opcion_id', $remove[1])
                                                     ->first();
                    if(!is_null($respuesta)){
                      $respuesta->delete();
                    }

                  }else{
                    $respuesta = beneficios_respuesta::where('beneficios_pregunta_id', $pregunta->id)
                                                     ->where('beneficios_cabecera_encuesta_id', $id)
                                                     ->where('beneficios_opcion_id', $opcion)
                                                     ->first();
                    
                    if(is_null($respuesta)){
                      $respuesta = new beneficios_respuesta();  
                    }
                    $respuesta->beneficios_cabecera_encuesta_id = $id;
                    $respuesta->beneficios_pregunta_id = $pregunta->id;
                    if($opcion == ""){
                      $respuesta->beneficios_opcion_id = null;
                    }else{
                      $respuesta->beneficios_opcion_id = $opcion;
                    }
                    $respuesta->save();

                    }
                }
              }else{
                  
                  $respuesta = beneficios_respuesta::where('beneficios_pregunta_id', $pregunta->id)
                                                   ->where('beneficios_cabecera_encuesta_id', $id)
                                                   ->first();
                  if(is_null($respuesta)){
                    $respuesta = new beneficios_respuesta();  
                  }
                  $respuesta->beneficios_cabecera_encuesta_id = $id;
                  $respuesta->beneficios_pregunta_id = $pregunta->id;
                  if($value == ""){
                    $respuesta->beneficios_opcion_id = null;
                  }else{
                    $respuesta->beneficios_opcion_id = $value;
                  }
                  $respuesta->save();
                
              }
              break;
            
            case 'N':
              $respuesta = beneficios_respuesta::where('beneficios_pregunta_id', $pregunta->id)
                                               ->where('beneficios_cabecera_encuesta_id', $id)
                                               ->first();
              if(is_null($respuesta)){
                $respuesta = new beneficios_respuesta();  
              }
              $respuesta->beneficios_cabecera_encuesta_id = $id;
              $respuesta->beneficios_pregunta_id = $pregunta->id;
              $respuesta->abierta = $value;
              $respuesta->save();
              break;
          }
        }
        if(Auth::user()->is_admin){
          return redirect()->route('beneficios_admin.index');
        }else{
          return redirect()->route('home');
        }
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

    public function getModelos(Request $request){
        $id = $request->marca_id;
        $modelos = Autos_modelo::where('autos_marca_id', $id)->pluck('descripcion', 'id');
        return $modelos;
    }

    /**
     * Display Benefits Charts
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function report(Request $request){
      // Empresa a la que pertenece el usuario
      $dbEmpresa = Auth::user()->empresa;
      // Rubro de la empresa
      $rubro = $dbEmpresa->rubro_id;
      // recuperamos la penultima encuesta
      $encuesta = beneficios_cabecera_encuesta::where('empresa_id', $dbEmpresa->id)
                                              ->orderBy('id', 'DESC')
                                              ->skip(1)
                                              ->first();
      // recuperamos el item para encontrar la pregunta
      $id = $request->item_id;
      $item = beneficios_item::find($id);
      // recuperamos la pregunta
      $pregunta = $item->pregunta;

      // Encuestas del rubro
      $encuestas = beneficios_cabecera_encuesta::where('rubro_id', $rubro)
                                               ->where('periodo', $encuesta->periodo)
                                               ->pluck('id');

      // Si la pregunta es cerrada verificamos la cantidad de "no aplica" que tiene
      if($pregunta->cerrada == 'S'){
        if(!$pregunta->beneficios_pregunta_id){
          // Buscamos las encuestas que fueron respondidas con "Aplica"
          $aplicables = beneficios_respuesta::whereIn('beneficios_cabecera_encuesta_id', $encuestas)
                                            ->where('beneficios_pregunta_id', $pregunta->id)
                                            ->get();
          
          //dd($encuestas, $aplicables);
        
          //dd($aplicables);
          $aplicables = $aplicables->reject(function($item){
             if(!$item->beneficiosOpcion){
              return false;
            } 
            if($item->beneficiosOpcion->opcion_no_aplica){
              return $item;  
            }
          })->unique('beneficios_cabecera_encuesta_id')->pluck('beneficios_cabecera_encuesta_id');
        }
        if($aplicables->count() > 0){
          $practicas = beneficios_pregunta::where('beneficios_pregunta_id', $pregunta->id)->get();  
        }else{
          $practicas = collect();
        }
      }else{
        $practicas = beneficios_pregunta::where('beneficios_pregunta_id', $pregunta->id)->get();  
      }
      
      return view('beneficios_report.charts')->with('dbEmpresa', $dbEmpresa)
                                             ->with('item', $item)
                                             ->with('practicas', $practicas) ;
    }

    /**
     * Return Data for Charts
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function getChartData(Request $request){

      // Empresa
      $empresa = Auth::user()->empresa;
      // Rubro de la empresa
      $rubro = $empresa->rubro_id;

      // Ultima encuesta de la empresa
      $encuesta = beneficios_cabecera_encuesta::where("empresa_id", $empresa->id)->orderBy('id', 'DESC')->skip(1)->first();

      // Encuestas del rubro
      $encuestas = beneficios_cabecera_encuesta::where('rubro_id', $rubro)
                                                ->where('periodo', $encuesta->periodo)
                                                ->pluck('id');
      // Recuperamos el id de la Pregunta del cuestionario
      $id = $request->pregunta;
      $pregunta = beneficios_pregunta::find($id);
      // recuperamos el titulo del gráfico
      if($request->composicion){
        $titulo = beneficios_composicion_item::where('beneficios_pregunta_id', $id)
                                             ->first()
                                             ->titulo;
      }else{
        // temporalmente hasta que las prácticas estén dentro de la tabla Items
        $itemPregunta = beneficios_item::where('beneficios_pregunta_id', $id)->first();
        if(!$itemPregunta){
          $titulo = $pregunta->pregunta;
        }else{
          $titulo = $itemPregunta->titulo;  
        }
          
      }
      
      if($pregunta->cerrada == 'S'){
        if($pregunta->beneficios_pregunta_id){
          $idBeneficio = $pregunta->beneficios_pregunta_id;

          // Buscamos las encuestas que fueron respondidas con "Aplica"
          $aplicables = beneficios_respuesta::whereIn('beneficios_cabecera_encuesta_id', $encuestas)
                                            ->where('beneficios_pregunta_id', $idBeneficio)
                                            ->get();
          $aplicables = $aplicables->reject(function($item){
            if($item->beneficiosOpcion->opcion_no_aplica){
              return $item;  
            }
          })->unique('beneficios_cabecera_encuesta_id')->pluck('beneficios_cabecera_encuesta_id');

          // Recuperamos las opciones respondidas
          $opcionesResp = beneficios_respuesta::where('beneficios_pregunta_id', $pregunta->id)
                        ->whereIn('beneficios_cabecera_encuesta_id', $aplicables)
                        ->orderBy('beneficios_opcion_id')
                        ->get();
        }else{
          // Recuperamos las opciones respondidas
          $opcionesResp = beneficios_respuesta::
                          where('beneficios_pregunta_id', $pregunta->id)
                        ->whereIn('beneficios_cabecera_encuesta_id', $encuestas)
                        ->orderBy('beneficios_opcion_id')
                        ->get();
        }
        // "distinct" de las opciones respondidas
        $opcionesRespId = $opcionesResp->unique('beneficios_opcion_id')
                                       ->pluck('beneficios_opcion_id');            
        
        // Recuperamos las opciones de Respuesta de la pregunta para las leyendas del gráfico
        if($pregunta->id == 66){
          $labels = Autos_marca::whereIn('id', $opcionesRespId)
          ->orderBy('id')
          ->pluck('descripcion');
        }else if($pregunta->id == 67){
          $labels = Autos_modelo::whereIn('id', $opcionesRespId)
          ->orderBy('id')
          ->pluck('descripcion');
        }else{
          $labels = $pregunta->beneficiosOpcion
          ->whereIn('id', $opcionesRespId)
          ->sortBy('id')
          ->pluck('opcion');
        }
        // tabulamos las respuestas
        $respuestas = collect();
        $encuestasResp = beneficios_respuesta::where('beneficios_pregunta_id', $id)
                        ->whereIn('beneficios_cabecera_encuesta_id', $encuestas)
                        ->get();
        foreach($opcionesResp->groupBy('beneficios_opcion_id') as $element){
          $respuestas->push($element->count());
        };

        // sumamos el total de respuestas
        $total = $respuestas->sum();
        
        //return($opcionesResp);
        // calculamos el porcentaje de cada respuesta
        $respuestas = $respuestas->map(function($item) use($total){
          return round($item/$total*100);
        });  

        // recuperamos la lista de colores para el gráfico
        $colores = Color::get()->take(count($respuestas))->pluck('hexadecimal');
               
        // devolvemos el array con los datos para el gráfico
        $data = array(  "cerrada"=>"S",
                        "labels"=>$labels, 
                        "respuesta"=>$respuestas->toArray(), 
                        "colores"=>$colores, 
                        "titulo"=>$titulo);

      }else{
        $respuesta = beneficios_conclusion_abierta::where("beneficios_pregunta_id", $id)
                                                  ->where("rubro_id", $rubro)
                                                  ->where("periodo", $encuesta->periodo )
                                                  ->first();
        $data = array(  "cerrada"=>"N", 
                        "respuesta"=> $respuesta->conclusion,
                        "titulo"=>$titulo);
      }
      
      return $data;

    }

    /**
     * Display Sampble Composition Charts
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function compositionReport(Request $request){
      // Recuperamos el registro de empresa del Usuario 
      $dbEmpresa = Auth::user()->empresa;
      $otrosItems = beneficios_composicion_item::whereRaw('rubro_id = ? or rubro_id is null', $dbEmpresa->rubro_id)->get();
      // Recuperamos el Item a mostrar
      $item = $otrosItems->first();
      $pregunta = $item->pregunta;

      return view('beneficios_report.composicion_charts')->with('dbEmpresa', $dbEmpresa)
                                             ->with('item', $item)
                                             ->with('otrosItems', $otrosItems) ;
    }



}
