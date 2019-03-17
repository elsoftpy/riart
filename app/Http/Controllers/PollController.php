<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Auth;
use Carbon\Carbon;
class PollController extends Controller
{
    public function home(Request $request){

        if(Auth::user()){

			if(session()->has('flash_notification')){
				$flash = session('flash_notification');
				
				session()->flash('flash_notification.message', $flash["message"]);
				session()->flash('flash_notification.number', $flash["number"]);
				session()->flash('flash_notification.title', $flash["title"]);
				session()->flash('flash_notification.overlay', $flash["overlay"]);
				session()->flash('flash_modal_class', session('flash_modal_class'));
				return redirect('/home');
			}else{
				return redirect('/home');
			}
			
    		
    	}else{
            
    		return view('auth/login');	
    	}
    	
    }
}
