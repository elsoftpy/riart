<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Sub_rubro;
use App\Rubro;
use Auth;
use flash;
use Exception;
use DB;

class SubRubrosController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $dbData = Sub_rubro::get();
        return view('sub_rubros.list')->with('dbData', $dbData);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $dbRubro = rubro::get()->pluck('descripcion', 'id');
       
        return view('sub_rubros.create')->with('dbRubro', $dbRubro);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $dbData = new Sub_rubro($request->all());
        $dbData->save();
        return redirect()->route('sub_rubros.index');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $dbData = Sub_rubro::find($id);
        $dbRubro = rubro::get()->pluck('descripcion', 'id');
        return view('sub_rubros.edit')->with('dbData', $dbData)
                                    ->with('dbRubro', $dbRubro);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $dbData = Sub_rubro::find($id);
        $dbData->fill($request->all());
        $dbData->save();

            return redirect()->route('sub_rubros.index');    
        
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $dbData = Sub_rubro::find($id);
        DB::beginTransaction();
        try{
            $dbData->delete();
            DB::commit();
            flash::elsoftMessage(2011, true);
        }catch(Exception $exception){
            DB::rollback();
            if ($exception instanceof \Illuminate\Database\QueryException) {
                switch ($exception->errorInfo[1]) {
                    case 1048:
                        Flash::elsoftMessage(4001, true);
                        break;
                    case 1062:
                        Flash::elsoftMessage(4002, true);    
                        break;
                    case 1451:
                        Flash::elsoftMessage(4003, true);
                    default:
                        Flash::elsoftMessage(4000, true);
                        break;
                }
            }else{
                Flash::elsoftMessage(3012, true);
            }
            return redirect()->back();
        }

        return redirect()->route('sub_rubros.index');
    }

    
}
