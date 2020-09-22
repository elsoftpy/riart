<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Cabecera_encuesta;
use App\Encuestas_cargo;
use App\Detalle_encuesta;
use App\Cargos_rubro;
use App\Empresa;
use App\Ficha_dato;
use App\Nivel;
use App\Nivel_en;
use App\Cargo;
use App\Cargo_en;
use App\Rubro;
use App\User;
use App\Detalle_encuestas_nivel;
use App\Traits\ClubsTrait;
use App\Traits\ReportTrait;
use PHPExcel_Worksheet_Drawing;
use Auth;
use Excel;
use Session;
use DB;



class ReporteController extends Controller 
{
    use ClubsTrait, ReportTrait;
    
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
        $locale = $this->getIdioma();

        $imagen = $this->club($empresa->rubro_id, true);

        if($id == '95'){
            $imagen = "images/ccfc-caratula.PNG";
        }

        return view('report.home')->with('dbEmpresa', $id)
                                  ->with('imagen', $imagen)
                                  ->with('club', $club)
                                  ->with('locale', $locale);
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

    public function conceptos($id){
        $dbEmpresa = $id;
        $empresa = Empresa::find($id);
        $rubro = $empresa->rubro_id;
        $club = $this->club($empresa->rubro_id);
        $locale = $this->getIdioma();
        if($locale == 'en'){
            return view('report.conceptos_en')->with('club', $club)->with('dbEmpresa', $dbEmpresa)->with('locale', $locale);
        }else{
            return view('report.conceptos')->with('club', $club)->with('dbEmpresa', $dbEmpresa)->with('locale', $locale);
        }
        
    }

    public function metodologia($id){
        $dbEmpresa = $id;
        $empresa = Empresa::find($id);
        $rubro = $empresa->rubro_id;
        $club = $this->club($empresa->rubro_id);
        $locale = $this->getIdioma();
        if($locale == 'en'){
            return view('report.metodologia_en')->with('club', $club)->with('dbEmpresa', $dbEmpresa)->with("locale", $locale);   
        }

        return view('report.metodologia')->with('club', $club)->with('dbEmpresa', $dbEmpresa)->with("locale", $locale);
    }

