<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use App\Empresa;
use Hash;


class UsuariosController extends Controller
{
    public function index(){
    	$dbData = User::get();
    	return view('usuarios.list')->with('dbData', $dbData);
    }

  	public function create(){
        $dbEmpresas = empresa::get()->pluck('descripcion', 'id');
    	return view('usuarios.create')->with('dbEmpresas', $dbEmpresas);
    }

    public function store(Request $request){
        $dbData = new User();
    	$dbData->username = $request->username;
        $dbData->password = Hash::make($request->password);
    	$dbData->email = $request->email;
        $dbData->empresa_id = $request->empresa_id;
        if ($request->is_admin == '2') {
            $dbData->is_benefit = 1;
            $dbData->is_admin = 0;
        }else{
            $dbData->is_benefit = 0;
            $dbData->is_admin = $request->is_admin;    
        }
        
    	
    	$dbData->save();
    	return redirect()->route('usuarios.index');
    }

     public function show($id)
    {
        //
    }

    public function edit($id){
    	$dbData = User::find($id);
        $dbEmpresas = empresa::get()->pluck('descripcion', 'id');
    	return view('usuarios.edit')->with('dbData', $dbData)
                                    ->with('dbEmpresas', $dbEmpresas);
    }

    public function update(Request $request, $id){

    	$dbData = User::find($id);
        //dd($request->all());
        $dbData->password = Hash::make($request->password);
        $dbData->email = $request->email;
        $dbData->empresa_id = $request->empresa_id;
        if ($request->is_admin == '2') {
            $dbData->is_benefit = 1;
            $dbData->is_admin = 0;
        }else{
            $dbData->is_benefit = 0;
            //$dbData->is_admin = $request->is_admin;    
        }


    	
    	$dbData->save();
		return redirect()->route('usuarios.index');
    }

    public function destroy($id){
		$dbData = User::find($id);
    	$dbData->delete();
		return redirect()->route('usuarios.index');    	
    }    
}
