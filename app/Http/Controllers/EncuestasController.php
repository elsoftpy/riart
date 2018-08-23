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
}
