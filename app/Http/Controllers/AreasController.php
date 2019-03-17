<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Area;
use App\Area_en;
use flash;
use DB;

class AreasController extends Controller
{
    public function index(){
        $dbData = Area::get();
        // Si terminó de borrar el registro
        $toast = session('delete_failed');
        if($toast){
            session()->forget('delete_failed');
        }
    	return view('areas.list')->with('dbData', $dbData)->with('toast', $toast);
    }

    public function create(){
        return view('areas.create');
    }

    public function store(Request $request){
        $dbData = new Area($request->all());
        $dbDataEn = new Area_en();
        $dbDataEn->descripcion = $request->descripcion_en;
           
        DB::beginTransaction();
        try{
            $dbData->save();
            $dbDataEn->save();
            DB::commit();
            flash::elsoftMessage(2010, true);
        }catch(Exception $exception){
            DB::rollback();
            if ($exception instanceof \Illuminate\Database\QueryException) {
                switch ($exception->errorInfo[1]) {
                    case 1048:
                        Flash::elsoftMessage(4001, true);
                        break;
                    case 1062:
                        Flash::elsoftMessage(4002, true);    
                        break;
                    case 1451:
                        Flash::elsoftMessage(4003, true);
                    default:
                        Flash::elsoftMessage(4000, true);
                        break;
                }
            }else{
                Flash::elsoftMessage(3013, true);
            }
            return redirect()->back();
        }
    	return redirect()->route('areas.index');
    }

     public function show($id)
    {
        //
    }

    public function edit($id){
    	$dbData = Area::find($id);
       	return view('areas.edit')->with('dbData', $dbData);
    }

    public function update(Request $request, $id){
        
        //Area en español
        $dbData = Area::find($id);
        //Area en inglés
        $dbDataEn = Area_en::find($id);
        $dbData->fill($request->all());
        $dbDataEn->descripcion = $request->descripcion_en;

        DB::beginTransaction();
        try{
            $dbData->save();
            $dbDataEn->save();
            DB::commit();
            flash::elsoftMessage(2010, true);
        }catch(Exception $exception){
            DB::rollback();
            if ($exception instanceof \Illuminate\Database\QueryException) {
                switch ($exception->errorInfo[1]) {
                    case 1048:
                        Flash::elsoftMessage(4001, true);
                        break;
                    case 1062:
                        Flash::elsoftMessage(4002, true);    
                        break;
                    case 1451:
                        Flash::elsoftMessage(4003, true);
                    default:
                        Flash::elsoftMessage(4000, true);
                        break;
                }
            }else{
                Flash::elsoftMessage(3013, true);
            }
            return redirect()->back();
        }
        
        

		return redirect()->route('areas.index');
    }

    public function destroy($id){        
        $dbData = Area::find($id);
        $dbDataEn = Area_en::find($id);
        DB::beginTransaction();
        try{
            $dbData->delete();
            $dbDataEn->delete();
            DB::commit();
            flash::elsoftMessage(2011, true);
        }catch(Exception $exception){
            DB::rollback();
            if ($exception instanceof \Illuminate\Database\QueryException) {
                switch ($exception->errorInfo[1]) {
                    case 1048:
                        Flash::elsoftMessage(4001, true);
                        break;
                    case 1062:
                        Flash::elsoftMessage(4002, true);    
                        break;
                    case 1451:
                        Flash::elsoftMessage(4003, true);
                    default:
                        Flash::elsoftMessage(4000, true);
                        break;
                }
            }else{
                Flash::elsoftMessage(3012, true);
            }
            return redirect()->back();
        }

        return redirect()->route('areas.index');    
    }
}
