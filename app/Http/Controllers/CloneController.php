<?php

namespace App\Http\Controllers;

use App\Cabecera_encuesta;
use App\Empresa;
use App\Rubro;
use Illuminate\Http\Request;

class CloneController extends Controller
{
    public function cloneCofcoForm()
    {
        return view('encuestas.clone.clonar_cofco')->with('toast', false);
    }

    public function cloneCofco(Request $request){
        ini_set('max_execution_time', 0);
        ini_set('memory_limit', '500M');
        $periodo = $request->periodo;
        $empresasIn = [
            '28',
            '29',
            '30',
            '31',
            '32',
            '33',
            '34',
            '35',
            '36',
            '37',
            '38',
            '39',
            '40',
            '41',
            '43',
            '44',
            '45',
            '46',
            '47',
            '48',
            '49',
            '50',
            '51',
            '52',
            '85',
            '87',
            '89',
            '106',
            '110',
            '111',
            '125',
            '162',
        ];
        $encuestas = Cabecera_encuesta::where('periodo', $periodo)
                                      ->where('rubro_id', 2)
                                      ->whereIn('empresa_id', $empresasIn)
                                      ->get();
        $empresas = [
            '28'	=> '201',
            '29'	=> '202',
            '30'	=> '203',
            '31'	=> '204',
            '32'	=> '205',
            '33'	=> '206',
            '34'	=> '207',
            '35'	=> '208',
            '36'	=> '209',
            '37'	=> '210',
            '38'	=> '211',
            '39'	=> '212',
            '40'	=> '213',
            '41'	=> '214',
            '43'	=> '215',
            '44'	=> '216',
            '45'	=> '217',
            '46'	=> '218',
            '47'	=> '219',
            '48'	=> '220',
            '49'	=> '221',
            '50'	=> '222',
            '51'	=> '223',
            '52'	=> '224',
            '85'	=> '225',
            '87'	=> '226',
            '89'	=> '227',
            '106'	=> '228',
            '110'	=> '229',
            '111'	=> '230',
            '125'	=> '231',
            '162'	=> '232',
        ];
        foreach($encuestas as $encuesta){
            $cabecera = $encuesta->replicate();
            $cabecera->rubro_id = 17;

            $cabecera->empresa_id = $empresas[$cabecera->empresa_id];
            $cabecera->save();
            $encuestaCargo = $encuesta->encuestasCargo;
            foreach($encuestaCargo as $cargo){
                $newCargo = $cargo->replicate();
                $newCargo->cabecera_encuesta_id = $cabecera->id;
                $newCargo->save();
                $detalle = $cargo->detalleEncuestas;
                if($detalle){
                    $newDetalle = $detalle->replicate();
                    $newDetalle->cabecera_encuesta_id = $cabecera->id;
                    $newDetalle->encuestas_cargo_id = $newCargo->id;
                    $newDetalle->save();
                }
            }

        }
        $toast = true;

        return redirect()->route('clonar.cofco.form')->with('toast', $toast);
    }


    public function cloneAMXForm()
    {
        return view('encuestas.clone.clonar_amx')->with('toast', false);
    }

