<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\beneficios_cabecera_encuesta;
use App\beneficios_pregunta;
use App\beneficios_opcion;
use App\beneficios_respuesta;
use App\beneficios_conclusion_abierta;
use App\Autos_marca;
use App\Autos_modelo;
use App\Aseguradora;
use App\Empresa;
use App\Rubro;
use Carbon\Carbon;
use Hash;
use DB;
use Excel;
use Auth;

class BeneficiosAdminController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $dbData = Beneficios_cabecera_encuesta::all();
        return view('beneficios_admin.list')->with('dbData', $dbData);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $dbEmpresas = empresa::pluck('descripcion', 'id');

        return view('beneficios_admin.create')->with('dbEmpresas', $dbEmpresas);
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
        $empresa = $request->empresa_id;
        $dbEmpresa = Empresa::find($empresa);
        $dbData = new Beneficios_cabecera_encuesta();
        $dbData->empresa_id = $empresa;
        $dbData->rubro_id =  $dbEmpresa->rubro_id;
        $dbData->sub_rubro_id =  $dbEmpresa->sub_rubro_id;
        $dbData->cantidad_empleados =  $dbEmpresa->cantidad_empleados;
        $dbData->cantidad_sucursales =  $dbEmpresa->cantidad_sucursales;
        $dbData->periodo = $periodo;
        $dbData->save();

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

        $dbData = beneficios_cabecera_encuesta::find($id);
        $now = Carbon::now();
        
        $dbDetalle = beneficios_pregunta::get();
        $dbMarca = Autos_marca::pluck('descripcion', 'id');
        $dbModelo = Autos_modelo::where('autos_marca_id', 1)->pluck('descripcion', 'id');
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
        dd($request->all(), $id);
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

    public function resultados(){
        
        $dbData = beneficios_cabecera_encuesta::get()->unique('periodo')->pluck('periodo');
        $dbRubros = Rubro::get()->pluck('descripcion', 'id');
        return view('beneficios_admin.periodos')->with('dbRubros', $dbRubros)
                                                ->with('dbData', $dbData);
    }

    public function resultadosExcel(Request $request){
        ini_set('max_execution_time', 0);
        ini_set('memory_limit', '512M');
        $periodo = $request->periodo;
        $rubro = $request->rubro_id;
        $query = "SELECT e.descripcion, c.periodo, c.id, r.descripcion rubro 
                    FROM beneficios_cabecera_encuestas c, empresas e, rubros r
                   WHERE c.periodo = :periodo
                     AND c.rubro_id = :rubro
                     AND c.empresa_id = e.id
                     AND e.rubro_id = r.id";
        $empresas = collect(DB::select(DB::raw($query), ["periodo"=>$periodo, "rubro"=>$rubro]));
        $cabIds = $empresas->pluck('id');
        $resultados = $empresas->map(function($item) use ($cabIds){
            $respuestas = beneficios_respuesta::where('beneficios_cabecera_encuesta_id', $item->id)
                                              ->orderBy('beneficios_pregunta_id')
                                              ->get();
            $preguntaAnt = null;
            foreach ($respuestas as $respuesta) {
                $preguntaId = $respuesta->beneficios_pregunta_id;
                $hasTitulo = false;
                $id = $item->id;
                if($respuesta->beneficiosPregunta->item){
                    $titulo = $respuesta->beneficiosPregunta->item->titulo;
                }else{
                    if(!$respuesta->beneficiosPregunta->itemComposicion){
                        $titulo = $respuesta->beneficios_pregunta_id;
                    }else{
                        $titulo = $respuesta->beneficiosPregunta->itemComposicion->titulo;    
                    }
                    
                }
                if($respuesta->beneficiosPregunta->multiple){
                    $cantResp = DB::table('beneficios_respuestas')
                                  ->select('beneficios_cabecera_encuesta_id', DB::raw('count(*) as total'))
                                  ->where('beneficios_pregunta_id', $preguntaId)
                                  ->whereIn('beneficios_cabecera_encuesta_id', $cabIds)
                                  ->groupBy('beneficios_cabecera_encuesta_id')
                                  ->get();

                   $cantColumnas = $cantResp->max('total');
                   $countResp = $respuestas->where('beneficios_pregunta_id', $preguntaId)->count();

                   if( $countResp < $cantColumnas){
                       $hasTitulo = true;
                       for ($i=0; $i < $cantColumnas; $i++) { 
                            if($i > 0){
                                $tituloMult = $titulo.'('.$i.')';
                                $item->$tituloMult = null;
                            }else{
                                $item->$titulo = $respuesta->beneficiosOpcion->opcion;
                            }
                       }
                   }
                }

                if($preguntaId == $preguntaAnt){
                    $count++;
                    $titulo = $titulo.'('.$count.')';
                }else{
                    $count = 0;
                }
                if(!$hasTitulo){
                    if(!$respuesta->beneficiosOpcion){
                        if($respuesta->beneficiosPregunta->cerrada == "N"){
                            $item->$titulo = $respuesta->abierta;
                        }else{
                            $item->$titulo = "";
                        }
                    }else{
                        if($preguntaId == 74 || $preguntaId == 66){
                            $item->$titulo = Autos_marca::find($respuesta->beneficios_opcion_id)
                                                        ->descripcion;
                        }elseif($preguntaId == 67){
                            $item->$titulo = Autos_modelo::find($respuesta->beneficios_opcion_id)
                                                         ->descripcion;

                        }elseif($preguntaId == 80){
                            $item->$titulo = Aseguradora::find($respuesta->beneficios_opcion_id)
                                                         ->descripcion;
                        }else{
                            $item->$titulo = $respuesta->beneficiosOpcion->opcion;        
                        }
                        
                    }
                }
                $preguntaAnt = $preguntaId;
                
            }
            return (array) $item;
        })->toArray();

        $periodo = implode('-', explode('/', $periodo));
        $filename = 'Resultados_Beneficios_'.$periodo;
        Excel::create($filename, function($excel) use($resultados, $periodo) {
            $excel->sheet($periodo, function($sheet) use($resultados){
                $sheet->fromArray($resultados, null, 'A1');
                $sheet->row(1, function($row) { 
                    $row->setBackground('#00897b'); 
                    $row->setFontColor("#FFFFFF");
                    $row->setFontWeight("bold");
                    $row->setAlignment('center');                    
                });                
            });
        })->export('xlsx');
        
        return redirect()->route('beneficios.admin.resultados');
    }

    public function createConclusion(){
        $preguntas = beneficios_pregunta::abierta()->get()->pluck('titulo', 'id');
        $rubros = Rubro::pluck('descripcion', 'id');
        $periodos = beneficios_cabecera_encuesta::get()->unique('periodo')->pluck('periodo');
        return view('beneficios_admin.create_conclusion')->with('preguntas', $preguntas)
                                                         ->with('periodos', $periodos)
                                                         ->with('rubros', $rubros);
    }

    public function storeConclusion(Request $request){
        $dbData = beneficios_conclusion_abierta::where('beneficios_pregunta_id', $request->pregunta)
                                               ->where('rubro_id', $request->rubro)
                                               ->where('periodo', $request->periodo)
                                               ->first();
        if(!$dbData){
          $dbData = new beneficios_conclusion_abierta();  
        }
        
        $dbData->beneficios_pregunta_id = $request->pregunta;
        $dbData->rubro_id = $request->rubro;
        $dbData->periodo = $request->periodo;
        $dbData->conclusion = $request->conclusion;
        $dbData->conclusion_en = $request->conclusion_en;
        $dbData->save();

        return redirect()->route('home');

    }

    public function getConclusion(Request $request)
    {
      $dbData = beneficios_conclusion_abierta::where('beneficios_pregunta_id', $request->pregunta)
                                               ->where('rubro_id', $request->rubro)
                                               ->where('periodo', $request->periodo)
                                               ->first();
      if(!$dbData){
        return null;
      }
      return $dbData;
    }

    public function panel($id){
        $dbEmpresa = Empresa::find($id);
        $rubro = Auth::user()->empresa->rubro_id;
        $club = $this->club($rubro);
        $dbEncuesta = beneficios_cabecera_encuesta::where('empresa_id', $id)->whereRaw('id = (select max(id) from beneficios_cabecera_encuestas where empresa_id = '. $id.')')->first();
        $participantes = beneficios_cabecera_encuesta::where('periodo', $dbEncuesta->periodo)->where('rubro_id', $rubro)->get();
        $dbData = $participantes->map(function($item){
            return $item->empresa;    
        })->reject(function($item){
            if(!$item->listable_beneficios){
                return $item;
            }
        });

        return view('beneficios_report.panel')->with('dbData', $dbData)
                                              ->with('club', $club)
                                              ->with('dbEmpresa', $dbEmpresa);
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



}