    public function ficha($id){
        $reporteEspecial = Session::get('especial');
        $dbEmpresa = $id;
        $empresa = Empresa::find($id);
        $rubro = $empresa->rubro_id;
        $subRubro = $empresa->sub_rubro_id;
        $locale = $this->getIdioma();
        
        if(Session::has('periodo')){
            $per = Session::get('periodo');
            $dbEncuesta = Cabecera_encuesta::where('empresa_id', $id)
                                           ->whereRaw("periodo = '". $per."'")
                                           ->first();
            $dbFicha = Ficha_dato::where('rubro_id', $rubro)
                                 ->where('periodo', $per)
                                 ->first();
            
            if($dbFicha){
                $periodo = $dbFicha->periodo;
            }else{
                $periodo = $per;
            }
            
        }else{
            $dbFicha = Ficha_dato::activa()
                                 ->where('rubro_id', $rubro)
                                 ->first();
            
            if($dbFicha){
                $periodo = $dbFicha->periodo;
                $dbEncuesta = Cabecera_encuesta::where('empresa_id', $id)
                                               ->where('periodo', $periodo)->first();    
            }else{
                $dbEncuesta = Cabecera_encuesta::where('empresa_id', $id)
                                               ->whereRaw('id = (select max(id) from cabecera_encuestas where empresa_id = '. $id.')')
                                               ->first();           
            }    
            
        }
        
        if($dbFicha){
            if($reporteEspecial){
                $cargos = $this->countEmergentesSegmento($rubro, $subRubro, $periodo);
            }else{
                $cargos = $dbFicha->cargos_emergentes;
            }
            
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
        if($reporteEspecial){
            $participantes = Cabecera_encuesta::where('periodo', $periodo)
                                              ->where('rubro_id', $rubro)
                                              ->where('sub_rubro_id', $subRubro)
                                              ->get();
        }else{
            $participantes = Cabecera_encuesta::where('periodo', $periodo)
                                               ->where('rubro_id', $rubro)
                                               ->get();
        }
        

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
                                   ->with('locale', $locale)
                                   ->with('participantes', $participantes);
    }

    


    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function filter($id)
    {
        $empresa = Empresa::find($id);
        $cargosRubros = Cargos_rubro::where('rubro_id', $empresa->rubro_id)->pluck('cargo_id');
        $nivelesId = array();
        foreach($cargosRubros as $cargoId){
            $cargo = Cargo::find($cargoId);
            $nivelesId[] = $cargo->nivel_id;
        }
        if($this->getIdioma() == "en"){
            $dbNiveles = Nivel_en::whereIn('id', $nivelesId)->pluck('descripcion', 'id');
            $dbCargos = Cargo_en::orderBy('descripcion')
                                ->whereIn('id', $cargosRubros)
                                ->pluck('descripcion', 'id');
        }else{
            $dbNiveles = Nivel::whereIn('id', $nivelesId)->pluck('descripcion', 'id');
            $dbCargos = Cargo::orderBy('descripcion')
                             ->whereIn('id', $cargosRubros)
                             ->pluck('descripcion', 'id');
        }
        $dbEmpresa = $id;
        return view('report.filter')->with('dbNiveles', $dbNiveles)->with('dbCargos', $dbCargos)->with('dbEmpresa', $dbEmpresa);
    }

    public function showCargosClub($id){
        $dbEmpresa = $id;
        $empresa = Empresa::find($id);
        $club = $this->club($empresa->rubro_id);
        $rubro = $empresa->rubro_id;
        $locale = $this->getIdioma();
        if(Session::has('periodo')){
            $per = Session::get('periodo');
            $dbEncuesta = Cabecera_encuesta::where('empresa_id', $id)->whereRaw("periodo = '". $per."'")->first();
        }else{
            $dbEncuesta = Cabecera_encuesta::where('empresa_id', $id)->whereRaw('id = (select max(id) from cabecera_encuestas where empresa_id = '. $id.')')->first();            
        }
        $periodo = $dbEncuesta->periodo;    // periodo de la encuesta actual
        
        $cargosIds = $this->getCargosHomologados($rubro, $periodo);

        $cargos = Cargos_rubro::where('rubro_id', $rubro)
                              ->whereIn('cargo_id', $cargosIds)->get();
        
        $cargos = $cargos->map(function($item) use($locale){
            if($locale == "es"){
                $item['nivel_id'] = $item->cargo->nivel->id;
                $item['descripcion'] = $item->cargo->descripcion;
            }else{
                $item['nivel_id'] = $item->cargoEn->nivel->id;
                $item['descripcion'] = $item->cargoEn->descripcion;
            }
            
            return $item;
        });
        $colNiveles = collect();
        foreach ($cargos as $key => $value) {
            $colNiveles->push($value->cargo->nivel->id);
        }
        if($locale == "es"){
            $niveles = Nivel::whereIn('id', $colNiveles->unique())->orderBy('descripcion')->get();
        }else{
            $niveles = Nivel_en::whereIn('id', $colNiveles->unique())->orderBy('descripcion')->get();
        }

        return view('report.cargos_club')->with('dbEmpresa', $dbEmpresa)
                                         ->with('club', $club)       
                                         ->with('niveles', $niveles)
                                         ->with('periodo', $periodo)
                                         ->with('cargos', $cargos);
    }


    public function cargoReport(Request $request){

        if(Session::get('especial')){
            return $this->cargoReportEspecial($request, "view");
        }else{
            return $this->cargoReportAll($request, "view");
        }
        
    }

    public function cargoReportExcel(Request $request){
        
        if(Session::get('especial')){
            return $this->cargoReportEspecial($request, "excel");
        }else{
            return $this->cargoReportAll($request, "excel");
        }
    }

    public function cargoReportClubExcel(Request $request){
        ini_set('max_execution_time', 0);
        // datos de la empresa del cliente
        $dbEmpresa = Empresa::find($request->empresa_id);   
        // si la variable de sesion "periodo" está cargada (solo se carga con navieras)
        if(Session::has('periodo')){
            $per = Session::get('periodo');
            $dbEncuesta = Cabecera_encuesta::where('empresa_id', $dbEmpresa->id)->whereRaw("periodo = '". $per."'")->first();
        }else{
            $dbFicha = Ficha_dato::activa()
                                 ->where('rubro_id', $dbEmpresa->rubro_id)
                                 ->first();
            if($dbFicha){
                $periodo = $dbFicha->periodo;
                $dbEncuesta = Cabecera_encuesta::where('empresa_id', $dbEmpresa->id)
                                               ->where('periodo', $periodo)->first();    
            }else{
                $dbEncuesta = Cabecera_encuesta::where('empresa_id', $dbEmpresa->id)
                                               ->whereRaw('id = (select max(id) from cabecera_encuestas where empresa_id = '. $dbEmpresa->id.')')
                                               ->first();
            }
            
        }
        $reporteEspecial = Session::get('especial');
        // periodo de la encuesta actual (semestral para navieras)
        $periodo = $dbEncuesta->periodo;    
        // rubro de la empresa del cliente        
        $rubro = $dbEmpresa->rubro_id;      
        
        $cargosIds = $this->getCargosHomologados($rubro, $periodo);

        $cargos = Cargos_rubro::where('rubro_id', $rubro)
                              ->whereIn('cargo_id', $cargosIds)
                              ->get();

        // variables de detalle para cada segmento
        $detalleUniverso = collect();
        $detalleNacional = collect();
        $detalleInternacional = collect();
        // Procesamiento por cargo
        foreach ($cargos as $cargo) {
            $request->request->add(["cargo_id"=> $cargo->cargo_id]);
            // procesamos el reporte
            if($reporteEspecial){
                $respuesta = $this->cargoReportEspecial($request, "clubExcel", true);
                $filename = 'Resultados_especial_'.$periodo;
            }else{
                $respuesta = $this->cargoReportAll($request, "clubExcel", true);
                $filename = 'Resultados_'.$periodo;
            }
            $encuestaCargo = Encuestas_cargo::where('cabecera_encuesta_id', $dbEncuesta->id)
                                            ->where('cargo_id', $cargo->cargo_id)
                                            ->where('incluir', 1)
                                            ->first();
            if($encuestaCargo){
                $descripcion = $encuestaCargo->descripcion;
            }else{
                $descripcion = 'N/A';
            }
            // preparamos los datos para el array final del cargo
            $itemArray = array( $descripcion, 
                                $cargo->cargo->descripcion, 
                              );
            $itemArrayNac = $itemArray;
            $itemArrayInt = $itemArray;
            // por cada item del detalle
            //$cantConceptos = 0;
            foreach ($respuesta as $key => $item) {
               // dd($item);
                switch ($key) {
                    case 'detalle_universo':
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
            //dd($itemArray);
            $detalleUniverso->push($itemArray);
            $detalleNacional->push($itemArrayNac);
            $detalleInternacional->push($itemArrayInt);                 
        }
        
        $this->excelClubCargos($detalleUniverso, $detalleNacional, $detalleInternacional, $rubro, $filename);

    }

    public function cargoReportClubEspecial(Request $request){

        ini_set('max_execution_time', 0);
        // datos de la empresa del cliente
        $dbEmpresa = Empresa::find($request->empresa_id);   
        // si la variable de sesion "periodo" está cargada (solo se carga con navieras)
        if(Session::has('periodo')){
            $per = Session::get('periodo');
            $dbEncuesta = Cabecera_encuesta::where('empresa_id', $dbEmpresa->id)
                                           ->where('periodo', $per)
                                           ->first();
        }else{
            $dbEncuesta = Cabecera_encuesta::where('empresa_id', $dbEmpresa->id)
                                           ->whereRaw('id = (select max(id) from cabecera_encuestas where empresa_id = '. $dbEmpresa->id.')')
                                           ->first();            
        }
        // periodo de la encuesta actual (semestral para navieras)
        $periodo = $dbEncuesta->periodo;
        
        $periodoArray = explode("/", $periodo);
        $yearAnt = intval($periodoArray[1]) - 1;
        $periodoAnt = $periodoArray[0]."/".$yearAnt;
        // buscamos la encuesta del año pasado
        $dbEncuestaAnt = Cabecera_encuesta::where('empresa_id', $dbEmpresa->id)
                                          ->where('periodo', $periodoAnt)
                                          ->first();
        
        if(!$dbEncuestaAnt){
            $dbEncuestaAnt = Cabecera_encuesta::where('empresa_id', $dbEmpresa->id)
                                              ->where('id', '<', $dbEncuesta->id)  
                                              ->orderBy('id', 'DESC')    
                                              ->first();
        }

        $dbEncuestasId = Cabecera_encuesta::where('periodo', $periodo)
                                          ->pluck('id');

        // rubro de la empresa del cliente        
        $rubro = $dbEmpresa->rubro_id;  
        
        $cargosTrip = Cargo::where('area_id', 57)
                           ->pluck('id');

        $encuestasId = Cabecera_encuesta::where('rubro_id', $dbEncuesta->rubro_id)
                                        ->where('periodo', $dbEncuesta->periodo)
                                        ->pluck('id');
        
        // recupera los cargos del periodo para todos los que tengan homologación
        $encuestasCargos = Encuestas_cargo::whereIn('cabecera_encuesta_id', $encuestasId)
                                          ->whereIn('cargo_id', $cargosTrip)
                                          ->whereNotNull('cargo_id')
                                          ->where('incluir', 1)
                                          ->get();
        //dd($encuestasCargos->where('cargo_id', 538));
        // variables de detalle para cada segmento
        $salarioBase = collect();
        $efectivoTotalAnual = collect();
        $variable = collect();
        $variableViaje = collect();
        $salBaseVarViaje = collect();
        $adicionalTotal = collect();
        $contratoNuevo = collect();

        if($request->usd){
            $convertir = 1;
            $filename = 'Comparativo_interanual_usd_'.$periodo.'_'.$periodoAnt;
        }else{
            $convertir = 0;
            $filename = 'Comparativo_interanual_'.$periodo.'_'.$periodoAnt;
        }

        // Procesamiento por cargo
        foreach ($encuestasCargos as $encuestaCargo) {
            $cargoId = $encuestaCargo->cargo->id;
            // procesamos el reporte
            if(!$encuestaCargo->es_contrato_periodo){
                $respuesta = $this->cargoComparativoEspecial($dbEncuesta, $dbEncuestaAnt, $dbEmpresa, $cargoId, $convertir, false);
            }else{
                $respuesta = $this->cargoComparativoEspecial($dbEncuesta, $dbEncuestaAnt, $dbEmpresa, $cargoId, $convertir, true);
            }         
            
            // preparamos los datos para el array final del cargo
            $itemArray = array( $encuestaCargo->cargo->descripcion);
            $itemArrayETA = array( $encuestaCargo->cargo->descripcion);
            $itemArrayVar = array( $encuestaCargo->cargo->descripcion);
            $itemArrayVarViaje = array( $encuestaCargo->cargo->descripcion);
            $itemArraySBVV = array( $encuestaCargo->cargo->descripcion);
            $itemArrayATA = array( $encuestaCargo->cargo->descripcion);
            if($encuestaCargo->es_contrato_periodo){
                $itemArrayContNuevo = array($encuestaCargo->cargo->descripcion);
            }
            // por cada item del detalle
            foreach ($respuesta as $key => $item) {
                
                    switch ($key) {
                        case 'detalle_salario_base':
                            
                            if($encuestaCargo->es_contrato_periodo){
                                $this->CargaDetalleComparativo($item[0], $itemArrayContNuevo, 1);     
                            }else{
                                $this->CargaDetalleComparativo($item[0], $itemArray, 0);    
                            }
                            break;

                        case 'detalle_efectivo_total_anual':
                            $this->CargaDetalleComparativo($item[0], $itemArrayETA, 0);            
                            break;
                        case 'detalle_variable':
                            $this->CargaDetalleComparativo($item[0], $itemArrayVar, 0);            
                            break;
                        case 'detalle_variable_viaje':
                            $this->CargaDetalleComparativo($item[0], $itemArrayVarViaje, 0);            
                            break;
                        case 'detalle_sb_vv':
                            $this->CargaDetalleComparativo($item[0], $itemArraySBVV, 0);            
                            break;
                        case 'detalle_adicional_total_anual':
                            $this->CargaDetalleComparativo($item[0], $itemArrayATA, 0);            
                            break;                                                
                    }
                
            }
            /* if( count($itemArray)  == 1){
                dd($itemArray, $encuestaCargo);
            } */
            
            if($itemArrayETA[12] > 0){
                $efectivoTotalAnual->push($itemArrayETA);
            }            
            if($itemArrayVar[12] > 0){
                $variable->push($itemArrayVar);
            }
            if($itemArrayVarViaje[12] > 0){
                $variableViaje->push($itemArrayVarViaje);
            }
            if($itemArraySBVV[12] > 0){
                $salBaseVarViaje->push($itemArraySBVV);
            }
            if($itemArrayATA[12] > 0){
                $adicionalTotal->push($itemArrayATA);
            }
            
            if($encuestaCargo->es_contrato_periodo){
                if($itemArrayContNuevo[7] > 0){
                    $contratoNuevo->push($itemArrayContNuevo);
                }
                
            }else{
                if($itemArray[12] > 0){
                    $salarioBase->push($itemArray);
                }
            }
        }
        
        Excel::create($filename, function($excel) use(  $salarioBase, 
                                                        $efectivoTotalAnual, 
                                                        $variable, 
                                                        $variableViaje,
                                                        $salBaseVarViaje, 
                                                        $adicionalTotal, 
                                                        $contratoNuevo, 
                                                        $periodo, 
                                                        $periodoAnt, 
                                                        $convertir
                                                     ) {
            $excel->sheet("Salario Base", function($sheet) use($salarioBase, $periodo, $periodoAnt, $convertir){
                $salarioBase = $salarioBase->reject(function($item){
                                    if(count($item) < 13){
                                        return $item;
                                    }
                                    
                                    return false;
                                });
                $objDrawing = new PHPExcel_Worksheet_Drawing;
                $objDrawing->setPath(public_path('images/logo.jpg')); //your image path
                $objDrawing->setCoordinates('A1');
                $objDrawing->setWidthAndHeight(304,60);
                $objDrawing->setWorksheet($sheet);            

                
                if($convertir){
                    $titulo = 'COMPARATIVO INTERANUAL SALARIO BASE USD';
                }else{
                    $titulo = 'COMPARATIVO INTERANUAL SALARIO BASE';
                }
                // Título
                $sheet->cell('A5', function($cell) use($titulo){
                    $cell->setValue($titulo);
                });
                $sheet->mergeCells('A5:S5');
                $sheet->cells('A5:S5', function($cells){
                    $cells->setBackground('#0288d1');
                    $cells->setFontColor("#FFFFFF");
                    $cells->setFontWeight("bold");
                   // $cells->setValignment('center');
                    $cells->setAlignment('center');
                });

                $sheet->cell('B6', function($cell) use($periodoAnt){
                    $cell->setValue('PERIODO '.$periodoAnt);
                });
                $sheet->mergeCells('B6:G6');
                $sheet->cells('B6:G6', function($cells){
                    $cells->setBackground('#7cb342');
                    $cells->setFontColor("#FFFFFF");
                    $cells->setFontWeight("bold");
                    $cells->setAlignment('center');
                });

                $sheet->cell('H6', function($cell) use($periodo){
                    $cell->setValue('PERIODO '.$periodo);
                });
                $sheet->mergeCells('H6:M6');
                $sheet->cells('H6:M6', function($cells){
                    $cells->setBackground('#ffb300');
                    $cells->setFontColor("#FFFFFF");
                    $cells->setFontWeight("bold");
                    $cells->setAlignment('center');
                });

                $sheet->cell('N6', function($cell) use($periodo, $periodoAnt){
                    $cell->setValue('BRECHA '.$periodo.' vs. '.$periodoAnt);
                });
                $sheet->mergeCells('N6:S6');
                $sheet->cells('N6:S6', function($cells){
                    $cells->setBackground('#ff7043');
                    $cells->setFontColor("#FFFFFF");
                    $cells->setFontWeight("bold");
                    $cells->setAlignment('center');
                });
                

                $topeHeader = 3;
                $rango = 'A7:S7';
                       
                $itemsHeader = array("Mínimo", "25 Perc.", "Promedio", "Mediana", "75 Perc.", "Máximo");
                $cargoHeader = array("Cargo Oficial");

                for ($i= 0; $i < $topeHeader; $i++) {
                    foreach ($itemsHeader as $key => $value) {
                        array_push($cargoHeader, $value);
                    }
                    
                }
                   
                $sheet->row(7, $cargoHeader);
                $sheet->rows($salarioBase->unique());
                $sheet->cells($rango, function($cells){
                    $cells->setBackground('#a7ffeb');
                });                 
                $sheet->setFreeze('A7');  
            });

            $excel->sheet("Efectivo Total Anual", function($sheet) use($efectivoTotalAnual, $periodo, $periodoAnt, $convertir){
                
                $objDrawing = new PHPExcel_Worksheet_Drawing;
                $objDrawing->setPath(public_path('images/logo.jpg')); //your image path
                $objDrawing->setCoordinates('A1');
                $objDrawing->setWidthAndHeight(304,60);
                $objDrawing->setWorksheet($sheet);            

                
                if($convertir){
                    $titulo = 'COMPARATIVO INTERANUAL EFECTIVO TOTAL ANUAL USD';
                }else{
                    $titulo = 'COMPARATIVO INTERANUAL EFECTIVO TOTAL ANUAL';
                }
                // Título
                $sheet->cell('A5', function($cell) use($titulo){
                    $cell->setValue($titulo);
                });
               
                $sheet->mergeCells('A5:S5');
                $sheet->cells('A5:S5', function($cells){
                    $cells->setBackground('#0288d1');
                    $cells->setFontColor("#FFFFFF");
                    $cells->setFontWeight("bold");
                   // $cells->setValignment('center');
                    $cells->setAlignment('center');
                });

                $sheet->cell('B6', function($cell) use($periodoAnt){
                    $cell->setValue('PERIODO '.$periodoAnt);
                });
                $sheet->mergeCells('B6:G6');
                $sheet->cells('B6:G6', function($cells){
                    $cells->setBackground('#7cb342');
                    $cells->setFontColor("#FFFFFF");
                    $cells->setFontWeight("bold");
                   // $cells->setValignment('center');
                    $cells->setAlignment('center');
                });

                $sheet->cell('H6', function($cell) use($periodo){
                    $cell->setValue('PERIODO '.$periodo);
                });
                $sheet->mergeCells('H6:M6');
                $sheet->cells('H6:M6', function($cells){
                    $cells->setBackground('#ffb300');
                    $cells->setFontColor("#FFFFFF");
                    $cells->setFontWeight("bold");
                   // $cells->setValignment('center');
                    $cells->setAlignment('center');
                });

                $sheet->cell('N6', function($cell) use($periodo, $periodoAnt){
                    $cell->setValue('BRECHA '.$periodo.' vs. '.$periodoAnt);
                });
                $sheet->mergeCells('N6:S6');
                $sheet->cells('N6:S6', function($cells){
                    $cells->setBackground('#ff7043');
                    $cells->setFontColor("#FFFFFF");
                    $cells->setFontWeight("bold");
                    $cells->setAlignment('center');
                });

                $topeHeader = 3;
                $rango = 'A7:S7';
                       
                $itemsHeader = array("Mínimo", "25 Perc.", "Promedio", "Mediana", "75 Perc.", "Máximo");
                $cargoHeader = array("Cargo Oficial");

                for ($i= 0; $i < $topeHeader; $i++) {
                    foreach ($itemsHeader as $key => $value) {
                        array_push($cargoHeader, $value);
                    }
                    
                }
                   
                $sheet->row(7, $cargoHeader);
                $sheet->rows($efectivoTotalAnual->unique());
                $sheet->cells($rango, function($cells){
                    $cells->setBackground('#a7ffeb');
                });                 
                $sheet->setFreeze('A7');  
            });     
            
            $excel->sheet("Variable", function($sheet) use($variable, $periodo, $periodoAnt, $convertir){
                
                $objDrawing = new PHPExcel_Worksheet_Drawing;
                $objDrawing->setPath(public_path('images/logo.jpg')); //your image path
                $objDrawing->setCoordinates('A1');
                $objDrawing->setWidthAndHeight(304,60);
                $objDrawing->setWorksheet($sheet);            

                
                
                if($convertir){
                    $titulo = 'COMPARATIVO INTERANUAL VARIABLE ANUAL USD';
                }else{
                    $titulo = 'COMPARATIVO INTERANUAL VARIABLE ANUAL';
                }
                // Título
                $sheet->cell('A5', function($cell) use($titulo) {
                    $cell->setValue($titulo);
                });

                $sheet->mergeCells('A5:S5');
                $sheet->cells('A5:S5', function($cells){
                    $cells->setBackground('#0288d1');
                    $cells->setFontColor("#FFFFFF");
                    $cells->setFontWeight("bold");
                   // $cells->setValignment('center');
                    $cells->setAlignment('center');
                });

                $sheet->cell('B6', function($cell) use($periodoAnt){
                    $cell->setValue('PERIODO '.$periodoAnt);
                });
                $sheet->mergeCells('B6:G6');
                $sheet->cells('B6:G6', function($cells){
                    $cells->setBackground('#7cb342');
                    $cells->setFontColor("#FFFFFF");
                    $cells->setFontWeight("bold");
                   // $cells->setValignment('center');
                    $cells->setAlignment('center');
                });

                $sheet->cell('H6', function($cell) use($periodo){
                    $cell->setValue('PERIODO '.$periodo);
                });
                $sheet->mergeCells('H6:M6');
                $sheet->cells('H6:M6', function($cells){
                    $cells->setBackground('#ffb300');
                    $cells->setFontColor("#FFFFFF");
                    $cells->setFontWeight("bold");
                   // $cells->setValignment('center');
                    $cells->setAlignment('center');
                });

                $sheet->cell('N6', function($cell) use($periodo, $periodoAnt){
                    $cell->setValue('BRECHA '.$periodo.' vs. '.$periodoAnt);
                });
                $sheet->mergeCells('N6:S6');
                $sheet->cells('N6:S6', function($cells){
                    $cells->setBackground('#ff7043');
                    $cells->setFontColor("#FFFFFF");
                    $cells->setFontWeight("bold");
                    $cells->setAlignment('center');
                });

                $topeHeader = 3;
                $rango = 'A7:S7';
                       
                $itemsHeader = array("Mínimo", "25 Perc.", "Promedio", "Mediana", "75 Perc.", "Máximo");
                $cargoHeader = array("Cargo Oficial");

                for ($i= 0; $i < $topeHeader; $i++) {
                    foreach ($itemsHeader as $key => $value) {
                        array_push($cargoHeader, $value);
                    }
                    
                }
                   
                $sheet->row(7, $cargoHeader);
                $sheet->rows($variable->unique());
                $sheet->cells($rango, function($cells){
                    $cells->setBackground('#a7ffeb');
                });                 
                $sheet->setFreeze('A7');  
            });
            
            $excel->sheet("Variable Viaje", function($sheet) use($variableViaje, $periodo, $periodoAnt, $convertir){
                
                $objDrawing = new PHPExcel_Worksheet_Drawing;
                $objDrawing->setPath(public_path('images/logo.jpg')); //your image path
                $objDrawing->setCoordinates('A1');
                $objDrawing->setWidthAndHeight(304,60);
                $objDrawing->setWorksheet($sheet);            

                
                
                if($convertir){
                    $titulo = 'COMPARATIVO INTERANUAL VARIABLE POR VIAJE USD';
                }else{
                    $titulo = 'COMPARATIVO INTERANUAL VARIABLE POR VIAJE ANUAL';
                }
                // Título
                $sheet->cell('A5', function($cell) use($titulo){
                    $cell->setValue($titulo);
                });

                $sheet->mergeCells('A5:S5');
                $sheet->cells('A5:S5', function($cells){
                    $cells->setBackground('#0288d1');
                    $cells->setFontColor("#FFFFFF");
                    $cells->setFontWeight("bold");
                   // $cells->setValignment('center');
                    $cells->setAlignment('center');
                });

                $sheet->cell('B6', function($cell) use($periodoAnt){
                    $cell->setValue('PERIODO '.$periodoAnt);
                });
                $sheet->mergeCells('B6:G6');
                $sheet->cells('B6:G6', function($cells){
                    $cells->setBackground('#7cb342');
                    $cells->setFontColor("#FFFFFF");
                    $cells->setFontWeight("bold");
                   // $cells->setValignment('center');
                    $cells->setAlignment('center');
                });

                $sheet->cell('H6', function($cell) use($periodo){
                    $cell->setValue('PERIODO '.$periodo);
                });
                $sheet->mergeCells('H6:M6');
                $sheet->cells('H6:M6', function($cells){
                    $cells->setBackground('#ffb300');
                    $cells->setFontColor("#FFFFFF");
                    $cells->setFontWeight("bold");
                   // $cells->setValignment('center');
                    $cells->setAlignment('center');
                });

                $sheet->cell('N6', function($cell) use($periodo, $periodoAnt){
                    $cell->setValue('BRECHA '.$periodo.' vs. '.$periodoAnt);
                });
                $sheet->mergeCells('N6:S6');
                $sheet->cells('N6:S6', function($cells){
                    $cells->setBackground('#ff7043');
                    $cells->setFontColor("#FFFFFF");
                    $cells->setFontWeight("bold");
                    $cells->setAlignment('center');
                });

                $topeHeader = 3;
                $rango = 'A7:S7';
                       
                $itemsHeader = array("Mínimo", "25 Perc.", "Promedio", "Mediana", "75 Perc.", "Máximo");
                $cargoHeader = array("Cargo Oficial");

                for ($i= 0; $i < $topeHeader; $i++) {
                    foreach ($itemsHeader as $key => $value) {
                        array_push($cargoHeader, $value);
                    }
                    
                }
                   
                $sheet->row(7, $cargoHeader);
                $sheet->rows($variableViaje->unique());
                $sheet->cells($rango, function($cells){
                    $cells->setBackground('#a7ffeb');
                });                 
                $sheet->setFreeze('A7');  
            });

            $excel->sheet("SB + VV", function($sheet) use($salBaseVarViaje, $periodo, $periodoAnt, $convertir){
                
                $objDrawing = new PHPExcel_Worksheet_Drawing;
                $objDrawing->setPath(public_path('images/logo.jpg')); //your image path
                $objDrawing->setCoordinates('A1');
                $objDrawing->setWidthAndHeight(304,60);
                $objDrawing->setWorksheet($sheet);            

                
                
                if($convertir){
                    $titulo = 'COMPARATIVO SALARIO BASE + VARIABLE POR VIAJE USD';
                }else{
                    $titulo = 'COMPARATIVO SALARIO BASE + VARIABLE POR VIAJE';
                }
                // Título
                $sheet->cell('A5', function($cell) use($titulo){
                    $cell->setValue($titulo);
                });

                $sheet->mergeCells('A5:S5');
                $sheet->cells('A5:S5', function($cells){
                    $cells->setBackground('#0288d1');
                    $cells->setFontColor("#FFFFFF");
                    $cells->setFontWeight("bold");
                   // $cells->setValignment('center');
                    $cells->setAlignment('center');
                });

                $sheet->cell('B6', function($cell) use($periodoAnt){
                    $cell->setValue('PERIODO '.$periodoAnt);
                });
                $sheet->mergeCells('B6:G6');
                $sheet->cells('B6:G6', function($cells){
                    $cells->setBackground('#7cb342');
                    $cells->setFontColor("#FFFFFF");
                    $cells->setFontWeight("bold");
                   // $cells->setValignment('center');
                    $cells->setAlignment('center');
                });

                $sheet->cell('H6', function($cell) use($periodo){
                    $cell->setValue('PERIODO '.$periodo);
                });
                $sheet->mergeCells('H6:M6');
                $sheet->cells('H6:M6', function($cells){
                    $cells->setBackground('#ffb300');
                    $cells->setFontColor("#FFFFFF");
                    $cells->setFontWeight("bold");
                   // $cells->setValignment('center');
                    $cells->setAlignment('center');
                });

                $sheet->cell('N6', function($cell) use($periodo, $periodoAnt){
                    $cell->setValue('BRECHA '.$periodo.' vs. '.$periodoAnt);
                });
                $sheet->mergeCells('N6:S6');
                $sheet->cells('N6:S6', function($cells){
                    $cells->setBackground('#ff7043');
                    $cells->setFontColor("#FFFFFF");
                    $cells->setFontWeight("bold");
                    $cells->setAlignment('center');
                });

                $topeHeader = 3;
                $rango = 'A7:S7';
                       
                $itemsHeader = array("Mínimo", "25 Perc.", "Promedio", "Mediana", "75 Perc.", "Máximo");
                $cargoHeader = array("Cargo Oficial");

                for ($i= 0; $i < $topeHeader; $i++) {
                    foreach ($itemsHeader as $key => $value) {
                        array_push($cargoHeader, $value);
                    }
                    
                }
                   
                $sheet->row(7, $cargoHeader);
                $sheet->rows($salBaseVarViaje->unique());
                $sheet->cells($rango, function($cells){
                    $cells->setBackground('#a7ffeb');
                });                 
                $sheet->setFreeze('A7');  
            });

            $excel->sheet("Adicional Total Anual", function($sheet) use($adicionalTotal, $periodo, $periodoAnt, $convertir){
                
                $objDrawing = new PHPExcel_Worksheet_Drawing;
                $objDrawing->setPath(public_path('images/logo.jpg')); //your image path
                $objDrawing->setCoordinates('A1');
                $objDrawing->setWidthAndHeight(304,60);
                $objDrawing->setWorksheet($sheet);            

                
                
                if($convertir){
                    $titulo = 'COMPARATIVO INTERANUAL ADICIONAL TOTAL ANUAL USD';
                }else{
                    $titulo = 'COMPARATIVO INTERANUAL ADICIONAL TOTAL ANUAL';
                }
                // Título
                $sheet->cell('A5', function($cell) use($titulo){
                    $cell->setValue($titulo);
                });
                $sheet->mergeCells('A5:S5');
                $sheet->cells('A5:S5', function($cells){
                    $cells->setBackground('#0288d1');
                    $cells->setFontColor("#FFFFFF");
                    $cells->setFontWeight("bold");
                   // $cells->setValignment('center');
                    $cells->setAlignment('center');
                });

                $sheet->cell('B6', function($cell) use($periodoAnt){
                    $cell->setValue('PERIODO '.$periodoAnt);
                });
                $sheet->mergeCells('B6:G6');
                $sheet->cells('B6:G6', function($cells){
                    $cells->setBackground('#7cb342');
                    $cells->setFontColor("#FFFFFF");
                    $cells->setFontWeight("bold");
                   // $cells->setValignment('center');
                    $cells->setAlignment('center');
                });

                $sheet->cell('H6', function($cell) use($periodo){
                    $cell->setValue('PERIODO '.$periodo);
                });
                $sheet->mergeCells('H6:M6');
                $sheet->cells('H6:M6', function($cells){
                    $cells->setBackground('#ffb300');
                    $cells->setFontColor("#FFFFFF");
                    $cells->setFontWeight("bold");
                   // $cells->setValignment('center');
                    $cells->setAlignment('center');
                });

                $sheet->cell('N6', function($cell) use($periodo, $periodoAnt){
                    $cell->setValue('BRECHA '.$periodo.' vs. '.$periodoAnt);
                });
                $sheet->mergeCells('N6:S6');
                $sheet->cells('N6:S6', function($cells){
                    $cells->setBackground('#ff7043');
                    $cells->setFontColor("#FFFFFF");
                    $cells->setFontWeight("bold");
                    $cells->setAlignment('center');
                });

                $topeHeader = 3;
                $rango = 'A7:S7';
                       
                $itemsHeader = array("Mínimo", "25 Perc.", "Promedio", "Mediana", "75 Perc.", "Máximo");
                $cargoHeader = array("Cargo Oficial");

                for ($i= 0; $i < $topeHeader; $i++) {
                    foreach ($itemsHeader as $key => $value) {
                        array_push($cargoHeader, $value);
                    }
                    
                }
                   
                $sheet->row(7, $cargoHeader);
                $sheet->rows($adicionalTotal->unique());
                $sheet->cells($rango, function($cells){
                    $cells->setBackground('#a7ffeb');
                });                 
                $sheet->setFreeze('A7');  
            }); 

            $excel->sheet("Tripulacion Incorporada", function($sheet) use($contratoNuevo, $periodo, $convertir){
                
                $objDrawing = new PHPExcel_Worksheet_Drawing;
                $objDrawing->setPath(public_path('images/logo.jpg')); //your image path
                $objDrawing->setCoordinates('A1');
                $objDrawing->setWidthAndHeight(304,60);
                $objDrawing->setWorksheet($sheet);            

                
                
                if($convertir){
                    $titulo = 'TRIPULACION INCORPORADA EN EL PERIODO USD';
                }else{
                    $titulo = 'TRIPULACION INCORPORADA EN EL PERIODO';
                }
                // Título
                $sheet->cell('A5', function($cell) use($titulo){
                    $cell->setValue($titulo);
                });
                $sheet->mergeCells('A5:H5');
                $sheet->cells('A5:H5', function($cells){
                    $cells->setBackground('#0288d1');
                    $cells->setFontColor("#FFFFFF");
                    $cells->setFontWeight("bold");
                   // $cells->setValignment('center');
                    $cells->setAlignment('center');
                });

                

                $sheet->cell('B6', function($cell) use($periodo){
                    $cell->setValue('PERIODO '.$periodo);
                });
                $sheet->mergeCells('B6:H6');
                $sheet->cells('B6:H6', function($cells){
                    $cells->setBackground('#ffb300');
                    $cells->setFontColor("#FFFFFF");
                    $cells->setFontWeight("bold");
                   // $cells->setValignment('center');
                    $cells->setAlignment('center');
                });

                $topeHeader = 1;
                $rango = 'A7:H7';
                       
                $itemsHeader = array("Ocupantes","Mínimo", "25 Perc.", "Promedio", "Mediana", "75 Perc.", "Máximo");
                $cargoHeader = array("Cargo Oficial");

                for ($i= 0; $i < $topeHeader; $i++) {
                    foreach ($itemsHeader as $key => $value) {
                        array_push($cargoHeader, $value);
                    }
                    
                }
                   
                $sheet->row(7, $cargoHeader);
                $sheet->rows($contratoNuevo->unique());
                $sheet->cells($rango, function($cells){
                    $cells->setBackground('#a7ffeb');
                });                 
                $sheet->setFreeze('A7');  
            });
            $excel->setActiveSheetIndex(0);    
        })->export('xlsx');
    }

    public function nivelReportClubExcel(Request $request){
        ini_set('max_execution_time', 0);
        // periodo de la encuesta actual (semestral para navieras)
        $periodo = $request->periodo;    
        // rubro de la empresa del cliente        
        $rubro = $request->rubro_id;
        
        $encuestasIDs = Cabecera_encuesta::where('periodo', $periodo)
                                            ->where('rubro_id', $rubro)
                                            ->pluck('id');
        // recupera los cargos del periodo para todos los que tengan homologación con niveles
        $nivelesCargos = Detalle_encuestas_nivel::distinct()
                                          ->whereIn('cabecera_encuesta_id', $encuestasIDs)
                                          ->whereNotNull('cargo_oficial_id')
                                          ->pluck('nivel_oficial_id');
        // variables de detalle para cada segmento
        $detalleUniverso = collect();
        $detalleNacional = collect();
        $detalleInternacional = collect();
        
        $detalleInternacional = collect();
        // Procesamiento por cargo
        foreach ($nivelesCargos as $value) {
            $request->request->add(["nivel_id"=> $value]);
            // procesamos el reporte
            $respuesta = $this->nivelReport($request, $encuestasIDs, true);
            $nivel = Nivel::find($value);
            // preparamos los datos para el array final del cargo
            $itemArray = array( $nivel->descripcion, 
                                $nivel->descripcion, 
                              );
            $itemArrayNac = $itemArray;
            $itemArrayInt = $itemArray;
            // por cada item del detalle
            foreach ($respuesta as $key => $item) {
                
                 switch ($key) {
                    
                    case 'detalle_universo':
                        $this->cargaDetalleNivel($item, $itemArray);            
                        break;
                    case 'detalle_nacional':
                        $this->cargaDetalleNivel($item, $itemArrayNac);            
                        break;
                    case 'detalleInternacional':
                        $this->cargaDetalleNivel($item, $itemArrayInt);            
                        break;                                                
                } 

            }
            
            $detalleUniverso->push($itemArray);
            $detalleNacional->push($itemArrayNac);
            $detalleInternacional->push($itemArrayInt);                 
        }
    
 
        $filename = 'Resultados_Niveles'.$periodo;

        $this->excelNivelClub($detalleUniverso, $detalleNacional, $detalleInternacional, $rubro, $filename);

    }   
    
    public function cargosReportExcel(Request $request){
        ini_set('max_execution_time', 0);
        $cubo = true;
        // periodo de la encuesta actual (semestral para navieras)
        $periodo = $request->periodo;    
        // rubro de la empresa del cliente        
        $rubro = $request->rubro_id;
        
        $cargosIds = $this->getCargosHomologados($rubro, $periodo);

        $cargos = Cargos_rubro::where('rubro_id', $rubro)
                              ->whereIn('cargo_id', $cargosIds)
                              ->get();
        $empresa = Empresa::where('rubro_id', $rubro)
                          ->first()
                          ->id;
        // variables de detalle para cada segmento
        $detalleUniverso = collect();
        $detalleNacional = collect();
        $detalleInternacional = collect();
        // Procesamiento por cargo
        foreach ($cargos as $cargo) {
            $request->request->add(["cargo_id"=> $cargo->cargo_id, "empresa_id" => $empresa]);
            // procesamos el reporte
            $respuesta = $this->cargoReportAll($request, "clubExcel", true);
            $filename = 'Cubo_Resultados_'.$periodo;

            // preparamos los datos para el array final del cargo
            $itemArray = array( //$descripcion, 
                                $cargo->cargo->descripcion, 
                              );
            $itemArrayNac = $itemArray;
            $itemArrayInt = $itemArray;
            // por cada item del detalle
            //$cantConceptos = 0;
            foreach ($respuesta as $key => $item) {
                switch ($key) {
                    case 'detalle_universo':
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
        
        $this->excelClubCargos($detalleUniverso, $detalleNacional, $detalleInternacional, $rubro, $filename, $cubo);

    }
    

    public function resultados(){
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
                         salario_base, salario_base * 12 salario_anual, gratificacion, aguinaldo, comision, plus_rendimiento, variable_viaje, fallo_caja,
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
                   where c.periodo = :periodo
                     and c.rubro_id = :rubro
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
        $dbDetalle = DB::select($query, ["periodo" => $periodo, "rubro" => $rubro]);
        $detalle = array();
        foreach ($dbDetalle as $key => $item) {
            
            // Efectivo Anual Garantizado
            $efectivoAnual = $item->salario_anual + 
                             $item->gratificacion + 
                             $item->aguinaldo;

            // Total Adicional Anual
            if($rubro == 4){
                $adicional = $item->adicional_amarre + 
                $item->adicional_tipo_combustible + 
                $item->adicional_embarque + 
                $item->adicional_carga +
                ( ( $item->fallo_caja + 
                $item->fallo_caja_ext + 
                $item->gratificacion_contrato + 
                $item->adicional_nivel_cargo + 
                $item->adicional_titulo) * 12);

            }elseif($rubro == 1){
                $adicional = ( $item->fallo_caja + 
                $item->fallo_caja_ext + 
                $item->gratificacion_contrato + 
                $item->adicional_nivel_cargo + 
                $item->adicional_titulo) * 12;

            }else{
                $adicional = ( $item->fallo_caja + 
                $item->fallo_caja_ext + 
                $item->gratificacion_contrato + 
                $item->adicional_nivel_cargo + 
                $item->adicional_titulo) * 12;

            }

            // Efectivo Total Anual
            if($rubro == 4){
                $efectivoTotalAnual = $item->salario_anual +
                                      $item->aguinaldo; 
            }else{
                $efectivoTotalAnual = $efectivoAnual + 
                                      $adicional + 
                                      ( $item->comision * 12 ) +
                                      $item->bono_anual;
            }
            // Aguinaldo impactado
            if($rubro == 1){
                $aguinaldoImpactado = round( (  $item->salario_anual + 
                                                $item->gratificacion + 
                                                $adicional + 
                                                ( $item->comision * 12 ) +
                                                $item->bono_anual ) / 12, 0);
            }elseif($rubro == 4){
                $aguinaldoImpactado = round( (  $item->salario_anual + 
                                                $item->gratificacion + 
                                                $adicional +
                                                ( $item->comision * 12 ) +
                                                $item->bono_anual ) / 12, 0);
            }else{
                $aguinaldoImpactado = round( (  $item->salario_anual + 
                                                $item->gratificacion + 
                                                $adicional + 
                                                ( $item->comision * 12 ) +
                                                $item->bono_anual ) / 12, 0);
            }
           
            // Total Compensación Efectiva Anual
            $compensacionEfectiva = ( $item->salario_anual + 
                                      $item->gratificacion + 
                                      $item->aguinaldo + 
                                      $adicional + 
                                      $item->bono_anual );   
            
            $totalBeneficios =  $item->refrigerio * 12 + 
                                ($item->costo_seguro_medico * ($item->cobertura_seguro_medico/100)) * 12 + 
                                $item->costo_seguro_vida * 12 + 
                                ($item->monto_movil / 60) * 12 +
                                $item->gratificacion_contrato * 12+
                                $item->monto_tarjeta_flota * 12+
                                $item->seguro_movil * 12+
                                $item->monto_ayuda_escolar +
                                $item->monto_comedor_interno * 12+
                                ($item->monto_curso_idioma * ($item->cobertura_curso_idioma/100)) * 12 +
                                ($item->monto_post_grado * ($item->cobertura_post_grado/100))/ 2 +
                                $item->monto_celular_corporativo * 12 +
                                $item->monto_vivienda * 12+
                                $item->monto_colegiatura_hijos;
         
                        
         
            if($rubro == 1){
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
                                    "Salario Anual"=>$item->salario_anual,
                                    "Gratificación"=> $item->gratificacion, 
                                    "Aguinaldo"=> $item->aguinaldo,
                                    "Efectivo Anual Garantizado" => $efectivoAnual,
                                    "Comision"=> $item->comision, 
                                    "Fallo Caja"=> $item->fallo_caja,
                                    "Fallo Caja Ext."=> $item->fallo_caja_ext, 
                                    "Gratif. Contrato"=>$item->gratificacion_contrato, 
                                    "Adicional Nivel Cargo"=>$item->adicional_nivel_cargo, 
                                    "Adicional Título"=>$item->adicional_titulo,
                                    "Total Adicional"=>$adicional,
                                    "Bono Anual"=>$item->bono_anual, 
                                    "Incentivo a Largo Plazo"=>$item->incentivo_largo_plazo, 
                                    "Refrigerio"=>$item->refrigerio, 
                                    "Costo Seguro Médico"=>$item->costo_seguro_medico, 
                                    "Cobertura Seguro Médico"=>$item->cobertura_seguro_medico, 
                                    "Costo Seguro Vida"=>$item->costo_seguro_vida, 
                                    "Car Company"=>$item->car_company, 
                                    "Movilidad Full"=>$item->movilidad_full, 
                                    "Monto Tarj. Flota"=>$item->monto_tarjeta_flota, 
                                    "Tarj. Flota"=>$item->tarjeta_flota, 
                                    "Monto Automóvil"=>$item->monto_movil, 
                                    "Seguro Automóvil"=>$item->seguro_movil, 
                                    "Mantenimiento Automóvil"=>$item->mantenimiento_movil, 
                                    "Km recorrido"=>$item->monto_km_recorrido, 
                                    "Ayuda Escolar"=>$item->monto_ayuda_escolar, 
                                    "Comedor Interno"=>$item->monto_comedor_interno, 
                                    "Curso Idioma"=>$item->monto_curso_idioma, 
                                    "Cobertura idioma"=>$item->cobertura_curso_idioma, 
                                    "Post Grado"=>$item->monto_post_grado, 
                                    "Cobertura Post Grado"=>$item->cobertura_post_grado, 
                                    "Celular"=>$item->monto_celular_corporativo, 
                                    "Vivienda"=>$item->monto_vivienda, 
                                    "Colegiatura"=>$item->monto_colegiatura_hijos, 
                                    "Condición Ocupante"=>$item->condicion_ocupante,
                                    "Aguinaldo Impactado"=> $aguinaldoImpactado,
                                    "Total Compensación Efectiva Anual" => $compensacionEfectiva,
                                    
                                    
                                );

            }elseif($rubro == 4){
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
                                    "Salario Anual"=>$item->salario_anual,
                                    "Gratificación"=> $item->gratificacion, 
                                    "Aguinaldo"=> $item->aguinaldo,
                                    "comision"=> $item->comision, 
                                    "Variable Anual (plus_rendimiento)" => $item->plus_rendimiento,
                                    "Variable por Viaje" => $item->variable_viaje,
                                    "Adicional Amarre"=>$item->adicional_amarre, 
                                    "Adicional Tipo Combustible"=>$item->adicional_tipo_combustible, 
                                    "Adicional Embarque"=>$item->adicional_embarque, 
                                    "Adicional Tipo Carga"=>$item->adicional_carga,
                                    "Fallo Caja"=> $item->fallo_caja,
                                    "Fallo Caja Ext."=> $item->fallo_caja_ext, 
                                    "Gratif. Contrato"=>$item->gratificacion_contrato, 
                                    "Adicional Nivel Cargo"=>$item->adicional_nivel_cargo, 
                                    "Adicional Título"=>$item->adicional_titulo,
                                    "Total Adicional"=>$adicional,
                                    "Bono Anual"=>$item->bono_anual, 
                                    "Incentivo a Largo Plazo"=>$item->incentivo_largo_plazo, 
                                    "Efectivo Total Anual" => $efectivoTotalAnual,
                                    "Refrigerio"=>$item->refrigerio, 
                                    "Costo Seguro Médico"=>$item->costo_seguro_medico, 
                                    "Cobertura Seguro Médico"=>$item->cobertura_seguro_medico, 
                                    "Costo Seguro Vida"=>$item->costo_seguro_vida, 
                                    "Car Company"=>$item->car_company, 
                                    "Movilidad Full"=>$item->movilidad_full, 
                                    "Monto Tarj. Flota"=>$item->monto_tarjeta_flota, 
                                    "Tarj. Flota"=>$item->tarjeta_flota, 
                                    "Monto Automóvil"=>$item->monto_movil, 
                                    "Seguro Automóvil"=>$item->seguro_movil, 
                                    "Mantenimiento Automóvil"=>$item->mantenimiento_movil, 
                                    "Km recorrido"=>$item->monto_km_recorrido, 
                                    "Ayuda Escolar"=>$item->monto_ayuda_escolar, 
                                    "Comedor Interno"=>$item->monto_comedor_interno, 
                                    "Curso Idioma"=>$item->monto_curso_idioma, 
                                    "Cobertura idioma"=>$item->cobertura_curso_idioma, 
                                    "Post Grado"=>$item->monto_post_grado, 
                                    "Cobertura Post Grado"=>$item->cobertura_post_grado, 
                                    "Celular"=>$item->monto_celular_corporativo, 
                                    "Vivienda"=>$item->monto_vivienda, 
                                    "Colegiatura"=>$item->monto_colegiatura_hijos, 
                                    "Condición Ocupante"=>$item->condicion_ocupante,
                                    "Total Beneficios Anual"=>$totalBeneficios,
                                    "Aguinaldo Impactado"=> $aguinaldoImpactado,
                                    "Compensación Anual Total" => ($totalBeneficios + $efectivoTotalAnual),
                                    
                                );

            }else{
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
                                    "Comision"=> $item->comision, 
                                    "Fallo Caja"=> $item->fallo_caja,
                                    "Fallo Caja Ext."=> $item->fallo_caja_ext, 
                                    "Gratif. Contrato"=>$item->gratificacion_contrato, 
                                    "Adicional Nivel Cargo"=>$item->adicional_nivel_cargo, 
                                    "Adicional Título"=>$item->adicional_titulo,
                                    "Total Adiciona"=>$adicional,
                                    "Bono Anual"=>$item->bono_anual, 
                                    "Incentivo a Largo Plazo"=>$item->incentivo_largo_plazo, 
                                    "Efectivo Total Anual" => $efectivoTotalAnual,
                                    "Refrigerio"=>$item->refrigerio, 
                                    "Costo Seguro Médico"=>$item->costo_seguro_medico, 
                                    "Cobertura Seguro Médico"=>$item->cobertura_seguro_medico, 
                                    "Costo Seguro Vida"=>$item->costo_seguro_vida, 
                                    "Car Company"=>$item->car_company, 
                                    "Movilidad Full"=>$item->movilidad_full, 
                                    "Monto Tarj. Flota"=>$item->monto_tarjeta_flota, 
                                    "Tarj. Flota"=>$item->tarjeta_flota, 
                                    "Monto Automóvil"=>$item->monto_movil, 
                                    "Seguro Automóvil"=>$item->seguro_movil, 
                                    "Mantenimiento Automóvil"=>$item->mantenimiento_movil, 
                                    "Km recorrido"=>$item->monto_km_recorrido, 
                                    "Ayuda Escolar"=>$item->monto_ayuda_escolar, 
                                    "Comedor Interno"=>$item->monto_comedor_interno, 
                                    "Curso Idioma"=>$item->monto_curso_idioma, 
                                    "Cobertura idioma"=>$item->cobertura_curso_idioma, 
                                    "Post Grado"=>$item->monto_post_grado, 
                                    "Cobertura Post Grado"=>$item->cobertura_post_grado, 
                                    "Celular"=>$item->monto_celular_corporativo, 
                                    "Vivienda"=>$item->monto_vivienda, 
                                    "Colegiatura"=>$item->monto_colegiatura_hijos, 
                                    "Condición Ocupante"=>$item->condicion_ocupante,
                                    "Compensación Efectiva Anual" => $compensacionEfectiva,
                                    "Total Beneficios Anual" => $totalBeneficios,
                                    "Compensación Anual Total" => ($totalBeneficios + $efectivoTotalAnual),
                                    "Aguinaldo Impactado"=> $aguinaldoImpactado
                            );

            }

             
        }
    
        $periodo = implode('_', explode('/', $periodo));
        $filename = 'Resultados_'.$periodo.'_'.$rubroDesc;
        Excel::create($filename, function($excel) use($detalle, $periodo, $rubro) {
            $excel->sheet($periodo, function($sheet) use($detalle, $rubro){
                if($rubro == 1){
                    $sheet->cells('A1:BG1', function($cells){
                        $cells->setBackground('#00897b');
                        $cells->setFontColor("#FFFFFF");
                        $cells->setFontWeight("bold");
                       // $cells->setValignment('center');
                        $cells->setAlignment('center');
                    });
                }elseif($rubro == 4){
                    $sheet->cells('A1:BM1', function($cells){
                        $cells->setBackground('#00897b');
                        $cells->setFontColor("#FFFFFF");
                        $cells->setFontWeight("bold");
                       // $cells->setValignment('center');
                        $cells->setAlignment('center');
                    });
                }else{
                    $sheet->cells('A1:BJ1', function($cells){
                        $cells->setBackground('#00897b');
                        $cells->setFontColor("#FFFFFF");
                        $cells->setFontWeight("bold");
                       // $cells->setValignment('center');
                        $cells->setAlignment('center');
                    });
                }
                $sheet->fromArray($detalle, null, 'A1');                

            });
        })->export('xlsx');
        return redirect()->route('resultados');

    }
    public function panel($id){
        $dbEmpresa = $id;
        $rubro = Auth::user()->empresa->rubro_id;
        $subRubro = Auth::user()->empresa->sub_rubro_id;
        $subRubroEsp = 25;
        $reporteEspecial = Session::get('especial');
        
        $club = $this->club($rubro);
        $periodo = Session::get('periodo');
        if($periodo){
            $dbEncuesta = Cabecera_encuesta::where('empresa_id', $id)
                                           ->where('periodo', $periodo)
                                           ->first();

        }else{
            $dbEncuesta = Cabecera_encuesta::where('empresa_id', $id)
                                           ->whereRaw('id = (select max(id) from cabecera_encuestas where empresa_id = '. $id.')')
                                           ->first();

        }
        if($reporteEspecial){
            $participantes = Cabecera_encuesta::where('periodo', $dbEncuesta->periodo)
                                              ->where('rubro_id', $rubro)
                                              ->where('sub_rubro_id', $subRubroEsp)
                                              ->get();
        }else{
            $participantes = Cabecera_encuesta::where('periodo', $dbEncuesta->periodo)
                                              ->where('rubro_id', $rubro)
                                              ->get();
        }
        //dd($participantes, $subRubro, $dbEncuesta);
        $dbData = $participantes->map(function($item){
            return $item->empresa;    
        })->reject(function($item){
            if(!$item->listable){
                return $item;
            }
        });

        
        return view('report.panel') ->with('dbData', $dbData)
                                    ->with('club', $club)
                                    ->with('locale', $this->getIdioma())
                                    ->with('dbEmpresa', $dbEmpresa);
    }

    public function getCargos(Request $request){
        $id = $request->nivel_id;
        $empresa = Empresa::find($request->empresa_id);
        
        $cargosRubros = Cargos_rubro::where('rubro_id', $empresa->rubro_id)
                                    ->pluck('cargo_id');

        if($this->getIdioma() == "en"){
            $dbData = Cargo_en::orderBy('descripcion')
                                ->whereIn('id', $cargosRubros)
                                ->where('nivel_id', $id)
                                ->pluck('id', 'descripcion');
        }else{
            $dbData = Cargo::orderBy('descripcion')
                             ->whereIn('id', $cargosRubros)
                             ->where('nivel_id', $id)
                             ->pluck('id', 'descripcion');
        }
       

        return $dbData;        
    }

    

    public function setSession(Request $request){
        $request->session()->put('periodo', $request->periodo); 
        $request->session()->forget('especial');
        return "ok";
    }

    public function setSessionEspecial(Request $request){
        $request->session()->put('periodo', $request->periodo); 
        $request->session()->put('especial', "true"); 
        return "ok";
    }    
    
}
