<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Rubro;
use flash;
use DB;

class RubrosController extends Controller
{
    public function index(){
        $dbData = Rubro::get();
        // Si terminó de borrar el registro
        $toast = session('delete_failed');
        if($toast){
            session()->forget('delete_failed');
        }
    	return view('rubros.list')->with('dbData', $dbData)->with('toast', $toast);
    }

    public function create(){
        return view('rubros.create');
    }

    public function store(Request $request){
        $dbData = new Rubro($request->all());
           
        DB::beginTransaction();
        try{
            $dbData->save();
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
    	return redirect()->route('rubros.index');
    }

     public function show($id)
    {
        //
    }

    public function edit($id){
    	$dbData = Rubro::find($id);
       	return view('rubros.edit')->with('dbData', $dbData);
    }

    public function update(Request $request, $id){
        
        //Rubro en español
        $dbData = Rubro::find($id);
        //Rubro en inglés
        $dbData->fill($request->all());

        DB::beginTransaction();
        try{
            $dbData->save();
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
        
        

		return redirect()->route('rubros.index');
    }

    public function destroy($id){        
        $dbData = Rubro::find($id);
        DB::beginTransaction();
        try{
            $dbData->delete();
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

        return redirect()->route('rubros.index');    
    }
}
