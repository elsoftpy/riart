<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;
use App\Cabecera_encuesta;
use App\Encuestas_cargo;
use App\Detalle_encuesta;
use App\Cargos_rubro;
use App\Empresa;
use App\Ficha_dato;
use App\Nivel;
use App\Cargo;
use App\Rubro;
use App\User;
use Hash;
use DB;
use Auth;
use Excel;
use Session;

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
        $rubro = $empresa->rubro_id;
        //dd($club, $empresa->rubro_id);
        switch ($rubro) {
            case 1:
                $imagen = "images/caratula-bancos.PNG";
                break;
            case 2:
                $imagen = "images/caratula-agro_ok.PNG";
                break;
            case 4:
                $imagen = "images/caratula-naviera.PNG";
                break;
            case 3:
                $imagen = "images/caratula-autos.PNG";
                break;
            default:
                $imagen = "images/caratula-autos.PNG";
                break;
        }

        return view('report.home')->with('dbEmpresa', $id)->with('imagen', $imagen)->with('club', $club);
    }

    private function club($rubro){
        switch ($rubro) {
            case 1:
                $imagen = "images/caratula-bancos.PNG";
                $club = "- Bancos de Paraguay";
                break;
            case 2:
                $imagen = "images/caratula-agro.PNG";
                $club = "- Empresas de Agronegocios - Paraguay";
                break;
            case 3:
                $imagen = "images/caratula-autos.PNG";
                $club = '- Empresas del Sector Automotriz, Maquinarias y Utilitarios';
                break;
            case 4:
                $imagen = "images/caratula-naviera.PNG";
                $club = "- Navieras de Paraguay";
                break;
            default:
                $imagen = "images/caratula-bancos.PNG";
                $club = "de Bancos";
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
        $dbCargos = Cargo::orderBy('descripcion')->pluck('descripcion', 'id');
        $dbEmpresa = $id;
        return view('report.filter')->with('dbNiveles', $dbNiveles)->with('dbCargos', $dbCargos)->with('dbEmpresa', $dbEmpresa);
    }

    public function showCargosClub($id){
        $dbEmpresa = $id;
        $empresa = Empresa::find($id);
        $club = $this->club($empresa->rubro_id);
        $rubro = $empresa->rubro_id;
        if(Session::has('periodo')){
            $per = Session::get('periodo');
            $dbEncuesta = Cabecera_encuesta::where('empresa_id', $id)->whereRaw("periodo = '". $per."'")->first();
        }else{
            $dbEncuesta = Cabecera_encuesta::where('empresa_id', $id)->whereRaw('id = (select max(id) from cabecera_encuestas where empresa_id = '. $id.')')->first();            
        }
        $periodo = $dbEncuesta->periodo;    // periodo de la encuesta actual
        $empresasId = Empresa::where('rubro_id', $rubro)->pluck('id');
        $encuestasRubro = Cabecera_encuesta::whereIn('empresa_id', $empresasId)->where('periodo', $periodo)->pluck('id');
        $encuestasCargos = Encuestas_cargo::whereIn('cabecera_encuesta_id', $encuestasRubro)->whereNotNull('cargo_id')->get();
        $cargosEmpresas = collect();
        foreach ($encuestasCargos as $encuestaCargo) {
            if($encuestaCargo->detalleEncuestas){
                if($encuestaCargo->detalleEncuestas->cantidad_ocupantes > 0){
                    $cargosEmpresas->push(["cargo"=> $encuestaCargo->cargo_id, "empresa"=>$encuestaCargo->cabeceraEncuestas->empresa_id]);
                }
            }
        }

        $groupedCargosEmpresas = $cargosEmpresas->groupBy('cargo');
        $cargosIds = $groupedCargosEmpresas->map(function($item, $key){
            if($item->groupBy('empresa')->count() > 1){
                return $key;
            }
        })->values()->reject(function($value, $key){
            return is_null($value); 
        })->sort();
        
        $cargos = Cargos_rubro::where('rubro_id', $rubro)->whereIn('cargo_id', $cargosIds)->get();
        //$cargos = Cargos_rubro::where('rubro_id', $rubro)->get();
        $cargos = $cargos->map(function($item){
            $item['nivel_id'] = $item->cargo->nivel->id;
            $item['descripcion'] = $item->cargo->descripcion;
            return $item;
        });
        $colNiveles = collect();
        foreach ($cargos as $key => $value) {
            $colNiveles->push($value->cargo->nivel->id);
        }
        $niveles = Nivel::whereIn('id', $colNiveles->unique())->orderBy('descripcion')->get();
        return view('report.cargos_club')->with('dbEmpresa', $dbEmpresa)
                                         ->with('club', $club)       
                                         ->with('niveles', $niveles)
                                         ->with('cargos', $cargos);
    }

    public function lista($id){
        $dbEmpresa = $id;
        $empresa = Empresa::find($id);
        $club = $this->club($empresa->rubro_id);
        $rubro = $empresa->rubro_id;
        if(Session::has('periodo')){
            $per = Session::get('periodo');
            $dbEncuesta = Cabecera_encuesta::where('empresa_id', $id)->whereRaw("periodo = '". $per."'")->first();
        }else{
            $dbEncuesta = Cabecera_encuesta::where('empresa_id', $id)->whereRaw('id = (select max(id) from cabecera_encuestas where empresa_id = '. $id.')')->first();            
        }
        $periodo = $dbEncuesta->periodo;    // periodo de la encuesta actual
        
        $encuestasCargos = Encuestas_cargo::where('cabecera_encuesta_id', $dbEncuesta->id)->whereNotNull('cargo_id')->get();
        $cargosIds = collect();
        foreach ($encuestasCargos as $encuestaCargo) {
            if($encuestaCargo->detalleEncuestas){
                if($encuestaCargo->detalleEncuestas->cantidad_ocupantes > 0){
                    $cargosIds->push($encuestaCargo->cargo_id);
                }
            }
        }
        $cargosIds = $cargosIds->unique();
        $cargos = Cargos_rubro::where('rubro_id', $rubro)->whereIn('cargo_id', $cargosIds)->get();
        //$cargos = Cargos_rubro::where('rubro_id', $rubro)->get();
        $cargos = $cargos->map(function($item){
            $item['nivel_id'] = $item->cargo->nivel->id;
            $item['descripcion'] = $item->cargo->descripcion;
            return $item;
        });
        $colNiveles = collect();
        foreach ($cargos as $key => $value) {
            $colNiveles->push($value->cargo->nivel->id);
        }
        $niveles = Nivel::whereIn('id', $colNiveles->unique())->get();
        return view('report.cargos_club_print')->with('dbEmpresa', $dbEmpresa)
                                         ->with('club', $club)       
                                         ->with('niveles', $niveles)
                                         ->with('cargos', $cargos);
    }
    public function ficha($id){
        $dbEmpresa = $id;
        $empresa = Empresa::find($id);
        $rubro = $empresa->rubro_id;
        if(Session::has('periodo')){
            $per = Session::get('periodo');
            $dbEncuesta = Cabecera_encuesta::where('empresa_id', $id)->whereRaw("periodo = '". $per."'")->first();
        }else{
            $dbEncuesta = Cabecera_encuesta::where('empresa_id', $id)->whereRaw('id = (select max(id) from cabecera_encuestas where empresa_id = '. $id.')')->first();            
        }
        $cargos = Encuestas_cargo::where('cabecera_encuesta_id', $dbEncuesta->id)->get()->count();
        $periodo = $dbEncuesta->periodo;
        $dbFicha = Ficha_dato::where('rubro_id', $rubro)->where('periodo', $periodo)->first();
        if($dbFicha){
            $cargos = $dbFicha->cargos_emergentes;
            $tipoCambio = $dbFicha = $dbFicha->tipo_cambio;
        }else{
            if ($rubro == 4){
                if($per == "12/2016"){
                    $cargos = 160;
                }else{
                    $cargos = 174;
                }
            }elseif($rubro == 1){
                $cargos = 400;
            }elseif($rubro == 2){
                $cargos = 172;
            }elseif($rubro == 3){
                $cargos = 175;
            }
            $tipoCambio = 5600;
        }
        
        $participantes = Cabecera_encuesta::where('periodo', $periodo)->where('rubro_id', $rubro)->get();

        $participantes = $participantes->map(function($item){
            return $item->empresa;    
        })->reject(function($item){
            if(!$item->listable){
                return $item;
            }
        })->count();

        $club = $this->club($empresa->rubro_id);
        return view('report.ficha')->with('dbEmpresa', $dbEmpresa)
                                   ->with('cargos', $cargos)
                                   ->with('periodo', $periodo)
                                   ->with('club', $club)
                                   ->with('tipoCambio', $tipoCambio)
                                   ->with('participantes', $participantes);
    }

    public function conceptos($id){
        $dbEmpresa = $id;
        $empresa = Empresa::find($id);
        $rubro = $empresa->rubro_id;
        $club = $this->club($empresa->rubro_id);
        return view('report.conceptos')->with('club', $club)->with('dbEmpresa', $dbEmpresa);
    }

    public function metodologia($id){
        $dbEmpresa = $id;
        $empresa = Empresa::find($id);
        $rubro = $empresa->rubro_id;
        $club = $this->club($empresa->rubro_id);
        return view('report.metodologia')->with('club', $club)->with('dbEmpresa', $dbEmpresa);
    }

    public function cargoReport(Request $request){

        return $this->cargoReportAll($request, "view");
    }

    public function cargoReportExcel(Request $request){
        
        return $this->cargoReportAll($request, "excel");
    }

    public function cargoReportClubExcel(Request $request){
        // datos de la empresa del cliente
        $dbEmpresa = Empresa::find($request->empresa_id);   
        // si la variable de sesion "periodo" está cargada (solo se carga con navieras)
        if(Session::has('periodo')){
            $per = Session::get('periodo');
            $dbEncuesta = Cabecera_encuesta::where('empresa_id', $dbEmpresa->id)->whereRaw("periodo = '". $per."'")->first();
        }else{
            $dbEncuesta = Cabecera_encuesta::where('empresa_id', $dbEmpresa->id)->whereRaw('id = (select max(id) from cabecera_encuestas where empresa_id = '. $dbEmpresa->id.')')->first();            
        }
        // periodo de la encuesta actual (semestral para navieras)
        $periodo = $dbEncuesta->periodo;    
        // rubro de la empresa del cliente        
        $rubro = $dbEmpresa->rubro_id;      
        // recupera los cargos del periodo para todos los que tengan homologación
        $encuestasCargos = Encuestas_cargo::where('cabecera_encuesta_id', $dbEncuesta->id)
                                          ->whereNotNull('cargo_id')
                                          ->get();
        // variables de detalle para cada segmento
        $detalleUniverso = collect();
        $detalleNacional = collect();
        $detalleInternacional = collect();
        // Procesamiento por cargo
        foreach ($encuestasCargos as $encuestaCargo) {
            $request->request->add(["cargo_id"=> $encuestaCargo->cargo->id]);
            // procesamos el reporte
            $respuesta = $this->cargoReportAll($request, "clubExcel");
            // preparamos los datos para el array final del cargo
            $itemArray = array( $encuestaCargo->descripcion, 
                                $encuestaCargo->cargo->descripcion, 
                              );
            $itemArrayNac = $itemArray;
            $itemArrayInt = $itemArray;
            // por cada item del detalle

            foreach ($respuesta as $key => $item) {
                
                switch ($key) {
                    
                    case 'detalle_universo':
                        //dd($item, $itemArray);    
                        $this->CargaDetalle($item, $itemArray);            
                        break;

                    case 'detalle_nacional':
                        $this->CargaDetalle($item, $itemArrayNac);            
                        break;
                    case 'detalleInternacional':
                        $this->CargaDetalle($item, $itemArrayInt);            
                        break;                                                
                }

            }
            $detalleUniverso->push($itemArray);
            $detalleNacional->push($itemArrayNac);
            $detalleInternacional->push($itemArrayInt);                 
        }
        $filename = 'Resultados_'.$periodo;

        Excel::create($filename, function($excel) use($detalleUniverso, $detalleNacional, $detalleInternacional) {
            $excel->sheet("universo", function($sheet) use($detalleUniverso){
                $sheet->cell('A1', function($cell){
                    $cell->setValue('CARGO');
                });
                $sheet->mergeCells('A1:D1');
                $sheet->cells('A1:D1', function($cells){
                    $cells->setBackground('#00897b');
                    $cells->setFontColor("#FFFFFF");
                    $cells->setFontWeight("bold");
                   // $cells->setValignment('center');
                    $cells->setAlignment('center');
                });
                // Salario Base Header
                $sheet->cell('E1', function($cell){
                    $cell->setValue('SALARIO BASE');
                });
                $sheet->mergeCells('E1:J1');
                $sheet->cells('E1:J1', function($cells){
                    $cells->setBackground('#0288d1');
                    $cells->setFontColor("#FFFFFF");
                    $cells->setFontWeight("bold");
                   // $cells->setValignment('center');
                    $cells->setAlignment('center');
                });                
                // Salario Efectivo Anual Garantizado
                $sheet->cell('K1', function($cell){
                    $cell->setValue('EFECTIVO ANUAL GARANTIZADO');
                });
                $sheet->mergeCells('K1:P1');
                $sheet->cells('K1:P1', function($cells){
                    $cells->setBackground('#388e3c');
                    $cells->setFontColor("#FFFFFF");
                    $cells->setFontWeight("bold");
                   // $cells->setValignment('center');
                    $cells->setAlignment('center');
                });                
                // Salario Variable Anual
                $sheet->cell('Q1', function($cell){
                    $cell->setValue('VARIABLE ANUAL');
                });
                $sheet->mergeCells('Q1:V1');
                $sheet->cells('Q1:V1', function($cells){
                    $cells->setBackground('#afb42b');
                    $cells->setFontColor("#FFFFFF");
                    $cells->setFontWeight("bold");
                   // $cells->setValignment('center');
                    $cells->setAlignment('center');
                });                
                // Salario Total Adicional Anual
                $sheet->cell('W1', function($cell){
                    $cell->setValue('TOTAL ADICIONAL ANUAL');
                });
                $sheet->mergeCells('W1:AB1');
                $sheet->cells('W1:AB1', function($cells){
                    $cells->setBackground('#fbc02d');
                    $cells->setFontColor("#FFFFFF");
                    $cells->setFontWeight("bold");
                   // $cells->setValignment('center');
                    $cells->setAlignment('center');
                });                
                // Salario Bono Anual
                $sheet->cell('AC1', function($cell){
                    $cell->setValue('BONO ANUAL');
                });
                $sheet->mergeCells('AC1:AH1');
                $sheet->cells('AC1:AH1', function($cells){
                    $cells->setBackground('#ffa000');
                    $cells->setFontColor("#FFFFFF");
                    $cells->setFontWeight("bold");
                   // $cells->setValignment('center');
                    $cells->setAlignment('center');
                });                
                // Salario Efectivo Total Anual
                $sheet->cell('AI1', function($cell){
                    $cell->setValue('EFECTIVO TOTAL ANUAL');
                });
                $sheet->mergeCells('AI1:AN1');
                $sheet->cells('AI1:AN1', function($cells){
                    $cells->setBackground('#f57c00');
                    $cells->setFontColor("#FFFFFF");
                    $cells->setFontWeight("bold");
                   // $cells->setValignment('center');
                    $cells->setAlignment('center');
                });                
                // Salario Base Comparativo Header
                $sheet->cell('AO1', function($cell){
                    $cell->setValue('SALARIO BASE COMP.');
                });
                $sheet->mergeCells('AO1:AT1');
                $sheet->cells('AO1:AT1', function($cells){
                    $cells->setBackground('#0288d1');
                    $cells->setFontColor("#FFFFFF");
                    $cells->setFontWeight("bold");
                   // $cells->setValignment('center');
                    $cells->setAlignment('center');
                });                
                // Salario Variable Anual comp.
                $sheet->cell('AU1', function($cell){
                    $cell->setValue('VARIABLE ANUAL COMP.');
                });
                $sheet->mergeCells('AU1:AZ1');
                $sheet->cells('AU1:AZ1', function($cells){
                    $cells->setBackground('#afb42b');
                    $cells->setFontColor("#FFFFFF");
                    $cells->setFontWeight("bold");
                   // $cells->setValignment('center');
                    $cells->setAlignment('center');
                });                                                                                                                                
                $itemsHeader = array("Mínimo", "25 Perc.", "Promedio", "Mediana", "75 Perc.", "Máximo");
                $cargoHeader = array("Cargo Company", "Oficial", "Ocupantes", "Casos");
                for ($i= 0; $i < 8; $i++) {
                    foreach ($itemsHeader as $key => $value) {
                        array_push($cargoHeader, $value);
                    }
                    
                }
                
                $sheet->row(2, $cargoHeader);
                //dd($detalleUniverso);
                $sheet->rows($detalleUniverso);
                
                $sheet->cells('A2:AZ2', function($cells){
                    $cells->setBackground('#a7ffeb');
                });

                $sheet->setFreeze('A3');                
            });
            // hoja nacional
            $excel->sheet("nacional", function($sheet) use($detalleNacional){
                $sheet->cell('A1', function($cell){
                    $cell->setValue('CARGO');
                });
                $sheet->mergeCells('A1:D1');
                $sheet->cells('A1:D1', function($cells){
                    $cells->setBackground('#00897b');
                    $cells->setFontColor("#FFFFFF");
                    $cells->setFontWeight("bold");
                   // $cells->setValignment('center');
                    $cells->setAlignment('center');
                });
                // Salario Base Header
                $sheet->cell('E1', function($cell){
                    $cell->setValue('SALARIO BASE');
                });
                $sheet->mergeCells('E1:J1');
                $sheet->cells('E1:J1', function($cells){
                    $cells->setBackground('#0288d1');
                    $cells->setFontColor("#FFFFFF");
                    $cells->setFontWeight("bold");
                   // $cells->setValignment('center');
                    $cells->setAlignment('center');
                });                
                // Salario Efectivo Anual Garantizado
                $sheet->cell('K1', function($cell){
                    $cell->setValue('EFECTIVO ANUAL GARANTIZADO');
                });
                $sheet->mergeCells('K1:P1');
                $sheet->cells('K1:P1', function($cells){
                    $cells->setBackground('#388e3c');
                    $cells->setFontColor("#FFFFFF");
                    $cells->setFontWeight("bold");
                   // $cells->setValignment('center');
                    $cells->setAlignment('center');
                });                
                // Salario Variable Anual
                $sheet->cell('Q1', function($cell){
                    $cell->setValue('VARIABLE ANUAL');
                });
                $sheet->mergeCells('Q1:V1');
                $sheet->cells('Q1:V1', function($cells){
                    $cells->setBackground('#afb42b');
                    $cells->setFontColor("#FFFFFF");
                    $cells->setFontWeight("bold");
                   // $cells->setValignment('center');
                    $cells->setAlignment('center');
                });                
                // Salario Total Adicional Anual
                $sheet->cell('W1', function($cell){
                    $cell->setValue('TOTAL ADICIONAL ANUAL');
                });
                $sheet->mergeCells('W1:AB1');
                $sheet->cells('W1:AB1', function($cells){
                    $cells->setBackground('#fbc02d');
                    $cells->setFontColor("#FFFFFF");
                    $cells->setFontWeight("bold");
                   // $cells->setValignment('center');
                    $cells->setAlignment('center');
                });                
                // Salario Bono Anual
                $sheet->cell('AC1', function($cell){
                    $cell->setValue('BONO ANUAL');
                });
                $sheet->mergeCells('AC1:AH1');
                $sheet->cells('AC1:AH1', function($cells){
                    $cells->setBackground('#ffa000');
                    $cells->setFontColor("#FFFFFF");
                    $cells->setFontWeight("bold");
                   // $cells->setValignment('center');
                    $cells->setAlignment('center');
                });                
                // Salario Efectivo Total Anual
                $sheet->cell('AI1', function($cell){
                    $cell->setValue('EFECTIVO TOTAL ANUAL');
                });
                $sheet->mergeCells('AI1:AN1');
                $sheet->cells('AI1:AN1', function($cells){
                    $cells->setBackground('#f57c00');
                    $cells->setFontColor("#FFFFFF");
                    $cells->setFontWeight("bold");
                   // $cells->setValignment('center');
                    $cells->setAlignment('center');
                });                
                // Salario Base Comparativo Header
                $sheet->cell('AO1', function($cell){
                    $cell->setValue('SALARIO BASE COMP.');
                });
                $sheet->mergeCells('AO1:AT1');
                $sheet->cells('AO1:AT1', function($cells){
                    $cells->setBackground('#0288d1');
                    $cells->setFontColor("#FFFFFF");
                    $cells->setFontWeight("bold");
                   // $cells->setValignment('center');
                    $cells->setAlignment('center');
                });                
                // Salario Variable Anual comp.
                $sheet->cell('AU1', function($cell){
                    $cell->setValue('VARIABLE ANUAL COMP.');
                });
                $sheet->mergeCells('AU1:AZ1');
                $sheet->cells('AU1:AZ1', function($cells){
                    $cells->setBackground('#afb42b');
                    $cells->setFontColor("#FFFFFF");
                    $cells->setFontWeight("bold");
                   // $cells->setValignment('center');
                    $cells->setAlignment('center');
                });                                                                                                                                
                $itemsHeader = array("Mínimo", "25 Perc.", "Promedio", "Mediana", "75 Perc.", "Máximo");
                $cargoHeader = array("Cargo Company", "Oficial", "Ocupantes", "Casos");
                for ($i= 0; $i < 8; $i++) {
                    foreach ($itemsHeader as $key => $value) {
                        array_push($cargoHeader, $value);
                    }
                    
                }
                
                $sheet->row(2, $cargoHeader);
                $sheet->cells('A2:AZ2', function($cells){
                    $cells->setBackground('#a7ffeb');
                });
                
                $sheet->rows($detalleNacional);
                $sheet->setFreeze('A3');
            });
            // hoja internacional
            $excel->sheet("internacional", function($sheet) use($detalleInternacional){
                $sheet->cell('A1', function($cell){
                    $cell->setValue('CARGO');
                });
                $sheet->mergeCells('A1:D1');
                $sheet->cells('A1:D1', function($cells){
                    $cells->setBackground('#00897b');
                    $cells->setFontColor("#FFFFFF");
                    $cells->setFontWeight("bold");
                   // $cells->setValignment('center');
                    $cells->setAlignment('center');
                });
                // Salario Base Header
                $sheet->cell('E1', function($cell){
                    $cell->setValue('SALARIO BASE');
                });
                $sheet->mergeCells('E1:J1');
                $sheet->cells('E1:J1', function($cells){
                    $cells->setBackground('#0288d1');
                    $cells->setFontColor("#FFFFFF");
                    $cells->setFontWeight("bold");
                   // $cells->setValignment('center');
                    $cells->setAlignment('center');
                });                
                // Salario Efectivo Anual Garantizado
                $sheet->cell('K1', function($cell){
                    $cell->setValue('EFECTIVO ANUAL GARANTIZADO');
                });
                $sheet->mergeCells('K1:P1');
                $sheet->cells('K1:P1', function($cells){
                    $cells->setBackground('#388e3c');
                    $cells->setFontColor("#FFFFFF");
                    $cells->setFontWeight("bold");
                   // $cells->setValignment('center');
                    $cells->setAlignment('center');
                });                
                // Salario Variable Anual
                $sheet->cell('Q1', function($cell){
                    $cell->setValue('VARIABLE ANUAL');
                });
                $sheet->mergeCells('Q1:V1');
                $sheet->cells('Q1:V1', function($cells){
                    $cells->setBackground('#afb42b');
                    $cells->setFontColor("#FFFFFF");
                    $cells->setFontWeight("bold");
                   // $cells->setValignment('center');
                    $cells->setAlignment('center');
                });                
                // Salario Total Adicional Anual
                $sheet->cell('W1', function($cell){
                    $cell->setValue('TOTAL ADICIONAL ANUAL');
                });
                $sheet->mergeCells('W1:AB1');
                $sheet->cells('W1:AB1', function($cells){
                    $cells->setBackground('#fbc02d');
                    $cells->setFontColor("#FFFFFF");
                    $cells->setFontWeight("bold");
                   // $cells->setValignment('center');
                    $cells->setAlignment('center');
                });                
                // Salario Bono Anual
                $sheet->cell('AC1', function($cell){
                    $cell->setValue('BONO ANUAL');
                });
                $sheet->mergeCells('AC1:AH1');
                $sheet->cells('AC1:AH1', function($cells){
                    $cells->setBackground('#ffa000');
                    $cells->setFontColor("#FFFFFF");
                    $cells->setFontWeight("bold");
                   // $cells->setValignment('center');
                    $cells->setAlignment('center');
                });                
                // Salario Efectivo Total Anual
                $sheet->cell('AI1', function($cell){
                    $cell->setValue('EFECTIVO TOTAL ANUAL');
                });
                $sheet->mergeCells('AI1:AN1');
                $sheet->cells('AI1:AN1', function($cells){
                    $cells->setBackground('#f57c00');
                    $cells->setFontColor("#FFFFFF");
                    $cells->setFontWeight("bold");
                   // $cells->setValignment('center');
                    $cells->setAlignment('center');
                });                
                // Salario Base Comparativo Header
                $sheet->cell('AO1', function($cell){
                    $cell->setValue('SALARIO BASE COMP.');
                });
                $sheet->mergeCells('AO1:AT1');
                $sheet->cells('AO1:AT1', function($cells){
                    $cells->setBackground('#0288d1');
                    $cells->setFontColor("#FFFFFF");
                    $cells->setFontWeight("bold");
                   // $cells->setValignment('center');
                    $cells->setAlignment('center');
                });                
                // Salario Variable Anual comp.
                $sheet->cell('AU1', function($cell){
                    $cell->setValue('VARIABLE ANUAL COMP.');
                });
                $sheet->mergeCells('AU1:AZ1');
                $sheet->cells('AU1:AZ1', function($cells){
                    $cells->setBackground('#afb42b');
                    $cells->setFontColor("#FFFFFF");
                    $cells->setFontWeight("bold");
                   // $cells->setValignment('center');
                    $cells->setAlignment('center');
                });                                                                                                                                
                $itemsHeader = array("Mínimo", "25 Perc.", "Promedio", "Mediana", "75 Perc.", "Máximo");
                $cargoHeader = array("Cargo Company", "Oficial", "Ocupantes", "Casos");
                for ($i= 0; $i < 8; $i++) {
                    foreach ($itemsHeader as $key => $value) {
                        array_push($cargoHeader, $value);
                    }
                    
                }
                
                $sheet->row(2, $cargoHeader);
                
                $sheet->rows($detalleInternacional);

                $sheet->cells('A2:AZ2', function($cells){
                    $cells->setBackground('#a7ffeb');
                });
                $sheet->setFreeze('A3');
            });
            $excel->setActiveSheetIndex(0);    
        })->export('xlsx');
    }

    private function cargaDetalle($item, &$itemArray){
        $variableAnual = false;
        $salarioEmpresa = 0;
        $variableAnualEmp = 0;
        foreach ($item as $key => $value) {
/*            dd($value, $key, $item);
            if( array_key_exists("Variable Anual", $value)){
                
            }*/
            switch ($value["Concepto"]) {
                case "Salario Base":
                    $this->cargador($value, $itemArray, true);
                    $salarioEmpresa = intval(str_replace(".", "", $value["Empresa"]));
                    break;
                case "Efectivo Anual Garantizado":
                    $this->cargador($value, $itemArray, false);
                    break;
                case "Variable Anual":
                    $variableAnual = true;
                    $this->cargador($value, $itemArray, false);
                    $variableAnualEmp = $value["Empresa"];
                    break;
                case "Total Adicional Anual":
                    $this->cargador($value, $itemArray, false);
                    break;
                case "Bono Anual":
                    $this->cargador($value, $itemArray, false);
                    break;
                case "Compensación Anual Total":
                    $this->cargador($value, $itemArray, false);
                    break;
            }
        }
        //dd($item);
        if(!$variableAnual){
            // agregamos 0 a los lugares de Variable Anual
            $aux = array_slice($itemArray, 0, 16, true);
            array_push($aux, 0,0,0,0,0,0);
            $aux2 = array_merge($aux, array_slice($itemArray, 16));
            $itemArray = $aux2; 
        }
        // comparativo salario base
        if($itemArray[4] > 0){
            $compMinSal = round($salarioEmpresa/$itemArray[4] - 1, 2); 
        }else{
            $compMinSal = 0;
        }
        if($itemArray[5] > 0){
            $comp25PercSal = round($salarioEmpresa/$itemArray[5] - 1, 2); 
        }else{
            $comp25PercSal = 0;
        }
        if($itemArray[6] > 0){
            $compPromSal = round($salarioEmpresa/$itemArray[6] - 1, 2); 
        }else{
            $compPromSal = 0;
        }
        if($itemArray[7] > 0){
            $compMedSal = round($salarioEmpresa/$itemArray[7] - 1, 2); 
        }else{
            $compMedSal = 0;
        }
        if($itemArray[8] > 0){
            $comp75PercSal = round($salarioEmpresa/$itemArray[8] - 1, 2); 
        }else{
            $comp75PercSal = 0;
        }
        if($itemArray[9] > 0){
            $compMaxSal = round($salarioEmpresa/$itemArray[9] - 1, 2); 
        }else{
            $compMaxSal = 0;
        }
        // comparativo variable anual
        if($itemArray[16] > 0){
            $compMinVar = round($salarioEmpresa/$itemArray[16] - 1, 2); 
        }else{
            $compMinVar = 0;
        }
        if($itemArray[17] > 0){
            $comp25PercVar = round($salarioEmpresa/$itemArray[17] - 1 , 2); 
        }else{
            $comp25PercVar = 0;
        }
        if($itemArray[18] > 0){
            $compPromVar = round($salarioEmpresa/$itemArray[18] - 1 , 2); 
        }else{
            $compPromVar = 0;
        }
        if($itemArray[19] > 0){
            $compMedVar =  round($salarioEmpresa/$itemArray[19] - 1, 2); 
        }else{
            $compMedVar = 0;
        }        
        if($itemArray[20] > 0){
            $comp75PercVar = round($salarioEmpresa/$itemArray[20] - 1, 2); 
        }else{
            $comp75PercVar = 0;
        }        
        if($itemArray[21] > 0){
            $compMaxVar = round($salarioEmpresa/$itemArray[21] - 1, 2); 
        }else{
            $compMaxVar = 0;
        }        
        array_push( $itemArray, 
                    $compMinSal,
                    $comp25PercSal, 
                    $compPromSal, 
                    $compMedSal, 
                    $comp75PercSal, 
                    $compMaxSal, 
                    $compMinVar, 
                    $comp25PercVar, 
                    $compPromVar, 
                    $compMedVar, 
                    $comp75PercVar, 
                    $compMaxVar);
        
    }
    private function cargador($value, &$itemArray, $casos){
            if($casos){
                array_push($itemArray, $value["ocupantes"]);
                array_push($itemArray, $value["Casos"]);            
            }
            /*array_push($itemArray, intval(str_replace(".", "", $value["Min"])));
            array_push($itemArray, intval(str_replace(".", "", $value["25 Percentil"])));
            array_push($itemArray, intval(str_replace(".", "", $value["Promedio"])));
            array_push($itemArray, intval(str_replace(".", "", $value["Mediana"])));
            array_push($itemArray, intval(str_replace(".", "", $value["75 Percentil"])));
            array_push($itemArray, intval(str_replace(".", "", $value["Max"])));*/
            array_push($itemArray, $value["Min"]);
            array_push($itemArray, $value["25 Percentil"]);
            array_push($itemArray, round($value["Promedio"], 2));
            array_push($itemArray, $value["Mediana"]);
            array_push($itemArray, $value["75 Percentil"]);
            array_push($itemArray, $value["Max"]);            
    }


    private function segmenter( &$collection, 
                                $countCasosSeg, 
                                $detalle, 
                                $countCasos, 
                                $countCasosGratif, 
                                $countCasosAguinaldo, 
                                //$countCasosBeneficios, 
                                $countCasosBono, 
                                $dbClienteEnc, 
                                $rubro, 
                                $segmento, 
                                $dbCargo){
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
                            $segmento, 
                            $dbCargo);        
     
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
                            $segmento, 
                            $dbCargo);

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
                            $segmento, 
                            $dbCargo);

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
                            $segmento, 
                            $dbCargo);

            // Efectivo Anual Garantizado
            $detalle = $detalle->map(function($item){
                $item['efectivo_anual_garantizado'] = ($item['salario_base'] * 12) + 
                                                      $item['aguinaldo'] + 
                                                      $item['gratificacion'];
                return $item;
            });                                                
           
            $efectivoMin = $detalle->pluck('efectivo_anual_garantizado')->min();
            $efectivoMax = $detalle->pluck('efectivo_anual_garantizado')->max();
            $efectivoProm = $detalle->pluck('efectivo_anual_garantizado')->avg();
            $efectivoMed = $this->median($detalle->pluck('efectivo_anual_garantizado'));
            $efectivo25Per = $this->percentile(25, $detalle->pluck('efectivo_anual_garantizado'));
            $efectivo75Per = $this->percentile(75, $detalle->pluck('efectivo_anual_garantizado'));
            
            $found = $detalle->where('cabecera_encuesta_id', $dbClienteEnc->cabecera_encuesta_id)
                             ->first();
            if($found){
                $efectivoEmpresa = $found->efectivo_anual_garantizado;
            }else{
                $efectivoEmpresa = 0;    
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
                            $segmento, 
                            $dbCargo);


            //Adicional
            $casosAdicionalesBancos = $detalle->where('adicionales_bancos', '>', '0')->unique('cabecera_encuesta_id')->count();
            $adicionalesBancos = $detalle->where('adicionales_bancos', '>', '0')->pluck('adicionales_bancos');
            $adicionalesMin = $adicionalesBancos->min();
            $adicionalesMax = $adicionalesBancos->max();
            $adicionalesProm = $adicionalesBancos->avg();
            $adicionalesMed = $this->median($adicionalesBancos);
            $adicionales25Per = $this->percentile(25, $adicionalesBancos);
            $adicionales75Per = $this->percentile(75, $adicionalesBancos);
 
            $this->pusher(  $collection, 
                            $casosAdicionalesBancos, 
                            "Total Adicional Anual",
                            $adicionalesMin * 12,
                            $adicionalesMax * 12,
                            $adicionalesProm * 12,
                            $adicionalesMed * 12,
                            $adicionales25Per * 12,
                            $adicionales75Per * 12,
                            $dbClienteEnc->adicionales_bancos * 12, 
                            $segmento, 
                            $dbCargo);


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
                            $segmento, 
                            $dbCargo);

         
            //Aguinaldo Impactado
            $aguinaldoImpMin = 0;
            $aguinaldoImpMax = 0;
            $aguinaldoImpProm = 0;
            $aguinaldoImpMed = 0;
            $aguinaldoImp25Per = 0;
            $aguinaldoImp75Per = 0;
            $aguinaldoImpEmpresa = 0;

            $detalle = $detalle->map(function($item){
                $item['aguinaldo_impactado'] = (($item['salario_base'] * 12) + 
                                                $item['gratificacion'] + 
                                                $item['bono_anual'] +
                                                $item['adicionales_bancos'])/12;
                return $item;
            });                                                
           
            $aguinaldoImpMin = $detalle->pluck('aguinaldo_impactado')->min();
            $aguinaldoImpMax = $detalle->pluck('aguinaldo_impactado')->max();
            $aguinaldoImpProm = $detalle->pluck('aguinaldo_impactado')->avg();
            $aguinaldoImpMed = $this->median($detalle->pluck('aguinaldo_impactado'));
            $aguinaldoImp25Per = $this->percentile(25, $detalle->pluck('aguinaldo_impactado'));
            $aguinaldoImp75Per = $this->percentile(75, $detalle->pluck('aguinaldo_impactado'));
            
            $found = $detalle->where('cabecera_encuesta_id', $dbClienteEnc->cabecera_encuesta_id)
                             ->first();
            if($found){
                $aguinaldoImpEmpresa = $found->aguinaldo_impactado;
            }else{
                $aguinaldoImpEmpresa = 0;    
            }

            $this->pusher(  $collection, 
                            $countCasos, 
                            "Aguinaldo Impactado por Adicional, Gratificación y Bono",
                            $aguinaldoImpMin, 
                            $aguinaldoImpMax, 
                            $aguinaldoImpProm,
                            $aguinaldoImpMed,
                            $aguinaldoImp25Per,
                            $aguinaldoImp75Per,
                            $aguinaldoImpEmpresa,
                            $segmento, 
                            $dbCargo);

            //Total Compensación Efectiva anual
            $detalle = $detalle->map(function($item){
                $item['total_comp_anual'] = ($item['salario_base'] * 12) + 
                                             $item['gratificacion'] + 
                                             $item['bono_anual'] +
                                             $item['adicionales_bancos']*12+
                                             $item['aguinaldo'];
                return $item;
            });                                                
           
            $totalCompAnualMin = $detalle->pluck('total_comp_anual')->min();
            $totalCompAnualMax = $detalle->pluck('total_comp_anual')->max();
            $totalCompAnualProm = $detalle->pluck('total_comp_anual')->avg();
            $totalCompAnualMed = $this->median($detalle->pluck('total_comp_anual'));
            $totalCompAnual25Per = $this->percentile(25, $detalle->pluck('total_comp_anual'));
            $totalCompAnual75Per = $this->percentile(75, $detalle->pluck('total_comp_anual'));
            
            $found = $detalle->where('cabecera_encuesta_id', $dbClienteEnc->cabecera_encuesta_id)
                             ->first();
            if($found){
                $totalCompAnualEmpresa = $found->total_comp_anual;
            }else{
                $totalCompAnualEmpresa = 0;    
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
                            $segmento, 
                            $dbCargo);

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
                            $segmento, 
                            $dbCargo);        
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
                            $segmento, 
                            $dbCargo);
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
                            $segmento, 
                            $dbCargo);

            // Efectivo Anual Garantizado
            $detalle = $detalle->map(function($item){
                $item['efectivo_anual_garantizado'] = ($item['salario_base'] * 12) + 
                                                      $item['aguinaldo'];
                return $item;
            });                                                
           
            $efectivoMin = $detalle->pluck('efectivo_anual_garantizado')->min();
            $efectivoMax = $detalle->pluck('efectivo_anual_garantizado')->max();
            $efectivoProm = $detalle->pluck('efectivo_anual_garantizado')->avg();
            $efectivoMed = $this->median($detalle->pluck('efectivo_anual_garantizado'));
            $efectivo25Per = $this->percentile(25, $detalle->pluck('efectivo_anual_garantizado'));
            $efectivo75Per = $this->percentile(75, $detalle->pluck('efectivo_anual_garantizado'));
            
            $found = $detalle->where('cabecera_encuesta_id', $dbClienteEnc->cabecera_encuesta_id)
                             ->first();
            if($found){
                $efectivoEmpresa = $found->efectivo_anual_garantizado;
            }else{
                $efectivoEmpresa = 0;    
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
                            $segmento, 
                            $dbCargo);

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
                            $segmento, 
                            $dbCargo);        

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
                            $segmento, 
                            $dbCargo);        


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
                            $segmento, 
                            $dbCargo);        

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
                            $segmento, 
                            $dbCargo);        

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
                            $segmento, 
                            $dbCargo);        

            // Total Adicional 
            $casosAdicionales = collect([$countCasosAmarre, $countCasosTipoCombustible, $countCasosEmbarque, $countCasosCarga])->max();
            $adicionalAnual = $detalle->map(function($item){
                $item['total_adicional_anual'] = $item['adicional_amarre'] +
                                                 $item['adicional_tipo_combustible']+
                                                 $item['adicional_embarque']+
                                                 $item['adicional_carga'];
                return $item;
            });                                                
           
            $adicionalAnual = $adicionalAnual->where('total_adicional_anual', '>', 0);
            $totalAdicionalMin = $adicionalAnual->pluck('total_adicional_anual')->min();
            $totalAdicionalMax = $adicionalAnual->pluck('total_adicional_anual')->max();
            $totalAdicionalProm = $adicionalAnual->pluck('total_adicional_anual')->avg();
            $totalAdicionalMed = $this->median($adicionalAnual->pluck('total_adicional_anual'));
            $totalAdicional25Per = $this->percentile(25, $adicionalAnual->pluck('total_adicional_anual'));
            $totalAdicional75Per = $this->percentile(75, $adicionalAnual->pluck('total_adicional_anual'));
            
            $found = $adicionalAnual->where('cabecera_encuesta_id', $dbClienteEnc->cabecera_encuesta_id)
                             ->first();
            if($found){
                $totalAdicionalEmpresa = $found->total_adicional_anual;
            }else{
                $totalAdicionalEmpresa = 0;    
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
                            $segmento, 
                            $dbCargo);

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
                            $segmento, 
                            $dbCargo);

            // Efectivo Total Anual
            $detalle = $detalle->map(function($item){
                $item['efectivo_total_anual'] = $item['efectivo_anual_garantizado'] +
                                                 $item['plus_rendimiento']+
                                                 $item['total_adicional_anual']+
                                                 $item['bono_anual'];
                return $item;
            });                                                
           
            $efectivoTotalMin = $detalle->pluck('efectivo_total_anual')->min();
            $efectivoTotalMax = $detalle->pluck('efectivo_total_anual')->max();
            $efectivoTotalProm = $detalle->pluck('efectivo_total_anual')->avg();
            $efectivoTotalMed = $this->median($detalle->pluck('efectivo_total_anual'));
            $efectivoTotal25Per = $this->percentile(25, $detalle->pluck('efectivo_total_anual'));
            $efectivoTotal75Per = $this->percentile(75, $detalle->pluck('efectivo_total_anual'));
            
            $found = $detalle->where('cabecera_encuesta_id', $dbClienteEnc->cabecera_encuesta_id)
                             ->first();
            if($found){
                $efectivoTotalEmpresa = $found->efectivo_total_anual;
            }else{
                $efectivoTotalEmpresa = 0;    
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
                            $segmento, 
                            $dbCargo);
        

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
                            $segmento, 
                            $dbCargo);



         
            //Total Compensación anual
            $detalle = $detalle->map(function($item){
                $item['total_comp_anual'] = $item['efectivo_total_anual'] +
                                            $item['beneficios_navieras']*12;
                return $item;
            });                                                
           
            $totalCompAnualMin = $detalle->pluck('total_comp_anual')->min();
            $totalCompAnualMax = $detalle->pluck('total_comp_anual')->max();
            $totalCompAnualProm = $detalle->pluck('total_comp_anual')->avg();
            $totalCompAnualMed = $this->median($detalle->pluck('total_comp_anual'));
            $totalCompAnual25Per = $this->percentile(25, $detalle->pluck('total_comp_anual'));
            $totalCompAnual75Per = $this->percentile(75, $detalle->pluck('total_comp_anual'));
            
            $found = $detalle->where('cabecera_encuesta_id', $dbClienteEnc->cabecera_encuesta_id)
                             ->first();
            if($found){
                $totalCompAnualEmpresa = $found->total_comp_anual;
            }else{
                $totalCompAnualEmpresa = 0;    
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
                            $segmento, 
                            $dbCargo);            
        }else{ // Los otros rubros son iguales
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
                            $segmento, 
                            $dbCargo);        
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
                            $segmento, 
                            $dbCargo);
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
                            $segmento, 
                            $dbCargo);
        
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
                            $segmento, 
                            $dbCargo);

            // Efectivo Anual Garantizado

            $detalle = $detalle->map(function($item){
                $item['efectivo_anual_garantizado'] = ($item['salario_base'] * 12) + 
                                                       $item['gratificacion']+
                                                       $item['aguinaldo'];
                return $item;
            });   
            /*if($segmento == "nacional"){
                dd($detalle);                                             
            }*/
            
           
            $efectivoMin = $detalle->pluck('efectivo_anual_garantizado')->min();
            $efectivoMax = $detalle->pluck('efectivo_anual_garantizado')->max();
            $efectivoProm = $detalle->pluck('efectivo_anual_garantizado')->avg();
            $efectivoMed = $this->median($detalle->pluck('efectivo_anual_garantizado'));
            $efectivo25Per = $this->percentile(25, $detalle->pluck('efectivo_anual_garantizado'));
            $efectivo75Per = $this->percentile(75, $detalle->pluck('efectivo_anual_garantizado'));
            
            $found = $detalle->where('cabecera_encuesta_id', $dbClienteEnc->cabecera_encuesta_id)
                             ->first();
            if($found){
                $efectivoEmpresa = $found->efectivo_anual_garantizado;
            }else{
                $efectivoEmpresa = 0;    
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
                            $segmento, 
                            $dbCargo);

            //Comisión
            $countCasosComision = $detalle->where('comision', '>', '0')->unique('cabecera_encuesta_id')->count();            
            $comision = $detalle->where('comision', '>', '0')->pluck('comision');
            $comisionMin = $comision->min();
            $comisionMax = $comision->max();
            $comisionProm = $comision->avg();
            $comisionMed = $this->median($comision);
            $comision25Per = $this->percentile(25, $comision);
            $comision75Per = $this->percentile(75, $comision);

            $this->pusher(  $collection, 
                            $countCasosComision, 
                            "Comisión Anual",
                            $comisionMin*12,
                            $comisionMax*12,
                            $comisionProm*12,
                            $comisionMed*12,
                            $comision25Per*12,
                            $comision75Per*12,
                            $dbClienteEnc->comision*12, 
                            $segmento, 
                            $dbCargo);

            //Adicional
            $countCasosAdicionales = $detalle->where('adicionales_resto', '>', '0')->unique('cabecera_encuesta_id')->count();            
            $adicionales = $detalle->where('adicionales_resto', '>', '0')->pluck('adicionales_resto');
            $adicionalesMin = $adicionales->min();
            $adicionalesMax = $adicionales->max();
            $adicionalesProm = $adicionales->avg();
            $adicionalesMed = $this->median($adicionales);
            $adicionales25Per = $this->percentile(25, $adicionales);
            $adicionales75Per = $this->percentile(75, $adicionales);

            $this->pusher(  $collection, 
                            $countCasosAdicionales, 
                            "Total Adicional Anual",
                            $adicionalesMin * 12,
                            $adicionalesMax * 12,
                            $adicionalesProm * 12,
                            $adicionalesMed * 12,
                            $adicionales25Per * 12,
                            $adicionales75Per * 12,
                            $dbClienteEnc->adicionales_resto * 12, 
                            $segmento, 
                            $dbCargo);

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
                            $segmento, 
                            $dbCargo);

            // Efectivo Total Anual
            $detalle = $detalle->map(function($item){
                $item['efectivo_total_anual'] = $item['efectivo_anual_garantizado'] +
                                                $item['comision']*12+
                                                $item['adicionales_resto']*12+
                                                $item['bono_anual'];
                return $item;
            });                                                
           
            $efectivoTotalMin = $detalle->pluck('efectivo_total_anual')->min();
            $efectivoTotalMax = $detalle->pluck('efectivo_total_anual')->max();
            $efectivoTotalProm = $detalle->pluck('efectivo_total_anual')->avg();
            $efectivoTotalMed = $this->median($detalle->pluck('efectivo_total_anual'));
            $efectivoTotal25Per = $this->percentile(25, $detalle->pluck('efectivo_total_anual'));
            $efectivoTotal75Per = $this->percentile(75, $detalle->pluck('efectivo_total_anual'));
            
            $found = $detalle->where('cabecera_encuesta_id', $dbClienteEnc->cabecera_encuesta_id)
                             ->first();
            if($found){
                $efectivoTotalEmpresa = $found->efectivo_total_anual;
            }else{
                $efectivoTotalEmpresa = 0;    
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
                            $segmento, 
                            $dbCargo);

            //Beneficios
            $countCasosBeneficios = $detalle->where('beneficios_resto', '>', '0')->unique('cabecera_encuesta_id')->count();
            $beneficiosResto = $detalle->where('beneficios_resto', '>', '0')->pluck('beneficios_resto');
            $beneficiosMin = $beneficiosResto->min();
            $beneficiosMax = $beneficiosResto->max();
            $beneficiosProm = $beneficiosResto->avg();
            $beneficiosMed = $this->median($beneficiosResto);
            $beneficios25Per = $this->percentile(25, $beneficiosResto);
            $beneficios75Per = $this->percentile(75, $beneficiosResto);

            $this->pusher(  $collection, 
                            $countCasosBeneficios, 
                            "Total Beneficios Anual",
                            $beneficiosMin * 12,
                            $beneficiosMax * 12,
                            $beneficiosProm * 12,
                            $beneficiosMed * 12,
                            $beneficios25Per * 12,
                            $beneficios75Per * 12,
                            $dbClienteEnc->beneficios_bancos * 12, 
                            $segmento, 
                            $dbCargo);
            
            //Total Compensación anual
            $detalle = $detalle->map(function($item){
/*                if($item->beneficios_resto > 0){
                    dd($item->efectivo_total_anual, $item->beneficios_resto*12);    
                }
*/                
                $item['total_comp_anual'] = $item['efectivo_total_anual'] +
                                            $item['beneficios_resto'] * 12;
                return $item;
            });
            $totalCompAnualMin = $detalle->pluck('total_comp_anual')->min();
            $totalCompAnualMax = $detalle->pluck('total_comp_anual')->max();
            $totalCompAnualProm = $detalle->pluck('total_comp_anual')->avg();
            $totalCompAnualMed = $this->median($detalle->pluck('total_comp_anual'));
            $totalCompAnual25Per = $this->percentile(25, $detalle->pluck('total_comp_anual'));
            $totalCompAnual75Per = $this->percentile(75, $detalle->pluck('total_comp_anual'));
            
            $found = $detalle->where('cabecera_encuesta_id', $dbClienteEnc->cabecera_encuesta_id)
                             ->first();
            if($found){
                $totalCompAnualEmpresa = $found->total_comp_anual;
            }else{
                $totalCompAnualEmpresa = 0;    
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
                            $segmento, 
                            $dbCargo);            

        }

    }
    private function pusher(&$collection, $casos, $concepto, $min, $max, $prom, $med, $per25, $per75, $empresa, $segmento, $dbCargo){
        if($casos >= 4){
            $collection->push([ "concepto"=> $concepto,
                              "casos"=> $casos,
                              "min"=>$min, 
                              "max"=>$max, 
                              "prom"=>$prom, 
                              "med"=>$med, 
                              "per25"=>$per25, 
                              "per75"=> $per75, 
                              "empresa"=>$empresa,
                              "segmento"=>$segmento
            ]);
        }elseif($casos <= 3 and $casos > 1){
            $collection->push([ "concepto"=> $concepto,
                              "casos"=> $casos,
                              "min"=>"", 
                              "max"=>"", 
                              "prom"=>$prom, 
                              "med"=>"", 
                              "per25"=>$per25, 
                              "per75"=> $per75, 
                              "empresa"=>$empresa,
                              "segmento"=>$segmento
            ]);

        }elseif ($casos <= 1) {
            if($dbCargo->is_temporal == '1'){
                $collection->push([ "concepto"=> $concepto,
                                  "casos"=> $casos,
                                  "min"=>$min, 
                                  "max"=>$max,
                                  "prom"=>$prom, 
                                  "med"=>$med, 
                                  "per25"=>$per25, 
                                  "per75"=> $per75, 
                                  "empresa"=>$empresa,
                                  "segmento"=>$segmento
                ]);
            }else{
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

    public function resultados(){
        //$dbData = Cabecera_encuesta::select(DB::Raw('distinct periodo'))->get();
        $dbData = Cabecera_encuesta::get();
        $dbData = $dbData->map(function($item){
            $rubro = $item->rubro->descripcion;
            $periodo = $item->periodo;
            $item['periodo'] = $periodo.' - '.$rubro;
            $item['periodo_rubro_id'] = $periodo.'-'.$item->rubro->id;
            return $item;
        })->unique('periodo');
        //dd($dbData);
        return view('report.periodos')->with('dbData', $dbData);
    }

    public function resultadosExcel(Request $request){
        ini_set('max_execution_time', 0);
        ini_set('memory_limit', '512M');
        $periodo_array = explode('-', $request->periodo);
        $periodo = $periodo_array[0];
        $rubro = $periodo_array[1];
        $rubroDesc = Rubro::find($rubro)->descripcion;
        $query = "SELECT d.cabecera_encuesta_id, IF(e.incluir = 1, 'NO', 'SI') excluir,  c.periodo, 
                         c.empresa_id, em.descripcion empresa, c.cantidad_empleados,
                         d.encuestas_cargo_id, convert(e.descripcion using utf8) cargo_cliente, d.area_id, convert(a.descripcion using utf8) area_cliente, d.nivel_id, n.descripcion nivel_cliente, c.rubro_id, 
                         r.descripcion rubro, ca.id, ca.descripcion cargo_oficial, ca.area_id id_area_oficial, convert(a1.descripcion using utf8) area_oficial, ca.nivel_id id_nivel_oficial, n1.descripcion nivel_oficial, cantidad_ocupantes, 
                         
                         salario_base, salario_base * 12 salario_anual, gratificacion, aguinaldo, comision, plus_rendimiento, fallo_caja,
                         fallo_caja_ext, gratificacion_contrato, adicional_nivel_cargo, adicional_titulo,
                         adicional_amarre, adicional_tipo_combustible, adicional_embarque, adicional_carga,
                         bono_anual, bono_anual_salarios, incentivo_largo_plazo, refrigerio, costo_seguro_medico, 
                         cobertura_seguro_medico, costo_seguro_vida, costo_poliza_muerte_natural,
                         costo_poliza_muerte_accidente, aseguradora_id, car_company, movilidad_full, flota monto_tarjeta_flota, autos_marca_id, autos_modelo_id, tarjeta_flota, monto_movil, 
                         seguro_movil, mantenimiento_movil, monto_km_recorrido, monto_ayuda_escolar, 
                         monto_comedor_interno, monto_curso_idioma, cobertura_curso_idioma, tipo_clase_idioma, 
                         monto_post_grado, cobertura_post_grado, monto_celular_corporativo, monto_vivienda, 
                         monto_colegiatura_hijos, condicion_ocupante
                    FROM detalle_encuestas d, encuestas_cargos e, cabecera_encuestas c, cargos ca, rubros r, 
                         areas a, niveles n, empresas em, niveles n1, areas a1
                   where c.periodo = '".$periodo."'
                     and c.rubro_id = '".$rubro."'
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
            
            $efectivoAnual = $item->salario_anual + 
                             $item->gratificacion + 
                             $item->aguinaldo;
            
            $adicionalNav = $item->adicional_amarre + 
                            $item->adicional_tipo_combustible + 
                            $item->adicional_embarque + 
                            $item->adicional_carga;
            
            $adicionalOtros = ( $item->fallo_caja + 
                                $item->fallo_caja_ext + 
                                $item->comision + 
                                $item->gratificacion_contrato + 
                                $item->adicional_nivel_cargo + 
                                $item->adicional_titulo) * 12;
            
            $efectivoTotalAnualNav = $efectivoAnual + 
                                     $item->plus_rendimiento + 
                                     $adicionalNav + 
                                     $item->bono_anual;
            $efectivoTotalAnual = $efectivoAnual + $adicionalOtros + $item->bono_anual;

            $totalBeneficios =  $item->refrigerio + 
                                $item->costo_seguro_medico * ($item->cobertura_seguro_medico/100) + 
                                $item->costo_seguro_vida + 
                                //$item->costo_poliza_muerte_accidente +
                                //$item->costo_poliza_muerte_natural +
                                $item->monto_movil / 60 +
                                $item->monto_tarjeta_flota+
                                $item->seguro_movil +
                                $item->monto_km_recorrido +
                                $item->monto_ayuda_escolar +
                                $item->monto_comedor_interno +
                                $item->monto_curso_idioma * ($item->cobertura_curso_idioma/100) +
                                $item->monto_post_grado * ($item->cobertura_post_grado/100)/ 24 +
                                $item->monto_celular_corporativo +
                                $item->monto_vivienda +
                                $item->monto_colegiatura_hijos;
         
            $aguinaldoImpactado = ( $item->salario_anual + 
                                    $item->gratificacion + 
                                    $adicionalOtros + 
                                    $item->bono_anual ) / 12;
            
            $compensacionEfectiva = ( $item->salario_anual + 
                                      $item->gratificacion + 
                                      $item->aguinaldo + 
                                      $adicionalOtros + 
                                      $item->bono_anual );            

            $detalle[] = array( "id_encuesta"=>$item->cabecera_encuesta_id,
                                "id_empresa"=>$item->empresa_id,
                                "Empresa"=>$item->empresa, 
                                "Cant. Empleados"=>$item->cantidad_empleados,
                                "Excluir"=>$item->excluir,
                                "Periodo"=>$item->periodo, 
                                "id_cargo_cliente"=>$item->encuestas_cargo_id, 
                                "Cargo Cliente"=> $item->cargo_cliente, 
                                "id_area"=>$item->area_id, 
                                "Area"=>$item->area_cliente, 
                                "id_nivel"=>$item->nivel_id, 
                                "Nivel"=>$item->nivel_cliente,
                                "id_rubro"=> $item->rubro_id, 
                                "Rubro"=> $item->rubro,
                                "id_cargo_oficial"=> $item->id, 
                                "Cargo Oficial"=> $item->cargo_oficial, 
                                "id_area_oficial"=>$item->id_area_oficial, 
                                "Area Oficial"=>$item->area_oficial, 
                                "id_nivel_oficial"=>$item->id_nivel_oficial, 
                                "Nivel Oficial"=>$item->nivel_oficial,
                                "Ocupantes"=>$item->cantidad_ocupantes, 
                                "Salario Base"=>$item->salario_base, 
                                "salario Anual"=>$item->salario_anual,
                                "Gratificación"=> $item->gratificacion, 
                                "Aguinaldo"=> $item->aguinaldo,
                                "Efectivo Anual Garantizado" => $efectivoAnual,
                                "comision"=> $item->comision, 
                                "Variable Anual (plus_rendimiento)" => $item->plus_rendimiento,
                                "Adicional Amarre"=>$item->adicional_amarre, 
                                "Adicional Tipo Combustible"=>$item->adicional_tipo_combustible, 
                                "Adicional Embarque"=>$item->adicional_embarque, 
                                "Adicional Tipo Carga"=>$item->adicional_carga,
                                "Total Adicional Naviera" => $adicionalNav,
                                "Fallo Caja"=> $item->fallo_caja,
                                "Fallo Caja Ext."=> $item->fallo_caja_ext, 
                                "Gratif. Contrato"=>$item->gratificacion_contrato, 
                                "Adicional Nivel Cargo"=>$item->adicional_nivel_cargo, 
                                "Adicional Título"=>$item->adicional_titulo,
                                "Total Adicional (Otros)"=>$adicionalOtros,
                                "Bono Anual"=>$item->bono_anual, 
//                                "bono_anual_salarios"=>$item->bono_anual_salarios, 
                                "Incentivo a Largo Plazo"=>$item->incentivo_largo_plazo, 
                                "Efectivo Total Anual Nav" => $efectivoTotalAnualNav,
                                "Efectivo Total Anual (Otros)" => $efectivoTotalAnual,
                                "Refrigerio"=>$item->refrigerio, 
                                "Costo Seguro Médico"=>$item->costo_seguro_medico, 
                                "Cobertura Seguro Médico"=>$item->cobertura_seguro_medico, 
                                "Costo Seguro Vida"=>$item->costo_seguro_vida, 
//                                "costo_poliza_muerte_natural"=>$item->costo_poliza_muerte_natural,
//                                "costo_poliza_muerte_accidente"=>$item->costo_poliza_muerte_accidente,
//                                "aseguradora_id"=>$item->aseguradora_id, 
                                "Car Company"=>$item->car_company, 
                                "Movilidad Full"=>$item->movilidad_full, 
                                "Monto Tarj. Flota"=>$item->monto_tarjeta_flota, 
//                                "autos_marca_id"=>$item->autos_marca_id, 
//                                "autos_modelo_id"=>$item->autos_modelo_id, 
                                "Tarj. Flota"=>$item->tarjeta_flota, 
                                "Monto Automóvil"=>$item->monto_movil, 
                                "Seguro Automóvil"=>$item->seguro_movil, 
                                "Mantenimiento Automóvil"=>$item->mantenimiento_movil, 
                                "Km recorrido"=>$item->monto_km_recorrido, 
                                "Ayuda Escolar"=>$item->monto_ayuda_escolar, 
                                "Comedor Interno"=>$item->monto_comedor_interno, 
                                "Curso Idioma"=>$item->monto_curso_idioma, 
                                "Cobertura idioma"=>$item->cobertura_curso_idioma, 
//                                "tipo clase idioma"=>$item->tipo_clase_idioma, 
                                "Post Grado"=>$item->monto_post_grado, 
                                "Cobertura Post Grado"=>$item->cobertura_post_grado, 
                                "Celular"=>$item->monto_celular_corporativo, 
                                "Vivienda"=>$item->monto_vivienda, 
                                "Colegiatura"=>$item->monto_colegiatura_hijos, 
                                "Condición Ocupante"=>$item->condicion_ocupante,
                                "Total Beneficios Anual"=>$totalBeneficios*12,
                                "Compensación Anual Total" => ($totalBeneficios*12 + $efectivoTotalAnual),
                                "Aguinaldo Impactado"=> $aguinaldoImpactado,
                                "Compensación Efectiva Anual" => $compensacionEfectiva
                );

             
        }
        $periodo = implode('_', explode('/', $periodo));
        $filename = 'Resultados_'.$periodo.'_'.$rubroDesc;
        Excel::create($filename, function($excel) use($detalle, $periodo) {
            $excel->sheet($periodo, function($sheet) use($detalle){
                
                $sheet->cells('A10:BQ10', function($cells){
                    $cells->setBackground('#00897b');
                    $cells->setFontColor("#FFFFFF");
                    $cells->setFontWeight("bold");
                   // $cells->setValignment('center');
                    $cells->setAlignment('center');
                });

                // Efectivo Anual Garantizado
                $sheet->cell('A1', function($cell){
                    $cell->setBackground('#1976d2');
                    $cell->setFontColor("#FFFFFF");
                    $cell->setFontWeight("bold");
                    $cell->setValue('Efectivo Anual Garantizado');
                });
                
                $sheet->cell('B1', function($cell){
                    $cell->setValue('Salario Base Anual');
                });

                $sheet->cell('C1', function($cell){
                    $cell->setValue('Gratificación');
                });

                $sheet->cell('D1', function($cell){
                    $cell->setValue('Aguinaldo');
                });

                // Adicional Navieras
                $sheet->cell('A2', function($cell){
                    $cell->setBackground('#ffff00');
                    $cell->setFontColor("#000000");
                    $cell->setFontWeight("bold");
                    $cell->setValue('Adicional Navieras');
                });                

                $sheet->cell('B2', function($cell){
                    $cell->setValue('Adicional Amarre');
                });

                $sheet->cell('C2', function($cell){
                    $cell->setValue('Adicional Tipo Combustible');
                });

                $sheet->cell('D2', function($cell){
                    $cell->setValue('Adicional Embarque');
                });

                $sheet->cell('E2', function($cell){
                    $cell->setValue('Adicional Carga');
                });

                // Adicional Otros
                $sheet->cell('A3', function($cell){
                    $cell->setBackground('#388e3c');
                    $cell->setFontColor("#FFFFFF");
                    $cell->setFontWeight("bold");
                    $cell->setValue('Adicional Otros');
                });    

                $sheet->cell('B3', function($cell){
                    $cell->setValue('Fallo Caja');
                });

                $sheet->cell('C3', function($cell){
                    $cell->setValue('Fallo Caja Mon. Ext.');
                });

                $sheet->cell('D3', function($cell){
                    $cell->setValue('Comisión');
                });

                $sheet->cell('E3', function($cell){
                    $cell->setValue('Gratificación por Contrato');
                });

                $sheet->cell('F3', function($cell){
                    $cell->setValue('Adicional por Nivel de Cargo');
                });

                $sheet->cell('F3', function($cell){
                    $cell->setValue('Adicional por Título');
                });

                // Efectivo Total Navieras
                $sheet->cell('A4', function($cell){
                    $cell->setBackground('#ff80ab');
                    $cell->setFontColor("#000000");
                    $cell->setFontWeight("bold");
                    $cell->setValue('Efectivo Total Anual Navieras');
                });   

                $sheet->cell('B4', function($cell){
                    $cell->setValue('Efectivo Anual Garantizado');
                });

                $sheet->cell('C4', function($cell){
                    $cell->setValue('Variable Anual');
                });

                $sheet->cell('D4', function($cell){
                    $cell->setValue('Adicional Navieras');
                });

                $sheet->cell('E4', function($cell){
                    $cell->setValue('Bono');
                });

                // Efectivo total anual otros
                $sheet->cell('A5', function($cell){
                    $cell->setBackground('#afb42b');
                    $cell->setFontColor("#FFFFFF");
                    $cell->setFontWeight("bold");
                    $cell->setValue('Efectivo Total Anual Otros');
                });     

                $sheet->cell('B5', function($cell){
                    $cell->setValue('Efectivo Anual Garantizado');
                });

                $sheet->cell('C5', function($cell){
                    $cell->setValue('Adicional Otros');
                });

                $sheet->cell('D5', function($cell){
                    $cell->setValue('Bono');
                });


                // Total Beneficios
                $sheet->cell('A6', function($cell){
                    $cell->setBackground('#ff9800');
                    $cell->setFontColor("#FFFFFF");
                    $cell->setFontWeight("bold");
                    $cell->setValue('Total Beneficios');
                });                
                
                // Compensación anual Total
                $sheet->cell('A7', function($cell){
                    $cell->setBackground('#5d4037');
                    $cell->setFontColor("#FFFFFF");
                    $cell->setFontWeight("bold");
                    $cell->setValue('Compensación Anual Total');
                }); 

                $sheet->cell('B7', function($cell){
                    $cell->setValue('Efectivo Total Anual');
                });

                $sheet->cell('C7', function($cell){
                    $cell->setValue('Total Beneficios');
                });

                // Aguinaldo Impactado
                $sheet->cell('A8', function($cell){
                    $cell->setBackground('#424242');
                    $cell->setFontColor("#FFFFFF");
                    $cell->setFontWeight("bold");
                    $cell->setValue('Aguinaldo Impactado');
                });  

                $sheet->cell('B8', function($cell){
                    $cell->setValue('(Salario Base Anual');
                });

                $sheet->cell('C8', function($cell){
                    $cell->setValue('Gratificación');
                });

                $sheet->cell('D8', function($cell){
                    $cell->setValue('Adicional Otros');
                });

                $sheet->cell('E8', function($cell){
                    $cell->setValue('Bono)/12');
                });

                // Compensación Efectiva anual Total
                $sheet->cell('A9', function($cell){
                    $cell->setBackground('#e53935');
                    $cell->setFontColor("#FFFFFF");
                    $cell->setFontWeight("bold");
                    $cell->setValue('Compensación Efectiva Anual Total');
                });                

                $sheet->cell('B9', function($cell){
                    $cell->setValue('Salario Base Anual');
                });

                $sheet->cell('C9', function($cell){
                    $cell->setValue('Gratificación');
                });

                $sheet->cell('D9', function($cell){
                    $cell->setValue('Adicional Otros');
                });

                $sheet->cell('E9', function($cell){
                    $cell->setValue('Aguinaldo');
                });

                $sheet->cell('F9', function($cell){
                    $cell->setValue('Bono');
                });

                // Efectivo Anual Garantizado
                $sheet->cell('V10', function($cell){
                    $cell->setBackground('#1976d2');
                    $cell->setFontColor("#FFFFFF");
                    $cell->setFontWeight("bold");
                   // $cells->setValignment('center');
                    $cell->setAlignment('center');
                });                
                // Adicional Navieras
                $sheet->cell('AC10', function($cell){
                    $cell->setBackground('#ffff00');
                    $cell->setFontColor("#000000");
                    $cell->setFontWeight("bold");
                   // $cells->setValignment('center');
                    $cell->setAlignment('center');
                });                
                // Adicional Otros
                $sheet->cell('AI10', function($cell){
                    $cell->setBackground('#388e3c');
                    $cell->setFontColor("#FFFFFF");
                    $cell->setFontWeight("bold");
                   // $cells->setValignment('center');
                    $cell->setAlignment('center');
                });                
                // Efectivo Total Navieras
                $sheet->cell('AL10', function($cell){
                    $cell->setBackground('#ff80ab');
                    $cell->setFontColor("#000000");
                    $cell->setFontWeight("bold");
                   // $cells->setValignment('center');
                    $cell->setAlignment('center');
                });                
                // Efectivo total anual otros
                $sheet->cell('AM10', function($cell){
                    $cell->setBackground('#afb42b');
                    $cell->setFontColor("#FFFFFF");
                    $cell->setFontWeight("bold");
                   // $cells->setValignment('center');
                    $cell->setAlignment('center');
                });                
                // Total Beneficios
                $sheet->cell('BJ10', function($cell){
                    $cell->setBackground('#ff9800');
                    $cell->setFontColor("#FFFFFF");
                    $cell->setFontWeight("bold");
                   // $cells->setValignment('center');
                    $cell->setAlignment('center');
                });                
                // Compensación anual Total
                $sheet->cell('BK10', function($cell){
                    $cell->setBackground('#5d4037');
                    $cell->setFontColor("#FFFFFF");
                    $cell->setFontWeight("bold");
                   // $cells->setValignment('center');
                    $cell->setAlignment('center');
                });                
                // Aguinaldo Impactado
                $sheet->cell('BL10', function($cell){
                    $cell->setBackground('#424242');
                    $cell->setFontColor("#FFFFFF");
                    $cell->setFontWeight("bold");
                   // $cells->setValignment('center');
                    $cell->setAlignment('center');
                });                
                // Compensación Efectiva anual Total
                $sheet->cell('BM10', function($cell){
                    $cell->setBackground('#e53935');
                    $cell->setFontColor("#FFFFFF");
                    $cell->setFontWeight("bold");
                   // $cells->setValignment('center');
                    $cell->setAlignment('center');
                });                
                $sheet->fromArray($detalle, null, 'A10');                

            });
        })->export('xlsx');
        return redirect()->route('resultados');

    }
    public function panel($id){
        $dbEmpresa = $id;
        $rubro = Auth::user()->empresa->rubro_id;
        $club = $this->club($rubro);
        $dbEncuesta = Cabecera_encuesta::where('empresa_id', $id)->whereRaw('id = (select max(id) from cabecera_encuestas where empresa_id = '. $id.')')->first();
        $participantes = Cabecera_encuesta::where('periodo', $dbEncuesta->periodo)->where('rubro_id', $rubro)->get();
        $dbData = $participantes->map(function($item){
            return $item->empresa;    
        })->reject(function($item){
            if(!$item->listable){
                return $item;
            }
        });


        return view('report.panel')->with('dbData', $dbData)->with('club', $club)->with('dbEmpresa', $dbEmpresa);
    }

    public function getCargos(Request $request){
        $id = $request->nivel_id;
        $dbData = Cargo::orderBy('descripcion')->where('nivel_id', $id)->pluck('id', 'descripcion');

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
   
        $count = $sorted->count();

        // percentil.exc -- método de cálculo de la fórmula percentil.exc en excel
        //$perN = (($count+1) * ($percentile/100));
        
        // percentil.inc -- método de cálculo de la fórmula percentil.inc en excel
        $perN = (($count-1)* ($percentile/100) + 1 );
        
        if (is_int($perN)) {
           $result = $sorted[$perN - 1];
        }
        else {
           $int = floor($perN);
           $dec = $perN - $int;

           $result = $dec * ($sorted[$int] - $sorted[$int - 1]) + $sorted[$int-1];
        }
        return $result;
    }

    public function setSession(Request $request){
        $request->session()->put('periodo', $request->periodo); 
        return "ok";
    }

    private function cargoReportAll(Request $request, $tipo){
        $dbEmpresa = Empresa::find($request->empresa_id);   // datos de la empresa del cliente
        if(Session::has('periodo')){
            $per = Session::get('periodo');
            $dbEncuesta = Cabecera_encuesta::where('empresa_id', $dbEmpresa->id)
                                           ->whereRaw("periodo = '". $per."'")
                                           ->first();
        }else{
            if(Auth::user()->is_admin){
                $dbEncuesta = Cabecera_encuesta::where('empresa_id', $dbEmpresa->id)
                                               ->whereRaw("periodo = '". $request->periodo."'")
                                               ->first();
            }else{
                $dbEncuesta = Cabecera_encuesta::where('empresa_id', $dbEmpresa->id)
                                               ->whereRaw('id = (select max(id) from cabecera_encuestas where empresa_id = '. $dbEmpresa->id.')')
                                               ->first();            

            }
        }
        $periodo = $dbEncuesta->periodo;    // periodo de la encuesta actual

        $rubro = $dbEmpresa->rubro_id;      // rubro de la empresa del cliente
        // cargo oficial para el informe
        $cargo = $request->cargo_id;        
        $dbCargo = Cargo::find($cargo);  
        // empresas y cabeceras de encuestas de este periodo para empresas del rubro del cliente
        $dbEncuestadas = Cabecera_encuesta::where('periodo', $periodo)
                                          ->where('rubro_id', $rubro)
                                          ->get();

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
        $dbCargosEncuestas = Encuestas_cargo::whereIn('cabecera_encuesta_id', $encuestadasIds)
                                            ->where('cargo_id', $cargo)
                                            ->where('incluir', 1)
                                            ->get();

        $dbCargosEncuestasNac = Encuestas_cargo::whereIn('cabecera_encuesta_id', $encuestadasNacIds)->where('cargo_id', $cargo)->where('incluir', 1)->get();
        $dbCargosEncuestasInter = Encuestas_cargo::whereIn('cabecera_encuesta_id', $encuestadasInterIds)->where('cargo_id', $cargo)->where('incluir', 1)->get();
        
        $cargosEncuestasIds = $dbCargosEncuestas->pluck('id');
        $cargosEncuestasNacIds = $dbCargosEncuestasNac->pluck('id');
        $cargosEncuestasInterIds = $dbCargosEncuestasInter->pluck('id');

        // Recuperamos los datos de las encuestas
        $dbDetalle = Detalle_encuesta::whereIn('encuestas_cargo_id', $cargosEncuestasIds)->get();

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
        $countCasos = $dbDetalle->where('cantidad_ocupantes', '>', '0')
                                ->unique('cabecera_encuesta_id')
                                ->count();
        $countOcupantes = $dbDetalle->sum('cantidad_ocupantes');
        $countCasosGratif = $dbDetalle->where('cantidad_ocupantes', '>', '0')
                                      ->where('gratificacion', '>', '0')
                                      ->unique('cabecera_encuesta_id')->count();
        $countCasosAguinaldo = $dbDetalle->where('cantidad_ocupantes', '>', '0')
                                         ->where('aguinaldo', '>', '0')
                                         ->unique('cabecera_encuesta_id')->count();
        //$countCasosBeneficios = $dbDetalle->where('beneficios_bancos', '>', 0)->unique('cabecera_encuesta_id')->count();
        $countCasosBono = $dbDetalle->where('cantidad_ocupantes', '>', '0')
                                    ->where('bono_anual', '>', 0)
                                    ->unique('cabecera_encuesta_id')->count();



        $universo = collect();
        $segmento = "universo";
        $this->segmenter( $universo, 
                          $dbUniverso, 
                          $dbDetalle, 
                          $countCasos,
                          $countCasosGratif,
                          $countCasosAguinaldo,
                         // $countCasosBeneficios, 
                          $countCasosBono,
                          $dbClienteEnc, 
                          $rubro, 
                          $segmento, 
                          $dbCargo);

        // conteo de casos encontrados nacionales
        $countCasosNac = $encuestadasNacIds->count();
        // buscamos los detalles de las encuestas
        $dbDetalleNac = Detalle_encuesta::whereIn('encuestas_cargo_id', $cargosEncuestasNacIds)->get();
        // conteo de casos encontrados
        $countOcupantesNac = $dbDetalleNac->sum('cantidad_ocupantes');
        $countCasos = $dbDetalleNac->where('cantidad_ocupantes', '>', '0')
                                   ->unique('cabecera_encuesta_id')
                                   ->count();
        $countCasosGratif = $dbDetalleNac->where('cantidad_ocupantes', '>', '0')
                                         ->where('gratificacion', '>', '0')
                                         ->unique('cabecera_encuesta_id')->count();
        $countCasosAguinaldo = $dbDetalleNac->where('cantidad_ocupantes', '>', '0')
                                            ->where('aguinaldo', '>', '0')
                                            ->unique('cabecera_encuesta_id')->count();

        $countCasosBono = $dbDetalleNac->where('cantidad_ocupantes', '>', '0')
                                       ->where('bono_anual', '>', 0)
                                       ->unique('cabecera_encuesta_id')
                                       ->count();

        $nacional = collect();
        $segmento = "nacional";
        $this->segmenter(   $nacional, 
                            $countCasosNac, 
                            $dbDetalleNac, 
                            $countCasos, 
                            $countCasosGratif, 
                            $countCasosAguinaldo, 
                        //    $countCasosBeneficios, 
                            $countCasosBono, 
                            $dbClienteEnc, 
                            $rubro, 
                            $segmento, 
                            $dbCargo);

        // conteo de casos encontrados internacionales
        $countCasosInt = $encuestadasInterIds->count();
        // buscamos los detalles de las encuestas
        $dbDetalleInt = Detalle_encuesta::whereIn('encuestas_cargo_id', $cargosEncuestasInterIds)->get();
        $countOcupantesInt = $dbDetalleInt->sum('cantidad_ocupantes');
        // conteo de casos encontrados
        $countCasos = $dbDetalleInt->where('cantidad_ocupantes', '>', '0')
                                   ->unique('cabecera_encuesta_id')
                                   ->count();
        $countCasosGratif = $dbDetalleInt->where('cantidad_ocupantes', '>', '0')
                                         ->where('gratificacion', '>', '0')
                                         ->unique('cabecera_encuesta_id')
                                         ->count();
        $countCasosAguinaldo = $dbDetalleInt->where('cantidad_ocupantes', '>', '0')
                                            ->where('aguinaldo', '>', '0')
                                            ->unique('cabecera_encuesta_id')
                                            ->count();
        
        //$countCasosBeneficios = $dbDetalleInt->where('beneficios_bancos', '>', 0)->unique('cabecera_encuesta_id')->count();




        $countCasosBono = $dbDetalleInt->where('cantidad_ocupantes', '>', '0')
                                       ->where('bono_anual', '>', 0)
                                       ->unique('cabecera_encuesta_id')
                                       ->count();

        $internacional = collect();
        $segmento = "internacional";

        $this->segmenter(   $internacional, 
                            $countCasosInt, 
                            $dbDetalleInt, 
                            $countCasos, 
                            $countCasosGratif, 
                            $countCasosAguinaldo, 
                           // $countCasosBeneficios, 
                            $countCasosBono, 
                            $dbClienteEnc,
                            $rubro, 
                            $segmento, 
                            $dbCargo);
        if($tipo == "view"){
            if($request->tour){
                $tour = true;
            }else{
                $tour = false;
            }

            if($request->moneda != "local"){
                $convertir = true;
            }else{
                $convertir = false;
            }

            $tipoCambio = 5600;

            return view('report.report')->with('dbCargo', $dbCargo)
                                        ->with('dbEmpresa', $dbEmpresa)
                                        ->with('universo', $universo)
                                        ->with('nacional', $nacional)
                                        ->with('internacional', $internacional)
                                        ->with('countOcupantes', $countOcupantes)
                                        ->with('countOcupantesNac', $countOcupantesNac)
                                        ->with('countOcupantesInt', $countOcupantesInt)
                                        ->with('tour', $tour)
                                        ->with('tipoCambio', $tipoCambio)
                                        ->with('periodo', $periodo)
                                        ->with('convertir', $convertir);            

        }elseif($tipo == "excel"){
            $periodo = implode('_', explode('/', $periodo));
            $cargoFileName = str_replace("-", "_", str_replace(" ", "_", $dbCargo->descripcion));
            $filename = 'Resultados_'.$periodo.'_'.$cargoFileName;
            $detalleUniverso = array();
            $detalleNacional = array();
            $detalleInternacional = array();
            foreach ($universo as $value) {
                $detalleUniverso[] = array( "Concepto"=>$value["concepto"], 
                                            "Casos"=>$value["casos"], 
                                            "Min"=>$value["min"], 
                                            "25 Percentil"=>$value["per25"], 
                                            "Promedio"=>$value["prom"], 
                                            "Mediana"=>$value["med"], 
                                            "75 Percentil"=>$value["per75"], 
                                            "Max"=>$value["max"], 
                                            "Empresa"=>$value["empresa"] 
                                          );
            }
            foreach ($nacional as $value) {
                $detalleNacional[] = array( "Concepto"=>$value["concepto"], 
                                            "Casos"=>$value["casos"], 
                                            "Min"=>$value["min"], 
                                            "25 Percentil"=>$value["per25"], 
                                            "Promedio"=>$value["prom"], 
                                            "Mediana"=>$value["med"], 
                                            "75 Percentil"=>$value["per75"], 
                                            "Max"=>$value["max"], 
                                            "Empresa"=>$value["empresa"] 
                                          );
            }

            foreach ($internacional as $value) {
                $detalleInternacional[] = array( "Concepto"=>$value["concepto"], 
                                            "Casos"=>$value["casos"], 
                                            "Min"=>$value["min"], 
                                            "25 Percentil"=>$value["per25"], 
                                            "Promedio"=>$value["prom"], 
                                            "Mediana"=>$value["med"], 
                                            "75 Percentil"=>$value["per75"], 
                                            "Max"=>$value["max"], 
                                            "Empresa"=>$value["empresa"] 
                                          );
            }


            Excel::create($filename, function($excel) use($detalleUniverso, $detalleNacional, $detalleInternacional ) {
                $excel->sheet("universo", function($sheet) use($detalleUniverso){
                    $sheet->fromArray($detalleUniverso);
                });
                $excel->sheet("nacional", function($sheet) use($detalleNacional){
                    $sheet->fromArray($detalleNacional);
                });
                $excel->sheet("internacional", function($sheet) use($detalleInternacional){
                    $sheet->fromArray($detalleInternacional);
                });

            })->export('xlsx');
            return redirect()->route('resultados');
        }elseif ($tipo == "clubExcel") {
            $cargoFileName = str_replace("-", "_", str_replace(" ", "_", $dbCargo->descripcion));
            $filename = 'Resultados_'.$periodo.'_'.$cargoFileName;
            $detalleUniverso = array();
            $detalleNacional = array();
            $detalleInternacional = array();
            foreach ($universo as $value) {

                $detalleUniverso[] = array( "Concepto"=>$value["concepto"], 
                                            "ocupantes"=> $countOcupantes,
                                            "Casos"=>$value["casos"], 
                                            "Min"=>$value["min"], 
                                            "25 Percentil"=>$value["per25"], 
                                            "Promedio"=>$value["prom"], 
                                            "Mediana"=>$value["med"], 
                                            "75 Percentil"=>$value["per75"], 
                                            "Max"=>$value["max"], 
                                            "Empresa"=>$value["empresa"] 
                                          );
            }
            foreach ($nacional as $value) {
                $detalleNacional[] = array( "Concepto"=>$value["concepto"],
                                            "ocupantes"=> $countOcupantesNac, 
                                            "Casos"=>$value["casos"], 
                                            "Min"=>$value["min"], 
                                            "25 Percentil"=>$value["per25"], 
                                            "Promedio"=>$value["prom"], 
                                            "Mediana"=>$value["med"], 
                                            "75 Percentil"=>$value["per75"], 
                                            "Max"=>$value["max"], 
                                            "Empresa"=>$value["empresa"] 
                                          );
            }

            foreach ($internacional as $value) {
                $detalleInternacional[] = array( "Concepto"=>$value["concepto"], 
                                            "ocupantes"=> $countOcupantesInt,
                                            "Casos"=>$value["casos"], 
                                            "Min"=>$value["min"], 
                                            "25 Percentil"=>$value["per25"], 
                                            "Promedio"=>$value["prom"], 
                                            "Mediana"=>$value["med"], 
                                            "75 Percentil"=>$value["per75"], 
                                            "Max"=>$value["max"], 
                                            "Empresa"=>$value["empresa"] 
                                          );
            }
            $resultado = collect([  
                                    "detalle_universo"=> $detalleUniverso, 
                                    "detalle_nacional"=> $detalleNacional, 
                                    "detalleInternacional"=>$detalleInternacional]);

            return $resultado;         
        }
    }


}
