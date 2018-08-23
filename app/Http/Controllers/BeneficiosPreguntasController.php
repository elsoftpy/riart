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
use Carbon\Carbon;
use Hash;
use Auth;

class BeneficiosPreguntasController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $dbData = beneficios_pregunta::orderBy('orden')->paginate(15);
        return view('beneficios_preguntas.list')->with('dbData', $dbData);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $dbData = beneficios_pregunta::orderBy('orden')->get();
        return view('beneficios_preguntas.create')->with('dbData', $dbData);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // guardar pregunta
        $dbData = new beneficios_pregunta();
        $dbData->pregunta = $request->pregunta;
        if($request->cerrada){
          $dbData->cerrada = 'S';
        }else{
          $dbData->cerrada = 'N';
        }
        $dbData->multiple = $request->multiple;
        $dbData->beneficio = $request->beneficio;
        if($request->beneficios_pregunta_id == ""){
          $dbData->beneficios_pregunta_id = null;  
        }else{
          $dbData->beneficios_pregunta_id = $request->beneficios_pregunta_id;  
        }

        $dbData->orden = $request->orden;
        $dbData->save();
        $id = $dbData->id;
        // guardar opciones
        $opciones = json_decode($request->options);
        if($opciones){
          foreach ($opciones as $key => $value) {
            $dbOpciones = new Beneficios_opcion();
            $dbOpciones->beneficios_pregunta_id = $id;
            $dbOpciones->opcion = $value->opcion;
            $dbOpciones->save();
          }
        }
        // reordenar
        $dbPreguntas = beneficios_pregunta::where('orden', '>', $request->orden - 1 )->get();
        
        foreach ($dbPreguntas as $pregunta) {
          if($id != $pregunta->id){
            $row =   beneficios_pregunta::find($pregunta->id);
            $row->orden = $pregunta->orden + 1;
            $row->save();
          }
        }
        return redirect()->route('beneficios_preguntas.index');
        
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {

        $dbData = beneficios_pregunta::find($id);
        $dbPreguntas = beneficios_pregunta::orderBy('orden')->get();
        return view('beneficios_preguntas.edit')->with('dbPreguntas', $dbPreguntas)
                                                ->with('dbData', $dbData);


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
        $dbData = beneficios_pregunta::find($id);
        $dbData->pregunta = $request->pregunta;
        if($request->cerrada){
          $dbData->cerrada = 'S';
        }else{
          $dbData->cerrada = 'N';
        }
        $dbData->multiple = $request->multiple;
        $dbData->beneficio = $request->beneficio;
        if($request->beneficios_pregunta_id == ""){
          $dbData->beneficios_pregunta_id = null;  
        }else{
          $dbData->beneficios_pregunta_id = $request->beneficios_pregunta_id;  
        }

        if($request->activa){
            $dbData->activa = 1;
        }else{
            $dbData->activa = 0;
        }
        
        $dbData->orden = $request->orden;
        $dbData->save();

      
        if($request->options){
            $options = json_decode($request->options);
            foreach ($options as $item) {
                if(isset($item->id)){
                    $opcion = beneficios_opcion::find($item->id);
                }else{
                    $opcion = new beneficios_opcion();
                }
                $opcion->beneficios_pregunta_id = $id;
                $opcion->opcion = $item->opcion;
                $opcion->save();
            }
        }

        if($request->deleted){
            $deleted = json_decode($request->deleted);
            foreach ($deleted as $item) {
                $opcion = beneficios_opcion::find($item);
                $opcion->delete();
            }
        }
      
        return redirect()->route('beneficios_preguntas.index');
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

}
