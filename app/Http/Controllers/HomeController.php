<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Auth;
use App\Cabecera_encuesta;
use App\beneficios_cabecera_encuesta;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if(Auth::user()->is_admin){
            return view('home');    
        }else{
            if(Auth::user()->is_benefit){
                $dbEmpresa = Auth::user()->empresa;
                $dbEncuestas = beneficios_cabecera_encuesta::where('empresa_id', $dbEmpresa->id)->orderBy('id', 'DESC')->get();
                if($dbEncuestas->count() > 0){
                    $dbEncuesta = $dbEncuestas->first();    
                }else{
                    $dbEncuesta = collect();
                }
                
                return view('beneficios.home')->with('dbEncuesta', $dbEncuesta)
                                              ->with('dbEmpresa', $dbEmpresa);    

            }else{
                $dbEmpresa = Auth::user()->empresa;
                $dbEncuestas = Cabecera_encuesta::where('empresa_id', $dbEmpresa->id)->orderBy('id', 'DESC')->get();
                $dbEncuesta = $dbEncuestas->first();
                $dbEncuestaAnt = $dbEncuestas->get(1);
                    return view('clientes.home')->with('dbEmpresa', $dbEmpresa)
                                                ->with('dbEncuesta', $dbEncuesta)
                                                ->with('dbEncuestaAnt' , $dbEncuestaAnt);

            }
        }
        
    }
}
