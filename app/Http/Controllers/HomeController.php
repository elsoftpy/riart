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
            $dbEncuesta = Cabecera_encuesta::where('empresa_id', $dbEmpresa->id)->whereRaw('id = (select max(id) from cabecera_encuestas where empresa_id = '. $dbEmpresa->id.')')->first();
            return view('clientes.home')->with('dbEmpresa', $dbEmpresa)->with('dbEncuesta', $dbEncuesta);    
        }
        
    }
}
