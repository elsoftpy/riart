<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;
use App\Cabecera_encuesta;
use App\Encuestas_cargo;
use App\Detalle_encuesta;
use App\Cargos_rubro;
use App\Empresa;
use App\Nivel;
use App\Cargo;
use App\Rubro;
use App\User;
use Hash;
use DB;
use Auth;
use Excel;
use Session;
use Validator;
use Exception;

class ImportExportController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $empresas = Empresa::pluck('descripcion', 'id');

        $empresaId = $empresas->keys()->first();

        $toast = session('export_done');
        if($toast){
            session()->forget('export_done');
        }

        Cabecera_encuesta::where('empresa_id', $empresaId)
                         ->pluck('periodo', 'periodo')
                         ->unique();   
        
        $periodos = Cabecera_encuesta::where('empresa_id', $empresaId)
                                      ->pluck('periodo', 'periodo')
                                      ->unique();

        return view('import_export.index')->with('empresas', $empresas)
                                          ->with('periodos', $periodos)
                                          ->with('toast', $toast);
    }

    public function getPeriodos(Request $request){
        $id = $request->empresa_id;
        $dbData = Cabecera_encuesta::where('empresa_id', $id)
                                   ->pluck('periodo', 'periodo')
                                   ->unique();

        return $dbData;        

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

    public function download(Request $request){
        ini_set('max_execution_time', 0);
        ini_set('memory_limit', '512M');
        $periodo = $request->periodo;
        $empresa = $request->empresa_id;
        $query = "SELECT d.cabecera_encuesta_id, IF(e.incluir = 1, 'NO', 'SI') excluir,  c.periodo, 
                         c.empresa_id, em.descripcion empresa, c.cantidad_empleados,
                         d.encuestas_cargo_id, convert(e.descripcion using utf8) cargo_cliente, d.area_id, convert(a.descripcion using utf8) area_cliente, d.nivel_id, n.descripcion nivel_cliente, c.rubro_id, 
                         r.descripcion rubro, ca.id, ca.descripcion cargo_oficial, ca.area_id id_area_oficial, convert(a1.descripcion using utf8) area_oficial, ca.nivel_id id_nivel_oficial, n1.descripcion nivel_oficial, cantidad_ocupantes, 
                         
                         salario_base, salario_base * 12 salario_anual, gratificacion, aguinaldo, comision, plus_rendimiento, variable_viaje, fallo_caja,
                         fallo_caja_ext, gratificacion_contrato, adicional_nivel_cargo, adicional_titulo,
                         adicional_amarre, adicional_tipo_combustible, adicional_embarque, adicional_carga,
                         bono_anual, bono_anual_salarios, incentivo_largo_plazo, refrigerio, costo_seguro_medico, 
                         cobertura_seguro_medico, costo_seguro_vida, costo_poliza_muerte_natural,
                         costo_poliza_muerte_accidente, aseguradora_id, car_company, movilidad_full, flota, autos_marca_id, autos_modelo_id, tarjeta_flota, monto_movil, 
                         seguro_movil, mantenimiento_movil, monto_km_recorrido, monto_ayuda_escolar, 
                         monto_comedor_interno, monto_curso_idioma, cobertura_curso_idioma, tipo_clase_idioma, 
                         monto_post_grado, cobertura_post_grado, monto_celular_corporativo, monto_vivienda, 
                         monto_colegiatura_hijos, condicion_ocupante
                    FROM detalle_encuestas d, encuestas_cargos e, cabecera_encuestas c, cargos ca, rubros r, 
                         areas a, niveles n, empresas em, niveles n1, areas a1
                   where c.periodo = '".$periodo."'
                     and c.empresa_id = '".$empresa."'
                     and c.id = d.cabecera_encuesta_id
                     and d.encuestas_cargo_id = e.id
                     and e.cargo_id = ca.id
                     and c.rubro_id = r.id
                     and d.area_id = a.id
                     and d.nivel_id = n.id
                     and c.empresa_id = em.id
                     and ca.nivel_id = n1.id
                     and ca.area_id = a1.id
                   order by 1";
        $dbDetalle = DB::select($query);
        // dd($query);
        $detalle = array();
        foreach ($dbDetalle as $key => $item) {
            
            $detalle[] = array( "id_encuesta"=>$item->cabecera_encuesta_id,
                                "id_empresa"=>$item->empresa_id,
                                "Empresa"=>$item->empresa, 
                                "Excluir"=>$item->excluir,
                                "Periodo"=>$item->periodo, 
                                "id_cargo_cliente"=>$item->encuestas_cargo_id, 
                                "CargoCliente"=> $item->cargo_cliente, 
                                "id_area"=>$item->area_id, 
                                "Area"=>$item->area_cliente, 
                                "id_nivel"=>$item->nivel_id, 
                                "Nivel"=>$item->nivel_cliente,
                                "id_rubro"=> $item->rubro_id, 
                                "Rubro"=> $item->rubro,
                                "id_cargo_oficial"=> $item->id, 
                                "CargoOficial"=> $item->cargo_oficial, 
                                "id_area_oficial"=>$item->id_area_oficial, 
                                "AreaOficial"=>$item->area_oficial, 
                                "id_nivel_oficial"=>$item->id_nivel_oficial, 
                                "NivelOficial"=>$item->nivel_oficial,
                                "Ocupantes"=>$item->cantidad_ocupantes, 
                                "SalarioBase"=>$item->salario_base, 
                                "Gratificacion"=> $item->gratificacion, 
                                "Aguinaldo"=> $item->aguinaldo,
                                "comision"=> $item->comision, 
                                "VariableAnual" => $item->plus_rendimiento,
                                "VariableViaje" => $item->variable_viaje,
                                "AdicionalAmarre"=>$item->adicional_amarre, 
                                "AdicionalTipoCombustible"=>$item->adicional_tipo_combustible, 
                                "AdicionalEmbarque"=>$item->adicional_embarque, 
                                "AdicionalTipoCarga"=>$item->adicional_carga,
                                "FalloCaja"=> $item->fallo_caja,
                                "FalloCajaExt"=> $item->fallo_caja_ext, 
                                "GratifContrato"=>$item->gratificacion_contrato, 
                                "AdicionalNivelCargo"=>$item->adicional_nivel_cargo, 
                                "AdicionalTítulo"=>$item->adicional_titulo,
                                "BonoAnual"=>$item->bono_anual, 
                                "IncentivoLargoPlazo"=>$item->incentivo_largo_plazo, 
                                "Refrigerio"=>$item->refrigerio, 
                                "CostoSeguroMedico"=>$item->costo_seguro_medico, 
                                "CoberturaSeguroMedico"=>$item->cobertura_seguro_medico, 
                                "CostoSeguroVida"=>$item->costo_seguro_vida, 
                                "CarCompany"=>$item->car_company, 
                                "MovilidadFull"=>$item->movilidad_full, 
                                "TarjFlota"=>$item->flota, 
                                "MontoTarjFlota"=>$item->tarjeta_flota, 
                                "MontoAutomovil"=>$item->monto_movil, 
                                "SeguroAutomovil"=>$item->seguro_movil, 
                                "MantenimientoAutomovil"=>$item->mantenimiento_movil, 
                                "KmRecorrido"=>$item->monto_km_recorrido, 
                                "AyudaEscolar"=>$item->monto_ayuda_escolar, 
                                "ComedorInterno"=>$item->monto_comedor_interno, 
                                "CursoIdioma"=>$item->monto_curso_idioma, 
                                "CoberturaIdioma"=>$item->cobertura_curso_idioma, 
                                "PostGrado"=>$item->monto_post_grado, 
                                "CoberturaPostGrado"=>$item->cobertura_post_grado, 
                                "Celular"=>$item->monto_celular_corporativo, 
                                "Vivienda"=>$item->monto_vivienda, 
                                "Colegiatura"=>$item->monto_colegiatura_hijos, 
                                "CondicionOcupante"=>$item->condicion_ocupante,
                );

             
        }
        $periodo = implode('_', explode('/', $periodo));
        $empresaDesc = Empresa::find($empresa)->descripcion;
        $filename = 'Export_'.$periodo.'_'.$empresaDesc;
        Excel::create($filename, function($excel) use($detalle, $periodo) {
            $excel->sheet($periodo, function($sheet) use($detalle){
                
                $sheet->cells('A1:BG1', function($cells){
                    $cells->setBackground('#00897b');
                    $cells->setFontColor("#FFFFFF");
                    $cells->setFontWeight("bold");
                   // $cells->setValignment('center');
                    $cells->setAlignment('center');
                });

                $sheet->cell('T1', function($cell){
                    $cell->setBackground('#fd8282');
                });

                $sheet->cell('U1', function($cell){
                    $cell->setBackground('#fd8282');
                });

                $sheet->cell('W1', function($cell){
                    $cell->setBackground('#fd8282');
                });

                $sheet->cell('BG1', function($cell){
                    $cell->setBackground('#fd8282');
                });

                $sheet->fromArray($detalle, null, 'A1');                

            });
        })->export('xlsx');
        return redirect()->route('import_export.index');

    }

    public function upload(Request $request){
        // recuperamos el archivo
        $file = $request->file('file');
        // validación
        $rules = array( 'file' => 'required|mimes:xls,xlsx'); 
        $messages = array( 'file.required' => 'No se especificó ningún archivo para subir', 
                           'file.mimes' => 'El tipo de archivo no es correcto'); 

        $validator = Validator::make($request->all(), $rules, $messages);
           
        if($validator->passes()){
            DB::beginTransaction();
            Excel::load($file, function($reader) {
                try{
                  
                    $reader->each(function($row){           
                        
                        if(!$row->id_encuesta){
                            dd($row->getRowNumber());
                        }       
                  
                        $encuesta = trim($row->id_encuesta);
                        $encCargoId = trim($row->id_cargo_cliente);
                        $cargoCliente = Encuestas_cargo::find($encCargoId);
                        if(!$cargoCliente){
                            $cargoCliente = new Encuestas_cargo();
                            $cargoCliente->cabecera_encuesta_id = $encuesta;
                        }
                        $cargoClienteDesc = trim($row->cargocliente);
                        $cargoOficialId = trim($row->id_cargo_oficial);
                        if($cargoOficialId == ''){
                            $cargoOficialId = null;
                        }
                        $excluir = trim($row->excluir);
                        if($excluir == "NO"){
                            $incluir = 1;
                        }else{
                            $incluir = 0;
                        }
                        $cargoCliente->descripcion = $cargoClienteDesc;
                        $cargoCliente->cargo_id = $cargoOficialId;
                        $cargoCliente->incluir = $incluir;
                        $cargoCliente->save();
                        $detalle = Detalle_encuesta::where('cabecera_encuesta_id', $encuesta)
                                                   ->where('encuestas_cargo_id', $encCargoId)
                                                   ->first();
                        if(!$detalle){
                            $detalle = new Detalle_encuesta();
                            $detalle->cabecera_encuesta_id = $encuesta;
                            $detalle->encuestas_cargo_id = $cargoCliente->id;
                        }
                        $detalle->cantidad_ocupantes = $row->ocupantes;
                        $detalle->area_id = $row->id_area;
                        $detalle->nivel_id = $row->id_nivel;
                        $detalle->salario_base = $row->salariobase;
                        $detalle->gratificacion = $row->gratificacion;
                        $detalle->aguinaldo = $row->aguinaldo;
                        $detalle->comision = $row->comision;
                        $detalle->plus_rendimiento = $row->variableanual;
                        $detalle->variable_viaje = $row->variableviaje;
                        $detalle->salario_base = $row->salariobase;
                        $detalle->adicional_amarre = $row->adicionalamarre;
                        $detalle->adicional_tipo_combustible = $row->adicionaltipocombustible;
                        $detalle->adicional_embarque = $row->adicionalembarque;
                        $detalle->adicional_carga = $row->adicionaltipocarga;
                        $detalle->adicional_titulo = $row->adicionaltitulo;
                        $detalle->fallo_caja = $row->fallocaja;
                        $detalle->fallo_caja_ext = $row->fallocajaext;
                        $detalle->gratificacion_contrato = $row->gratifcontrato;
                        $detalle->bono_anual = $row->bonoanual;
                        $detalle->incentivo_largo_plazo = $row->incentivolargoplazo;
                        $detalle->refrigerio = $row->refrigerio;
                        $detalle->costo_seguro_medico = $row->costoseguromedico;
                        $detalle->cobertura_seguro_medico = $row->coberturaseguromedico;
                        $detalle->costo_seguro_medico = $row->costoseguromedico;
                        $detalle->costo_seguro_vida = $row->costosegurovida;
                        $detalle->car_company = $row->carcompany;
                        $detalle->movilidad_full = $row->movilidadfull;
                        $detalle->flota = $row->tarjflota;
                        $detalle->tarjeta_flota = $row->montotarjflota;
                        $detalle->monto_movil = $row->montoautomovil;
                        $detalle->seguro_movil = $row->seguroautomovil;
                        $detalle->mantenimiento_movil = $row->mantenimientoautomovil;
                        $detalle->monto_km_recorrido = $row->kmrecorrido;
                        $detalle->monto_ayuda_escolar = $row->ayudaescolar;
                        $detalle->monto_comedor_interno = $row->comedorinterno;
                        $detalle->monto_curso_idioma = $row->cursoidioma;
                        $detalle->cobertura_curso_idioma = $row->coberturaidioma;
                        $detalle->monto_post_grado = $row->postgrado;
                        $detalle->cobertura_post_grado = $row->coberturapostgrado;
                        $detalle->monto_celular_corporativo = $row->celular;
                        $detalle->monto_vivienda = $row->vivienda;
                        $detalle->monto_colegiatura_hijos = $row->colegiatura;
                        $detalle->condicion_ocupante = $row->condicionocupante;
                        $detalle->save();                 
                        
                    });
                }catch(Exception $e){
                    DB::rollback();
                    dd($e->getMessage());
                } 
            });
            DB::commit();

        }
        
        session(['export_done'=>'true']);
        return redirect()->route('import_export.index');

    }


}
