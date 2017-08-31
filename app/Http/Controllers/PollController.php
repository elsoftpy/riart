<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Auth;
use Carbon\Carbon;
class PollController extends Controller
{
    public function home(Request $request){

        if(Auth::user()){
        	/*if(Auth::user()->is_admin){
    			return view("home");	
    		}else{
    			$dbEmpresa = null;
                return view("clientes.home")->with('dbEmpresa', $dbEmpresa);	
    		}*/
            return redirect('/home');
    		
    	}else{
            
    		return view('auth/login');	
    	}
    	
    }
}
