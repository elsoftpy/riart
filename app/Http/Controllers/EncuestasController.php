<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Cabecera_encuesta;
use App\Encuestas_cargo;
use App\Detalle_encuesta;
use App\Empresa;

class EncuestasController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $dbData = Cabecera_encuesta::all();

        return view('encuestas.list')->with('dbData', $dbData);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $dbEmpresas = empresa::pluck('descripcion', 'id');

        return view('encuestas.new')->with('dbEmpresas', $dbEmpresas);
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
        $dbOriginal = Cabecera_encuesta::find($id);
        $dbNuevo = new Cabecera_encuesta();
        $dbNuevo->empresa_id = $empresa;
        $dbNuevo->rubro_id =  $dbOriginal->rubro_id;
        $dbNuevo->sub_rubro_id =  $dbOriginal->sub_rubro_id;
        $dbNuevo->cantidad_empleados =  $dbOriginal->cantidad_empleados;
        $dbNuevo->cantidad_sucursales =  $dbOriginal->cantidad_sucursales;
        $dbNuevo->periodo = $periodo;
        $dbNuevo->save();

        $nuevoId = $dbNuevo->id;

        
        $oldCargos = Encuestas_cargo::where('cabecera_encuesta_id', $id)->get();
        foreach ($oldCargos as $key => $value) {
            $cargo = new Encuestas_cargo();
            $cargo->descripcion = $value->descripcion;
            $cargo->cabecera_encuesta_id = $nuevoId;
            $cargo->cargo_id = $value->cargo_id;
            $cargo->incluir = $value->incluir;
            if($value->es_contrato_periodo){
                $cargo->es_contrato_periodo = $value->es_contrato_perido;
            }else{
                $cargo->es_contrato_periodo = 0;
            }
            
            $cargo->revisado = $value->revisado;
            $cargo->save();
           
            $oldDetalle = Detalle_encuesta::where('encuestas_cargo_id', $value->id)->get();

            //dd($oldDetalle, $oldCargos);
            foreach ($oldDetalle as $index => $det) {
                //dd($det);
                $detalle = new Detalle_encuesta();
                $detalle->cabecera_encuesta_id = $nuevoId;
                $detalle->encuestas_cargo_id = $cargo->id;
                $detalle->cantidad_ocupantes = $det->cantidad_ocupantes;
                $detalle->area_id = $det->area_id;
                $detalle->nivel_id = $det->nivel_id;
                $detalle->salario_base = $det->salario_base;
                $detalle->gratificacion = $det->gratificacion;
                $detalle->aguinaldo = $det->aguinaldo;
                $detalle->comision = $det->comision;
                $detalle->plus_rendimiento = $det->plus_rendimiento;
                $detalle->fallo_caja = $det->fallo_caja;
                $detalle->fallo_caja_ext = $det->fallo_caja_ext;
                $detalle->gratificacion_contrato = $det->gratificacion_contrato;
                $detalle->adicional_nivel_cargo = $det->adicional_nivel_cargo;
                $detalle->adicional_titulo = $det->adicional_titulo;
                $detalle->adicional_amarre = $det->adicional_amarre;
                $detalle->adicional_tipo_combustible = $det->adicional_tipo_combustible;
                $detalle->adicional_embarque = $det->adicional_embarque;
                $detalle->adicional_carga = $det->adicional_carga;
                $detalle->bono_anual = $det->bono_anual;
                $detalle->bono_anual_salarios = $det->bono_anual_salarios;
                $detalle->incentivo_largo_plazo = $det->incentivo_largo_plazo;
                $detalle->refrigerio = $det->refrigerio;
                $detalle->costo_seguro_medico = $det->costo_seguro_medico;
                $detalle->cobertura_seguro_medico = $det->cobertura_seguro_medico;
                $detalle->costo_seguro_vida = $det->costo_seguro_vida;
                $detalle->costo_poliza_muerte_natural = $det->costo_poliza_muerte_natural;
                $detalle->costo_poliza_muerte_accidente = $det->costo_poliza_muerte_accidente;
                $detalle->aseguradora_id = $det->aseguradora_id;
                $detalle->car_company = $det->car_company;
                $detalle->movilidad_full = $det->movilidad_full;
                $detalle->flota = $det->flota;
                $detalle->autos_marca_id = $det->autos_marca_id;
                $detalle->autos_modelo_id = $det->autos_modelo_id;
                $detalle->tarjeta_flota = $det->tarjeta_flota;
                $detalle->monto_movil = $det->monto_movil;
                $detalle->mantenimiento_movil = $det->mantenimiento_movil;
                $detalle->monto_km_recorrido = $det->monto_km_recorrido;
                $detalle->monto_ayuda_escolar = $det->monto_ayuda_escolar;
                $detalle->monto_comedor_interno = $det->monto_comedor_interno;
                $detalle->monto_curso_idioma = $det->monto_curso_idioma;
                $detalle->cobertura_curso_idioma = $det->cobertura_curso_idioma;
                $detalle->tipo_clase_idioma = $det->tipo_clase_idioma;
                $detalle->monto_post_grado = $det->monto_post_grado;
                $detalle->cobertura_post_grado = $det->cobertura_post_grado;
                $detalle->monto_celular_corporativo = $det->monto_celular_corporativo;
                $detalle->monto_vivienda = $det->monto_vivienda;
                $detalle->monto_colegiatura_hijos = $det->monto_colegiatura_hijos;
                $detalle->condicion_ocupante = $det->condicion_ocupante;
                $detalle->zona_id = $det->zona_id;

                $detalle->save();
            }
        }

        return redirect()->route('encuestas.index');

    }

    public function storeNew(Request $request)
    {
        $periodo = $request->periodo;
        $empresa = $request->empresa_id;
        $dbEmpresa = Empresa::find($empresa);
        $dbData = new Cabecera_encuesta();
        $dbData->empresa_id = $empresa;
        $dbData->rubro_id =  $dbEmpresa->rubro_id;
        $dbData->sub_rubro_id =  $dbEmpresa->sub_rubro_id;
        $dbData->cantidad_empleados =  $dbEmpresa->cantidad_empleados;
        $dbData->cantidad_sucursales =  $dbEmpresa->cantidad_sucursales;
        $dbData->periodo = $periodo;
        $dbData->save();

        return redirect()->route('encuestas.index');

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
        $dbData = Cabecera_encuesta::find($id);
        $dbData->finalizada = 'S';
        $dbData->save();

        return redirect()->route('home');
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


    public function clonePoll($id)
    {
        $dbData = Cabecera_encuesta::find($id);

        return view('encuestas.create')->with('dbData', $dbData);
    }

    public function cloneBancard()
    {
        return view('encuestas.clonar_bancard')->with('toast', false);
    }

    public function clonarBancard(Request $request){
        ini_set('max_execution_time', 0);
        ini_set('memory_limit', '500M');
        $periodo = $request->periodo;

        $encuestas = Cabecera_encuesta::where('periodo', $periodo)
                                      ->where('rubro_id', 1)
                                      ->get();
        foreach($encuestas as $encuesta){
            $cabecera = $encuesta->replicate();
            $cabecera->rubro_id = 12;

            switch ($cabecera->empresa_id) {
                case 1:
                    $cabecera->empresa_id = 130;
                    break;
                case 2:
                    $cabecera->empresa_id = 131;
                    break;
                case 3:
                    $cabecera->empresa_id = 132;
                    break;
                
                case 4:
                    $cabecera->empresa_id = 133;
                    break;
                case 5:
                    $cabecera->empresa_id = 134;
                    break;
                case 6:
                    $cabecera->empresa_id = 135;
                    break;
                case 7:
                    $cabecera->empresa_id = 136;
                    break;
                case 8:
                    $cabecera->empresa_id = 137;
                    break;
                case 9:
                    $cabecera->empresa_id = 138;
                    break;
                case 10:
                    $cabecera->empresa_id = 139;
                    break;
                case 11:
                    $cabecera->empresa_id = 140;
                    break;
                case 12:
                    $cabecera->empresa_id = 141;
                    break;
                case 53:
                    $cabecera->empresa_id = 142;
                    break;
                case 83:
                    $cabecera->empresa_id = 143;
                    break;
                case 127:
                    $cabecera->empresa_id = 147;
                    break;
                
                default:
                    $cabecera->empresa_id = 130;
                    break;
            }
            $cabecera->save();
            $encuestaCargo = $encuesta->encuestasCargo;
            foreach($encuestaCargo as $cargo){
                $newCargo = $cargo->replicate();
                $newCargo->cabecera_encuesta_id = $cabecera->id;
                $newCargo->save();
                $detalle = $cargo->detalleEncuestas;
                if($detalle){
                    $newDetalle = $detalle->replicate();
                    $newDetalle->cabecera_encuesta_id = $cabecera->id;
                    $newDetalle->encuestas_cargo_id = $newCargo->id;
                    $newDetalle->save();
                }
            }

        }
        
        $toast = true;

        return redirect()->route('clonar.bancard')->with('toast', $toast);
    }

    public function clonePuente()
    {
        return view('encuestas.clonar_puente')->with('toast', false);
    }

    public function clonarPuente(Request $request){
        ini_set('max_execution_time', 0);
        ini_set('memory_limit', '500M');
        $periodo = $request->periodo;

        $encuestas = Cabecera_encuesta::where('periodo', $periodo)
                                      ->where('rubro_id', 1)
                                      ->get();
        foreach($encuestas as $encuesta){
            $cabecera = $encuesta->replicate();
            $cabecera->rubro_id = 13;

            switch ($cabecera->empresa_id) {
                case 1:
                    $cabecera->empresa_id = 149;
                    break;
                case 2:
                    $cabecera->empresa_id = 150;
                    break;
                case 3:
                    $cabecera->empresa_id = 151;
                    break;
                
                case 4:
                    $cabecera->empresa_id = 152;
                    break;
                case 5:
                    $cabecera->empresa_id = 153;
                    break;
                case 6:
                    $cabecera->empresa_id = 154;
                    break;
                case 7:
                    $cabecera->empresa_id = 155;
                    break;
                case 8:
                    $cabecera->empresa_id = 156;
                    break;
                case 9:
                    $cabecera->empresa_id = 157;
                    break;
                case 10:
                    $cabecera->empresa_id = 158;
                    break;
                case 11:
                    $cabecera->empresa_id = 159;
                    break;
                case 12:
                    $cabecera->empresa_id = 160;
                    break;
                case 53:
                    $cabecera->empresa_id = 161;
                    break;
                default:
                    $cabecera->empresa_id = 149;
                    break;
            }
            $cabecera->save();
            $encuestaCargo = $encuesta->encuestasCargo;
            foreach($encuestaCargo as $cargo){
                $newCargo = $cargo->replicate();
                $newCargo->cabecera_encuesta_id = $cabecera->id;
                $newCargo->save();
                $detalle = $cargo->detalleEncuestas;
                if($detalle){
                    $newDetalle = $detalle->replicate();
                    $newDetalle->cabecera_encuesta_id = $cabecera->id;
                    $newDetalle->encuestas_cargo_id = $newCargo->id;
                    $newDetalle->save();
                }
            }

        }
        $toast = true;

        return redirect()->route('clonar.puente')->with('toast', $toast);
    }

    public function cloneIndustrial()
    {
        return view('encuestas.clonar_industrial')->with('toast', false);
    }

    public function clonarIndustrial(Request $request){

        ini_set('max_execution_time', 0);
        ini_set('memory_limit', '500M');
        $periodoOriginal = $request->periodo_original;
        $periodoNuevo = $request->periodo_nuevo;
        //$empresas = [43, 110, 50, 106, 49, 48, 44, 32, 35, 125, 162, 51];
        $empresas = [30];

        $encuestas = Cabecera_encuesta::where('periodo', $periodoOriginal)
                                      ->where('rubro_id', 2)
                                      ->whereIn('empresa_id', $empresas)
                                      ->get();

        foreach($encuestas as $encuesta){
            $cabecera = $encuesta->replicate();
            $cabecera->rubro_id = 14;

            switch ($cabecera->empresa_id) {
                case 43:
                    $cabecera->empresa_id = 166;
                    break;
                case 110:
                    $cabecera->empresa_id = 167;
                    break;
                case 50:
                    $cabecera->empresa_id = 168;
                    break;
                
                case 106:
                    $cabecera->empresa_id = 169;
                    break;
                case 49:
                    $cabecera->empresa_id = 170;
                    break;
                case 48:
                    $cabecera->empresa_id = 171;
                    break;
                case 44:
                    $cabecera->empresa_id = 172;
                    break;
                case 32:
                    $cabecera->empresa_id = 173;
                    break;
                case 35:
                    $cabecera->empresa_id = 174;
                    break;
                case 125:
                    $cabecera->empresa_id = 175;
                    break;
                case 162:
                    $cabecera->empresa_id = 176;
                    break;
                case 51:
                    $cabecera->empresa_id = 177;
                    break;
                case 30:
                    $cabecera->empresa_id = 178;
            }
            $cabecera->save();
            $encuestaCargo = $encuesta->encuestasCargo;
            foreach($encuestaCargo as $cargo){
                $newCargo = $cargo->replicate();
                $newCargo->cabecera_encuesta_id = $cabecera->id;
                $newCargo->save();
                $detalle = $cargo->detalleEncuestas;
                if($detalle){
                    $newDetalle = $detalle->replicate();
                    $newDetalle->cabecera_encuesta_id = $cabecera->id;
                    $newDetalle->encuestas_cargo_id = $newCargo->id;
                    $newDetalle->save();
                }
            }

        }
        $toast = true;

        return redirect()->route('clonar.industrial')->with('toast', $toast);
    }

    public function cloneBancosNacionales()
    {
        return view('encuestas.clonar_bancos_nacional')->with('toast', false);
    }

    public function clonarBancosNacionales(Request $request){
        ini_set('max_execution_time', 0);
        ini_set('memory_limit', '500M');
        $periodo = $request->periodo;

        $encuestas = Cabecera_encuesta::where('periodo', $periodo)
                                      ->where('rubro_id', 1)
                                      ->whereIn('empresa_id', [1, 2, 5, 6, 8, 10, 53])
                                      ->get();

        foreach($encuestas as $encuesta){
            $cabecera = $encuesta->replicate();
            $cabecera->rubro_id = 15;

            switch ($cabecera->empresa_id) {
                case 1:
                    $cabecera->empresa_id = 179;
                    break;
                case 2:
                    $cabecera->empresa_id = 185;
                    break;
                case 5:
                    $cabecera->empresa_id = 184;
                    break;
                
                case 6:
                    $cabecera->empresa_id = 180;
                    break;
                case 8:
                    $cabecera->empresa_id = 183;
                    break;
                case 10:
                    $cabecera->empresa_id = 182;
                    break;
                case 53:
                    $cabecera->empresa_id = 181;
                    break;
                
                default:
                    $cabecera->empresa_id = 186;
                    break;
            }
            $cabecera->save();
            $encuestaCargo = $encuesta->encuestasCargo;
            foreach($encuestaCargo as $cargo){
                $newCargo = $cargo->replicate();
                $newCargo->cabecera_encuesta_id = $cabecera->id;
                $newCargo->save();
                $detalle = $cargo->detalleEncuestas;
                if($detalle){
                    $newDetalle = $detalle->replicate();
                    $newDetalle->cabecera_encuesta_id = $cabecera->id;
                    $newDetalle->encuestas_cargo_id = $newCargo->id;
                    $newDetalle->save();
                }
            }

        }
        
        $toast = true;

        return redirect()->route('clonar.bancard')->with('toast', $toast);
    }
}
