<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Auth;
use App\Cabecera_encuesta;
use App\beneficios_cabecera_encuesta;
use App\Ficha_dato;
use App\Traits\ClubsTrait;

class HomeController extends Controller
{
    use ClubsTrait;
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
        $user = Auth::user();
        
        if($user->id == 176 ){
            $ficha = Ficha_dato::find(2);
            $dbEmpresa = $user->empresa;
            session()->put('periodo', $ficha->periodo);
            $dbEncuesta = Cabecera_encuesta::where('empresa_id', $dbEmpresa->id)
                                                    ->where('periodo', $ficha->periodo)
                                                    ->first();                    
            
            $dbEncuestas = Cabecera_encuesta::where('empresa_id', $dbEmpresa->id)
                                            ->orderBy('id', 'DESC')
                                            ->get();
                                            $dbEncuestaAnt = $dbEncuestas->get(1);
            
            $club = $this->club($dbEmpresa->rubro_id);
                return view('clientes.home')->with('dbEmpresa', $dbEmpresa)
                                            ->with('club', $club)
                                            ->with('dbEncuesta', $dbEncuesta)
                                            ->with('dbEncuestaAnt' , $dbEncuestaAnt);                                            
        }
        if($user->is_admin){
            return view('home');    
        }else{
            if($user->is_benefit){
                $dbEmpresa = $user->empresa;
                $dbEncuestas = beneficios_cabecera_encuesta::where('empresa_id', $dbEmpresa->id)->orderBy('id', 'DESC')->get();
                if($dbEncuestas->count() > 0){
                    $dbEncuesta = $dbEncuestas->first();    
                }else{
                    $dbEncuesta = collect();
                }

                $club = $this->club($dbEmpresa->rubro_id);
                
                return view('beneficios.home')->with('dbEncuesta', $dbEncuesta)
                                              ->with('dbEmpresa', $dbEmpresa)
                                              ->with('club', $club);    

            }else{
                $dbEmpresa = $user->empresa;
                $ficha = Ficha_dato::where('rubro_id', $dbEmpresa->rubro_id)->activa()->first();
                
                if($ficha){
                    $dbEncuesta = Cabecera_encuesta::where('empresa_id', $dbEmpresa->id)
                                                    ->where('periodo', $ficha->periodo)
                                                    ->first();                    
                    $dbEncuestas = Cabecera_encuesta::where('empresa_id', $dbEmpresa->id)
                                                    ->orderBy('id', 'DESC')
                                                    ->get();                                                    
                }else{
                    $dbEncuestas = Cabecera_encuesta::where('empresa_id', $dbEmpresa->id)->orderBy('id', 'DESC')->get();
                    $dbEncuesta = $dbEncuestas->first();
                }
                
                $dbEncuestaAnt = $dbEncuestas->get(1);
                if($dbEncuestaAnt){
                    if($dbEncuestaAnt->periodo == $dbEncuesta->periodo){
                        $dbEncuestaAnt = $dbEncuestas->get(2);
                        if(!$dbEncuestaAnt){
                            // busca la tercera encuesta (descendente) de navemar para obtener el periodo en el caso de que no tengan  encuestas en periodos anteriores
                            $dbEncuestas = Cabecera_encuesta::where('empresa_id', 22)  
                                                             ->orderBy('id', 'DESC')
                                                             ->get();  
                            $dbEncuestaAnt = $dbEncuestas->get(2);
                        }
                    }
                    $dbEncuestaOld = $dbEncuestas->get(3);

                    if(!$dbEncuestaOld){
                        
                        $dbEncuestas = Cabecera_encuesta::where('empresa_id', 22)  
                                                             ->orderBy('id', 'DESC')
                                                             ->get();  
                        $dbEncuestaAnt = $dbEncuestas->get(3);
                        $dbEncuestaOld = $dbEncuestas->get(4);
                        
                    }
                }else{
                    
                    $dbEncuestaOld = null;
                }

                //dd($dbEncuestaOld);                
                $club = $this->club($dbEmpresa->rubro_id);
                
                return view('clientes.home')->with('dbEmpresa', $dbEmpresa)
                                            ->with('club', $club)
                                            ->with('dbEncuesta', $dbEncuesta)
                                            ->with('dbEncuestaAnt' , $dbEncuestaAnt)
                                            ->with('dbEncuestaOld', $dbEncuestaOld);

            }
        }
        
    }
}
