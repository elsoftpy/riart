<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\funcionario_encuesta;
use App\cabecera_encuesta;
use App\gerencia;
use App\departamento;
use App\sector;
use App\funcionario;
use App\cargo;
use App\Mail\InvitacionEncuesta;
use Carbon\Carbon;
use Mail;


class AplicantesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $dbData = cabecera_encuesta::get();
        
        return view('aplicantes.list')->with('dbData', $dbData);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        
        $dbData = cabecera_encuesta::find($request->id);
        $dbGerencia = gerencia::all()->pluck('descripcion', 'id');

        return view('aplicantes.create')->with('idEncuesta', $request->id)
                                        ->with('dbData', $dbData)
                                        ->with('dbGerencia', $dbGerencia);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        
        $funcionarios = json_decode($request->funcionarios);
        $idEncuesta = $funcionarios[0]->idEncuesta;
        $encuestaCab = cabecera_encuesta::find($idEncuesta);
        $now = Carbon::now();
        $when = $now->diffInMinutes($encuestaCab->fecha_vigencia_ini, false);
        $encuesta = $encuestaCab->descripcion;
        foreach ($funcionarios as $key => $value) {
            $id = $value->id;
            $token = sha1(uniqid($id.$idEncuesta, true));
            $dbFuncEnc = new funcionario_encuesta();
            $dbFuncEnc->cabecera_encuesta_id = $idEncuesta;
            $dbFuncEnc->funcionario_id = $id;
            $dbFuncEnc->token = $token;
            $dbFuncEnc->save();
            $funcionario = $dbFuncEnc->funcionario->persona->full_name;
            $mail = $dbFuncEnc->funcionario->persona->email;
            $link = route('encuestas.complete', [$idEncuesta, "token"=>$token]);
            
            //if($when > 0){
                /*Mail::to($mail)
                      ->later($when, new InvitacionEncuesta($link, $encuesta, $funcionario));*/
                $mail = 'test@elsoftpy.com';
               
                Mail::to($mail)
                      ->send(new InvitacionEncuesta($link, $encuesta, $funcionario));
            //}
        }
        $dbData = cabecera_encuesta::get();
        return view('aplicantes.list')->with('dbData', $dbData);
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

    public function getDepartamentos(Request $request){
        $gerenciaId = $request->gerencia_id;
        $dbData = departamento::where("gerencia_id", $gerenciaId)->get();
        return $dbData;
    }

    public function getSectores(Request $request){
        $gerenciaId = $request->gerencia_id;
        $departamentoId = $request->departamento_id;
        $dbData = sector::where("gerencia_id", $gerenciaId)
                                ->where("departamento_id", $departamentoId)
                                ->get();
        return $dbData;
    }

    public function getFuncionarios(Request $request){
        $gerenciaId = intval($request->gerencia_id);
        $departamentoId = intval($request->departamento_id);
        $sectorId = intval($request->sector_id);
        if($gerenciaId == 0){
            $dbData = funcionario::where("estado", "A")->get();
            $dataArray = $dbData->map(function($dbData){
                            $nombres = $dbData->persona->full_name;
                            $cargo = $dbData->cargo->descripcion;
                            $id = $dbData->id;
                            return collect(["nombre"=>$nombres, "cargo"=>$cargo, "id"=>$id]);
                         });
        
        }else{
          
            if($departamentoId == 0){
                $dbCargos = cargo::where("gerencia_id", $gerenciaId)->pluck("id");
                $dbCargos = $dbCargos->toArray();
                $dbData = funcionario::where("estado", "A")
                                       ->whereIn('cargo_id', $dbCargos)->get();
                $dataArray = $dbData->map(function($dbData){
                            $nombres = $dbData->persona->full_name;
                            $cargo = $dbData->cargo->descripcion;
                            $id = $dbData->id;
                            return collect(["nombre"=>$nombres, "cargo"=>$cargo, "id"=>$id]);
                         });
               
            }else{

                if($sectorId == 0){
                    $dbCargos = cargo::where("gerencia_id", $gerenciaId)
                                       ->where("departamento_id", $departamentoId)
                                       ->pluck("id");
                    $dbCargos = $dbCargos->toArray();
                    $dbData = funcionario::where("estado", "A")
                                           ->whereIn('cargo_id', $dbCargos)->get();
                    $dataArray = $dbData->map(function($dbData){
                                $nombres = $dbData->persona->full_name;
                                $cargo = $dbData->cargo->descripcion;
                                $id = $dbData->id;
                                return collect(["nombre"=>$nombres, "cargo"=>$cargo, "id"=>$id]);
                             });
                }else{
                    $dbCargos = cargo::where("gerencia_id", $gerenciaId)
                                       ->where("departamento_id", $departamentoId)
                                       ->where("sector_id", $sectorId)
                                       ->pluck("id");
                    $dbCargos = $dbCargos->toArray();
                    $dbData = funcionario::where("estado", "A")
                                           ->whereIn('cargo_id', $dbCargos)->get();
                    $dataArray = $dbData->map(function($dbData){
                                $nombres = $dbData->persona->full_name;
                                $cargo = $dbData->cargo->descripcion;
                                $id = $dbData->id;
                                return collect(["nombre"=>$nombres, "cargo"=>$cargo, "id"=>$id]);
                             });

                }
            }    
        }
        
        if($dataArray->count() < 1){
            $dataArray = [array("nombre"=>"not found", "cargo"=>"not found", "id"=>0)];
        }
        return $dataArray;
    }    

}