    public function cloneAMX(Request $request){
        ini_set('max_execution_time', 0);
        ini_set('memory_limit', '1000M');

        $encuestasId = [
            '642',
            '643',
            '644',
            '645',
            '646',
            '647',
            '648',
            '649',
            '650',
            '651',
            '652',
            '653',
            '654',
            '668',
            '526',
            '505',
            '514',
            '511',
            '556',
            '515',
            '508',
            '513',
            '518',
            '510',
            '677',
            '670',
            '695',
            '680',
            '696',
            '684',
            '678',
            '691',
            '681',
            '674',
            '694',
            '697',
            '687',
            '688',
            '599',
            '590',
            '609',
            '593',
            '596',
            '594',
            '598',
            '608',
            '612',
            '346',
            '345',
            '348',
            '349',
            '350',
            '351',
            '352',
            '353',
            '354',
            '355',
            '356',
            '357',
            '387',
            '469',
        ];

        $encuestas = Cabecera_encuesta::whereIn('id', $encuestasId)
                                      ->get();
        $empresas = [
            '1'     =>	'187',
            '2'     =>	'188',
            '3'     =>	'189',
            '4'     =>	'190',
            '5'     =>	'191',
            '6'     =>	'192',
            '7'     =>	'193',
            '8'     =>	'194',
            '9'     =>	'195',
            '10'    =>	'196',
            '11'    =>	'197',
            '12'    =>	'198',
            '53'    =>	'199',
            '143'   =>	'200',
            '84'    =>	'234',
            '56'    =>	'235',
            '67'    =>	'236',
            '64'    =>	'233',
            '163'   =>	'237',
            '68'    =>	'238',
            '61'    =>	'239',
            '66'    =>	'240',
            '71'    =>	'241',
            '63'    =>	'242',
            '207'   =>	'243',
            '213'   =>	'244',
            '214'   =>	'245',
            '202'   =>	'246',
            '206'   =>	'247',
            '201'   =>	'248',
            '215'   =>	'249',
            '208'   =>	'250',
            '218'   =>	'251',
            '203'   =>	'252',
            '225'   =>	'253',
            '210'   =>	'254',
            '204'   =>	'255',
            '224'   =>	'256',
            '27'    =>	'270',
            '26'    =>	'271',
            '19'    =>	'272',
            '22'    =>	'273',
            '25'    =>	'274',
            '17'    =>	'275',
            '15'    =>	'276',
            '16'    =>	'277',
            '18'    =>	'278',
            '109'   =>	'279',
            '112'   =>	'280',
            '113'   =>	'281',
            '114'   =>	'282',
            '115'   =>	'283',
            '116'   =>	'284',
            '117'   =>	'285',
            '118'   =>	'286',
            '120'   =>	'289',
            '121'   =>	'287',
            '122'   =>	'288',
            '123'   =>	'290',
            '128'   =>	'291',
            '148'   =>	'292',
        ];
       // dd($encuestas->where('empresa_id', '63'));
        
        foreach($encuestas as $encuesta){
            $cabecera = $encuesta->replicate();
            $cabecera->rubro_id = 16;
            $cabecera->periodo = '03/2021';
            $cabecera->empresa_id = $empresas[$cabecera->empresa_id];
            $cabecera->save();
            $encuestaCargo = $encuesta->encuestasCargo;
            foreach($encuestaCargo as $cargo){
                $newCargo = $cargo->replicate();
                $newCargo->cabecera_encuesta_id = $cabecera->id;
                $newCargo->save();
                $detalle = $cargo->detalleEncuestas;
                if($detalle){
                    $newDetalle = $detalle->replicate();
                    $newDetalle->cabecera_encuesta_id = $cabecera->id;
                    $newDetalle->encuestas_cargo_id = $newCargo->id;
                    $newDetalle->save();
                }
            }

        }
        $toast = true;

        return redirect()->route('clonar.amx.form')->with('toast', $toast);
    }

    public function index(){

        $rubros = Rubro::pluck('descripcion', 'id');
        $empresas = Empresa::pluck('descripcion', 'id');

        return view('encuestas.clone.index')->with('rubros', $rubros)
                                            ->with('empresas', $empresas);
                                            

    }

    public function cloneClub(Request $request)
    {
        ini_set('max_execution_time', 0);
        ini_set('memory_limit', '1000M');
        if(!$request->empresas){
            return redirect()->route('clone.error.missing', 'Empresas origen y destino');
        }

        if(!$request->rubro_id){
            return redirect()->route('clone.error.missing', 'CLUB DESTINO');
        }

        if(!$request->periodo){
            return redirect()->route('clone.error.missing', 'PERIODO');
        }

        $empresas = json_decode($request->empresas);
        foreach ($empresas as $empresa) {
            //dd($empresa);
            $encuesta = Cabecera_encuesta::find($empresa->encuesta);
            if($encuesta){
                $cabecera = $encuesta->replicate();
                $cabecera->rubro_id = $request->rubro_id;
                $cabecera->periodo = $request->periodo;
                $cabecera->empresa_id = $empresa->id;
                $cabecera->save();
                $encuestaCargo = $encuesta->encuestasCargo;
                foreach($encuestaCargo as $cargo){
                    $newCargo = $cargo->replicate();
                    $newCargo->cabecera_encuesta_id = $cabecera->id;
                    $newCargo->save();
                    $detalle = $cargo->detalleEncuestas;
                    if($detalle){
                        $newDetalle = $detalle->replicate();
                        $newDetalle->cabecera_encuesta_id = $cabecera->id;
                        $newDetalle->encuestas_cargo_id = $newCargo->id;
                        $newDetalle->save();
                    }
                }
            }
        }
        

        return redirect()->route('clone.success');
    }

    public function success()
    {
        return view('encuestas.clone.success');
    }

    public function errorMissing($field)
    {
        return view('encuestas.clone.errors.error_missing')->with('field', $field);
    }


}
