<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Cabecera_encuesta;
use App\Encuestas_cargo;
use App\Detalle_encuesta;
use App\Rubro;
use App\Empresa;
use App\Cargo;
use App\Traits\PeriodosTrait;
use DB;

class ReportController extends Controller
{
    use PeriodosTrait;
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $dbEmpresas = empresa::pluck('descripcion', 'id');
        $dbCargos = Cargo::pluck('descripcion', 'id');

        return view('admin.reportes.filter_empresas')->with('dbEmpresas', $dbEmpresas)
                                                     ->with('dbCargos', $dbCargos);

    }

    public function filterNiveles(){
        $rubro = Rubro::first()->id;
        $periodos = $this->getPeriodos($rubro);
        $rubros = $this->getRubros();

        $toast = session('update_done');
        if($toast){
            session()->forget('update_done');
        }
        
    	return view('admin.reportes.filter_niveles')->with('periodos', $periodos)
                                                    ->with('rubros', $rubros)
                                                    ->with('toast', $toast);
    }

    public function filterCargos(){
        $rubro = Rubro::first()->id;
        $periodos = $this->getPeriodos($rubro);
        $rubros = $this->getRubros();
    	return view('admin.reportes.filter_cargos')->with('periodos', $periodos)
    								                ->with('rubros', $rubros);
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
        //
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

    public function getPeriodosEmpresa(Request $request){
        $id = $request->empresa_id;
        $periodos = Cabecera_encuesta::distinct('periodo')
                                     ->where('empresa_id', $id)
                                     ->pluck('periodo', 'periodo');
        return $periodos;                                
    }

    public function updateTable(){

        $rows = DB::delete('delete from detalle_encuestas_niveles');

        $sql = "INSERT INTO detalle_encuestas_niveles
                    (select c.id cabecera_encuesta_id, c.periodo, c.rubro_id, r.descripcion rubro,
                            e.id id_cliente, e.descripcion cargo_cliente, ca.id cargo_oficial_id, 
                            ca.descripcion cargo_oficial, n1.id nivel_oficial_id, n1.descripcion nivel_oficial, 
                            e.incluir, d.cantidad_ocupantes, em.id empresa_id, em.descripcion empresa,
                            d.salario_base, d.bono_anual, d.aguinaldo, d.gratificacion, 
                            (d.salario_base + d.aguinaldo + d.gratificacion) efectivo_anual_garantizado,
                            (d.salario_base + d.aguinaldo + d.gratificacion + d.bono_anual +
                            ((ifnull(d.fallo_caja, 0) + ifnull(d.fallo_caja_ext, 0) + 
                            ifnull(d.gratificacion_contrato, 0) + ifnull(adicional_nivel_cargo, 0) +
                            ifnull(adicional_titulo, 0) + ifnull(d.comision, 0))*12)) total_efectivo_anual
                       FROM detalle_encuestas d, encuestas_cargos e, cabecera_encuestas c, cargos ca, rubros r, 
                            areas a, niveles n, empresas em, niveles n1, areas a1
                      where c.id = d.cabecera_encuesta_id
                        and d.encuestas_cargo_id = e.id
                        and e.cargo_id = ca.id
                        and c.rubro_id = r.id
                        and d.area_id = a.id
                        and d.nivel_id = n.id
                        and c.empresa_id = em.id
                        and ca.nivel_id = n1.id
                        and ca.area_id = a1.id
                        and salario_base > 0
                        and e.incluir = 1)";

        $insert = DB::statement($sql);
        session(['update_done'=>'true']);
        return redirect()->route('admin.reporte.filter.niveles');

    }    

}
