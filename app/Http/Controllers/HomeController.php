<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Auth;
use App\Cabecera_encuesta;

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
