<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Lang;
use Session;


class LanguageController extends Controller
{
    public function switchLang($lang){
        
        if (array_key_exists($lang, config('languages'))) {
            session()->put('applocale', $lang);
        }
        return redirect()->back();
    }

    public function switchLangReport($lang){
        if (array_key_exists($lang, config('languages'))) {
            session()->put('applocale', $lang);
        }
        return "true";
    }
}
