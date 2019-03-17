<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Nivel;
use App\Nivel_en;
use flash;
use DB;

class NivelesController extends Controller
{
    public function index(){
        $dbData = Nivel::get();
        
    	return view('niveles.list')->with('dbData', $dbData);
    }

    public function create(){
        return view('niveles.create');
    }

    public function store(Request $request){
        
        $dbData = new Nivel($request->all());    
        $dbDataEn = new Nivel_en();
        $dbDataEn->descripcion = $request->descripcion_en;
        
        DB::beginTransaction();
        try{
            $dbData->save();
            $dbDataEn->id = $dbData->id;
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
    	return redirect()->route('niveles.index');
    }

     public function show($id)
    {
        //
    }

    public function edit($id){
    	$dbData = nivel::find($id);
       	return view('niveles.edit')->with('dbData', $dbData);
    }

    public function update(Request $request, $id){
        
        $dbData = Nivel::find($id);
        $dbDataEn = Nivel_en::find($id);
        if(!$dbDataEn){
            $dbDataEn = new Nivel_en();
            $dbDataEn->id = $id;
        }
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
		return redirect()->route('niveles.index');
    }

    public function destroy($id){
        $dbData = Nivel::find($id);
        $dbDataEn = Nivel_en::find($id);

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

		return redirect()->route('niveles.index');    	
    }
}
