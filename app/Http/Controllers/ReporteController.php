<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;
use App\Cabecera_encuesta;
use App\Encuestas_cargo;
use App\Detalle_encuesta;
use App\Empresa;
use App\Nivel;
use App\Cargo;
use App\Rubro;
use DB;
use Auth;

class ReporteController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

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
        $empresa = Empresa::find($id);
        $club = $this->club($empresa->rubro_id);
        switch ($club) {
            case "Bancos":
                $imagen = "images/caratula-bancos.PNG";
                break;
            case "Agronegocios":
                $imagen = "images/caratula-agro.PNG";
                break;
            case "Navieras":
                $imagen = "images/caratula-naviera.PNG";
                break;
            default:
                $imagen = "images/caratula-bancos.PNG";
                break;
        }

        return view('report.home')->with('dbEmpresa', $id)->with('imagen', $imagen)->with('club', $club);
    }

    private function club($rubro){
        switch ($rubro) {
            case 1:
                $imagen = "images/caratula-bancos.PNG";
                $club = "Bancos";
                break;
            case 2:
                $imagen = "images/caratula-agro.PNG";
                $club = "Agronegocios";
                break;
            case 4:
                $imagen = "images/caratula-naviera.PNG";
                $club = "Navieras";
                break;
            default:
                $imagen = "images/caratula-bancos.PNG";
                $club = "Bancos";
                break;
        }
        return $club;        
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function filter($id)
    {
        $dbNiveles = Nivel::pluck('descripcion', 'id');
        $dbCargos = Cargo::pluck('descripcion', 'id');
        $dbEmpresa = $id;
        return view('report.filter')->with('dbNiveles', $dbNiveles)->with('dbCargos', $dbCargos)->with('dbEmpresa', $dbEmpresa);
    }

    public function ficha($id){
        $dbEmpresa = $id;
        $empresa = Empresa::find($id);
        $rubro = $empresa->rubro_id;
        $dbEncuesta = Cabecera_encuesta::where('empresa_id', $id)->whereRaw('id = (select max(id) from cabecera_encuestas where empresa_id = '. $id.')')->first();
        $cargos = Encuestas_cargo::where('cabecera_encuesta_id', $dbEncuesta->id)->get()->count();
        $periodo = $dbEncuesta->periodo;
        $participantes = Cabecera_encuesta::where('periodo', $periodo)->where('rubro_id', $rubro)->get()->count();
        $club = $this->club($empresa->rubro_id);
        return view('report.ficha')->with('dbEmpresa', $dbEmpresa)
                                   ->with('cargos', $cargos)
                                   ->with('periodo', $periodo)
                                   ->with('club', $club)
                                   ->with('participantes', $participantes);
    }

    public function cargoReport(Request $request){

        
        $dbEmpresa = Empresa::find($request->empresa_id);   // datos de la empresa del cliente
        $dbEncuesta = Cabecera_encuesta::where('empresa_id', $request->empresa_id)->whereRaw('id = (select max(id) from cabecera_encuestas where empresa_id = '. $request->empresa_id.')')->first();    // datos de la encuesta actual

        $periodo = $dbEncuesta->periodo;    // periodo de la encuesta actual
        $rubro = $dbEmpresa->rubro_id;      // rubro de la empresa del cliente
        // cargo oficial para el informe
        $cargo = $request->cargo_id;        
        $dbCargo = Cargo::find($cargo);     
        // empresas y cabeceras de encuestas de este periodo para empresas del rubro del cliente
        $dbEncuestadas = Cabecera_encuesta::where('periodo', $periodo)->where('rubro_id', $rubro)->get();
        $encuestadasIds = $dbEncuestadas->pluck("id");  // Ids de las encuestas para where in
        // conteo de encuestas según origen
        $dbNacionales = 0;
        $dbInternacionales = 0;
        $dbUniverso = $dbEncuestadas->count();
        $encuestadasNacIds = collect();
        $encuestadasInterIds = collect();
        foreach ($dbEncuestadas as $key => $value) {
            if($value->empresa->tipo == 0){
                $dbNacionales++;
                $encuestadasNacIds[] = $value->id;
            }else{
                $dbInternacionales++;
                $encuestadasInterIds[] = $value->id;
            };
        }
        // Recuperamos los datos de los cargos proveídos por las empresas encuestadas
        $dbCargosEncuestas = Encuestas_cargo::whereIn('cabecera_encuesta_id', $encuestadasIds)->where('cargo_id', $cargo)->where('incluir', 1)->get();
        $dbCargosEncuestasNac = Encuestas_cargo::whereIn('cabecera_encuesta_id', $encuestadasNacIds)->where('cargo_id', $cargo)->where('incluir', 1)->get();
        $dbCargosEncuestasInter = Encuestas_cargo::whereIn('cabecera_encuesta_id', $encuestadasInterIds)->where('cargo_id', $cargo)->where('incluir', 1)->get();
        $cargosEncuestasIds = $dbCargosEncuestas->pluck('id');
        $cargosEncuestasNacIds = $dbCargosEncuestasNac->pluck('id');
        $cargosEncuestasInterIds = $dbCargosEncuestasInter->pluck('id');

        // Recuperamos los datos de las encuestas
        $dbDetalle = Detalle_encuesta::whereIn('encuestas_cargo_id', $cargosEncuestasIds)->get();
        //dd($dbDetalle->where('beneficios_navieras', 12));
        // Datos de la encuesta llenada por el cliente
        $dbClienteEnc = $dbDetalle->where('cabecera_encuesta_id', $dbEncuesta->id)->first();
        if(empty($dbClienteEnc)){
            // get the column names for the table
            $columns = Schema::getColumnListing('detalle_encuestas');
            // create array where column names are keys, and values are null
            $columns = array_fill_keys($columns, 0);        
            $dbClienteEnc = new Detalle_encuesta();
            $dbClienteEnc = $dbClienteEnc->newInstance($columns, true);
        }
        // conteo de casos encontrados
        $countCasos = $dbDetalle->unique('cabecera_encuesta_id')->count();
        $countOcupantes = $dbDetalle->sum('cantidad_ocupantes');
        $countCasosGratif = $dbDetalle->where('gratificacion', '>', '0')->unique('cabecera_encuesta_id')->count();
        $countCasosAguinaldo = $dbDetalle->where('aguinaldo', '>', '0')->unique('cabecera_encuesta_id')->count();
        $countCasosBeneficios = $dbDetalle->where('beneficios_bancos', '>', 0)->unique('cabecera_encuesta_id')->count();
        $countCasosBono = $dbDetalle->where('bono_anual', '>', 0)->unique('cabecera_encuesta_id')->count();

        $universo = collect();
        $segmento = "universo";
        $this->segmenter( $universo, 
                          $dbUniverso, 
                          $dbDetalle, 
                          $countCasos,
                          $countCasosGratif,
                          $countCasosAguinaldo,
                          $countCasosBeneficios, 
                          $countCasosBono,
                          $dbClienteEnc, 
                          $rubro, 
                          $segmento);
        // conteo de casos encontrados nacionales
        $countCasosNac = $encuestadasNacIds->count();
        // buscamos los detalles de las encuestas
        $dbDetalleNac = Detalle_encuesta::whereIn('encuestas_cargo_id', $cargosEncuestasNacIds)->get();
        // conteo de casos encontrados
        $countOcupantesNac = $dbDetalleNac->sum('cantidad_ocupantes');
        $countCasos = $dbDetalleNac->unique('cabecera_encuesta_id')->count();
        $countCasosGratif = $dbDetalleNac->where('gratificacion', '>', '0')->unique('cabecera_encuesta_id')->count();
        $countCasosAguinaldo = $dbDetalleNac->where('aguinaldo', '>', '0')->unique('cabecera_encuesta_id')->count();
        $countCasosBeneficios = $dbDetalleNac->where('beneficios_bancos', '>', 0)->unique('cabecera_encuesta_id')->count();
        $countCasosBono = $dbDetalleNac->where('bono_anual', '>', 0)->unique('cabecera_encuesta_id')->count();

        $nacional = collect();
        $segmento = "nacional";
        $this->segmenter(   $nacional, 
                            $countCasosNac, 
                            $dbDetalleNac, 
                            $countCasos, 
                            $countCasosGratif, 
                            $countCasosAguinaldo, 
                            $countCasosBeneficios, 
                            $countCasosBono, 
                            $dbClienteEnc, 
                            $rubro, 
                            $segmento);

        // conteo de casos encontrados internacionales
        $countCasosInt = $encuestadasInterIds->count();
        // buscamos los detalles de las encuestas
        $dbDetalleInt = Detalle_encuesta::whereIn('encuestas_cargo_id', $cargosEncuestasInterIds)->get();
        $countOcupantesInt = $dbDetalleInt->sum('cantidad_ocupantes');
        // conteo de casos encontrados
        $countCasos = $dbDetalleInt->unique('cabecera_encuesta_id')->count();
        $countCasosGratif = $dbDetalleInt->where('gratificacion', '>', '0')->unique('cabecera_encuesta_id')->count();
        $countCasosAguinaldo = $dbDetalleInt->where('aguinaldo', '>', '0')->unique('cabecera_encuesta_id')->count();
        $countCasosBeneficios = $dbDetalleInt->where('beneficios_bancos', '>', 0)->unique('cabecera_encuesta_id')->count();
        $countCasosBono = $dbDetalleInt->where('bono_anual', '>', 0)->unique('cabecera_encuesta_id')->count();

        $internacional = collect();
        $segmento = "internacional";

        $this->segmenter(   $internacional, 
                            $countCasosInt, 
                            $dbDetalleInt, 
                            $countCasos, 
                            $countCasosGratif, 
                            $countCasosAguinaldo, 
                            $countCasosBeneficios, 
                            $countCasosBono, 
                            $dbClienteEnc,
                            $rubro, 
                            $segmento);
        return view('report.report')->with('dbCargo', $dbCargo)
                                    ->with('dbEmpresa', $dbEmpresa)
                                    ->with('universo', $universo)
                                    ->with('nacional', $nacional)
                                    ->with('internacional', $internacional)
                                    ->with('countOcupantes', $countOcupantes)
                                    ->with('countOcupantesNac', $countOcupantesNac)
                                    ->with('countOcupantesInt', $countOcupantesInt);

    }

    private function segmenter( &$collection, 
                                $countCasosSeg, 
                                $detalle, 
                                $countCasos, 
                                $countCasosGratif, 
                                $countCasosAguinaldo, 
                                $countCasosBeneficios, 
                                $countCasosBono, 
                                $dbClienteEnc, 
                                $rubro, 
                                $segmento){
        if($rubro == 1 ){ // Bancos
            $salariosBase = $detalle->where('salario_base', '>', '0')->pluck('salario_base');
            $salarioMin = $salariosBase->min();
            $salarioMax = $salariosBase->max();
            $salarioProm = $salariosBase->avg();
            $salarioMed = $this->median($salariosBase);
            $salario25Per = $this->percentile(25,$salariosBase);
            $salario75Per = $this->percentile(75, $salariosBase);

            // Salario Base y Anual
            $this->pusher(  $collection, 
                            $countCasos, 
                            "Salario Base",
                            $salarioMin,
                            $salarioMax,
                            $salarioProm,
                            $salarioMed,
                            $salario25Per,
                            $salario75Per,
                            $dbClienteEnc->salario_base, 
                            $segmento);        
     
            $salariosBaseAnual = $salariosBase->map(function($item){
                return $item * 12;
            });

            $salarioAnualMin = $salariosBaseAnual->min();
            $salarioAnualMax = $salariosBaseAnual->max();
            $salarioAnualProm = $salariosBaseAnual->avg();
            $salarioAnualMed = $this->median($salariosBaseAnual);
            $salarioAnual25Per = $this->percentile(25,$salariosBaseAnual);
            $salarioAnual75Per = $this->percentile(75, $salariosBaseAnual);
            $this->pusher(  $collection, 
                            $countCasos, 
                            "Salario Base Anual",
                            $salarioAnualMin,
                            $salarioAnualMax,
                            $salarioAnualProm,
                            $salarioAnualMed,
                            $salarioAnual25Per,
                            $salarioAnual75Per,
                            $dbClienteEnc->salario_base * 12, 
                            $segmento);

            // Gratificacion
            $gratificaciones = $detalle->where('gratificacion', '>', '0')->pluck('gratificacion');
            $gratificacionMin = $gratificaciones->min();
            $gratificacionMax = $gratificaciones->max();
            $gratificacionProm = $gratificaciones->avg();
            $gratificacionMed = $this->median($gratificaciones);
            $gratificacion25Per = $this->percentile(25, $gratificaciones);
            $gratificacion75Per = $this->percentile(75, $gratificaciones);

            $this->pusher(  $collection, 
                            $countCasosGratif, 
                            "Gratificación Anual Garantizada",
                            $gratificacionMin,
                            $gratificacionMax,
                            $gratificacionProm,
                            $gratificacionMed,
                            $gratificacion25Per,
                            $gratificacion75Per,
                            $dbClienteEnc->gratificacion, 
                            $segmento);

            //Aguinaldo
            $aguinaldos = $detalle->where('aguinaldo', '>', '0')->pluck('aguinaldo');
            $aguinaldoMin = $aguinaldos->min();
            $aguinaldoMax = $aguinaldos->max();
            $aguinaldoProm = $aguinaldos->avg();
            $aguinaldoMed = $this->median($aguinaldos);
            $aguinaldo25Per = $this->percentile(25, $aguinaldos);
            $aguinaldo75Per = $this->percentile(75, $aguinaldos);

            $this->pusher(  $collection, 
                            $countCasosAguinaldo, 
                            "Aguinaldo",
                            $aguinaldoMin,
                            $aguinaldoMax,
                            $aguinaldoProm,
                            $aguinaldoMed,
                            $aguinaldo25Per,
                            $aguinaldo75Per,
                            $dbClienteEnc->aguinaldo, 
                            $segmento);

            // Efectivo Anual Garantizado
            $efectivoMin = 0;
            $efectivoMax = 0;
            $efectivoProm = 0;
            $efectivoMed = 0;
            $efectivo25Per = 0;
            $efectivo75Per = 0;
            $efectivoEmpresa = 0;

            foreach ($collection->whereIn('concepto', ["Salario Base Anual", "Gratificación Anual Garantizada", "Aguinaldo"]) as $key => $value) {
                $efectivoMin += str_replace(".", "", $value['min']);
                $efectivoMax += str_replace(".", "", $value['max']);
                $efectivoProm += str_replace(".", "", $value['prom']);
                $efectivoMed += str_replace(".", "", $value['med']);
                $efectivo25Per += str_replace(".", "", $value['per25']);
                $efectivo75Per += str_replace(".", "", $value['per75']);
                $efectivoEmpresa += str_replace(".", "", $value['empresa']);

            }
            $this->pusher(  $collection, 
                            $countCasos, 
                            "Efectivo Anual Garantizado",
                            $efectivoMin,
                            $efectivoMax,
                            $efectivoProm,
                            $efectivoMed,
                            $efectivo25Per,
                            $efectivo75Per,
                            $efectivoEmpresa, 
                            $segmento);

            //Beneficios
            $beneficiosBancos = $detalle->where('beneficios_bancos', '>', '0')->pluck('beneficios_bancos');
            $beneficiosMin = $beneficiosBancos->min();
            $beneficiosMax = $beneficiosBancos->max();
            $beneficiosProm = $beneficiosBancos->avg();
            $beneficiosMed = $this->median($beneficiosBancos);
            $beneficios25Per = $this->percentile(25, $beneficiosBancos);
            $beneficios75Per = $this->percentile(75, $beneficiosBancos);

            $this->pusher(  $collection, 
                            $countCasosBeneficios, 
                            "Total Adicional Anual",
                            $beneficiosMin * 12,
                            $beneficiosMax * 12,
                            $beneficiosProm * 12,
                            $beneficiosMed * 12,
                            $beneficios25Per * 12,
                            $beneficios75Per * 12,
                            $dbClienteEnc->beneficios_bancos * 12, 
                            $segmento);


            //Bono
            $bonos = $detalle->where('bono_anual', '>', '0')->pluck('bono_anual');
            $bonoMin = $bonos->min();
            $bonoMax = $bonos->max();
            $bonoProm = $bonos->avg();
            $bonoMed = $this->median($bonos);
            $bono25Per = $this->percentile(25, $bonos);
            $bono75Per = $this->percentile(75, $bonos);

            $this->pusher(  $collection, 
                            $countCasosBono, 
                            "Bono Anual",
                            $bonoMin,
                            $bonoMax,
                            $bonoProm,
                            $bonoMed,
                            $bono25Per,
                            $bono75Per,
                            $dbClienteEnc->bono_anual, 
                            $segmento);

         
            //Aguinaldo Impactado
            $aguinaldoImpMin = 0;
            $aguinaldoImpMax = 0;
            $aguinaldoImpProm = 0;
            $aguinaldoImpMed = 0;
            $aguinaldoImp25Per = 0;
            $aguinaldoImp75Per = 0;
            $aguinaldoImpEmpresa = 0;

            foreach ($collection->whereIn('concepto', ["Efectivo Anual Garantizado", "Total Adicional Anual", "Bono Anual"]) as $key => $value) {
                $aguinaldoImpMin += str_replace(".", "", $value['min']);
                $aguinaldoImpMax += str_replace(".", "", $value['max']);
                $aguinaldoImpProm += str_replace(".", "", $value['prom']);
                $aguinaldoImpMed += str_replace(".", "", $value['med']);
                $aguinaldoImp25Per += str_replace(".", "", $value['per25']);
                $aguinaldoImp75Per += str_replace(".", "", $value['per75']);
                $aguinaldoImpEmpresa += str_replace(".", "", $value['empresa']);

            }

            $this->pusher(  $collection, 
                            $countCasos, 
                            "Aguinaldo Impactado por Adicional, Gratificación y Bono",
                            $aguinaldoImpMin / 12, 
                            $aguinaldoImpMax / 12, 
                            $aguinaldoImpProm / 12,
                            $aguinaldoImpMed / 12,
                            $aguinaldoImp25Per / 12,
                            $aguinaldoImp75Per / 12,
                            $aguinaldoImpEmpresa / 12,
                            $segmento);

            //Total Compensación Efectiva anual
            $totalCompAnualMin = 0;
            $totalCompAnualMax = 0;
            $totalCompAnualProm = 0;
            $totalCompAnualMed = 0;
            $totalCompAnual25Per = 0;
            $totalCompAnual75Per = 0;
            $totalCompAnualEmpresa = 0;

            foreach ($collection->whereIn('concepto', ["Salario Base Anual", "Gratificación Anual Garantizada",  "Total Adicional Anual", "Bono Anual", "Aguinaldo Impactado por Adicional, Gratificación y Bono"]) as $key => $value) {
                $totalCompAnualMin += str_replace(".", "", $value['min']);
                $totalCompAnualMax += str_replace(".", "", $value['max']);
                $totalCompAnualProm += str_replace(".", "", $value['prom']);
                $totalCompAnualMed += str_replace(".", "", $value['med']);
                $totalCompAnual25Per += str_replace(".", "", $value['per25']);
                $totalCompAnual75Per += str_replace(".", "", $value['per75']);
                $totalCompAnualEmpresa += str_replace(".", "", $value['empresa']);

            }
            $this->pusher(  $collection, 
                            $countCasos, 
                            "Compensación Anual Total",
                            $totalCompAnualMin, 
                            $totalCompAnualMax, 
                            $totalCompAnualProm, 
                            $totalCompAnualMed, 
                            $totalCompAnual25Per, 
                            $totalCompAnual75Per, 
                            $totalCompAnualEmpresa,
                            $segmento);
        }elseif($rubro == 2){ // Agronegocios
        }elseif($rubro == 3){
        }elseif ($rubro == 4) {  // Navieras
            // Salario Base
            $salariosBase = $detalle->where('salario_base', '>', '0')->pluck('salario_base');
            $salarioMin = $salariosBase->min();
            $salarioMax = $salariosBase->max();
            $salarioProm = $salariosBase->avg();
            $salarioMed = $this->median($salariosBase);
            $salario25Per = $this->percentile(25,$salariosBase);
            $salario75Per = $this->percentile(75, $salariosBase);

            $this->pusher(  $collection, 
                            $countCasos, 
                            "Salario Base",
                            $salarioMin,
                            $salarioMax,
                            $salarioProm,
                            $salarioMed,
                            $salario25Per,
                            $salario75Per,
                            $dbClienteEnc->salario_base,
                            $segmento);        
            // Salario Base Anual     
            $salariosBaseAnual = $salariosBase->map(function($item){
                return $item * 12;
            });

            $salarioAnualMin = $salariosBaseAnual->min();
            $salarioAnualMax = $salariosBaseAnual->max();
            $salarioAnualProm = $salariosBaseAnual->avg();
            $salarioAnualMed = $this->median($salariosBaseAnual);
            $salarioAnual25Per = $this->percentile(25,$salariosBaseAnual);
            $salarioAnual75Per = $this->percentile(75, $salariosBaseAnual);
            $this->pusher(  $collection, 
                            $countCasos, 
                            "Salario Base Anual",
                            $salarioAnualMin,
                            $salarioAnualMax,
                            $salarioAnualProm,
                            $salarioAnualMed,
                            $salarioAnual25Per,
                            $salarioAnual75Per,
                            $dbClienteEnc->salario_base * 12,
                            $segmento);
            //Aguinaldo
            $aguinaldos = $detalle->where('aguinaldo', '>', '0')->pluck('aguinaldo');
            $aguinaldoMin = $aguinaldos->min();
            $aguinaldoMax = $aguinaldos->max();
            $aguinaldoProm = $aguinaldos->avg();
            $aguinaldoMed = $this->median($aguinaldos);
            $aguinaldo25Per = $this->percentile(25, $aguinaldos);
            $aguinaldo75Per = $this->percentile(75, $aguinaldos);

            $this->pusher(  $collection, 
                            $countCasosAguinaldo, 
                            "Aguinaldo",
                            $aguinaldoMin,
                            $aguinaldoMax,
                            $aguinaldoProm,
                            $aguinaldoMed,
                            $aguinaldo25Per,
                            $aguinaldo75Per,
                            $dbClienteEnc->aguinaldo,
                            $segmento);

            // Efectivo Anual Garantizado
            $efectivoMin = 0;
            $efectivoMax = 0;
            $efectivoProm = 0;
            $efectivoMed = 0;
            $efectivo25Per = 0;
            $efectivo75Per = 0;
            $efectivoEmpresa = 0;

            foreach ($collection->whereIn('concepto', ["Salario Base Anual", "Aguinaldo"]) as $key => $value) {
                $efectivoMin += str_replace(".", "", $value['min']);
                $efectivoMax += str_replace(".", "", $value['max']);
                $efectivoProm += str_replace(".", "", $value['prom']);
                $efectivoMed += str_replace(".", "", $value['med']);
                $efectivo25Per += str_replace(".", "", $value['per25']);
                $efectivo75Per += str_replace(".", "", $value['per75']);
                $efectivoEmpresa += str_replace(".", "", $value['empresa']);

            }
            $this->pusher(  $collection, 
                            $countCasos, 
                            "Efectivo Anual Garantizado",
                            $efectivoMin,
                            $efectivoMax,
                            $efectivoProm,
                            $efectivoMed,
                            $efectivo25Per,
                            $efectivo75Per,
                            $efectivoEmpresa,
                            $segmento);

            // Variable Anual
            $plusRendimiento = $detalle->where('plus_rendimiento', '>', '0')->pluck('plus_rendimiento');
            $plusMin = $plusRendimiento->min();
            $plusMax = $plusRendimiento->max();
            $plusProm = $plusRendimiento->avg();
            $plusMed = $this->median($plusRendimiento);
            $plus25Per = $this->percentile(25,$plusRendimiento);
            $plus75Per = $this->percentile(75, $plusRendimiento);
            $countCasosPlus = $detalle->where('plus_rendimiento', '>', '0')->unique('cabecera_encuesta_id')->count();
            $this->pusher(  $collection, 
                            $countCasosPlus, 
                            "Variable Anual",
                            $plusMin,
                            $plusMax,
                            $plusProm,
                            $plusMed,
                            $plus25Per,
                            $plus75Per,
                            $dbClienteEnc->plus_rendimiento,
                            $segmento);        

            // Adicional Amarre
            $adicionalAmarre = $detalle->where('adicional_amarre', '>', '0')->pluck('adicional_amarre');
            $amarreMin = $adicionalAmarre->min();
            $amarreMax = $adicionalAmarre->max();
            $amarreProm = $adicionalAmarre->avg();
            $amarreMed = $this->median($adicionalAmarre);
            $amarre25Per = $this->percentile(25,$adicionalAmarre);
            $amarre75Per = $this->percentile(75, $adicionalAmarre);
            $countCasosAmarre = $detalle->where('adicional_amarre', '>', '0')->unique('cabecera_encuesta_id')->count();
            $this->pusher(  $collection, 
                            $countCasosAmarre, 
                            "Adicional por Amarre",
                            $amarreMin,
                            $amarreMax,
                            $amarreProm,
                            $amarreMed,
                            $amarre25Per,
                            $amarre75Per,
                            $dbClienteEnc->adicional_amarre,
                            $segmento);        


            // Adicional Tipo de Combustible
            $adicionalTipoCombustible = $detalle->where('adicional_tipo_combustible', '>', '0')->pluck('adicional_tipo_combustible');
            $TipoCombustibleMin = $adicionalTipoCombustible->min();
            $TipoCombustibleMax = $adicionalTipoCombustible->max();
            $TipoCombustibleProm = $adicionalTipoCombustible->avg();
            $TipoCombustibleMed = $this->median($adicionalTipoCombustible);
            $TipoCombustible25Per = $this->percentile(25,$adicionalTipoCombustible);
            $TipoCombustible75Per = $this->percentile(75, $adicionalTipoCombustible);
            $countCasosTipoCombustible = $detalle->where('adicional_tipo_combustible', '>', '0')->unique('cabecera_encuesta_id')->count();
            $this->pusher(  $collection, 
                            $countCasosTipoCombustible, 
                            "Adicional por Tipo de Combustible",
                            $TipoCombustibleMin,
                            $TipoCombustibleMax,
                            $TipoCombustibleProm,
                            $TipoCombustibleMed,
                            $TipoCombustible25Per,
                            $TipoCombustible75Per,
                            $dbClienteEnc->adicional_tipo_combustible,
                            $segmento);        

            // Adicional por disponiblidad/embarque
            $adicionalEmbarque = $detalle->where('adicional_embarque', '>', '0')->pluck('adicional_embarque');
            $embarqueMin = $adicionalEmbarque->min();
            $embarqueMax = $adicionalEmbarque->max();
            $embarqueProm = $adicionalEmbarque->avg();
            $embarqueMed = $this->median($adicionalEmbarque);
            $embarque25Per = $this->percentile(25,$adicionalEmbarque);
            $embarque75Per = $this->percentile(75, $adicionalEmbarque);
            $countCasosEmbarque = $detalle->where('adicional_embarque', '>', '0')->unique('cabecera_encuesta_id')->count();
            $this->pusher(  $collection, 
                            $countCasosEmbarque, 
                            "Adicional por Disponibilidad/Embarque",
                            $embarqueMin,
                            $embarqueMax,
                            $embarqueProm,
                            $embarqueMed,
                            $embarque25Per,
                            $embarque75Per,
                            $dbClienteEnc->adicional_embarque,
                            $segmento);        

            // Adicional Carga
            $adicionalCarga = $detalle->where('adicional_carga', '>', '0')->pluck('adicional_carga');
            $cargaMin = $adicionalCarga->min();
            $cargaMax = $adicionalCarga->max();
            $cargaProm = $adicionalCarga->avg();
            $cargaMed = $this->median($adicionalCarga);
            $carga25Per = $this->percentile(25,$adicionalCarga);
            $carga75Per = $this->percentile(75, $adicionalCarga);
            $countCasosCarga = $detalle->where('adicional_carga', '>', '0')->unique('cabecera_encuesta_id')->count();
            $this->pusher(  $collection, 
                            $countCasosCarga, 
                            "Adicional por Carga",
                            $cargaMin,
                            $cargaMax,
                            $cargaProm,
                            $cargaMed,
                            $carga25Per,
                            $carga75Per,
                            $dbClienteEnc->adicional_carga,
                            $segmento);        

            // Total Adicional 
            $totalAdicionalMin = 0;
            $totalAdicionalMax = 0;
            $totalAdicionalProm = 0;
            $totalAdicionalMed = 0;
            $totalAdicional25Per = 0;
            $totalAdicional75Per = 0;
            $totalAdicionalEmpresa = 0;
            $casosAdicionales = collect([$countCasosAmarre, $countCasosTipoCombustible, $countCasosEmbarque, $countCasosCarga])->max();

            foreach ($collection->whereIn('concepto', ["Adicional por Amarre", "Adicional por Tipo de Combustible", "Adicional por Disponibilidad/Embarque", "Adicional por Carga" ]) as $key => $value) {
                $totalAdicionalMin += str_replace(".", "", $value['min']);
                $totalAdicionalMax += str_replace(".", "", $value['max']);
                $totalAdicionalProm += str_replace(".", "", $value['prom']);
                $totalAdicionalMed += str_replace(".", "", $value['med']);
                $totalAdicional25Per += str_replace(".", "", $value['per25']);
                $totalAdicional75Per += str_replace(".", "", $value['per75']);
                $totalAdicionalEmpresa += str_replace(".", "", $value['empresa']);

            }


            $this->pusher(  $collection, 
                            $casosAdicionales, 
                            "Total Adicional Anual",
                            $totalAdicionalMin,
                            $totalAdicionalMax,
                            $totalAdicionalProm,
                            $totalAdicionalMed,
                            $totalAdicional25Per,
                            $totalAdicional75Per,
                            $totalAdicionalEmpresa,
                            $segmento);

            //Bono
            $bonos = $detalle->where('bono_anual', '>', '0')->pluck('bono_anual');
            $bonoMin = $bonos->min();
            $bonoMax = $bonos->max();
            $bonoProm = $bonos->avg();
            $bonoMed = $this->median($bonos);
            $bono25Per = $this->percentile(25, $bonos);
            $bono75Per = $this->percentile(75, $bonos);

            $this->pusher(  $collection, 
                            $countCasosBono, 
                            "Bono Anual",
                            $bonoMin,
                            $bonoMax,
                            $bonoProm,
                            $bonoMed,
                            $bono25Per,
                            $bono75Per,
                            $dbClienteEnc->bono_anual,
                            $segmento);

            // Efectivo Total Anual
            $efectivoTotalMin = 0;
            $efectivoTotalMax = 0;
            $efectivoTotalProm = 0;
            $efectivoTotalMed = 0;
            $efectivoTotal25Per = 0;
            $efectivoTotal75Per = 0;
            $efectivoTotalEmpresa = 0;

            foreach ($collection->whereIn('concepto', ["Efectivo Anual Garantizado", "Variable Anual", "Total Adicional Anual", "Bono Anual"]) as $key => $value) {
                $efectivoTotalMin += str_replace(".", "", $value['min']);
                $efectivoTotalMax += str_replace(".", "", $value['max']);
                $efectivoTotalProm += str_replace(".", "", $value['prom']);
                $efectivoTotalMed += str_replace(".", "", $value['med']);
                $efectivoTotal25Per += str_replace(".", "", $value['per25']);
                $efectivoTotal75Per += str_replace(".", "", $value['per75']);
                $efectivoTotalEmpresa += str_replace(".", "", $value['empresa']);

            }
            $this->pusher(  $collection, 
                            $countCasos, 
                            "Efectivo Total Anual",
                            $efectivoTotalMin,
                            $efectivoTotalMax,
                            $efectivoTotalProm,
                            $efectivoTotalMed,
                            $efectivoTotal25Per,
                            $efectivoTotal75Per,
                            $efectivoTotalEmpresa,
                            $segmento);


            //Beneficios
            $beneficiosNavieras = $detalle->where('beneficios_navieras', '>', '0')->pluck('beneficios_navieras');
            //dd($detalle->where('beneficios_navieras', 1));
            $beneficiosMin = $beneficiosNavieras->min();
            $beneficiosMax = $beneficiosNavieras->max();
            $beneficiosProm = $beneficiosNavieras->avg();
            $beneficiosMed = $this->median($beneficiosNavieras);
            $beneficios25Per = $this->percentile(25, $beneficiosNavieras);
            $beneficios75Per = $this->percentile(75, $beneficiosNavieras);
            $casosBeneficiosNavieras = $detalle->where('beneficios_navieras', '>', '0')->unique('cabecera_encuesta_id')->count();
            $this->pusher(  $collection, 
                            $casosBeneficiosNavieras, 
                            "Total Beneficios Anual",
                            $beneficiosMin * 12,
                            $beneficiosMax * 12,
                            $beneficiosProm * 12,
                            $beneficiosMed * 12,
                            $beneficios25Per * 12,
                            $beneficios75Per * 12,
                            $dbClienteEnc->beneficios_bancos * 12,
                            $segmento);



         
            //Total Compensación anual
            $totalCompAnualMin = 0;
            $totalCompAnualMax = 0;
            $totalCompAnualProm = 0;
            $totalCompAnualMed = 0;
            $totalCompAnual25Per = 0;
            $totalCompAnual75Per = 0;
            $totalCompAnualEmpresa = 0;

            foreach ($collection->whereIn('concepto', ["Efectivo Total Anual", "Total Beneficios Anual"]) as $key => $value) {
                $totalCompAnualMin += str_replace(".", "", $value['min']);
                $totalCompAnualMax += str_replace(".", "", $value['max']);
                $totalCompAnualProm += str_replace(".", "", $value['prom']);
                $totalCompAnualMed += str_replace(".", "", $value['med']);
                $totalCompAnual25Per += str_replace(".", "", $value['per25']);
                $totalCompAnual75Per += str_replace(".", "", $value['per75']);
                $totalCompAnualEmpresa += str_replace(".", "", $value['empresa']);

            }
            $this->pusher(  $collection, 
                            $countCasos, 
                            "Compensación Anual Total",
                            $totalCompAnualMin, 
                            $totalCompAnualMax, 
                            $totalCompAnualProm, 
                            $totalCompAnualMed, 
                            $totalCompAnual25Per, 
                            $totalCompAnual75Per, 
                            $totalCompAnualEmpresa,
                            $segmento);            
        }

    }
    private function pusher(&$collection, $casos, $concepto, $min, $max, $prom, $med, $per25, $per75, $empresa, $segmento){
        if($casos >= 4){
            $collection->push([ "concepto"=> $concepto,
                              "casos"=> $casos,
                              "min"=>number_format($min, 0, ",", "."), 
                              "max"=>number_format($max, 0, ",", "."), 
                              "prom"=>number_format($prom, 0, ",", "."), 
                              "med"=>number_format($med, 0, ",", "."), 
                              "per25"=>number_format($per25, 0, ",", "."), 
                              "per75"=> number_format($per75, 0, ",", "."), 
                              "empresa"=>number_format($empresa, 0, ",", "."),
                              "segmento"=>$segmento
            ]);
        }elseif ($casos < 4 && $casos > 1 ) {
            $collection->push([ "concepto"=> $concepto,
                              "casos"=> $casos,
                              "min"=>"",
                              "max"=>"",
                              "prom"=>number_format($prom, 0, ",", "."), 
                              "med"=>"",
                              "per25"=>"",
                              "per75"=>"",
                              "empresa"=>"",
                              "segmento"=>$segmento
            ]);

        }elseif ($casos <= 1) {
            $collection->push([ "concepto"=> $concepto,
                              "casos"=> $casos,
                              "min"=>"",
                              "max"=>"",
                              "prom"=>"",
                              "med"=>"",
                              "per25"=>"",
                              "per75"=>"",
                              "empresa"=>"",
                              "segmento"=>$segmento
            ]);
        }
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

    public function panel($id){
        $dbEmpresa = $id;
        $rubro = Auth::user()->empresa->rubro_id;
        $club = $this->club($rubro);
        $dbEncuesta = Cabecera_encuesta::where('empresa_id', $id)->whereRaw('id = (select max(id) from cabecera_encuestas where empresa_id = '. $id.')')->first();
        $participantes = Cabecera_encuesta::where('periodo', $dbEncuesta->periodo)->where('rubro_id', $rubro)->get();
        $dbData = $participantes->map(function($item){
            return $item->empresa;
        });
        return view('report.panel')->with('dbData', $dbData)->with('club', $club)->with('dbEmpresa', $dbEmpresa);
    }

    public function getCargos(Request $request){
        $id = $request->nivel_id;
        $dbData = Cargo::where('nivel_id', $id)->pluck('descripcion', 'id');

        return $dbData;        
    }

    private function median($arr) {
        if($arr->count() <= 0){
            return 0;
        }
        $sorted = $arr->sort()->values();
        $count = count($sorted); //total numbers in array
        $middleval = floor(($count-1)/2); // find the middle value, or the lowest middle value
        if($count % 2) { // odd number, middle is the median
            $median = $sorted[$middleval];
        } else { // even number, calculate avg of 2 medians
            $low = $sorted[$middleval];
            $high = $sorted[$middleval+1];
            $median = (($low+$high)/2);
        }
        return $median;
    }

    private function percentile($percentile, $arr) {
        if($arr->count() <= 1){
            return 0;
        }
        $sorted = $arr->sort()->values();
        //dd($sorted);
        //$index = ($percentile/100) * count($sorted);
        $count = $sorted->count();

        $perN = ($percentile * ($count - 1)/100) + 1;
        
        if (is_int($perN)) {
           $result = $sorted[$perN];
        }
        else {
           $int = floor($perN);
           $dec = $perN - $int;

           $result = $dec * ($sorted[$int] - $sorted[$int - 1]) + $sorted[$int-1];
        }

        return $result;
    }


}
