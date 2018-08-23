<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use Auth;
use flash;
use Hash;

use App\Empresa;


class ResetPasswordController extends Controller
{
    public function showResetForm(){
    	if(Auth::user()){
    		return view('auth.passwords.reset');	
    	}else{
    		return redirect()->route('login');
    	}
    	
    }

    public function resetPassword(Request $request){
    	if(Auth::user()){
    		if (Hash::check($request->old_password, Auth::user()->password))
            {
                if($request->password !== $request->password_confirmation){
                    //Flash::elsoftMessage(1003, true);
                    return redirect()->back()->withErrors(['password' => 'La contraseñas nuevas no coinciden']);
                }else{
                    $user = User::find(Auth::user()->id);
                    $user->password = Hash::make($request->password);
                    $user->save();
                    return redirect()->route('home.page');
                }
            }else{
                return redirect()->back()->withErrors(['old_password'=>'La contraseña actual no coincide']);
            }
    	}else{
    		return redirect()->route('login');
    	}

    }

    public function generate(){
        // generar contraseñas a bancos
        $empresas = Empresa::where('rubro_id', 1)->get();
        $update = collect();
        foreach ($empresas as $empresa) {
            $user = User::where('empresa_id', $empresa->id)->first();
            $chars = '@+*?-#$&%';
            $first = substr($user->username,0, 4);
            $second = substr(uniqid(), -4);
            $pos = rand(0,8);
            $third = substr($chars, $pos, 1);
            $pass = $first.$second.$third;
            $update->push("update users set password = '".Hash::make($pass)."' where username = '".$user->username."'; </br>");
            echo "usuario: ".$user->username."</br>";
            echo "password: ".$pass."</br>";
        }
        foreach ($update as $key => $value) {
            echo $value;
        }
    }
}
