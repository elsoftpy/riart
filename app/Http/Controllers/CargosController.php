<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Cargo;
use App\Cargo_en;
use App\Cargos_rubro;
use App\Area;
use App\Nivel;
use App\Rubro;
use Carbon\Carbon;
use PHPExcel_Worksheet_Drawing;
use flash;
use Excel;
use DB;

class CargosController extends Controller
{
    public function index()
    {
        $dbData = Cargo::get();
        return view('cargos.list')->with('dbData', $dbData);
    }

    public function create()
    {
        $dbNivel = Nivel::all()->pluck('descripcion', 'id');
        $dbArea = Area::all()->pluck('descripcion', 'id');
        $dbRubros = Rubro::get()->pluck('descripcion', 'id');
        return view('cargos.create')->with('dbNivel', $dbNivel)
            ->with('dbRubros', $dbRubros)
            ->with('dbArea', $dbArea);
    }

    public function store(Request $request)
    {
        DB::transaction(function () use ($request) {
            $dbData = new Cargo($request->all());
            if (!is_null($request->is_temporal)) {
                $dbData->is_temporal = 1;
            } else {
                $dbData->is_temporal = 0;
            }

            $dbDataEn = new Cargo_en($request->all());
            if (!is_null($request->is_temporal)) {
                $dbDataEn->is_temporal = 1;
            } else {
                $dbDataEn->is_temporal = 0;
            }
            $dbDataEn->descripcion = $request->descripcion_en;
            $dbDataEn->detalle = $request->detalle_en;

            $dbData->save();
            $dbDataEn->save();

            if ($request->rubros) {
                foreach ($request->rubros as $key => $value) {
                    $dbRubro = new Cargos_rubro();
                    $dbRubro->cargo_id = $dbData->id;
                    $dbRubro->rubro_id = $value;
                    $dbRubro->save();
                }
            }
        });

        return redirect()->route('cargos.index');
    }

    public function show($id)
    {
        //
    }

    public function edit($id)
    {
        $dbData = Cargo::find($id);
        $dbNivel = Nivel::all()->pluck('descripcion', 'id');
        $dbArea = Area::all()->pluck('descripcion', 'id');
        $dbRubros = Rubro::get()->pluck('descripcion', 'id');
        return view('cargos.edit')->with('dbData', $dbData)
            ->with('dbNivel', $dbNivel)
            ->with('dbRubros', $dbRubros)
            ->with('dbArea', $dbArea);
    }

    public function update(Request $request, $id)
    {

        //Cargo en español
        $dbData = Cargo::find($id);
        //Cargo en inglés
        $dbDataEn = Cargo_en::find($id);
        DB::transaction(function () use ($request, $id, $dbData, $dbDataEn) {
            $dbRubros = Cargos_rubro::where('cargo_id', $id);

            if ($request->rubros) {
                if ($dbRubros->count() > 0) {
                    $dbRubros->delete();
                }
                foreach ($request->rubros as $key => $value) {
                    $dbRubro = new Cargos_rubro();
                    $dbRubro->cargo_id = $id;
                    $dbRubro->rubro_id = $value;
                    $dbRubro->save();
                }
            } else {
                if ($dbRubros->count() > 0) {
                    $dbRubros->delete();
                }
            }

            $dbData->fill($request->all());

            if (!is_null($request->is_temporal)) {
                $dbData->is_temporal = 1;
            } else {
                $dbData->is_temporal = 0;
            }

            $dbData->save();

            if ($dbDataEn) {
                $dbDataEn->fill($request->all());
                if (!is_null($request->is_temporal)) {

                    $dbDataEn->is_temporal = 1;
                } else {

                    $dbDataEn->is_temporal = 0;
                }
                $dbDataEn->descripcion = $request->descripcion_en;
                $dbDataEn->detalle = $request->detalle_en;
                $dbDataEn->save();
            }else{
                if($request->descripcion_en || $request->detalle_en){
                    $isTemporal = 0;
                    if (!is_null($request->is_temporal)) {
                        $isTemporal = 1;
                    }
                    $cargoEn = new Cargo_en();
                    $cargoEn->id = $id;
                    $cargoEn->descripcion = $request->descripcion_en;
                    $cargoEn->detalle = $request->detalle_en;
                    $cargoEn->is_temporal = $isTemporal;
                    $cargoEn->save();
                }
            }
        });



        return redirect()->route('cargos.index');
    }

    public function destroy($id)
    {
        $dbData = Cargo::find($id);
        $dbDataEn = Cargo_en::find($id);
        $dbData->delete();

        return redirect()->route('cargos.index');
    }

    public function getDetalle(Request $request)
    {
        if (app()->getLocale() == "en") {
            $cargo = Cargo_en::find($request->id);
        } else {
            $cargo = Cargo::find($request->id);
        }
        return $cargo->detalle;
    }

    public function excel(Request $request)
    {

        $now = Carbon::now();
        $filename = "cargos_" . $now->format('diYHms');
        $cargos = DB::table('cargos')
            ->leftJoin('areas', 'cargos.area_id', '=', 'areas.id')
            ->leftJoin('niveles', 'cargos.nivel_id', '=', 'niveles.id')
            ->leftJoin('cargos_en', 'cargos.id', '=', 'cargos_en.id')
            ->select(DB::raw(
                'cargos.id, 
                                    cargos.descripcion nombre_cargo,
                                    cargos.detalle descripcion, 
                                    cargos.area_id,
                                    areas.descripcion area,
                                    cargos.nivel_id, 
                                    niveles.descripcion nivel, 
                                    cargos_en.descripcion cargo_ingles, 
                                    cargos_en.detalle detalle_ingles'
            ))
            ->get();

        $data = array();
        foreach ($cargos as $cargo) {
            $data[] = (array)$cargo;
        };

        Excel::create($filename, function ($excel) use ($data) {
            $excel->sheet("Cargos", function ($sheet) use ($data) {
                $objDrawing = new PHPExcel_Worksheet_Drawing;
                $objDrawing->setPath(public_path('images/logo.jpg')); //your image path
                $objDrawing->setCoordinates('A1');
                $objDrawing->setWidthAndHeight(304, 60);
                $objDrawing->setWorksheet($sheet);

                $sheet->cells('A5:I5', function ($cells) {
                    $cells->setBackground('#00897b');
                    $cells->setFontColor("#FFFFFF");
                    $cells->setFontWeight("bold");
                    // $cells->setValignment('center');
                    $cells->setAlignment('center');
                });
                $sheet->fromArray($data, null, 'A5');
            });
        })->export('xlsx');
    }
}
