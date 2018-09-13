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
use App\File_attachment;
use App\Traits\PeriodosTrait;
use Hash;
use DB;
use Auth;
use Excel;
use Session;
use Validator;

class FileAttachmentController extends Controller
{
    use PeriodosTrait;
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $rubros =  $this->getRubros();
        // recuperamos todos los rubros
        $rubro = Rubro::all()->first()->id;
        $periodos = $this->getPeriodos($rubro);
        // Si terminó de subir el archivo mostramos el toast correspondiente
        $toast = session('upload_done');
        if($toast){
            session()->forget('upload_done');
        }
        $errorUploading = session('upload_nok');
        if($errorUploading){
            session()->forget('upload_nok');
        }

    	return view('file_attachment.index')->with('periodos', $periodos)
                                            ->with('rubros', $rubros)
                                            ->with('toast', $toast)
                                            ->with('errorUploading', $errorUploading);
    }

    
   public function download(){
        $empresa = Auth::user()->empresa;
        $id = $empresa->id;
        if(Session::has('periodo')){
            $per = Session::get('periodo');
            $dbEncuesta = Cabecera_encuesta::where('empresa_id', $id)->whereRaw("periodo = '". $per."'")->first();
        }else{
            $dbEncuesta = Cabecera_encuesta::where('empresa_id', $id)->whereRaw('id = (select max(id) from cabecera_encuestas where empresa_id = '. $id.')')->first();            
        }
        $rubro = $empresa->rubro_id;
        $periodo = $dbEncuesta->periodo;
        $dbFile = File_attachment::where('rubro_id', $rubro)->where('periodo', $periodo)->first();
        if($dbFile){
            $filename = $dbFile->filename;
            try{
                return response()->download(storage_path('app/public/uploads/'.$filename), $filename, []);
            }catch(\Exception $exception){
                return view('file_attachment.notfound');
            }
    
        }else{
            return view('file_attachment.notfound');
        }
    }

    public function upload(Request $request){
        //dd($request->all());
        // recuperamos el archivo
        $file = $request->file('file');
        // validación
        $rules = array( 'file' => 'required|mimes:xls,xlsx,pdf'); 
        $messages = array( 'file.required' => 'No se especificó ningún archivo para subir', 
                           'file.mimes' => 'El tipo de archivo no es correcto'); 

        $validator = Validator::make($request->all(), $rules, $messages);
           
        if($validator->passes()){
            $filename = $file->getClientOriginalName();
            $rubro = $request->rubro_id;
            $periodo = $request->periodo;
            try{
                $file->storeAs('public/uploads', $filename);
                $dbData = File_attachment::where('rubro_id', $rubro)->where('periodo', $periodo)->first();
                if(!$dbData){
                    $dbData = new File_attachment();
                }                
                $dbData->rubro_id = $rubro;
                $dbData->filename = $filename;
                $dbData->periodo = $periodo;
                $dbData->activo = 1;
                $dbData->save();
            }catch(\Exception $exception){
                session(['upload_nok'=>'true']);
                redirect()->back()->withInput();
            }
            
        }else{
            session(['upload_nok'=>'true']);
            redirect()->back()->withInput();
        }
        session(['upload_done'=>'true']);
        return redirect()->route('file_attachment');

    }

    public function getPeriodosAjax(Request $request){
        
        $periodos = $this->getPeriodos($request->rubro_id);
        
        return $periodos;
    }

}
