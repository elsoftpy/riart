<?php

namespace App\Traits;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;
use App\Cabecera_encuesta;
use App\Encuestas_cargo;
use App\Detalle_encuesta;
use App\Detalle_encuestas_nivel;
use App\Cargos_rubro;
use App\Empresa;
use App\Ficha_dato;
use App\Nivel;
use App\Nivel_en;
use App\Cargo;
use App\Cargo_en;
use App\Rubro;
use App\User;
use PHPExcel_Worksheet_Drawing;
use Hash;
use DB;
use Auth;
use Excel;
use Session;
use Lang;

trait ReportTrait{
    public function countEmergentesSegmento($rubro, $subRubro, $periodo){

        $results = DB::select( DB::raw(
            "SELECT count(distinct cargo_id) cargos
               FROM encuestas_cargos e 
              WHERE cabecera_encuesta_id in ( select id 
                                                from cabecera_encuestas
                                               where rubro_id = :rubro
                                                 and sub_rubro_id = :subRubro
                                                 and periodo = :periodo)"), 
            array('rubro' => $rubro, 'subRubro' => $subRubro, 'periodo' => $periodo));
            //return $results;
            if($results){
                $count = $results[0]->cargos;
            }else{
                $count = 0;
            }
            return $count;
    }
    public function cargaDetalle($item, &$itemArray){
        
        $variableAnual = false;
        $efectivoTotal = false;
        $efectivoGarantizado = false;
        $salarioEmpresa = 0;
        $variableAnualEmp = 0;
        //dd($item, $itemArray);
        foreach ($item as $key => $value) {
            switch ($value["Concepto"]) {
                case "Comision":
                    $this->cargador($value, $itemArray, false);
                    break;
                case Lang::get('reportReport.concept_salary'):
                    $this->cargador($value, $itemArray, true);
                    $salarioEmpresa = intval(str_replace(".", "", $value["Empresa"]));
                    break;
                case Lang::get('reportReport.concept_annual_cash'):
                    $efectivoGarantizado = true;
                    $this->cargador($value, $itemArray, false);
                    break;
                case Lang::get('reportReport.concept_variable_pay'):
                    $variableAnual = true;
                    $this->cargador($value, $itemArray, false);
                    $variableAnualEmp = $value["Empresa"];
                    break;
                case Lang::get('reportReport.concept_total_incentives'):
                    $this->cargador($value, $itemArray, false);
                    break;
                case Lang::get('reportReport.concept_bonus'):
                    $this->cargador($value, $itemArray, false);
                    break;
                case Lang::get('reportReport.concept_total_compensation'):
                    $this->cargador($value, $itemArray, false);
                    break;
                case Lang::get('reportReport.concept_annual_cash_total'):
                    $efectivoTotal = true;
                    $efectivoTotalEmpresa = $value["Empresa"];
                    $this->cargador($value, $itemArray, false);
                    break;
                
            }
        }
       
        // comparativo salario base
        if($itemArray[4] > 0){
            $compMinSal = round($salarioEmpresa/$itemArray[4] - 1, 2); 
        }else{
            $compMinSal = 0;
        }
        if($itemArray[5] > 0){
            $comp25PercSal = round($salarioEmpresa/$itemArray[5] - 1, 2); 
        }else{
            $comp25PercSal = 0;
        }
        if($itemArray[6] > 0){
            $compPromSal = round($salarioEmpresa/$itemArray[6] - 1, 2); 
        }else{
            $compPromSal = 0;
        }
        if($itemArray[7] > 0){
            $compMedSal = round($salarioEmpresa/$itemArray[7] - 1, 2); 
        }else{
            $compMedSal = 0;
        }

        if($itemArray[8] > 0){
            $comp75PercSal = round($salarioEmpresa/$itemArray[8] - 1, 2); 
        }else{
            $comp75PercSal = 0;
        }
        if($itemArray[9] > 0){
            $compMaxSal = round($salarioEmpresa/$itemArray[9] - 1, 2); 
        }else{
            $compMaxSal = 0;
        }

        if($variableAnual){
            // comparativo variable anual
            if($itemArray[10] > 0){
                $compMinVar = round($salarioEmpresa/$itemArray[10] - 1, 2); 
            }else{
                $compMinVar = 0;
            }
            if($itemArray[11] > 0){
                $comp25PercVar = round($salarioEmpresa/$itemArray[11] - 1 , 2); 
            }else{
                $comp25PercVar = 0;
            }
            if($itemArray[12] > 0){
                $compPromVar = round($salarioEmpresa/$itemArray[12] - 1 , 2); 
            }else{
                $compPromVar = 0;
            }
            if($itemArray[13] > 0){
                $compMedVar =  round($salarioEmpresa/$itemArray[13] - 1, 2); 
            }else{
                $compMedVar = 0;
            }        
            if($itemArray[14] > 0){
                $comp75PercVar = round($salarioEmpresa/$itemArray[14] - 1, 2); 
            }else{
                $comp75PercVar = 0;
            }        
            if($itemArray[15] > 0){
                $compMaxVar = round($salarioEmpresa/$itemArray[15] - 1, 2); 
            }else{
                $compMaxVar = 0;
            }    
                
            if($itemArray[34] > 0){
                $ratioSalBaseTotalEfectivoMin = round(($itemArray[4]*12)/$itemArray[34], 2);
            }else{
                $ratioSalBaseTotalEfectivoMin = 0;
            }
            if($itemArray[35] > 0){
                $ratioSalBaseTotalEfectivo25 = round(($itemArray[5]*12)/$itemArray[35], 2);
            }else{
                $ratioSalBaseTotalEfectivo25 = 0;
            }
            if($itemArray[36] > 0){
                $ratioSalBaseTotalEfectivoProm = round(($itemArray[6]*12)/$itemArray[36], 2);
            }else{
                $ratioSalBaseTotalEfectivoProm = 0;
            }
            if($itemArray[37] > 0){
                $ratioSalBaseTotalEfectivoMed = round(($itemArray[7]*12)/$itemArray[37], 2);
            }else{
                $ratioSalBaseTotalEfectivoMed = 0;
            }
            if($itemArray[38] > 0){
                $ratioSalBaseTotalEfectivo75 = round(($itemArray[8]*12)/$itemArray[38], 2);
            }else{
                $ratioSalBaseTotalEfectivo75 = 0;
            }
            if($itemArray[39] > 0){
                
                if($itemArray[9] > 0){
                    $ratioSalBaseTotalEfectivoMax = round(($itemArray[9]*12)/$itemArray[39], 2);
                }else{
                    $ratioSalBaseTotalEfectivoMax = 0;
                }
                
            }else{
                $ratioSalBaseTotalEfectivoMax = 0;
            }
            array_push( $itemArray, 
            $compMinSal,
            $comp25PercSal, 
            $compPromSal, 
            $compMedSal, 
            $comp75PercSal, 
            $compMaxSal, 
            $compMinVar, 
            $comp25PercVar, 
            $compPromVar, 
            $compMedVar, 
            $comp75PercVar, 
            $compMaxVar,
            $ratioSalBaseTotalEfectivoMin,
            $ratioSalBaseTotalEfectivo25,
            $ratioSalBaseTotalEfectivoProm,
            $ratioSalBaseTotalEfectivoMed,
            $ratioSalBaseTotalEfectivo75,
            $ratioSalBaseTotalEfectivoMax);
        }elseif($efectivoTotal){
            // comparativo efectivo total anual
            if($itemArray[34] > 0){
                $ratioSalBaseTotalEfectivoMin = round(($itemArray[4]*12)/$itemArray[34], 2);
                $ratioSalEmpresaTotalEfectivoMin = round($efectivoTotalEmpresa/$itemArray[34], 2);
            }else{
                $ratioSalBaseTotalEfectivoMin = 0;
                $ratioSalEmpresaTotalEfectivoMin = 0;
            }
            if($itemArray[35] > 0){
                $ratioSalBaseTotalEfectivo25 = round(($itemArray[5]*12)/$itemArray[35], 2);
                $ratioSalEmpresaTotalEfectivo25 = round($efectivoTotalEmpresa/$itemArray[35], 2);
            }else{
                $ratioSalBaseTotalEfectivo25 = 0;
                $ratioSalEmpresaTotalEfectivo25 = 0;
            }
            if($itemArray[36] > 0){
                $ratioSalBaseTotalEfectivoProm = round(($itemArray[6]*12)/$itemArray[36], 2);
                $ratioSalEmpresaTotalEfectivoProm = round($efectivoTotalEmpresa/$itemArray[36], 2);
            }else{
                $ratioSalBaseTotalEfectivoProm = 0;
                $ratioSalEmpresaTotalEfectivoProm = 0;
            }
            if($itemArray[37] > 0){
                $ratioSalBaseTotalEfectivoMed = round(($itemArray[7]*12)/$itemArray[37], 2);
                $ratioSalEmpresaTotalEfectivoMed = round($efectivoTotalEmpresa/$itemArray[37], 2);
            }else{
                $ratioSalBaseTotalEfectivoMed = 0;
                $ratioSalEmpresaTotalEfectivoMed = 0;
            }
            if($itemArray[38] > 0){
                $ratioSalBaseTotalEfectivo75 = round(($itemArray[8]*12)/$itemArray[38], 2);
                $ratioSalEmpresaTotalEfectivo75 = round($efectivoTotalEmpresa/$itemArray[38], 2);
            }else{
                $ratioSalBaseTotalEfectivo75 = 0;
                $ratioSalEmpresaTotalEfectivo75 = 0;
            }
            if($itemArray[39] > 0){
                $ratioSalBaseTotalEfectivoMax = round(($itemArray[9]*12)/$itemArray[39], 2);
                $ratioSalEmpresaTotalEfectivoMax = round($efectivoTotalEmpresa/$itemArray[39], 2);
            }else{
                $ratioSalBaseTotalEfectivoMax = 0;
                $ratioSalEmpresaTotalEfectivoMax = 0;
            }
            
            // esto debe estar en la línea 251
            array_push( $itemArray, 
                        $compMinSal,
                        $comp25PercSal, 
                        $compPromSal, 
                        $compMedSal, 
                        $comp75PercSal, 
                        $compMaxSal, 
                        $ratioSalBaseTotalEfectivoMin,
                        $ratioSalBaseTotalEfectivo25,
                        $ratioSalBaseTotalEfectivoProm,
                        $ratioSalBaseTotalEfectivoMed,
                        $ratioSalBaseTotalEfectivo75,
                        $ratioSalBaseTotalEfectivoMax, 
                        $ratioSalEmpresaTotalEfectivoMin,
                        $ratioSalEmpresaTotalEfectivo25,
                        $ratioSalEmpresaTotalEfectivoProm,
                        $ratioSalEmpresaTotalEfectivoMed,
                        $ratioSalEmpresaTotalEfectivo75,
                        $ratioSalEmpresaTotalEfectivoMax
                    );            
        }else{

            if($itemArray[10] > 0){
                $ratioSalBaseTotalEfectivoMin = round(($itemArray[4]*12)/$itemArray[10], 2);
            }else{
                $ratioSalBaseTotalEfectivoMin = 0;
            }
            if($itemArray[11] > 0){
                $ratioSalBaseTotalEfectivo25 = round(($itemArray[5]*12)/$itemArray[11], 2);
            }else{
                $ratioSalBaseTotalEfectivo25 = 0;
            }
            if($itemArray[12] > 0){
                $ratioSalBaseTotalEfectivoProm = round(($itemArray[6]*12)/$itemArray[12], 2);
            }else{
                $ratioSalBaseTotalEfectivoProm = 0;
            }
            if($itemArray[13] > 0){
                $ratioSalBaseTotalEfectivoMed = round(($itemArray[7]*12)/$itemArray[13], 2);
            }else{
                $ratioSalBaseTotalEfectivoMed = 0;
            }
            if($itemArray[14] > 0){
                $ratioSalBaseTotalEfectivo75 = round(($itemArray[8]*12)/$itemArray[14], 2);
            }else{
                $ratioSalBaseTotalEfectivo75 = 0;
            }
            if($itemArray[15] > 0){
                $ratioSalBaseTotalEfectivoMax = round(($itemArray[9]*12)/$itemArray[15], 2);
            }else{
                $ratioSalBaseTotalEfectivoMax = 0;
            }
            
            array_push( $itemArray, 
            $compMinSal,
            $comp25PercSal, 
            $compPromSal, 
            $compMedSal, 
            $comp75PercSal, 
            $compMaxSal, 
            $ratioSalBaseTotalEfectivoMin,
            $ratioSalBaseTotalEfectivo25,
            $ratioSalBaseTotalEfectivoProm,
            $ratioSalBaseTotalEfectivoMed,
            $ratioSalBaseTotalEfectivo75,
            $ratioSalBaseTotalEfectivoMax);            
        }
        
        
    }
    public function cargaDetalleComparativo($value, &$itemArray, $contNuevo){
        
        if($contNuevo){
            array_push($itemArray, $value["Ocupantes"]);
            array_push($itemArray, $value["Min"]);
            array_push($itemArray, $value["25 Percentil"]);
            array_push($itemArray, round($value["Promedio"], 2));
            array_push($itemArray, $value["Mediana"]);
            array_push($itemArray, $value["75 Percentil"]);
            array_push($itemArray, $value["Max"]);       
        }else{
            array_push($itemArray, $value["Min Ant"]);
            array_push($itemArray, $value["25P Ant"]);
            array_push($itemArray, round($value["Prom Ant"], 2));
            array_push($itemArray, $value["Med Ant"]);
            array_push($itemArray, $value["75P Ant"]);
            array_push($itemArray, $value["Max Ant"]);
            array_push($itemArray, $value["Min"]);
            array_push($itemArray, $value["25 Percentil"]);
            array_push($itemArray, round($value["Promedio"], 2));
            array_push($itemArray, $value["Mediana"]);
            array_push($itemArray, $value["75 Percentil"]);
            array_push($itemArray, $value["Max"]);  
            array_push($itemArray, $value["Brecha Min"]);
            array_push($itemArray, $value["Brecha 25P"]);
            array_push($itemArray, $value["Brecha Prom"]);
            array_push($itemArray, $value["Brecha Med"]);
            array_push($itemArray, $value["Brecha 75P"]);
            array_push($itemArray, $value["Brecha Max"]);     
        }
    }
    public function cargador($value, &$itemArray, $casos){
            if($casos){
                array_push($itemArray, $value["ocupantes"]);
                array_push($itemArray, $value["Casos"]);            
            }
            //array_push($itemArray, $value["Concepto"]);
            array_push($itemArray, $value["Min"]);
            array_push($itemArray, $value["25 Percentil"]);
            array_push($itemArray, round($value["Promedio"], 2));
            array_push($itemArray, $value["Mediana"]);
            array_push($itemArray, $value["75 Percentil"]);
            array_push($itemArray, $value["Max"]);       
    }


    public function segmenter( &$collection, 
                                $countCasosSeg, 
                                $detalle, 
                                $countCasos, 
                                $countCasosGratif, 
                                $countCasosAguinaldo, 
                                //$countCasosBeneficios, 
                                $countCasosBono, 
                                $dbClienteEnc, 
                                $rubro, 
                                $segmento, 
                                $dbCargo, 
                                $muestraComision){
        if($rubro == 1 || $rubro == 15 ){ // Bancos
            $salariosBase = $detalle->where('salario_base', '>', '0')->pluck('salario_base');
            $salarioMin = $salariosBase->min();
            $salarioMax = $salariosBase->max();
            $salarioProm = $salariosBase->avg();
            $salarioMed = $this->median($salariosBase);
            $salario25Per = $this->percentile(25,$salariosBase);
            $salario75Per = $this->percentile(75, $salariosBase);

            // Salario Base y Anual
            $this->pusher(  $collection, 
                            $countCasos, 
                            Lang::get('reportReport.concept_salary'),
                            $salarioMin,
                            $salarioMax,
                            round($salarioProm, 0),
                            round($salarioMed, 0),
                            round($salario25Per, 0),
                            round($salario75Per, 0),
                            $dbClienteEnc->salario_base, 
                            $segmento, 
                            $dbCargo);        
     
            $salariosBaseAnual = $salariosBase->map(function($item){
                return $item * 12;
            });

            $salarioAnualMin = $salariosBaseAnual->min();
            $salarioAnualMax = $salariosBaseAnual->max();
            $salarioAnualProm = $salariosBaseAnual->avg();
            $salarioAnualMed = $this->median($salariosBaseAnual);
            $salarioAnual25Per = $this->percentile(25,$salariosBaseAnual);
            $salarioAnual75Per = $this->percentile(75, $salariosBaseAnual);
            $this->pusher(  $collection, 
                            $countCasos, 
                            Lang::get('reportReport.concept_annual_salary'),
                            $salarioAnualMin,
                            $salarioAnualMax,
                            round($salarioAnualProm, 0),
                            round($salarioAnualMed, 0),
                            round($salarioAnual25Per, 0),
                            round($salarioAnual75Per, 0),
                            $dbClienteEnc->salario_base * 12, 
                            $segmento, 
                            $dbCargo);

            // Gratificacion
            $gratificaciones = $detalle->where('gratificacion', '>', '0')->pluck('gratificacion');
            $gratificacionMin = $gratificaciones->min();
            $gratificacionMax = $gratificaciones->max();
            $gratificacionProm = $gratificaciones->avg();
            $gratificacionMed = $this->median($gratificaciones);
            $gratificacion25Per = $this->percentile(25, $gratificaciones);
            $gratificacion75Per = $this->percentile(75, $gratificaciones);

            $this->pusher(  $collection, 
                            $countCasosGratif, 
                            Lang::get('reportReport.concept_annual_gratif.'),
                            $gratificacionMin,
                            $gratificacionMax,
                            round($gratificacionProm, 0),
                            round($gratificacionMed, 0),
                            round($gratificacion25Per, 0),
                            round($gratificacion75Per, 0),
                            $dbClienteEnc->gratificacion, 
                            $segmento, 
                            $dbCargo);

            //Aguinaldo
            $aguinaldos = $detalle->where('aguinaldo', '>', '0')->pluck('aguinaldo');
            $aguinaldoMin = $aguinaldos->min();
            $aguinaldoMax = $aguinaldos->max();
            $aguinaldoProm = $aguinaldos->avg();
            $aguinaldoMed = $this->median($aguinaldos);
            $aguinaldo25Per = $this->percentile(25, $aguinaldos);
            $aguinaldo75Per = $this->percentile(75, $aguinaldos);

            $this->pusher(  $collection, 
                            $countCasosAguinaldo, 
                            Lang::get('reportReport.concept_13month'),
                            $aguinaldoMin,
                            $aguinaldoMax,
                            round($aguinaldoProm, 0),
                            round($aguinaldoMed, 0),
                            round($aguinaldo25Per, 0),
                            round($aguinaldo75Per, 0),
                            $dbClienteEnc->aguinaldo, 
                            $segmento, 
                            $dbCargo);

            // Efectivo Anual Garantizado
            $detalle = $detalle->map(function($item){
                $item['efectivo_anual_garantizado'] = ($item['salario_base'] * 12) + 
                                                      $item['aguinaldo'] + 
                                                      $item['gratificacion'];
                return $item;
            });                                                
           
            $efectivoMin = $detalle->pluck('efectivo_anual_garantizado')->min();
            $efectivoMax = $detalle->pluck('efectivo_anual_garantizado')->max();
            $efectivoProm = $detalle->pluck('efectivo_anual_garantizado')->avg();
            $efectivoMed = $this->median($detalle->pluck('efectivo_anual_garantizado'));
            $efectivo25Per = $this->percentile(25, $detalle->pluck('efectivo_anual_garantizado'));
            $efectivo75Per = $this->percentile(75, $detalle->pluck('efectivo_anual_garantizado'));
            
            $found = $detalle->where('cabecera_encuesta_id', $dbClienteEnc->cabecera_encuesta_id)
                             ->first();
            if($found){
                $efectivoEmpresa = $found->efectivo_anual_garantizado;
            }else{
                $efectivoEmpresa = 0;    
            }
                                                
            $this->pusher(  $collection, 
                            $countCasos, 
                            Lang::get('reportReport.concept_annual_cash'),
                            $efectivoMin,
                            $efectivoMax,
                            round($efectivoProm, 0),
                            round($efectivoMed, 0),
                            round($efectivo25Per, 0),
                            round($efectivo75Per, 0),
                            $efectivoEmpresa, 
                            $segmento, 
                            $dbCargo);


            //Adicional
            $casosAdicionalesBancos = $detalle->where('adicionales_bancos', '>', '0')->unique('cabecera_encuesta_id')->count();
            $adicionalesBancos = $detalle->where('adicionales_bancos', '>', '0')->pluck('adicionales_bancos');
            $adicionalesMin = $adicionalesBancos->min();
            $adicionalesMax = $adicionalesBancos->max();
            $adicionalesProm = $adicionalesBancos->avg();
            $adicionalesMed = $this->median($adicionalesBancos);
            $adicionales25Per = $this->percentile(25, $adicionalesBancos);
            $adicionales75Per = $this->percentile(75, $adicionalesBancos);
 
            $this->pusher(  $collection, 
                            $casosAdicionalesBancos, 
                            Lang::get('reportReport.concept_total_incentives'),
                            $adicionalesMin,
                            $adicionalesMax,
                            round($adicionalesProm, 0),
                            round($adicionalesMed, 0),
                            round($adicionales25Per, 0),
                            round($adicionales75Per, 0),
                            $dbClienteEnc->adicionales_bancos, 
                            $segmento, 
                            $dbCargo);


            //Bono
            $bonos = $detalle->where('bono_anual', '>', '0')->pluck('bono_anual');
            $bonoMin = $bonos->min();
            $bonoMax = $bonos->max();
            $bonoProm = $bonos->avg();
            $bonoMed = $this->median($bonos);
            $bono25Per = $this->percentile(25, $bonos);
            $bono75Per = $this->percentile(75, $bonos);

            $this->pusher(  $collection, 
                            $countCasosBono, 
                            Lang::get('reportReport.concept_bonus'),
                            $bonoMin,
                            $bonoMax,
                            $bonoProm,
                            $bonoMed,
                            $bono25Per,
                            $bono75Per,
                            $dbClienteEnc->bono_anual, 
                            $segmento, 
                            $dbCargo);

            if($muestraComision){
                //Comisión
                $countCasosComision = $detalle->where('comision', '>', '0')->unique('cabecera_encuesta_id')->count();
                $comision = $detalle->where('comision', '>', '0')->pluck('comision');
                $comisionMin = $comision->min();
                $comisionMax = $comision->max();
                $comisionProm = $comision->avg();
                $comisionMed = $this->median($comision);
                $comision25Per = $this->percentile(25, $comision);
                $comision75Per = $this->percentile(75, $comision);

                $this->pusher(  $collection, 
                                $countCasosComision, 
                                //Lang::get('reportReport.concept_bonus'),
                                "Comision",
                                $comisionMin,
                                $comisionMax,
                                round($comisionProm, 0) * 12,
                                round($comisionMed, 0) * 12,
                                round($comision25Per, 0) * 12,
                                round($comision75Per, 0) *12,
                                $dbClienteEnc->comision, 
                                $segmento, 
                                $dbCargo);

            }
            
                            
            //Aguinaldo Impactado
            $aguinaldoImpMin = 0;
            $aguinaldoImpMax = 0;
            $aguinaldoImpProm = 0;
            $aguinaldoImpMed = 0;
            $aguinaldoImp25Per = 0;
            $aguinaldoImp75Per = 0;
            $aguinaldoImpEmpresa = 0;

            $detalle = $detalle->map(function($item){
                $item['aguinaldo_impactado'] = (($item['salario_base'] * 12) + 
                                                $item['gratificacion'] + 
                                                $item['bono_anual'] +
                                                $item['adicionales_bancos']+
                                                $item['comision'] * 12)/12;
                return $item;
            });                                                
           
            $aguinaldoImpMin = $detalle->pluck('aguinaldo_impactado')->min();
            $aguinaldoImpMax = $detalle->pluck('aguinaldo_impactado')->max();
            $aguinaldoImpProm = $detalle->pluck('aguinaldo_impactado')->avg();
            $aguinaldoImpMed = $this->median($detalle->pluck('aguinaldo_impactado'));
            $aguinaldoImp25Per = $this->percentile(25, $detalle->pluck('aguinaldo_impactado'));
            $aguinaldoImp75Per = $this->percentile(75, $detalle->pluck('aguinaldo_impactado'));
            
            $found = $detalle->where('cabecera_encuesta_id', $dbClienteEnc->cabecera_encuesta_id)
                             ->first();
            if($found){
                $aguinaldoImpEmpresa = $found->aguinaldo_impactado;
            }else{
                $aguinaldoImpEmpresa = 0;    
            }

            $this->pusher(  $collection, 
                            $countCasos, 
                            Lang::get('reportReport.concept_13month_impacted'),
                            $aguinaldoImpMin, 
                            $aguinaldoImpMax, 
                            round($aguinaldoImpProm, 0),
                            round($aguinaldoImpMed, 0),
                            round($aguinaldoImp25Per, 0),
                            round($aguinaldoImp75Per, 0),
                            $aguinaldoImpEmpresa,
                            $segmento, 
                            $dbCargo);

            //Total Compensación Efectiva anual
            $detalle = $detalle->map(function($item){
                $item['total_comp_anual'] = ($item['salario_base'] * 12) + 
                                             $item['gratificacion'] + 
                                             $item['bono_anual'] +
                                             $item['adicionales_bancos']+
                                             $item['comision'] *12 +
                                             $item['aguinaldo'];
                return $item;
            });                                                
           
            $totalCompAnualMin = $detalle->pluck('total_comp_anual')->min();
            $totalCompAnualMax = $detalle->pluck('total_comp_anual')->max();
            $totalCompAnualProm = $detalle->pluck('total_comp_anual')->avg();
            $totalCompAnualMed = $this->median($detalle->pluck('total_comp_anual'));
            $totalCompAnual25Per = $this->percentile(25, $detalle->pluck('total_comp_anual'));
            $totalCompAnual75Per = $this->percentile(75, $detalle->pluck('total_comp_anual'));
            
            $found = $detalle->where('cabecera_encuesta_id', $dbClienteEnc->cabecera_encuesta_id)
                             ->first();
            if($found){
                $totalCompAnualEmpresa = $found->total_comp_anual;
            }else{
                $totalCompAnualEmpresa = 0;    
            }


            $this->pusher(  $collection, 
                            $countCasos, 
                            Lang::get('reportReport.concept_total_compensation'),
                            $totalCompAnualMin, 
                            $totalCompAnualMax, 
                            round($totalCompAnualProm, 0),
                            round($totalCompAnualMed, 0),
                            round($totalCompAnual25Per, 0),
                            round($totalCompAnual75Per, 0),
                            $totalCompAnualEmpresa,
                            $segmento, 
                            $dbCargo);

        }elseif ($rubro == 4) {  // Navieras
            // Salario Base
            $salariosBase = $detalle->where('salario_base', '>', '0')->pluck('salario_base');
            
            $salarioMin = $salariosBase->min();
            $salarioMax = $salariosBase->max();
            $salarioProm = $salariosBase->avg();
            $salarioMed = $this->median($salariosBase);
            $salario25Per = $this->percentile(25,$salariosBase);
            $salario75Per = $this->percentile(75, $salariosBase);
            //dd($salariosBase);
            $this->pusher(  $collection, 
                            $countCasos, 
                            Lang::get('reportReport.concept_salary'),
                            $salarioMin,
                            $salarioMax,
                            round($salarioProm, 0),
                            round($salarioMed, 0),
                            round($salario25Per, 0),
                            round($salario75Per, 0),
                            $dbClienteEnc->salario_base,
                            $segmento, 
                            $dbCargo);   
                            
                            
            // Salario Base Anual     
            $salariosBaseAnual = $salariosBase->map(function($item){
                return $item * 12;
            });

            $salarioAnualMin = $salariosBaseAnual->min();
            $salarioAnualMax = $salariosBaseAnual->max();
            $salarioAnualProm = $salariosBaseAnual->avg();
            $salarioAnualMed = $this->median($salariosBaseAnual);
            $salarioAnual25Per = $this->percentile(25,$salariosBaseAnual);
            $salarioAnual75Per = $this->percentile(75, $salariosBaseAnual);
            $this->pusher(  $collection, 
                            $countCasos, 
                            Lang::get('reportReport.concept_annual_salary'),
                            $salarioAnualMin,
                            $salarioAnualMax,
                            round($salarioAnualProm, 0),
                            round($salarioAnualMed, 0),
                            round($salarioAnual25Per, 0), 
                            round($salarioAnual75Per, 0),
                            $dbClienteEnc->salario_base * 12,
                            $segmento, 
                            $dbCargo);
            //Aguinaldo
            $aguinaldos = $detalle->where('aguinaldo', '>', '0')->pluck('aguinaldo');
            $aguinaldoMin = $aguinaldos->min();
            $aguinaldoMax = $aguinaldos->max();
            $aguinaldoProm = $aguinaldos->avg();
            $aguinaldoMed = $this->median($aguinaldos);
            $aguinaldo25Per = $this->percentile(25, $aguinaldos);
            $aguinaldo75Per = $this->percentile(75, $aguinaldos);

            $this->pusher(  $collection, 
                            $countCasosAguinaldo, 
                            Lang::get('reportReport.concept_13month'),
                            $aguinaldoMin,
                            $aguinaldoMax,
                            round($aguinaldoProm, 0),
                            round($aguinaldoMed, 0),
                            round($aguinaldo25Per, 0),
                            round($aguinaldo75Per, 0),
                            $dbClienteEnc->aguinaldo,
                            $segmento, 
                            $dbCargo);
                       
            // Variable Anual
            $plusRendimiento = $detalle->where('plus_rendimiento', '>', '0')->pluck('plus_rendimiento');
            $plusMin = $plusRendimiento->min();
            $plusMax = $plusRendimiento->max();
            $plusProm = $plusRendimiento->avg();
            $plusMed = $this->median($plusRendimiento);
            $plus25Per = $this->percentile(25,$plusRendimiento);
            $plus75Per = $this->percentile(75, $plusRendimiento);
            $countCasosPlus = $detalle->where('plus_rendimiento', '>', '0')->unique('cabecera_encuesta_id')->count();
            $this->pusher(  $collection, 
                            $countCasosPlus, 
                            Lang::get('reportReport.concept_variable_pay'),
                            $plusMin,
                            $plusMax,
                            round($plusProm, 0),
                            round($plusMed,0),
                            round($plus25Per, 0),
                            round($plus75Per, 0),
                            $dbClienteEnc->plus_rendimiento,
                            $segmento, 
                            $dbCargo);        

            // Variable por Viaje
            $variableViaje = $detalle->where('variable_viaje', '>', '0')->pluck('variable_viaje');
            $plusMin = $variableViaje->min();
            $plusMax = $variableViaje->max();
            $plusProm = $variableViaje->avg();
            $plusMed = $this->median($variableViaje);
            $plus25Per = $this->percentile(25,$variableViaje);
            $plus75Per = $this->percentile(75, $variableViaje);
            $countCasosPlus = $detalle->where('variable_viaje', '>', '0')->unique('cabecera_encuesta_id')->count();
            $this->pusher(  $collection, 
                            $countCasosPlus, 
                            Lang::get('reportReport.concept_variable_pay_trip'),
                            $plusMin,
                            $plusMax,
                            round($plusProm, 0),
                            round($plusMed,0),
                            round($plus25Per, 0),
                            round($plus75Per, 0),
                            $dbClienteEnc->variable_viaje,
                            $segmento, 
                            $dbCargo);       

            // Adicional Amarre
            $adicionalAmarre = $detalle->where('adicional_amarre', '>', '0')->pluck('adicional_amarre');
            $amarreMin = $adicionalAmarre->min();
            $amarreMax = $adicionalAmarre->max();
            $amarreProm = $adicionalAmarre->avg();
            $amarreMed = $this->median($adicionalAmarre);
            $amarre25Per = $this->percentile(25,$adicionalAmarre);
            $amarre75Per = $this->percentile(75, $adicionalAmarre);
            $countCasosAmarre = $detalle->where('adicional_amarre', '>', '0')->unique('cabecera_encuesta_id')->count();
            $this->pusher(  $collection, 
                            $countCasosAmarre, 
                            Lang::get('reportReport.concept_mooring'),
                            $amarreMin,
                            $amarreMax,
                            round($amarreProm, 0),
                            round($amarreMed, 0),
                            round($amarre25Per, 0),
                            round($amarre75Per, 0),
                            $dbClienteEnc->adicional_amarre,
                            $segmento, 
                            $dbCargo);        


            // Adicional Tipo de Combustible
            $adicionalTipoCombustible = $detalle->where('adicional_tipo_combustible', '>', '0')->pluck('adicional_tipo_combustible');
            $TipoCombustibleMin = $adicionalTipoCombustible->min();
            $TipoCombustibleMax = $adicionalTipoCombustible->max();
            $TipoCombustibleProm = $adicionalTipoCombustible->avg();
            $TipoCombustibleMed = $this->median($adicionalTipoCombustible);
            $TipoCombustible25Per = $this->percentile(25,$adicionalTipoCombustible);
            $TipoCombustible75Per = $this->percentile(75, $adicionalTipoCombustible);
            $countCasosTipoCombustible = $detalle->where('adicional_tipo_combustible', '>', '0')->unique('cabecera_encuesta_id')->count();
            $this->pusher(  $collection, 
                            $countCasosTipoCombustible, 
                            Lang::get('reportReport.concept_fuel_type'),
                            $TipoCombustibleMin,
                            $TipoCombustibleMax,
                            round($TipoCombustibleProm, 0),
                            round($TipoCombustibleMed, 0),
                            round($TipoCombustible25Per, 0),
                            round($TipoCombustible75Per, 0),
                            $dbClienteEnc->adicional_tipo_combustible,
                            $segmento, 
                            $dbCargo);        

            // Adicional por disponiblidad/embarque
            $adicionalEmbarque = $detalle->where('adicional_embarque', '>', '0')->pluck('adicional_embarque');
            $embarqueMin = $adicionalEmbarque->min();
            $embarqueMax = $adicionalEmbarque->max();
            $embarqueProm = $adicionalEmbarque->avg();
            $embarqueMed = $this->median($adicionalEmbarque);
            $embarque25Per = $this->percentile(25,$adicionalEmbarque);
            $embarque75Per = $this->percentile(75, $adicionalEmbarque);
            $countCasosEmbarque = $detalle->where('adicional_embarque', '>', '0')->unique('cabecera_encuesta_id')->count();
            $this->pusher(  $collection, 
                            $countCasosEmbarque, 
                            Lang::get('reportReport.concept_shipping'),
                            $embarqueMin,
                            $embarqueMax,
                            round($embarqueProm, 0),
                            round($embarqueMed, 0),
                            round($embarque25Per, 0),
                            round($embarque75Per, 0),
                            $dbClienteEnc->adicional_embarque,
                            $segmento, 
                            $dbCargo);        

            // Adicional Carga
            $adicionalCarga = $detalle->where('adicional_carga', '>', '0')->pluck('adicional_carga');
            $cargaMin = $adicionalCarga->min();
            $cargaMax = $adicionalCarga->max();
            $cargaProm = $adicionalCarga->avg();
            $cargaMed = $this->median($adicionalCarga);
            $carga25Per = $this->percentile(25,$adicionalCarga);
            $carga75Per = $this->percentile(75, $adicionalCarga);
            $countCasosCarga = $detalle->where('adicional_carga', '>', '0')->unique('cabecera_encuesta_id')->count();
            $this->pusher(  $collection, 
                            $countCasosCarga, 
                            Lang::get('reportReport.concept_load'),
                            $cargaMin,
                            $cargaMax,
                            round($cargaProm, 0),
                            round($cargaMed, 0),
                            round($carga25Per, 0),
                            round($carga75Per, 0),
                            $dbClienteEnc->adicional_carga,
                            $segmento, 
                            $dbCargo);        

            // Total Adicional 
            $casosAdicionales = $detalle->where('adicionales_navieras', '>', '0')->unique('cabecera_encuesta_id')->count();   
            $adicionalAnual = $detalle->where('adicionales_navieras', '>', '0')->pluck('adicionales_navieras');
            $totalAdicionalMin = $adicionalAnual->min();
            $totalAdicionalMax = $adicionalAnual->max();
            $totalAdicionalProm = $adicionalAnual->avg();
            $totalAdicionalMed = $this->median($adicionalAnual);
            $totalAdicional25Per = $this->percentile(25, $adicionalAnual);
            $totalAdicional75Per = $this->percentile(75, $adicionalAnual);
            

            $this->pusher(  $collection, 
                            $casosAdicionales, 
                            Lang::get('reportReport.concept_total_incentives'),
                            $totalAdicionalMin,
                            $totalAdicionalMax,
                            round($totalAdicionalProm, 0),
                            round($totalAdicionalMed, 0),
                            round($totalAdicional25Per, 0),
                            round($totalAdicional75Per, 0),
                            $dbClienteEnc->adicionales_navieras,
                            $segmento, 
                            $dbCargo);

            //Bono
            $bonos = $detalle->where('bono_anual', '>', '0')->pluck('bono_anual');
            $bonoMin = $bonos->min();
            $bonoMax = $bonos->max();
            $bonoProm = $bonos->avg();
            $bonoMed = $this->median($bonos);
            $bono25Per = $this->percentile(25, $bonos);
            $bono75Per = $this->percentile(75, $bonos);

            $this->pusher(  $collection, 
                            $countCasosBono, 
                            Lang::get('reportReport.concept_bonus'),
                            $bonoMin,
                            $bonoMax,
                            round($bonoProm, 0),
                            round($bonoMed, 0),
                            round($bono25Per, 0),
                            round($bono75Per, 0),
                            $dbClienteEnc->bono_anual,
                            $segmento, 
                            $dbCargo);
            
            if($muestraComision){
                //Comisión
                $countCasosComision = $detalle->where('comision', '>', '0')->unique('cabecera_encuesta_id')->count();
                $comision = $detalle->where('comision', '>', '0')->pluck('comision');
                $comisionMin = $comision->min();
                $comisionMax = $comision->max();
                $comisionProm = $comision->avg();
                $comisionMed = $this->median($comision);
                $comision25Per = $this->percentile(25, $comision);
                $comision75Per = $this->percentile(75, $comision);

                $this->pusher(  $collection, 
                                $countCasosComision, 
                                //Lang::get('reportReport.concept_bonus'),
                                "Comision",
                                $comisionMin *12,
                                $comisionMax *12,
                                round($comisionProm, 0) *12,
                                round($comisionMed, 0) *12,
                                round($comision25Per, 0) *12,
                                round($comision75Per, 0) *12,
                                $dbClienteEnc->comision *12, 
                                $segmento, 
                                $dbCargo);

            }

            // Efectivo Total Anual
            $detalle = $detalle->map(function($item){
                $item['efectivo_total_anual'] =  $item['salario_base'] * 12 +
                                                 $item['aguinaldo'];
                return $item;
            });                                                
           
            $efectivoTotalMin = $detalle->pluck('efectivo_total_anual')->min();
            $efectivoTotalMax = $detalle->pluck('efectivo_total_anual')->max();
            $efectivoTotalProm = $detalle->pluck('efectivo_total_anual')->avg();
            $efectivoTotalMed = $this->median($detalle->pluck('efectivo_total_anual'));
            $efectivoTotal25Per = $this->percentile(25, $detalle->pluck('efectivo_total_anual'));
            $efectivoTotal75Per = $this->percentile(75, $detalle->pluck('efectivo_total_anual'));
            
            $found = $detalle->where('cabecera_encuesta_id', $dbClienteEnc->cabecera_encuesta_id)
                             ->first();
            if($found){
                $efectivoTotalEmpresa = $found->efectivo_total_anual;
            }else{
                $efectivoTotalEmpresa = 0;    
            }

            $this->pusher(  $collection, 
                            $countCasos, 
                            Lang::get('reportReport.concept_annual_cash_total'),
                            $efectivoTotalMin,
                            $efectivoTotalMax,
                            round($efectivoTotalProm, 0),
                            round($efectivoTotalMed, 0),
                            round($efectivoTotal25Per, 0),
                            round($efectivoTotal75Per, 0),
                            $efectivoTotalEmpresa,
                            $segmento, 
                            $dbCargo);
        

            //Beneficios
            $beneficiosNavieras = $detalle->where('beneficios_navieras', '>', '0')->pluck('beneficios_navieras');
            
            $beneficiosMin = $beneficiosNavieras->min();
            $beneficiosMax = $beneficiosNavieras->max();
            $beneficiosProm = $beneficiosNavieras->avg();
            $beneficiosMed = $this->median($beneficiosNavieras);
            $beneficios25Per = $this->percentile(25, $beneficiosNavieras);
            $beneficios75Per = $this->percentile(75, $beneficiosNavieras);
            $casosBeneficiosNavieras = $detalle->where('beneficios_navieras', '>', '0')->unique('cabecera_encuesta_id')->count();
            $this->pusher(  $collection, 
                            $casosBeneficiosNavieras, 
                            Lang::get('reportReport.concept_total_benefits'),
                            $beneficiosMin,
                            $beneficiosMax,
                            round($beneficiosProm, 0),
                            round($beneficiosMed, 0), 
                            round($beneficios25Per, 0),
                            round($beneficios75Per, 0),
                            $dbClienteEnc->beneficios_navieras,
                            $segmento, 
                            $dbCargo);

            //Aguinaldo Impactado
            $aguinaldoImpMin = 0;
            $aguinaldoImpMax = 0;
            $aguinaldoImpProm = 0;
            $aguinaldoImpMed = 0;
            $aguinaldoImp25Per = 0;
            $aguinaldoImp75Per = 0;
            $aguinaldoImpEmpresa = 0;

            $detalle = $detalle->map(function($item){
                $item['aguinaldo_impactado'] = (($item['salario_base'] * 12) + 
                                                $item['gratificacion'] + 
                                                $item['bono_anual'] +
                                                $item["comision"] * 12 +
                                                $item['adicionales_navieras'])/12;
                return $item;
            });                                                

            $aguinaldoImpMin = $detalle->pluck('aguinaldo_impactado')->min();
            $aguinaldoImpMax = $detalle->pluck('aguinaldo_impactado')->max();
            $aguinaldoImpProm = $detalle->pluck('aguinaldo_impactado')->avg();
            $aguinaldoImpMed = $this->median($detalle->pluck('aguinaldo_impactado'));
            $aguinaldoImp25Per = $this->percentile(25, $detalle->pluck('aguinaldo_impactado'));
            $aguinaldoImp75Per = $this->percentile(75, $detalle->pluck('aguinaldo_impactado'));

            $found = $detalle->where('cabecera_encuesta_id', $dbClienteEnc->cabecera_encuesta_id)
                            ->first();
            if($found){
                $aguinaldoImpEmpresa = $found->aguinaldo_impactado;
            }else{
                $aguinaldoImpEmpresa = 0;    
            }

            $this->pusher(  $collection, 
                            $countCasos, 
                            Lang::get('reportReport.concept_13month_impacted'),
                            $aguinaldoImpMin, 
                            $aguinaldoImpMax, 
                            round($aguinaldoImpProm, 0),
                            round($aguinaldoImpMed, 0),
                            round($aguinaldoImp25Per, 0),
                            round($aguinaldoImp75Per, 0),
                            $aguinaldoImpEmpresa,
                            $segmento, 
                            $dbCargo);

         
            //Total Compensación anual
            $detalle = $detalle->map(function($item){
                $item['total_comp_anual'] = $item['efectivo_total_anual'] +
                                            $item['beneficios_navieras'];
                return $item;
            });                                                
           
            $totalCompAnualMin = $detalle->pluck('total_comp_anual')->min();
            $totalCompAnualMax = $detalle->pluck('total_comp_anual')->max();
            $totalCompAnualProm = $detalle->pluck('total_comp_anual')->avg();
            $totalCompAnualMed = $this->median($detalle->pluck('total_comp_anual'));
            $totalCompAnual25Per = $this->percentile(25, $detalle->pluck('total_comp_anual'));
            $totalCompAnual75Per = $this->percentile(75, $detalle->pluck('total_comp_anual'));
            
            $found = $detalle->where('cabecera_encuesta_id', $dbClienteEnc->cabecera_encuesta_id)
                             ->first();
            if($found){
                $totalCompAnualEmpresa = $found->total_comp_anual;
            }else{
                $totalCompAnualEmpresa = 0;    
            }


            $this->pusher(  $collection, 
                            $countCasos, 
                            Lang::get('reportReport.concept_total_compensation'),
                            $totalCompAnualMin, 
                            $totalCompAnualMax, 
                            round($totalCompAnualProm, 0),
                            round($totalCompAnualMed, 0),
                            round($totalCompAnual25Per, 0),
                            round($totalCompAnual75Per, 0),
                            $totalCompAnualEmpresa,
                            $segmento, 
                            $dbCargo);            
        }else{ // Los otros rubros son iguales
            // Salario Base
            $salariosBase = $detalle->where('salario_base', '>', '0')->pluck('salario_base');
            $salarioMin = $salariosBase->min();
            $salarioMax = $salariosBase->max();
            $salarioProm = $salariosBase->avg();
            $salarioMed = $this->median($salariosBase);
            $salario25Per = $this->percentile(25,$salariosBase);
            $salario75Per = $this->percentile(75, $salariosBase);

            $this->pusher(  $collection, 
                            $countCasos, 
                            Lang::get('reportReport.concept_salary'),
                            $salarioMin,
                            $salarioMax,
                            round($salarioProm, 0),
                            round($salarioMed, 0),
                            round($salario25Per, 0),
                            round($salario75Per, 0),
                            $dbClienteEnc->salario_base,
                            $segmento, 
                            $dbCargo);        

            // Salario Base Anual     
            $salariosBaseAnual = $salariosBase->map(function($item){
                return $item * 12;
            });

            $salarioAnualMin = $salariosBaseAnual->min();
            $salarioAnualMax = $salariosBaseAnual->max();
            $salarioAnualProm = $salariosBaseAnual->avg();
            $salarioAnualMed = $this->median($salariosBaseAnual);
            $salarioAnual25Per = $this->percentile(25,$salariosBaseAnual);
            $salarioAnual75Per = $this->percentile(75, $salariosBaseAnual);
            $this->pusher(  $collection, 
                            $countCasos, 
                            Lang::get('reportReport.concept_annual_salary'),
                            $salarioAnualMin,
                            $salarioAnualMax,
                            round($salarioAnualProm, 0),
                            round($salarioAnualMed, 0),
                            round($salarioAnual25Per, 0), 
                            round($salarioAnual75Per, 0),
                            $dbClienteEnc->salario_base * 12,
                            $segmento, 
                            $dbCargo);

            // Gratificacion
            $gratificaciones = $detalle->where('gratificacion', '>', '0')->pluck('gratificacion');
            $gratificacionMin = $gratificaciones->min();
            $gratificacionMax = $gratificaciones->max();
            $gratificacionProm = $gratificaciones->avg();
            $gratificacionMed = $this->median($gratificaciones);
            $gratificacion25Per = $this->percentile(25, $gratificaciones);
            $gratificacion75Per = $this->percentile(75, $gratificaciones);

            $this->pusher(  $collection, 
                            $countCasosGratif, 
                            Lang::get('reportReport.concept_annual_gratif.'),
                            $gratificacionMin,
                            $gratificacionMax,
                            round($gratificacionProm, 0),
                            round($gratificacionMed, 0),
                            round($gratificacion25Per, 0),
                            round($gratificacion75Per, 0),
                            $dbClienteEnc->gratificacion, 
                            $segmento, 
                            $dbCargo);
        
            //Aguinaldo
            $aguinaldos = $detalle->where('aguinaldo', '>', '0')->pluck('aguinaldo');
            $aguinaldoMin = $aguinaldos->min();
            $aguinaldoMax = $aguinaldos->max();
            $aguinaldoProm = $aguinaldos->avg();
            $aguinaldoMed = $this->median($aguinaldos);
            $aguinaldo25Per = $this->percentile(25, $aguinaldos);
            $aguinaldo75Per = $this->percentile(75, $aguinaldos);

            $this->pusher(  $collection, 
                            $countCasosAguinaldo, 
                            Lang::get('reportReport.concept_13month'),
                            $aguinaldoMin,
                            $aguinaldoMax,
                            round($aguinaldoProm, 0),
                            round($aguinaldoMed, 0),
                            round($aguinaldo25Per, 0),
                            round($aguinaldo75Per, 0),
                            $dbClienteEnc->aguinaldo, 
                            $segmento, 
                            $dbCargo);

            // Efectivo Anual Garantizado
            $detalle = $detalle->map(function($item){
                $item['efectivo_anual_garantizado'] = ($item['salario_base'] * 12) + 
                                                       $item['gratificacion']+
                                                       $item['aguinaldo'];
                return $item;
            }); 
            $efectivoMin = $detalle->pluck('efectivo_anual_garantizado')->min();
            $efectivoMax = $detalle->pluck('efectivo_anual_garantizado')->max();
            $efectivoProm = $detalle->pluck('efectivo_anual_garantizado')->avg();
            $efectivoMed = $this->median($detalle->pluck('efectivo_anual_garantizado'));
            $efectivo25Per = $this->percentile(25, $detalle->pluck('efectivo_anual_garantizado'));
            $efectivo75Per = $this->percentile(75, $detalle->pluck('efectivo_anual_garantizado'));
            
            $found = $detalle->where('cabecera_encuesta_id', $dbClienteEnc->cabecera_encuesta_id)
                             ->first();
            if($found){
                $efectivoEmpresa = $found->efectivo_anual_garantizado;
            }else{
                $efectivoEmpresa = 0;    
            }

            $this->pusher(  $collection, 
                            $countCasos, 
                            Lang::get('reportReport.concept_annual_cash'),
                            $efectivoMin,
                            $efectivoMax,
                            round($efectivoProm, 0),
                            round($efectivoMed, 0),
                            round($efectivo25Per, 0),
                            round($efectivo75Per, 0),
                            $efectivoEmpresa, 
                            $segmento, 
                            $dbCargo);
            
            //Adicional
            $countCasosAdicionales = $detalle->where('adicionales_resto', '>', '0')->unique('cabecera_encuesta_id')->count();            
            $adicionales = $detalle->where('adicionales_resto', '>', '0')->pluck('adicionales_resto');
            $adicionalesMin = $adicionales->min();
            $adicionalesMax = $adicionales->max();
            $adicionalesProm = $adicionales->avg();
            $adicionalesMed = $this->median($adicionales);
            $adicionales25Per = $this->percentile(25, $adicionales);
            $adicionales75Per = $this->percentile(75, $adicionales);

            $this->pusher(  $collection, 
                            $countCasosAdicionales, 
                            Lang::get('reportReport.concept_total_additional'),
                            $adicionalesMin,
                            $adicionalesMax,
                            round($adicionalesProm, 0),
                            round($adicionalesMed, 0),
                            round($adicionales25Per, 0),
                            round($adicionales75Per, 0),
                            $dbClienteEnc->adicionales_resto, 
                            $segmento, 
                            $dbCargo);

            //Bono
            $bonos = $detalle->where('bono_anual', '>', '0')->pluck('bono_anual');
            $bonoMin = $bonos->min();
            $bonoMax = $bonos->max();
            $bonoProm = $bonos->avg();
            $bonoMed = $this->median($bonos);
            $bono25Per = $this->percentile(25, $bonos);
            $bono75Per = $this->percentile(75, $bonos);

            $this->pusher(  $collection, 
                            $countCasosBono, 
                            Lang::get('reportReport.concept_bonus'),
                            $bonoMin,
                            $bonoMax,
                            round($bonoProm, 0),
                            round($bonoMed, 0),
                            round($bono25Per, 0),
                            round($bono75Per, 0),
                            $dbClienteEnc->bono_anual, 
                            $segmento, 
                            $dbCargo);


            if($muestraComision){
                //Comisión
                $countCasosComision = $detalle->where('comision', '>', '0')->unique('cabecera_encuesta_id')->count();
                $comision = $detalle->where('comision', '>', '0')->pluck('comision');
                $comisionMin = $comision->min();
                $comisionMax = $comision->max();
                $comisionProm = $comision->avg();
                $comisionMed = $this->median($comision);
                $comision25Per = $this->percentile(25, $comision);
                $comision75Per = $this->percentile(75, $comision);

                $this->pusher(  $collection, 
                                $countCasosComision, 
                                //Lang::get('reportReport.concept_bonus'),
                                "Comision",
                                $comisionMin * 12,
                                $comisionMax * 12,
                                round($comisionProm, 0) * 12,
                                round($comisionMed, 0) * 12,
                                round($comision25Per, 0) * 12,
                                round($comision75Per, 0) * 12,
                                $dbClienteEnc->comision * 12, 
                                $segmento, 
                                $dbCargo);

            }            
                
            // Efectivo Total Anual
            $detalle = $detalle->map(function($item){
                $item['efectivo_total_anual'] = $item['efectivo_anual_garantizado'] +
                                                $item['adicionales_resto']+
                                                $item['comision'] * 12 +
                                                $item['bono_anual'];
                return $item;
            });                                                
           
            $efectivoTotalMin = $detalle->pluck('efectivo_total_anual')->min();
            $efectivoTotalMax = $detalle->pluck('efectivo_total_anual')->max();
            $efectivoTotalProm = $detalle->pluck('efectivo_total_anual')->avg();
            $efectivoTotalMed = $this->median($detalle->pluck('efectivo_total_anual'));
            $efectivoTotal25Per = $this->percentile(25, $detalle->pluck('efectivo_total_anual'));
            $efectivoTotal75Per = $this->percentile(75, $detalle->pluck('efectivo_total_anual'));
            
            $found = $detalle->where('cabecera_encuesta_id', $dbClienteEnc->cabecera_encuesta_id)
                             ->first();
            if($found){
                $efectivoTotalEmpresa = $found->efectivo_total_anual;
            }else{
                $efectivoTotalEmpresa = 0;    
            }

            $this->pusher(  $collection, 
                            $countCasos, 
                            Lang::get('reportReport.concept_annual_cash_total'),
                            $efectivoTotalMin,
                            $efectivoTotalMax,
                            round($efectivoTotalProm, 0),
                            round($efectivoTotalMed, 0),
                            round($efectivoTotal25Per, 0),
                            round($efectivoTotal75Per, 0),
                            $efectivoTotalEmpresa,
                            $segmento, 
                            $dbCargo);

            //Beneficios
            $countCasosBeneficios = $detalle->where('beneficios_resto', '>', '0')->unique('cabecera_encuesta_id')->count();
            $beneficiosResto = $detalle->where('beneficios_resto', '>', '0')->pluck('beneficios_resto');
            $beneficiosMin = $beneficiosResto->min();
            $beneficiosMax = $beneficiosResto->max();
            $beneficiosProm = $beneficiosResto->avg();
            $beneficiosMed = $this->median($beneficiosResto);
            $beneficios25Per = $this->percentile(25, $beneficiosResto);
            $beneficios75Per = $this->percentile(75, $beneficiosResto);
            
           // dd($detalle);            
            
            $this->pusher(  $collection, 
                            $countCasosBeneficios, 
                            Lang::get('reportReport.concept_total_benefits'),
                            $beneficiosMin,
                            $beneficiosMax,
                            round($beneficiosProm, 0),
                            round($beneficiosMed, 0),
                            round($beneficios25Per, 0),
                            round($beneficios75Per, 0),
                            $dbClienteEnc->beneficios_resto, 
                            $segmento, 
                            $dbCargo);

            //Aguinaldo Impactado
            $aguinaldoImpMin = 0;
            $aguinaldoImpMax = 0;
            $aguinaldoImpProm = 0;
            $aguinaldoImpMed = 0;
            $aguinaldoImp25Per = 0;
            $aguinaldoImp75Per = 0;
            $aguinaldoImpEmpresa = 0;

            $detalle = $detalle->map(function($item){
                $aguinaldoImp = (($item['salario_base'] * 12) + 
                                  $item['gratificacion'] + 
                                  $item['bono_anual'] +
                                  $item['comision'] * 12 +
                                  $item['adicionales_resto'])/12;
                $item['aguinaldo_impactado'] = $aguinaldoImp;
                
                return $item;
            });                                                
            
            $aguinaldoImpMin = $detalle->pluck('aguinaldo_impactado')->min();
            $aguinaldoImpMax = $detalle->pluck('aguinaldo_impactado')->max();
            $aguinaldoImpProm = $detalle->pluck('aguinaldo_impactado')->avg();
            $aguinaldoImpMed = $this->median($detalle->pluck('aguinaldo_impactado'));
            $aguinaldoImp25Per = $this->percentile(25, $detalle->pluck('aguinaldo_impactado'));
            $aguinaldoImp75Per = $this->percentile(75, $detalle->pluck('aguinaldo_impactado'));

            $found = $detalle->where('cabecera_encuesta_id', $dbClienteEnc->cabecera_encuesta_id)
                            ->first();
            if($found){
                $aguinaldoImpEmpresa = $found->aguinaldo_impactado;
            }else{
                $aguinaldoImpEmpresa = 0;    
            }

            $this->pusher(  $collection, 
                            $countCasos, 
                            Lang::get('reportReport.concept_13month_impacted'),
                            $aguinaldoImpMin, 
                            $aguinaldoImpMax, 
                            round($aguinaldoImpProm, 0),
                            round($aguinaldoImpMed, 0),
                            round($aguinaldoImp25Per, 0),
                            round($aguinaldoImp75Per, 0),
                            $aguinaldoImpEmpresa,
                            $segmento, 
                            $dbCargo);                            

            //Total Compensación anual
            $detalle = $detalle->map(function($item){
                $item['total_comp_anual'] = $item['efectivo_total_anual'] +
                                            $item['beneficios_resto'];
                return $item;
            });
            $totalCompAnualMin = $detalle->pluck('total_comp_anual')->min();
            $totalCompAnualMax = $detalle->pluck('total_comp_anual')->max();
            $totalCompAnualProm = $detalle->pluck('total_comp_anual')->avg();
            $totalCompAnualMed = $this->median($detalle->pluck('total_comp_anual'));
            $totalCompAnual25Per = $this->percentile(25, $detalle->pluck('total_comp_anual'));
            $totalCompAnual75Per = $this->percentile(75, $detalle->pluck('total_comp_anual'));
            
            $found = $detalle->where('cabecera_encuesta_id', $dbClienteEnc->cabecera_encuesta_id)
                             ->first();
            if($found){
                $totalCompAnualEmpresa = $found->total_comp_anual;
            }else{
                $totalCompAnualEmpresa = 0;    
            }

            $this->pusher(  $collection, 
                            $countCasos, 
                            Lang::get('reportReport.concept_total_compensation'),
                            $totalCompAnualMin, 
                            $totalCompAnualMax, 
                            round($totalCompAnualProm, 0),
                            round($totalCompAnualMed, 0),
                            round($totalCompAnual25Per, 0),
                            round($totalCompAnual75Per, 0),
                            $totalCompAnualEmpresa,
                            $segmento, 
                            $dbCargo);            

        }

    }

    public function pusher(&$collection, $casos, $concepto, $min, $max, $prom, $med, $per25, $per75, $empresa, $segmento, $dbCargo){
        if($casos >= 4){
            $collection->push([ "concepto"=> $concepto,
                              "casos"=> $casos,
                              "min"=>$min, 
                              "max"=>$max, 
                              "prom"=>$prom, 
                              "med"=>$med, 
                              "per25"=>$per25, 
                              "per75"=> $per75, 
                              "empresa"=>$empresa,
                              "segmento"=>$segmento
            ]);
        }elseif($casos <= 3 and $casos > 1){
            $collection->push([ "concepto"=> $concepto,
                              "casos"=> $casos,
                              "min"=>"", 
                              "max"=>"", 
                              "prom"=>$prom, 
                              "med"=>"", 
                              "per25"=>$per25, 
                              "per75"=> $per75, 
                              "empresa"=>$empresa,
                              "segmento"=>$segmento
            ]);

        }elseif ($casos <= 1) {
            if($dbCargo->is_temporal == '1'){
                $collection->push([ "concepto"=> $concepto,
                                  "casos"=> $casos,
                                  "min"=>$min, 
                                  "max"=>$max,
                                  "prom"=>$prom, 
                                  "med"=>$med, 
                                  "per25"=>$per25, 
                                  "per75"=> $per75, 
                                  "empresa"=>$empresa,
                                  "segmento"=>$segmento
                ]);
            }else{
                $collection->push([ "concepto"=> $concepto,
                                  "casos"=> $casos,
                                  "min"=>"",
                                  "max"=>"",
                                  "prom"=>"",
                                  "med"=>"",
                                  "per25"=>"",
                                  "per75"=>"",
                                  "empresa"=>"",
                                  "segmento"=>$segmento
                ]);
            }
        }
    }

    public function segmenterComparativo( &$collection, 
                                            $detalle, 
                                            $detalleAnt,
                                            $dbClienteEnc,
                                            $dbClienteEncAnt,
                                            $concepto, 
                                            $dbCargo, 
                                            $countCasos = 0){

            // Salario Base Anterior
            $salariosBaseAnt = $detalleAnt->where('salario_base', '>', '0')->pluck('salario_base');
            $salarioMinAnt   = $salariosBaseAnt->min();
            $salarioMaxAnt   = $salariosBaseAnt->max();
            $salarioPromAnt  = $salariosBaseAnt->avg();
            $salarioMedAnt   = $this->median($salariosBaseAnt);
            $salario25PerAnt = $this->percentile(25,$salariosBaseAnt);
            $salario75PerAnt = $this->percentile(75, $salariosBaseAnt);                                 
        
            // Salario Base
            $salariosBase = $detalle->where('salario_base', '>', '0')->pluck('salario_base');
            $salarioMin = $salariosBase->min();
            $salarioMax = $salariosBase->max();
            $salarioProm = $salariosBase->avg();
            $salarioMed = $this->median($salariosBase);
            $salario25Per = $this->percentile(25,$salariosBase);
            $salario75Per = $this->percentile(75, $salariosBase);

            if($concepto == "SB"){
                $collection->push([ "concepto"     => Lang::get('reportReport.concept_salary'),
                                    "ocupantes"    => $countCasos, 
                                    "min_ant"      => $salarioMinAnt, 
                                    "max_ant"      => $salarioMaxAnt, 
                                    "prom_ant"     => round($salarioPromAnt, 0), 
                                    "med_ant"      => round($salarioMedAnt, 0), 
                                    "per25_ant"    => round($salario25PerAnt, 0),
                                    "per75_ant"    => round($salario75PerAnt, 0), 
                                    "min"          => $salarioMin, 
                                    "max"          => $salarioMax, 
                                    "prom"         => round($salarioProm, 0), 
                                    "med"          => round($salarioMed, 0), 
                                    "per25"        => round($salario25Per, 0),
                                    "per75"        => round($salario75Per, 0), 
                                    "empresa"      => $dbClienteEnc->salario_base,
                                    "indicador"    => "SA"
                ]);
                
            }
                   
            // Salario Base Anual     
            $salariosBaseAnual = $salariosBase->map(function($item){
                return $item * 12;
            });

            $salarioAnualMin = $salariosBaseAnual->min();
            $salarioAnualMax = $salariosBaseAnual->max();
            $salarioAnualProm = $salariosBaseAnual->avg();
            $salarioAnualMed = $this->median($salariosBaseAnual);
            $salarioAnual25Per = $this->percentile(25,$salariosBaseAnual);
            $salarioAnual75Per = $this->percentile(75, $salariosBaseAnual);
           
            //Aguinaldo
            $aguinaldos = $detalle->where('aguinaldo', '>', '0')->pluck('aguinaldo');
            $aguinaldoMin = $aguinaldos->min();
            $aguinaldoMax = $aguinaldos->max();
            $aguinaldoProm = $aguinaldos->avg();
            $aguinaldoMed = $this->median($aguinaldos);
            $aguinaldo25Per = $this->percentile(25, $aguinaldos);
            $aguinaldo75Per = $this->percentile(75, $aguinaldos);
                     
            // Variable Anual Anterior
            $plusRendimientoAnt = $detalleAnt->where('plus_rendimiento', '>', '0')->pluck('plus_rendimiento');
            $plusMinAnt = $plusRendimientoAnt->min();
            $plusMaxAnt = $plusRendimientoAnt->max();
            $plusPromAnt = $plusRendimientoAnt->avg();
            $plusMedAnt = $this->median($plusRendimientoAnt);
            $plus25PerAnt = $this->percentile(25,$plusRendimientoAnt);
            $plus75PerAnt = $this->percentile(75, $plusRendimientoAnt);

            // Variable Anual
            $plusRendimiento = $detalle->where('plus_rendimiento', '>', '0')->pluck('plus_rendimiento');
            $plusMin = $plusRendimiento->min();
            $plusMax = $plusRendimiento->max();
            $plusProm = $plusRendimiento->avg();
            $plusMed = $this->median($plusRendimiento);
            $plus25Per = $this->percentile(25,$plusRendimiento);
            $plus75Per = $this->percentile(75, $plusRendimiento);
            $countCasosPlus = $detalle->where('plus_rendimiento', '>', '0')->unique('cabecera_encuesta_id')->count();  

            if($concepto == "VAR"){
                $collection->push([ "concepto"     => Lang::get('reportReport.concept_variable_pay'),
                                    "min_ant"      => $plusMinAnt, 
                                    "max_ant"      => $plusMaxAnt, 
                                    "prom_ant"     => round($plusPromAnt, 0), 
                                    "med_ant"      => round($plusMedAnt, 0), 
                                    "per25_ant"    => round($plus25PerAnt, 0),
                                    "per75_ant"    => round($plus75PerAnt, 0), 
                                    "min"          => $plusMin, 
                                    "max"          => $plusMax, 
                                    "prom"         => round($plusProm, 0), 
                                    "med"          => round($plusMed, 0), 
                                    "per25"        => round($plus25Per, 0),
                                    "per75"        => round($plus75Per, 0), 
                                    "empresa"      => 0,
                                    "indicador"    => "VAR"
                ]);
                
            }

            // Variable por Viaje Anterior
            $variableViajeAnt = $detalleAnt->where('variable_viaje', '>', '0')->pluck('variable_viaje');
            $plusMinAnt = $variableViajeAnt->min();
            $plusMaxAnt = $variableViajeAnt->max();
            $plusPromAnt = $variableViajeAnt->avg();
            $plusMedAnt = $this->median($variableViajeAnt);
            $plus25PerAnt = $this->percentile(25,$variableViajeAnt);
            $plus75PerAnt = $this->percentile(75, $variableViajeAnt);
            $countCasosPlusAnt = $detalle->where('variable_viaje', '>', '0')->unique('cabecera_encuesta_id')->count();

            // Variable por Viaje
            $variableViaje = $detalle->where('variable_viaje', '>', '0')->pluck('variable_viaje');
            $plusMin = $variableViaje->min();
            $plusMax = $variableViaje->max();
            $plusProm = $variableViaje->avg();
            $plusMed = $this->median($variableViaje);
            $plus25Per = $this->percentile(25,$variableViaje);
            $plus75Per = $this->percentile(75, $variableViaje);
            $countCasosPlus = $detalle->where('variable_viaje', '>', '0')->unique('cabecera_encuesta_id')->count();

            if($concepto == "VV"){
                $collection->push([ "concepto"     => Lang::get('reportReport.concept_variable_pay_trip'),
                                    "min_ant"      => $plusMinAnt, 
                                    "max_ant"      => $plusMaxAnt, 
                                    "prom_ant"     => round($plusPromAnt, 0), 
                                    "med_ant"      => round($plusMedAnt, 0), 
                                    "per25_ant"    => round($plus25PerAnt, 0),
                                    "per75_ant"    => round($plus75PerAnt, 0), 
                                    "min"          => $plusMin, 
                                    "max"          => $plusMax, 
                                    "prom"         => round($plusProm, 0), 
                                    "med"          => round($plusMed, 0), 
                                    "per25"        => round($plus25Per, 0),
                                    "per75"        => round($plus75Per, 0), 
                                    "empresa"      => 0,
                                    "indicador"    => "VV"
                ]);
                
            }

            // Salario Base + Variable por Viaje Anterior
            $salBaseVarViajeAnt = $detalleAnt->where('sal_base_var_viaje_navieras', '>', '0')->pluck('sal_base_var_viaje_navieras');
            $salBaseVarViajeMinAnt = $salBaseVarViajeAnt->min();
            $salBaseVarViajeMaxAnt = $salBaseVarViajeAnt->max();
            $salBaseVarViajePromAnt = $salBaseVarViajeAnt->avg();
            $salBaseVarViajeMedAnt = $this->median($salBaseVarViajeAnt);
            $salBaseVarViaje25PerAnt = $this->percentile(25,$salBaseVarViajeAnt);
            $salBaseVarViaje75PerAnt = $this->percentile(75, $salBaseVarViajeAnt);
            $countCasossalBaseVarViajeAnt = $detalle->where('variable_viaje', '>', '0')->unique('cabecera_encuesta_id')->count();

            // Salario Base + Variable por Viaje
            $salBaseVarViaje = $detalle->where('sal_base_var_viaje_navieras', '>', '0')->pluck('sal_base_var_viaje_navieras');
            $salBaseVarViajeMin = $salBaseVarViaje->min();
            $salBaseVarViajeMax = $salBaseVarViaje->max();
            $salBaseVarViajeProm = $salBaseVarViaje->avg();
            $salBaseVarViajeMed = $this->median($salBaseVarViaje);
            $salBaseVarViaje25Per = $this->percentile(25,$salBaseVarViaje);
            $salBaseVarViaje75Per = $this->percentile(75, $salBaseVarViaje);
            $countCasossalBaseVarViaje = $detalle->where('variable_viaje', '>', '0')->unique('cabecera_encuesta_id')->count();

            if($concepto == "SBVV"){
                $collection->push([ "concepto"     => Lang::get('reportReport.concept_variable_pay_trip'),
                                    "min_ant"      => $salBaseVarViajeMinAnt, 
                                    "max_ant"      => $salBaseVarViajeMaxAnt, 
                                    "prom_ant"     => round($salBaseVarViajePromAnt, 0), 
                                    "med_ant"      => round($salBaseVarViajeMedAnt, 0), 
                                    "per25_ant"    => round($salBaseVarViaje25PerAnt, 0),
                                    "per75_ant"    => round($salBaseVarViaje75PerAnt, 0), 
                                    "min"          => $salBaseVarViajeMin, 
                                    "max"          => $salBaseVarViajeMax, 
                                    "prom"         => round($salBaseVarViajeProm, 0), 
                                    "med"          => round($salBaseVarViajeMed, 0), 
                                    "per25"        => round($salBaseVarViaje25Per, 0),
                                    "per75"        => round($salBaseVarViaje75Per, 0), 
                                    "empresa"      => 0,
                                    "indicador"    => "SBVV"
                ]);
                
            }

            // Adicional Amarre
            $adicionalAmarre = $detalle->where('adicional_amarre', '>', '0')->pluck('adicional_amarre');
            $amarreMin = $adicionalAmarre->min();
            $amarreMax = $adicionalAmarre->max();
            $amarreProm = $adicionalAmarre->avg();
            $amarreMed = $this->median($adicionalAmarre);
            $amarre25Per = $this->percentile(25,$adicionalAmarre);
            $amarre75Per = $this->percentile(75, $adicionalAmarre);
            $countCasosAmarre = $detalle->where('adicional_amarre', '>', '0')->unique('cabecera_encuesta_id')->count();

            // Adicional Tipo de Combustible
            $adicionalTipoCombustible = $detalle->where('adicional_tipo_combustible', '>', '0')->pluck('adicional_tipo_combustible');
            $TipoCombustibleMin = $adicionalTipoCombustible->min();
            $TipoCombustibleMax = $adicionalTipoCombustible->max();
            $TipoCombustibleProm = $adicionalTipoCombustible->avg();
            $TipoCombustibleMed = $this->median($adicionalTipoCombustible);
            $TipoCombustible25Per = $this->percentile(25,$adicionalTipoCombustible);
            $TipoCombustible75Per = $this->percentile(75, $adicionalTipoCombustible);
            $countCasosTipoCombustible = $detalle->where('adicional_tipo_combustible', '>', '0')->unique('cabecera_encuesta_id')->count();      

            // Adicional por disponiblidad/embarque
            $adicionalEmbarque = $detalle->where('adicional_embarque', '>', '0')->pluck('adicional_embarque');
            $embarqueMin = $adicionalEmbarque->min();
            $embarqueMax = $adicionalEmbarque->max();
            $embarqueProm = $adicionalEmbarque->avg();
            $embarqueMed = $this->median($adicionalEmbarque);
            $embarque25Per = $this->percentile(25,$adicionalEmbarque);
            $embarque75Per = $this->percentile(75, $adicionalEmbarque);
            $countCasosEmbarque = $detalle->where('adicional_embarque', '>', '0')->unique('cabecera_encuesta_id')->count();
            
            // Adicional Carga
            $adicionalCarga = $detalle->where('adicional_carga', '>', '0')->pluck('adicional_carga');
            $cargaMin = $adicionalCarga->min();
            $cargaMax = $adicionalCarga->max();
            $cargaProm = $adicionalCarga->avg();
            $cargaMed = $this->median($adicionalCarga);
            $carga25Per = $this->percentile(25,$adicionalCarga);
            $carga75Per = $this->percentile(75, $adicionalCarga);
            $countCasosCarga = $detalle->where('adicional_carga', '>', '0')->unique('cabecera_encuesta_id')->count();
                   

            // Total Adicional Anterior

            $adicionalAnualAnt = $detalleAnt->where('adicionales_navieras', '>', '0')->pluck('adicionales_navieras');
            $totalAdicionalMinAnt = $adicionalAnualAnt->min();
            $totalAdicionalMaxAnt = $adicionalAnualAnt->max();
            $totalAdicionalPromAnt = $adicionalAnualAnt->avg();
            $totalAdicionalMedAnt = $this->median($adicionalAnualAnt);
            $totalAdicional25PerAnt = $this->percentile(25, $adicionalAnualAnt);
            $totalAdicional75PerAnt = $this->percentile(75, $adicionalAnualAnt);

            // Total Adicional 
            $casosAdicionales = $detalle->where('adicionales_navieras', '>', '0')->unique('cabecera_encuesta_id')->count();   
            $adicionalAnual = $detalle->where('adicionales_navieras', '>', '0')->pluck('adicionales_navieras');
            $totalAdicionalMin = $adicionalAnual->min();
            $totalAdicionalMax = $adicionalAnual->max();
            $totalAdicionalProm = $adicionalAnual->avg();
            $totalAdicionalMed = $this->median($adicionalAnual);
            $totalAdicional25Per = $this->percentile(25, $adicionalAnual);
            $totalAdicional75Per = $this->percentile(75, $adicionalAnual);
            
            if($concepto == "ATA"){
                $collection->push([ "concepto"     => Lang::get('reportReport.concept_total_incentives'),
                                    "min_ant"      => $totalAdicionalMinAnt, 
                                    "max_ant"      => $totalAdicionalMaxAnt, 
                                    "prom_ant"     => round($totalAdicionalPromAnt, 0), 
                                    "med_ant"      => round($totalAdicionalMedAnt, 0), 
                                    "per25_ant"    => round($totalAdicional25PerAnt, 0),
                                    "per75_ant"    => round($totalAdicional75PerAnt, 0), 
                                    "min"          => $totalAdicionalMin, 
                                    "max"          => $totalAdicionalMax, 
                                    "prom"         => round($totalAdicionalProm, 0), 
                                    "med"          => round($totalAdicionalMed, 0), 
                                    "per25"        => round($totalAdicional25Per, 0),
                                    "per75"        => round($totalAdicional75Per, 0), 
                                    "empresa"      => 0,
                                    "indicador"    => "ATA"
                ]);
                
            }

            //Bono
            $bonos = $detalle->where('bono_anual', '>', '0')->pluck('bono_anual');
            $bonoMin = $bonos->min();
            $bonoMax = $bonos->max();
            $bonoProm = $bonos->avg();
            $bonoMed = $this->median($bonos);
            $bono25Per = $this->percentile(25, $bonos);
            $bono75Per = $this->percentile(75, $bonos);

            // Efectivo Total Anual Anteior
            $detalleAnt = $detalleAnt->map(function($item){
                $item['efectivo_total_anual'] =  $item['salario_base'] * 12 +
                                                 $item['aguinaldo'];
                return $item;
            });                                                
           
            $efectivoTotalMinAnt = $detalleAnt->pluck('efectivo_total_anual')->min();
            $efectivoTotalMaxAnt = $detalleAnt->pluck('efectivo_total_anual')->max();
            $efectivoTotalPromAnt = $detalleAnt->pluck('efectivo_total_anual')->avg();
            $efectivoTotalMedAnt = $this->median($detalleAnt->pluck('efectivo_total_anual'));
            $efectivoTotal25PerAnt = $this->percentile(25, $detalleAnt->pluck('efectivo_total_anual'));
            $efectivoTotal75PerAnt = $this->percentile(75, $detalleAnt->pluck('efectivo_total_anual'));
                        
            // Efectivo Total Anual
            $detalle = $detalle->map(function($item){
                $item['efectivo_total_anual'] =  $item['salario_base'] * 12 +
                                                 $item['aguinaldo'];
                return $item;
            });                                                
           
            $efectivoTotalMin = $detalle->pluck('efectivo_total_anual')->min();
            $efectivoTotalMax = $detalle->pluck('efectivo_total_anual')->max();
            $efectivoTotalProm = $detalle->pluck('efectivo_total_anual')->avg();
            $efectivoTotalMed = $this->median($detalle->pluck('efectivo_total_anual'));
            $efectivoTotal25Per = $this->percentile(25, $detalle->pluck('efectivo_total_anual'));
            $efectivoTotal75Per = $this->percentile(75, $detalle->pluck('efectivo_total_anual'));
            
            $found = $detalle->where('cabecera_encuesta_id', $dbClienteEnc->cabecera_encuesta_id)
                             ->first();
            if($found){
                $efectivoTotalEmpresa = $found->efectivo_total_anual;
            }else{
                $efectivoTotalEmpresa = 0;    
            }

            if($concepto == "ETA"){
                $collection->push([ "concepto"     => Lang::get('reportReport.concept_annual_cash_total'),
                                    "min_ant"      => $efectivoTotalMinAnt, 
                                    "max_ant"      => $efectivoTotalMaxAnt, 
                                    "prom_ant"     => round($efectivoTotalPromAnt, 0), 
                                    "med_ant"      => round($efectivoTotalMedAnt, 0), 
                                    "per25_ant"    => round($efectivoTotal25PerAnt, 0),
                                    "per75_ant"    => round($efectivoTotal75PerAnt, 0), 
                                    "min"          => $efectivoTotalMin, 
                                    "max"          => $efectivoTotalMax, 
                                    "prom"         => round($efectivoTotalProm, 0), 
                                    "med"          => round($efectivoTotalMed, 0), 
                                    "per25"        => round($efectivoTotal25Per, 0),
                                    "per75"        => round($efectivoTotal75Per, 0), 
                                    "empresa"      => $efectivoTotalEmpresa,
                                    "indicador"    => "ETA"
                ]);
                
            }

            //Beneficios
            $beneficiosNavieras = $detalle->where('beneficios_navieras', '>', '0')->pluck('beneficios_navieras');
            $beneficiosMin = $beneficiosNavieras->min();
            $beneficiosMax = $beneficiosNavieras->max();
            $beneficiosProm = $beneficiosNavieras->avg();
            $beneficiosMed = $this->median($beneficiosNavieras);
            $beneficios25Per = $this->percentile(25, $beneficiosNavieras);
            $beneficios75Per = $this->percentile(75, $beneficiosNavieras);
            $casosBeneficiosNavieras = $detalle->where('beneficios_navieras', '>', '0')->unique('cabecera_encuesta_id')->count();

            //Aguinaldo Impactado
            $aguinaldoImpMin = 0;
            $aguinaldoImpMax = 0;
            $aguinaldoImpProm = 0;
            $aguinaldoImpMed = 0;
            $aguinaldoImp25Per = 0;
            $aguinaldoImp75Per = 0;
            $aguinaldoImpEmpresa = 0;

            $detalle = $detalle->map(function($item){
                $item['aguinaldo_impactado'] = (($item['salario_base'] * 12) + 
                                                $item['gratificacion'] + 
                                                $item['bono_anual'] +
                                                $item['adicionales_navieras'])/12;
                return $item;
            });                                                

            $aguinaldoImpMin = $detalle->pluck('aguinaldo_impactado')->min();
            $aguinaldoImpMax = $detalle->pluck('aguinaldo_impactado')->max();
            $aguinaldoImpProm = $detalle->pluck('aguinaldo_impactado')->avg();
            $aguinaldoImpMed = $this->median($detalle->pluck('aguinaldo_impactado'));
            $aguinaldoImp25Per = $this->percentile(25, $detalle->pluck('aguinaldo_impactado'));
            $aguinaldoImp75Per = $this->percentile(75, $detalle->pluck('aguinaldo_impactado'));

            $found = $detalle->where('cabecera_encuesta_id', $dbClienteEnc->cabecera_encuesta_id)
                            ->first();
            if($found){
                $aguinaldoImpEmpresa = $found->aguinaldo_impactado;
            }else{
                $aguinaldoImpEmpresa = 0;    
            }

         
            //Total Compensación anual
            $detalle = $detalle->map(function($item){
                $item['total_comp_anual'] = $item['efectivo_total_anual'] +
                                            $item['beneficios_navieras'];
                return $item;
            });                                                
           
            $totalCompAnualMin = $detalle->pluck('total_comp_anual')->min();
            $totalCompAnualMax = $detalle->pluck('total_comp_anual')->max();
            $totalCompAnualProm = $detalle->pluck('total_comp_anual')->avg();
            $totalCompAnualMed = $this->median($detalle->pluck('total_comp_anual'));
            $totalCompAnual25Per = $this->percentile(25, $detalle->pluck('total_comp_anual'));
            $totalCompAnual75Per = $this->percentile(75, $detalle->pluck('total_comp_anual'));
            
            $found = $detalle->where('cabecera_encuesta_id', $dbClienteEnc->cabecera_encuesta_id)
                             ->first();
            if($found){
                $totalCompAnualEmpresa = $found->total_comp_anual;
            }else{
                $totalCompAnualEmpresa = 0;    
            }


    }
    public function segmentArrayFactory($segmentArray){
        foreach ($segmentArray as $value) {

            $response[] = array(    Lang::get('reportReport.table_concepts')  => $value["concepto"], 
                                    Lang::get('reportReport.table_occupants') => $value["casos"],
                                    Lang::get('reportReport.table_cases')     => $value["casos"], 
                                    Lang::get('reportReport.table_min')       => $value["min"], 
                                    Lang::get('reportReport.table_perc25')    => $value["per25"], 
                                    Lang::get('reportReport.table_average')   => $value["prom"], 
                                    Lang::get('reportReport.table_median')    => $value["med"], 
                                    Lang::get('reportReport.table_perc75')    => $value["per75"], 
                                    Lang::get('reportReport.table_max')       => $value["max"], 
                                    Lang::get('reportReport.table_company')   => $value["empresa"] 
                                );
        }

        return $response;
    }
    
    public function getIdioma(){
        return $locale = app()->getLocale();
    } 

    public function nivelReport(Request $request, $encuestasIDs, $muestraComision = false){
        
        $periodo = $request->periodo;    // periodo de la encuesta actual

        $rubro = $request->rubro_id;      // rubro de la empresa del cliente
        // cargo oficial para el informe
        $nivel = $request->nivel_id; 
        if($this->getIdioma() == "es"){
            $dbNivel = Nivel::find($nivel);
        }else{
            $dbNivel = Nivel_en::find($nivel);
        }    
          
        $dbEncuestadas = Cabecera_encuesta::whereIn('id', $encuestasIDs)->get();
        // conteo de encuestas según origen
        $dbNacionales = 0;
        $dbInternacionales = 0;
        $dbUniverso = $dbEncuestadas->count();
        $encuestadasNacIds = collect();
        $encuestadasInterIds = collect();
        foreach ($dbEncuestadas as $key => $value) {
            if($value->empresa->tipo == 0){
                $dbNacionales++;
                $encuestadasNacIds[] = $value->id;
            }else{
                $dbInternacionales++;
                $encuestadasInterIds[] = $value->id;
            };
        }
        // Recuperamos los datos de los cargos proveídos por las empresas encuestadas

        $dbEncuestasNivel = Detalle_encuestas_nivel::whereIn('cabecera_encuesta_id', $encuestasIDs)
                                                      ->where('nivel_oficial_id', $nivel)
                                                      ->where('incluir', 1)
                                                      ->get();
        $dbEncuestasNivelNac = Detalle_encuestas_nivel::whereIn('cabecera_encuesta_id', $encuestadasNacIds)
                                                      ->where('nivel_oficial_id', $nivel)
                                                      ->where('incluir', 1)
                                                      ->get();
        $dbEncuestasNivelInter = Detalle_encuestas_nivel::whereIn('cabecera_encuesta_id', $encuestadasInterIds)
                                                      ->where('nivel_oficial_id', $nivel)
                                                      ->where('incluir', 1)
                                                      ->get();

        // conteo de casos encontrados universo
        $countCasos = $dbEncuestasNivel->where('cantidad_ocupantes', '>', '0')
                                       ->unique('cabecera_encuesta_id')
                                       ->count();
        // conteo de casos encontrados nacionales
        $countCasosNac = $dbEncuestasNivelNac->where('cantidad_ocupantes', '>', '0')
                                             ->unique('cabecera_encuesta_id')
                                             ->count();
        // conteo de casos encontrados internacionales
        $countCasosInter = $dbEncuestasNivelInter->where('cantidad_ocupantes', '>', '0')
                                                 ->unique('cabecera_encuesta_id')
                                                 ->count();                                             

        // conteo de casos encontrados
        $countOcupantes = $dbEncuestasNivel->sum('cantidad_ocupantes');        
        $universo = collect();
        $segmento = "universo";
        // Salario Base
        $salariosBase = $dbEncuestasNivel->where('salario_base', '>', '0')
                                         ->pluck('salario_base');
        $salarioMin = $salariosBase->min();
        $salarioMax = $salariosBase->max();
        $salarioProm = $salariosBase->avg();
        $salarioMed = $this->median($salariosBase);
        $salario25Per = $this->percentile(25,$salariosBase);
        $salario75Per = $this->percentile(75, $salariosBase);

        $this->pusherNivel( $universo, 
                            $countCasos, 
                            Lang::get('reportReport.concept_salary'),
                            $salarioMin,
                            $salarioMax,
                            $salarioProm,
                            $salarioMed,
                            $salario25Per,
                            $salario75Per,
                            $segmento, 
                            $dbNivel);
        
        //Bono
        $bonos = $dbEncuestasNivel->where('bono_anual', '>', '0')
                                  ->pluck('bono_anual');
        $bonoMin = $bonos->min();
        $bonoMax = $bonos->max();
        $bonoProm = $bonos->avg();
        $bonoMed = $this->median($bonos);
        $bono25Per = $this->percentile(25, $bonos);
        $bono75Per = $this->percentile(75, $bonos);

        $countCasosBonos = $dbEncuestasNivel->where('bono_anual', '>', '0')
                                            ->unique('cabecera_encuesta_id')
                                            ->count();
        $this->pusherNivel( $universo, 
                            $countCasosBonos, 
                            Lang::get('reportReport.concept_bonus'),
                            $bonoMin,
                            $bonoMax,
                            $bonoProm,
                            $bonoMed,
                            $bono25Per,
                            $bono75Per,
                            $segmento, 
                            $dbNivel);                
            
        // Efectivo Total Anual
        $efectivoAnual = $dbEncuestasNivel->where('salario_base', '>', '0')
                                          ->pluck('total_efectivo_anual');
               
        $efectivoTotalMin = $efectivoAnual->min();
        $efectivoTotalMax = $efectivoAnual->max();
        $efectivoTotalProm = $efectivoAnual->avg();
        $efectivoTotalMed = $this->median($efectivoAnual);
        $efectivoTotal25Per = $this->percentile(25, $efectivoAnual);
        $efectivoTotal75Per = $this->percentile(75, $efectivoAnual);

        $this->pusherNivel( $universo, 
                            $countCasos, 
                            Lang::get('reportReport.concept_bonus'),
                            $efectivoTotalMin,
                            $efectivoTotalMax,
                            $efectivoTotalProm,
                            $efectivoTotalMed,
                            $efectivoTotal25Per,
                            $efectivoTotal75Per,
                            $segmento, 
                            $dbNivel);                
        
        //$detalleUniverso = $this->nivelSegmentArrayFactory($universo);
        $detalleUniverso = array();
        foreach ($universo as $value) {
            $detalleUniverso[] = array( "Concepto"=>$value["concepto"],
                                        "ocupantes"=> $countOcupantes, 
                                        "Casos"=>$value["casos"], 
                                        "Min"=>$value["min"], 
                                        "25 Percentil"=>$value["per25"], 
                                        "Promedio"=>$value["prom"], 
                                        "Mediana"=>$value["med"], 
                                        "75 Percentil"=>$value["per75"], 
                                        "Max"=>$value["max"], 
                                        "nivel"=>$value["nivel"] 
                                        );
        } 

        $nacional = collect();
        $salarioBase = 0;
        $salarioMin = 0;
        $salarioMax = 0;
        $salarioProm = 0;
        $salarioMed = 0;
        $salario25Per = 0;
        $salario75Per = 0;
        $bonos = 0;
        $bonoMin = 0;
        $bonoMax = 0;
        $bonoProm = 0;
        $bonoMed = 0;
        $bono25Per = 0;
        $bono75Per = 0;        
        $efectivoAnual = 0;
        $efectivoTotalMin = 0;
        $efectivoTotalMax = 0;
        $efectivoTotalProm = 0;
        $efectivoTotalMed = 0;
        $efectivoTotal25Per = 0;
        $efectivoTotal75Per = 0;

        $segmento = "nacional";
        $countOcupantesNac = $dbEncuestasNivelNac->sum('cantidad_ocupantes');
        // Salario Base
        $salariosBase = $dbEncuestasNivelNac->where('salario_base', '>', '0')->pluck('salario_base');
        $salarioMin = $salariosBase->min();
        $salarioMax = $salariosBase->max();
        $salarioProm = $salariosBase->avg();
        $salarioMed = $this->median($salariosBase);
        $salario25Per = $this->percentile(25,$salariosBase);
        $salario75Per = $this->percentile(75, $salariosBase);

        $this->pusherNivel( $nacional, 
                            $countCasosNac, 
                            Lang::get('reportReport.concept_salary'),
                            $salarioMin,
                            $salarioMax,
                            $salarioProm,
                            $salarioMed,
                            $salario25Per,
                            $salario75Per,
                            $segmento, 
                            $dbNivel);
        
        //Bono
        $bonos = $dbEncuestasNivelNac->where('bono_anual', '>', '0')->pluck('bono_anual');
        $bonoMin = $bonos->min();
        $bonoMax = $bonos->max();
        $bonoProm = $bonos->avg();
        $bonoMed = $this->median($bonos);
        $bono25Per = $this->percentile(25, $bonos);
        $bono75Per = $this->percentile(75, $bonos);

        $countCasosBonosNac = $dbEncuestasNivelNac->where('bono_anual', '>', '0')
                                                  ->unique('cabecera_encuesta_id')
                                                  ->count();
        $this->pusherNivel( $nacional, 
                            $countCasosBonosNac, 
                            Lang::get('reportReport.concept_bonus'),
                            $bonoMin,
                            $bonoMax,
                            $bonoProm,
                            $bonoMed,
                            $bono25Per,
                            $bono75Per,
                            $segmento, 
                            $dbNivel);                
            
        // Efectivo Total Anual
        $efectivoAnual = $dbEncuestasNivelNac->where('salario_base', '>', '0')
                                             ->pluck('total_efectivo_anual');
               
        $efectivoTotalMin = $efectivoAnual->min();
        $efectivoTotalMax = $efectivoAnual->max();
        $efectivoTotalProm = $efectivoAnual->avg();
        $efectivoTotalMed = $this->median($efectivoAnual);
        $efectivoTotal25Per = $this->percentile(25, $efectivoAnual);
        $efectivoTotal75Per = $this->percentile(75, $efectivoAnual);

        $this->pusherNivel( $nacional, 
                            $countCasosBonosNac, 
                            Lang::get('reportReport.concept_bonus'),
                            $efectivoTotalMin,
                            $efectivoTotalMax,
                            $efectivoTotalProm,
                            $efectivoTotalMed,
                            $efectivoTotal25Per,
                            $efectivoTotal75Per,
                            $segmento, 
                            $dbNivel);                
        
        //$detalleNacional = $this->nivelSegmentArrayFactory($nacional);
        $detalleNacional = array();
         foreach ($nacional as $value) {
            $detalleNacional[] = array( "Concepto"=>$value["concepto"],
                                        "ocupantes"=> $countOcupantesNac, 
                                        "Casos"=>$value["casos"], 
                                        "Min"=>$value["min"], 
                                        "25 Percentil"=>$value["per25"], 
                                        "Promedio"=>$value["prom"], 
                                        "Mediana"=>$value["med"], 
                                        "75 Percentil"=>$value["per75"], 
                                        "Max"=>$value["max"], 
                                        "nivel"=>$value["nivel"] 
                                        );
        }         
        
        $internacional = collect();
        $salarioBase = 0;
        $salarioMin = 0;
        $salarioMax = 0;
        $salarioProm = 0;
        $salarioMed = 0;
        $salario25Per = 0;
        $salario75Per = 0;
        $bonos = 0;
        $bonoMin = 0;
        $bonoMax = 0;
        $bonoProm = 0;
        $bonoMed = 0;
        $bono25Per = 0;
        $bono75Per = 0;        
        $efectivoAnual = 0;
        $efectivoTotalMin = 0;
        $efectivoTotalMax = 0;
        $efectivoTotalProm = 0;
        $efectivoTotalMed = 0;
        $efectivoTotal25Per = 0;
        $efectivoTotal75Per = 0;
        $countOcupantesInter = $dbEncuestasNivelInter->sum('cantidad_ocupantes');
        $segmento = "internacional";
        // Salario Base
        $salariosBase = $dbEncuestasNivelInter->where('salario_base', '>', '0')
                                              ->pluck('salario_base');
        $salarioMin = $salariosBase->min();
        $salarioMax = $salariosBase->max();
        $salarioProm = $salariosBase->avg();
        $salarioMed = $this->median($salariosBase);
        $salario25Per = $this->percentile(25,$salariosBase);
        $salario75Per = $this->percentile(75, $salariosBase);

        $this->pusherNivel( $internacional, 
                            $countCasosInter, 
                            Lang::get('reportReport.concept_salary'),
                            $salarioMin,
                            $salarioMax,
                            $salarioProm,
                            $salarioMed,
                            $salario25Per,
                            $salario75Per,
                            $segmento, 
                            $dbNivel);
        
        //Bono
        $bonos = $dbEncuestasNivelInter->where('bono_anual', '>', '0')
                                       ->pluck('bono_anual');
        $bonoMin = $bonos->min();
        $bonoMax = $bonos->max();
        $bonoProm = $bonos->avg();
        $bonoMed = $this->median($bonos);
        $bono25Per = $this->percentile(25, $bonos);
        $bono75Per = $this->percentile(75, $bonos);

        $countCasosBonosInter = $dbEncuestasNivelInter->where('bono_anual', '>', '0')
                                                      ->unique('cabecera_encuesta_id')
                                                      ->count();
        $this->pusherNivel( $internacional, 
                            $countCasosBonosInter, 
                            Lang::get('reportReport.concept_bonus'),
                            $bonoMin,
                            $bonoMax,
                            $bonoProm,
                            $bonoMed,
                            $bono25Per,
                            $bono75Per,
                            $segmento, 
                            $dbNivel);                
            
        // Efectivo Total Anual
        $efectivoAnual = $dbEncuestasNivelInter->where('salario_base', '>', '0')
                                               ->pluck('total_efectivo_anual');
               
        $efectivoTotalMin = $efectivoAnual->min();
        $efectivoTotalMax = $efectivoAnual->max();
        $efectivoTotalProm = $efectivoAnual->avg();
        $efectivoTotalMed = $this->median($efectivoAnual);
        $efectivoTotal25Per = $this->percentile(25, $efectivoAnual);
        $efectivoTotal75Per = $this->percentile(75, $efectivoAnual);

        $this->pusherNivel( $internacional, 
                            $countCasosInter, 
                            Lang::get('reportReport.concept_bonus'),
                            $efectivoTotalMin,
                            $efectivoTotalMax,
                            $efectivoTotalProm,
                            $efectivoTotalMed,
                            $efectivoTotal25Per,
                            $efectivoTotal75Per,
                            $segmento, 
                            $dbNivel);                
        
        //$detalleInternacional = $this->nivelSegmentArrayFactory($internacional);
         $detalleInternacional = array();
        foreach ($internacional as $value) {
            $detalleInternacional[] = array("Concepto"=>$value["concepto"],
                                            "ocupantes"=> $countOcupantesInter, 
                                            "Casos"=>$value["casos"], 
                                            "Min"=>$value["min"], 
                                            "25 Percentil"=>$value["per25"], 
                                            "Promedio"=>$value["prom"], 
                                            "Mediana"=>$value["med"], 
                                            "75 Percentil"=>$value["per75"], 
                                            "Max"=>$value["max"], 
                                            "nivel"=>$value["nivel"] 
                                           );
        } 

        $resultado = collect([  
                               "detalle_universo"=> $detalleUniverso, 
                               "detalle_nacional"=> $detalleNacional, 
                               "detalleInternacional"=>$detalleInternacional
                            ]);
        return $resultado;         

    }    
    public function pusherNivel(&$collection, $casos, $concepto, $min, $max, $prom, $med, $per25, $per75, $segmento, $dbNivel){

        $collection->push([ "concepto"=> $concepto,
                            "casos"=> $casos,
                            "min"=>$min, 
                            "max"=>$max, 
                            "prom"=>$prom, 
                            "med"=>$med, 
                            "per25"=>$per25, 
                            "per75"=> $per75, 
                            "segmento"=>$segmento,
                            "nivel" => $dbNivel->descripcion
        ]);
        
    }   
    
    public function nivelSegmentArrayFactory($segmentArray){
        foreach ($segmentArray as $value) {

            $response[] = array(    Lang::get('reportReport.table_concepts')  => $value["concepto"], 
                                    Lang::get('reportReport.table_occupants') => $value["casos"],
                                    Lang::get('reportReport.table_cases')     => $value["casos"], 
                                    Lang::get('reportReport.table_min')       => $value["min"], 
                                    Lang::get('reportReport.table_perc25')    => $value["per25"], 
                                    Lang::get('reportReport.table_average')   => $value["prom"], 
                                    Lang::get('reportReport.table_median')    => $value["med"], 
                                    Lang::get('reportReport.table_perc75')    => $value["per75"], 
                                    Lang::get('reportReport.table_max')       => $value["max"]
                                );
        }

        return $response;
    }        
    public function cargaDetalleNivel($item, &$itemArray){
        $variableAnual = false;
        
        foreach ($item as $key => $value) {
            switch ($value["Concepto"]) {
                case "Comision":
                    $this->cargadorNivel($value, $itemArray, false);
                    break;
                case Lang::get('reportReport.concept_salary'):
                    $this->cargadorNivel($value, $itemArray, true);
                    break;
                case Lang::get('reportReport.concept_annual_cash'):
                    $this->cargadorNivel($value, $itemArray, false);
                    break;
                case Lang::get('reportReport.variable_pay'):
                    $variableAnual = true;
                    $this->cargadorNivel($value, $itemArray, false);
                    
                    break;
                case Lang::get('reportReport.concept_total_incentives'):
                    $this->cargadorNivel($value, $itemArray, false);
                    break;
                case Lang::get('reportReport.concept_bonus'):
                    $this->cargadorNivel($value, $itemArray, false);
                    break;
                case Lang::get('reportReport.concept_total_compensation'):
                    $this->cargadorNivel($value, $itemArray, false);
                    break;
                
            }
        }
        
    }

    public function cargadorNivel($value, &$itemArray, $casos){
        if($casos){
            array_push($itemArray, $value["ocupantes"]);
            array_push($itemArray, $value["Casos"]);           
        }
        array_push($itemArray, $value["Min"]);
        array_push($itemArray, $value["25 Percentil"]);
        array_push($itemArray, round($value["Promedio"], 2));
        array_push($itemArray, $value["Mediana"]);
        array_push($itemArray, $value["75 Percentil"]);
        array_push($itemArray, $value["Max"]);            
    }

    public function cargoReportAll(Request $request, $tipo, $muestraComision = true){

        $dbEmpresa = Empresa::find($request->empresa_id);   // datos de la empresa del cliente
        if(Session::has('periodo')){
            $per = Session::get('periodo');
            $dbEncuesta = Cabecera_encuesta::where('empresa_id', $dbEmpresa->id)
                                           ->whereRaw("periodo = '". $per."'")
                                           ->first();
        }else{
            if(Auth::user()->is_admin){
                $dbEncuesta = Cabecera_encuesta::where('empresa_id', $dbEmpresa->id)
                                               ->whereRaw("periodo = '". $request->periodo."'")
                                               ->first();
            }else{
                $ficha = Ficha_dato::activa()->where('rubro_id', $dbEmpresa->rubro_id)->first();
                if($ficha){
                    $per = $ficha->periodo;
                }else{
                    $per = null;
                }
                if($per){
                    $dbEncuesta = Cabecera_encuesta::where('empresa_id', $dbEmpresa->id)
                                                   ->where("periodo",$per)
                                                   ->first();
                }else{
                    if($dbEmpresa->rubro_id == '1'){
                        $per = '06/2018';
                        $dbEncuesta = Cabecera_encuesta::where('empresa_id', $dbEmpresa->id)
                                               ->whereRaw("periodo = '". $per."'")
                                               ->first();
                    }else{
                        $dbEncuesta = Cabecera_encuesta::where('empresa_id', $dbEmpresa->id)
                        ->whereRaw('id = (select max(id) from cabecera_encuestas where empresa_id = '. $dbEmpresa->id.')')
                        ->first();
                    }
                }
                
                            

            }
        }

        $periodo = $dbEncuesta->periodo;    // periodo de la encuesta actual

        $rubro = $dbEmpresa->rubro_id;      // rubro de la empresa del cliente
        // cargo oficial para el informe
        $cargo = $request->cargo_id; 
        if($this->getIdioma() == "es"){
            $dbCargo = Cargo::find($cargo);
        }else{
            $dbCargo = Cargo_en::find($cargo);
        }    
          
        // empresas y cabeceras de encuestas de este periodo para empresas del rubro del cliente
        $dbEncuestadas = Cabecera_encuesta::where('periodo', $periodo)
                                          ->where('rubro_id', $rubro)
                                          ->get();
        
        $encuestadasIds = $dbEncuestadas->pluck("id");  // Ids de las encuestas para where in

        // conteo de encuestas según origen
        $dbNacionales = 0;
        $dbInternacionales = 0;
        $dbUniverso = $dbEncuestadas->count();
        $encuestadasNacIds = collect();
        $encuestadasInterIds = collect();
        foreach ($dbEncuestadas as $key => $value) {
            if($value->empresa->tipo == 0){
                $dbNacionales++;
                $encuestadasNacIds[] = $value->id;
            }else{
                $dbInternacionales++;
                $encuestadasInterIds[] = $value->id;
            };
        }
        // Recuperamos los datos de los cargos proveídos por las empresas encuestadas
        $dbCargosEncuestas = Encuestas_cargo::whereIn('cabecera_encuesta_id', $encuestadasIds)
                                            ->where('cargo_id', $cargo)
                                            ->where('incluir', 1)
                                            ->get();
        

        $dbCargosEncuestasNac = Encuestas_cargo::whereIn('cabecera_encuesta_id', $encuestadasNacIds)->where('cargo_id', $cargo)->where('incluir', 1)->get();
        $dbCargosEncuestasInter = Encuestas_cargo::whereIn('cabecera_encuesta_id', $encuestadasInterIds)->where('cargo_id', $cargo)->where('incluir', 1)->get();
        
        $cargosEncuestasIds = $dbCargosEncuestas->pluck('id');
        $cargosEncuestasNacIds = $dbCargosEncuestasNac->pluck('id');
        $cargosEncuestasInterIds = $dbCargosEncuestasInter->pluck('id');

        // Recuperamos los datos de las encuestas
        $dbDetalle = Detalle_encuesta::whereIn('encuestas_cargo_id', $cargosEncuestasIds)->get();
        
        // Datos de la encuesta llenada por el cliente
        $dbClienteEnc = $dbDetalle->where('cabecera_encuesta_id', $dbEncuesta->id)->first();
        if(empty($dbClienteEnc)){
            // get the column names for the table
            $columns = Schema::getColumnListing('detalle_encuestas');
            // create array where column names are keys, and values are null
            $columns = array_fill_keys($columns, 0);        
            $dbClienteEnc = new Detalle_encuesta();
            $dbClienteEnc = $dbClienteEnc->newInstance($columns, true);
        }
        // conteo de casos encontrados
        $countCasos = $dbDetalle->where('cantidad_ocupantes', '>', '0')
                                ->unique('cabecera_encuesta_id')
                                ->count();
        
        $countOcupantes = $dbDetalle->sum('cantidad_ocupantes');
        $countCasosGratif = $dbDetalle->where('cantidad_ocupantes', '>', '0')
                                      ->where('gratificacion', '>', '0')
                                      ->unique('cabecera_encuesta_id')->count();
        $countCasosAguinaldo = $dbDetalle->where('cantidad_ocupantes', '>', '0')
                                         ->where('aguinaldo', '>', '0')
                                         ->unique('cabecera_encuesta_id')->count();
        //$countCasosBeneficios = $dbDetalle->where('beneficios_bancos', '>', 0)->unique('cabecera_encuesta_id')->count();
        $countCasosBono = $dbDetalle->where('cantidad_ocupantes', '>', '0')
                                    ->where('bono_anual', '>', 0)
                                    ->unique('cabecera_encuesta_id')->count();



        $universo = collect();
        $segmento = "universo";
        $this->segmenter( $universo, 
                          $dbUniverso, 
                          $dbDetalle, 
                          $countCasos,
                          $countCasosGratif,
                          $countCasosAguinaldo,
                         // $countCasosBeneficios, 
                          $countCasosBono,
                          $dbClienteEnc, 
                          $rubro, 
                          $segmento, 
                          $dbCargo, 
                          $muestraComision);

        // conteo de casos encontrados nacionales
        $countCasosNac = $encuestadasNacIds->count();
        // buscamos los detalles de las encuestas
        $dbDetalleNac = Detalle_encuesta::whereIn('encuestas_cargo_id', $cargosEncuestasNacIds)->get();
        // conteo de casos encontrados
        $countOcupantesNac = $dbDetalleNac->sum('cantidad_ocupantes');
        $countCasos = $dbDetalleNac->where('cantidad_ocupantes', '>', '0')
                                   ->unique('cabecera_encuesta_id')
                                   ->count();
        $countCasosGratif = $dbDetalleNac->where('cantidad_ocupantes', '>', '0')
                                         ->where('gratificacion', '>', '0')
                                         ->unique('cabecera_encuesta_id')->count();
        $countCasosAguinaldo = $dbDetalleNac->where('cantidad_ocupantes', '>', '0')
                                            ->where('aguinaldo', '>', '0')
                                            ->unique('cabecera_encuesta_id')->count();

        $countCasosBono = $dbDetalleNac->where('cantidad_ocupantes', '>', '0')
                                       ->where('bono_anual', '>', 0)
                                       ->unique('cabecera_encuesta_id')
                                       ->count();

        $nacional = collect();
        $segmento = "nacional";
        $this->segmenter(   $nacional, 
                            $countCasosNac, 
                            $dbDetalleNac, 
                            $countCasos, 
                            $countCasosGratif, 
                            $countCasosAguinaldo, 
                        //    $countCasosBeneficios, 
                            $countCasosBono, 
                            $dbClienteEnc, 
                            $rubro, 
                            $segmento, 
                            $dbCargo, 
                            $muestraComision);

        // conteo de casos encontrados internacionales
        $countCasosInt = $encuestadasInterIds->count();
        // buscamos los detalles de las encuestas
        $dbDetalleInt = Detalle_encuesta::whereIn('encuestas_cargo_id', $cargosEncuestasInterIds)->get();
        $countOcupantesInt = $dbDetalleInt->sum('cantidad_ocupantes');
        // conteo de casos encontrados
        $countCasos = $dbDetalleInt->where('cantidad_ocupantes', '>', '0')
                                   ->unique('cabecera_encuesta_id')
                                   ->count();
        $countCasosGratif = $dbDetalleInt->where('cantidad_ocupantes', '>', '0')
                                         ->where('gratificacion', '>', '0')
                                         ->unique('cabecera_encuesta_id')
                                         ->count();
        $countCasosAguinaldo = $dbDetalleInt->where('cantidad_ocupantes', '>', '0')
                                            ->where('aguinaldo', '>', '0')
                                            ->unique('cabecera_encuesta_id')
                                            ->count();
        
        //$countCasosBeneficios = $dbDetalleInt->where('beneficios_bancos', '>', 0)->unique('cabecera_encuesta_id')->count();




        $countCasosBono = $dbDetalleInt->where('cantidad_ocupantes', '>', '0')
                                       ->where('bono_anual', '>', 0)
                                       ->unique('cabecera_encuesta_id')
                                       ->count();

        $internacional = collect();
        $segmento = "internacional";

        $this->segmenter(   $internacional, 
                            $countCasosInt, 
                            $dbDetalleInt, 
                            $countCasos, 
                            $countCasosGratif, 
                            $countCasosAguinaldo, 
                           // $countCasosBeneficios, 
                            $countCasosBono, 
                            $dbClienteEnc,
                            $rubro, 
                            $segmento, 
                            $dbCargo, 
                            $muestraComision);
        if($tipo == "view"){
            if($request->tour){
                $tour = true;
            }else{
                $tour = false;
            }

            if($request->moneda == "local"){
                $convertir = false;
            }else{
                $convertir = true;
            }

            $ficha = Ficha_dato::where('periodo', $periodo)->first();
            if($ficha){
                $tipoCambio = $ficha->tipo_cambio;
            }else{
                $tipoCambio = 5600;
            }
            
            
            return view('report.report')->with('dbCargo', $dbCargo)
                                        ->with('dbEmpresa', $dbEmpresa)
                                        ->with('universo', $universo)
                                        ->with('nacional', $nacional)
                                        ->with('internacional', $internacional)
                                        ->with('countOcupantes', $countOcupantes)
                                        ->with('countOcupantesNac', $countOcupantesNac)
                                        ->with('countOcupantesInt', $countOcupantesInt)
                                        ->with('tour', $tour)
                                        ->with('tipoCambio', $tipoCambio)
                                        ->with('periodo', $periodo)
                                        ->with('convertir', $convertir);            

        }elseif($tipo == "excel"){
            $periodo = implode('_', explode('/', $periodo));
            $cargoFileName = str_replace("-", "_", str_replace(" ", "_", $dbCargo->descripcion));
            $filename = 'Resultados_'.$periodo.'_'.$cargoFileName;
            $detalleUniverso = $this->segmentArrayFactory($universo);
            $detalleNacional = $this->segmentArrayFactory($nacional);
            $detalleInternacional = $this->segmentArrayFactory($internacional);

            Excel::create($filename, function($excel) use($detalleUniverso, $detalleNacional, $detalleInternacional ) {
                $excel->sheet("universo", function($sheet) use($detalleUniverso){
                    $sheet->fromArray($detalleUniverso);
                });
                $excel->sheet("nacional", function($sheet) use($detalleNacional){
                    $sheet->fromArray($detalleNacional);
                });
                $excel->sheet("internacional", function($sheet) use($detalleInternacional){
                    $sheet->fromArray($detalleInternacional);
                });

            })->export('xlsx');
            return redirect()->route('resultados');
        }elseif ($tipo == "clubExcel") {
            
            $cargoFileName = str_replace("-", "_", str_replace(" ", "_", $dbCargo->descripcion));
            $filename = 'Resultados_'.$periodo.'_'.$cargoFileName;
            $detalleUniverso = array();
            $detalleNacional = array();
            $detalleInternacional = array();
            foreach ($universo as $value) {

                $detalleUniverso[] = array( "Concepto"=>$value["concepto"], 
                                            "ocupantes"=> $countOcupantes,
                                            "Casos"=>$value["casos"], 
                                            "Min"=>$value["min"], 
                                            "25 Percentil"=>$value["per25"], 
                                            "Promedio"=>$value["prom"], 
                                            "Mediana"=>$value["med"], 
                                            "75 Percentil"=>$value["per75"], 
                                            "Max"=>$value["max"], 
                                            "Empresa"=>$value["empresa"] 
                                          );
            }
            foreach ($nacional as $value) {
                $detalleNacional[] = array( "Concepto"=>$value["concepto"],
                                            "ocupantes"=> $countOcupantesNac, 
                                            "Casos"=>$value["casos"], 
                                            "Min"=>$value["min"], 
                                            "25 Percentil"=>$value["per25"], 
                                            "Promedio"=>$value["prom"], 
                                            "Mediana"=>$value["med"], 
                                            "75 Percentil"=>$value["per75"], 
                                            "Max"=>$value["max"], 
                                            "Empresa"=>$value["empresa"] 
                                          );
            }

            foreach ($internacional as $value) {
                $detalleInternacional[] = array( "Concepto"=>$value["concepto"], 
                                            "ocupantes"=> $countOcupantesInt,
                                            "Casos"=>$value["casos"], 
                                            "Min"=>$value["min"], 
                                            "25 Percentil"=>$value["per25"], 
                                            "Promedio"=>$value["prom"], 
                                            "Mediana"=>$value["med"], 
                                            "75 Percentil"=>$value["per75"], 
                                            "Max"=>$value["max"], 
                                            "Empresa"=>$value["empresa"] 
                                          );
            }
            $resultado = collect([  
                                    "detalle_universo"=> $detalleUniverso, 
                                    "detalle_nacional"=> $detalleNacional, 
                                    "detalleInternacional"=>$detalleInternacional
                                ]);

            return $resultado;         
        }

    }

    public function cargoReportEspecial(Request $request, $tipo, $muestraComision = true){
  
        $dbEmpresa = Empresa::find($request->empresa_id);   // datos de la empresa del cliente
        if(Session::has('periodo')){
            $per = Session::get('periodo');
            $dbEncuesta = Cabecera_encuesta::where('empresa_id', $dbEmpresa->id)
                                           ->whereRaw("periodo = '". $per."'")
                                           ->first();
        }else{
            if(Auth::user()->is_admin){
                $dbEncuesta = Cabecera_encuesta::where('empresa_id', $dbEmpresa->id)
                                               ->whereRaw("periodo = '". $request->periodo."'")
                                               ->first();
            }else{
                $ficha = Ficha_dato::activa()->where('rubro_id', $dbEmpresa->rubro_id)->first();
                if($ficha){
                    $per = $ficha->periodo;
                }else{
                    $per = null;
                }
                if($per){
                    $dbEncuesta = Cabecera_encuesta::where('empresa_id', $dbEmpresa->id)
                                                   ->where("periodo",$per)
                                                   ->first();
                }else{
                    if($dbEmpresa->rubro_id == '1'){
                        $per = '06/2018';
                        $dbEncuesta = Cabecera_encuesta::where('empresa_id', $dbEmpresa->id)
                                               ->whereRaw("periodo = '". $per."'")
                                               ->first();
                    }else{
                        $dbEncuesta = Cabecera_encuesta::where('empresa_id', $dbEmpresa->id)
                        ->whereRaw('id = (select max(id) from cabecera_encuestas where empresa_id = '. $dbEmpresa->id.')')
                        ->first();
                    }
                }
                
                            

            }
        }
        $periodo = $dbEncuesta->periodo;    // periodo de la encuesta actual

        $rubro = $dbEmpresa->rubro_id;      // rubro de la empresa del cliente
        $subRubro = $dbEmpresa->sub_rubro_id;
        // cargo oficial para el informe
        $cargo = $request->cargo_id; 
        if($this->getIdioma() == "es"){
            $dbCargo = Cargo::find($cargo);
        }else{
            $dbCargo = Cargo_en::find($cargo);
        }    
          
        // empresas y cabeceras de encuestas de este periodo para empresas del rubro del cliente
        $dbEncuestadas = Cabecera_encuesta::where('periodo', $periodo)
                                          ->where('rubro_id', $rubro)
                                          ->where('sub_rubro_id', $subRubro)
                                          ->get();

        $encuestadasIds = $dbEncuestadas->pluck("id");  // Ids de las encuestas para where in
        // conteo de encuestas según origen
        $dbNacionales = 0;
        $dbInternacionales = 0;
        $dbUniverso = $dbEncuestadas->count();
        $encuestadasNacIds = collect();
        $encuestadasInterIds = collect();
        foreach ($dbEncuestadas as $key => $value) {
            if($value->empresa->tipo == 0){
                $dbNacionales++;
                $encuestadasNacIds[] = $value->id;
            }else{
                $dbInternacionales++;
                $encuestadasInterIds[] = $value->id;
            };
        }
        // Recuperamos los datos de los cargos proveídos por las empresas encuestadas
        $dbCargosEncuestas = Encuestas_cargo::whereIn('cabecera_encuesta_id', $encuestadasIds)
                                            ->where('cargo_id', $cargo)
                                            ->where('incluir', 1)
                                            ->get();

        $dbCargosEncuestasNac = Encuestas_cargo::whereIn('cabecera_encuesta_id', $encuestadasNacIds)->where('cargo_id', $cargo)->where('incluir', 1)->get();
        $dbCargosEncuestasInter = Encuestas_cargo::whereIn('cabecera_encuesta_id', $encuestadasInterIds)->where('cargo_id', $cargo)->where('incluir', 1)->get();
        
        $cargosEncuestasIds = $dbCargosEncuestas->pluck('id');
        $cargosEncuestasNacIds = $dbCargosEncuestasNac->pluck('id');
        $cargosEncuestasInterIds = $dbCargosEncuestasInter->pluck('id');

        // Recuperamos los datos de las encuestas
        $dbDetalle = Detalle_encuesta::whereIn('encuestas_cargo_id', $cargosEncuestasIds)->get();

        // Datos de la encuesta llenada por el cliente
        $dbClienteEnc = $dbDetalle->where('cabecera_encuesta_id', $dbEncuesta->id)->first();
        if(empty($dbClienteEnc)){
            // get the column names for the table
            $columns = Schema::getColumnListing('detalle_encuestas');
            // create array where column names are keys, and values are null
            $columns = array_fill_keys($columns, 0);        
            $dbClienteEnc = new Detalle_encuesta();
            $dbClienteEnc = $dbClienteEnc->newInstance($columns, true);
        }
        // conteo de casos encontrados
        $countCasos = $dbDetalle->where('cantidad_ocupantes', '>', '0')
                                ->unique('cabecera_encuesta_id')
                                ->count();
        $countOcupantes = $dbDetalle->sum('cantidad_ocupantes');
        $countCasosGratif = $dbDetalle->where('cantidad_ocupantes', '>', '0')
                                      ->where('gratificacion', '>', '0')
                                      ->unique('cabecera_encuesta_id')->count();
        $countCasosAguinaldo = $dbDetalle->where('cantidad_ocupantes', '>', '0')
                                         ->where('aguinaldo', '>', '0')
                                         ->unique('cabecera_encuesta_id')->count();
        //$countCasosBeneficios = $dbDetalle->where('beneficios_bancos', '>', 0)->unique('cabecera_encuesta_id')->count();
        $countCasosBono = $dbDetalle->where('cantidad_ocupantes', '>', '0')
                                    ->where('bono_anual', '>', 0)
                                    ->unique('cabecera_encuesta_id')->count();



        $universo = collect();
        $segmento = "universo";
        $this->segmenter( $universo, 
                          $dbUniverso, 
                          $dbDetalle, 
                          $countCasos,
                          $countCasosGratif,
                          $countCasosAguinaldo,
                         // $countCasosBeneficios, 
                          $countCasosBono,
                          $dbClienteEnc, 
                          $rubro, 
                          $segmento, 
                          $dbCargo, 
                          $muestraComision);

        // conteo de casos encontrados nacionales
        $countCasosNac = $encuestadasNacIds->count();
        // buscamos los detalles de las encuestas
        $dbDetalleNac = Detalle_encuesta::whereIn('encuestas_cargo_id', $cargosEncuestasNacIds)->get();
        // conteo de casos encontrados
        $countOcupantesNac = $dbDetalleNac->sum('cantidad_ocupantes');
        $countCasos = $dbDetalleNac->where('cantidad_ocupantes', '>', '0')
                                   ->unique('cabecera_encuesta_id')
                                   ->count();
        $countCasosGratif = $dbDetalleNac->where('cantidad_ocupantes', '>', '0')
                                         ->where('gratificacion', '>', '0')
                                         ->unique('cabecera_encuesta_id')->count();
        $countCasosAguinaldo = $dbDetalleNac->where('cantidad_ocupantes', '>', '0')
                                            ->where('aguinaldo', '>', '0')
                                            ->unique('cabecera_encuesta_id')->count();

        $countCasosBono = $dbDetalleNac->where('cantidad_ocupantes', '>', '0')
                                       ->where('bono_anual', '>', 0)
                                       ->unique('cabecera_encuesta_id')
                                       ->count();

        $nacional = collect();
        $segmento = "nacional";
        $this->segmenter(   $nacional, 
                            $countCasosNac, 
                            $dbDetalleNac, 
                            $countCasos, 
                            $countCasosGratif, 
                            $countCasosAguinaldo, 
                        //    $countCasosBeneficios, 
                            $countCasosBono, 
                            $dbClienteEnc, 
                            $rubro, 
                            $segmento, 
                            $dbCargo, 
                            $muestraComision);

        // conteo de casos encontrados internacionales
        $countCasosInt = $encuestadasInterIds->count();
        // buscamos los detalles de las encuestas
        $dbDetalleInt = Detalle_encuesta::whereIn('encuestas_cargo_id', $cargosEncuestasInterIds)->get();
        $countOcupantesInt = $dbDetalleInt->sum('cantidad_ocupantes');
        // conteo de casos encontrados
        $countCasos = $dbDetalleInt->where('cantidad_ocupantes', '>', '0')
                                   ->unique('cabecera_encuesta_id')
                                   ->count();
        $countCasosGratif = $dbDetalleInt->where('cantidad_ocupantes', '>', '0')
                                         ->where('gratificacion', '>', '0')
                                         ->unique('cabecera_encuesta_id')
                                         ->count();
        $countCasosAguinaldo = $dbDetalleInt->where('cantidad_ocupantes', '>', '0')
                                            ->where('aguinaldo', '>', '0')
                                            ->unique('cabecera_encuesta_id')
                                            ->count();

        $countCasosBono = $dbDetalleInt->where('cantidad_ocupantes', '>', '0')
                                       ->where('bono_anual', '>', 0)
                                       ->unique('cabecera_encuesta_id')
                                       ->count();

        $internacional = collect();
        $segmento = "internacional";

        $this->segmenter(   $internacional, 
                            $countCasosInt, 
                            $dbDetalleInt, 
                            $countCasos, 
                            $countCasosGratif, 
                            $countCasosAguinaldo, 
                           // $countCasosBeneficios, 
                            $countCasosBono, 
                            $dbClienteEnc,
                            $rubro, 
                            $segmento, 
                            $dbCargo, 
                            $muestraComision);
        if($tipo == "view"){
            if($request->tour){
                $tour = true;
            }else{
                $tour = false;
            }

            if($request->moneda == "local"){
                $convertir = false;
            }else{
                $convertir = true;
            }

            $ficha = Ficha_dato::where('periodo', $periodo)->first();
            if($ficha){
                $tipoCambio = $ficha->tipo_cambio;
            }else{
                $tipoCambio = 5600;
            }
            

            return view('report.report')->with('dbCargo', $dbCargo)
                                        ->with('dbEmpresa', $dbEmpresa)
                                        ->with('universo', $universo)
                                        ->with('nacional', $nacional)
                                        ->with('internacional', $internacional)
                                        ->with('countOcupantes', $countOcupantes)
                                        ->with('countOcupantesNac', $countOcupantesNac)
                                        ->with('countOcupantesInt', $countOcupantesInt)
                                        ->with('tour', $tour)
                                        ->with('tipoCambio', $tipoCambio)
                                        ->with('periodo', $periodo)
                                        ->with('convertir', $convertir);            

        }elseif($tipo == "excel"){
            $periodo = implode('_', explode('/', $periodo));
            $cargoFileName = str_replace("-", "_", str_replace(" ", "_", $dbCargo->descripcion));
            $filename = 'Resultados_'.$periodo.'_'.$cargoFileName;
            $detalleUniverso = $this->segmentArrayFactory($universo);
            $detalleNacional = $this->segmentArrayFactory($nacional);
            $detalleInternacional = $this->segmentArrayFactory($internacional);

            Excel::create($filename, function($excel) use($detalleUniverso, $detalleNacional, $detalleInternacional ) {
                $excel->sheet("universo", function($sheet) use($detalleUniverso){
                    $sheet->fromArray($detalleUniverso);
                });
                $excel->sheet("nacional", function($sheet) use($detalleNacional){
                    $sheet->fromArray($detalleNacional);
                });
                $excel->sheet("internacional", function($sheet) use($detalleInternacional){
                    $sheet->fromArray($detalleInternacional);
                });

            })->export('xlsx');
            return redirect()->route('resultados');
        }elseif ($tipo == "clubExcel") {
            
            $cargoFileName = str_replace("-", "_", str_replace(" ", "_", $dbCargo->descripcion));
            $filename = 'Resultados_'.$periodo.'_'.$cargoFileName;
            $detalleUniverso = array();
            $detalleNacional = array();
            $detalleInternacional = array();
            foreach ($universo as $value) {

                $detalleUniverso[] = array( "Concepto"=>$value["concepto"], 
                                            "ocupantes"=> $countOcupantes,
                                            "Casos"=>$value["casos"], 
                                            "Min"=>$value["min"], 
                                            "25 Percentil"=>$value["per25"], 
                                            "Promedio"=>$value["prom"], 
                                            "Mediana"=>$value["med"], 
                                            "75 Percentil"=>$value["per75"], 
                                            "Max"=>$value["max"], 
                                            "Empresa"=>$value["empresa"] 
                                          );
            }
            foreach ($nacional as $value) {
                $detalleNacional[] = array( "Concepto"=>$value["concepto"],
                                            "ocupantes"=> $countOcupantesNac, 
                                            "Casos"=>$value["casos"], 
                                            "Min"=>$value["min"], 
                                            "25 Percentil"=>$value["per25"], 
                                            "Promedio"=>$value["prom"], 
                                            "Mediana"=>$value["med"], 
                                            "75 Percentil"=>$value["per75"], 
                                            "Max"=>$value["max"], 
                                            "Empresa"=>$value["empresa"] 
                                          );
            }

            foreach ($internacional as $value) {
                $detalleInternacional[] = array( "Concepto"=>$value["concepto"], 
                                            "ocupantes"=> $countOcupantesInt,
                                            "Casos"=>$value["casos"], 
                                            "Min"=>$value["min"], 
                                            "25 Percentil"=>$value["per25"], 
                                            "Promedio"=>$value["prom"], 
                                            "Mediana"=>$value["med"], 
                                            "75 Percentil"=>$value["per75"], 
                                            "Max"=>$value["max"], 
                                            "Empresa"=>$value["empresa"] 
                                          );
            }
            $resultado = collect([  
                                    "detalle_universo"=> $detalleUniverso, 
                                    "detalle_nacional"=> $detalleNacional, 
                                    "detalleInternacional"=>$detalleInternacional
                                ]);

            return $resultado;         
        }

    }

    public function cargoComparativoEspecial($encuesta, $encuestaAnt, $empresa, $cargo, $convertir, $contNuevo){
        // periodo de la encuesta actual
        $periodo = $encuesta->periodo;  
        // periodo de la encuesta anterior  
        $periodoAnt = $encuestaAnt->periodo;
        // rubro de la empresa del cliente
        $rubro = $empresa->rubro_id;      
        $subRubro = $empresa->sub_rubro_id;
        // tipo de cambio
        if($convertir){
            $ficha = Ficha_dato::where('periodo', $periodo)
                               ->where('rubro_id', $rubro)
                               ->first();
            if($ficha){
                $tipoCambio = $ficha->tipo_cambio;
            }else{
                $tipoCambio = 6000;
            }
        }else{
            $tipoCambio = 1;
        }
        // cargo oficial para el informe
        if($this->getIdioma() == "es"){
            $dbCargo = Cargo::find($cargo);
        }else{
            $dbCargo = Cargo_en::find($cargo);
        }    
          
        // empresas y cabeceras de encuestas de este periodo para empresas del rubro del cliente
        $dbEncuestadas = Cabecera_encuesta::where('periodo', $periodo)
                                          ->where('rubro_id', $rubro)
                                          ->where('sub_rubro_id', $subRubro)
                                          ->get();

        $encuestadasIds = $dbEncuestadas->pluck("id");  // Ids de las encuestas para where in
        // Recuperamos los datos de los cargos proveídos por las empresas encuestadas
        $dbCargosEncuestas = Encuestas_cargo::whereIn('cabecera_encuesta_id', $encuestadasIds)
                                            ->where('cargo_id', $cargo)
                                            ->where('incluir', 1)
                                            ->get();

        $cargosEncuestasIds = $dbCargosEncuestas->pluck('id');

        // Recuperamos los datos de las encuestas
        if($contNuevo){
            $cargosNuevosID = Encuestas_cargo::whereIn('cabecera_encuesta_id', $encuestadasIds)
                                             ->where('cargo_id', $cargo)
                                             ->where('es_contrato_periodo', 1)
                                             ->where('incluir', 1)
                                             ->pluck('id');

            $dbDetalle = Detalle_encuesta::whereIn('encuestas_cargo_id', $cargosNuevosID)
                                         ->get();
           
            $countCasos =  $dbDetalle->where('cantidad_ocupantes', '>', '0')
                                     ->whereIn('encuestas_cargo_id', $cargosNuevosID)  
                                     ->sum('cantidad_ocupantes');                                                
        }else{
            $dbDetalle = Detalle_encuesta::whereIn('encuestas_cargo_id', $cargosEncuestasIds)
                                         ->get();
            $countCasos = 0;
        }    
        
        
        // Datos de la encuesta llenada por el cliente
        $dbClienteEnc = $dbDetalle->where('cabecera_encuesta_id', $encuesta->id)->first();
        if(empty($dbClienteEnc)){
            // get the column names for the table
            $columns = Schema::getColumnListing('detalle_encuestas');
            // create array where column names are keys, and values are null
            $columns = array_fill_keys($columns, 0);        
            $dbClienteEnc = new Detalle_encuesta();
            $dbClienteEnc = $dbClienteEnc->newInstance($columns, true);
        }        

        // empresas y cabeceras de encuestas de este periodo para empresas del rubro del cliente
        $dbEncuestadasAnt = Cabecera_encuesta::where('periodo', $periodoAnt)
                                             ->where('rubro_id', $rubro)
                                             ->where('sub_rubro_id', $subRubro)
                                             ->get();

        $encuestadasAntIds = $dbEncuestadasAnt->pluck("id");  // Ids de las encuestas para where in
        // Recuperamos los datos de los cargos proveídos por las empresas encuestadas
        $dbCargosEncuestasAnt = Encuestas_cargo::whereIn('cabecera_encuesta_id', $encuestadasAntIds)
                                               ->where('cargo_id', $cargo)
                                               ->where('incluir', 1)
                                               ->get();

        $cargosEncuestasAntIds = $dbCargosEncuestasAnt->pluck('id');

        // Recuperamos los datos de las encuestas
        $dbDetalleAnt = Detalle_encuesta::whereIn('encuestas_cargo_id', $cargosEncuestasAntIds)->get();
        // Datos de la encuesta llenada por el cliente
        $dbClienteEncAnt = $dbDetalleAnt->where('cabecera_encuesta_id', $encuestaAnt->id)->first();
        if(empty($dbClienteEncAnt)){
            // get the column names for the table
            $columns = Schema::getColumnListing('detalle_encuestas');
            // create array where column names are keys, and values are null
            $columns = array_fill_keys($columns, 0);        
            $dbClienteEnc = new Detalle_encuesta();
            $dbClienteEnc = $dbClienteEnc->newInstance($columns, true);
        }   

        $salBase = collect();        
        $this->segmenterComparativo( $salBase, 
                                     $dbDetalle,
                                     $dbDetalleAnt, 
                                     $dbClienteEnc,
                                     $dbClienteEncAnt, 
                                     "SB",
                                     $dbCargo,
                                     $countCasos
                                    );        
        $detalleSalarioBase = $this->comparativoArrayFactory($salBase, $tipoCambio, $contNuevo);
        
        
        
        $efectivoTotalAnual = collect();        
        $this->segmenterComparativo( $efectivoTotalAnual, 
                                     $dbDetalle,
                                     $dbDetalleAnt, 
                                     $dbClienteEnc,
                                     $dbClienteEncAnt, 
                                     "ETA",
                                     $dbCargo
                                    );         
        $detalleETA = $this->comparativoArrayFactory($efectivoTotalAnual, $tipoCambio);

        $variable = collect();
        $this->segmenterComparativo( $variable, 
                                     $dbDetalle,
                                     $dbDetalleAnt, 
                                     $dbClienteEnc,
                                     $dbClienteEncAnt, 
                                     "VAR",
                                     $dbCargo
                                    ); 
        $detalleVar = $this->comparativoArrayFactory($variable, $tipoCambio);

        $variableViaje = collect();
        $this->segmenterComparativo( $variableViaje, 
                                     $dbDetalle,
                                     $dbDetalleAnt, 
                                     $dbClienteEnc,
                                     $dbClienteEncAnt, 
                                     "VV",
                                     $dbCargo
                                    ); 
        $detalleVarViaje = $this->comparativoArrayFactory($variableViaje, $tipoCambio);

        $salBaseVarViaje = collect();
        $this->segmenterComparativo( $salBaseVarViaje, 
                                     $dbDetalle,
                                     $dbDetalleAnt, 
                                     $dbClienteEnc,
                                     $dbClienteEncAnt, 
                                     "SBVV",
                                     $dbCargo
                                    ); 
        $detalleSBVV = $this->comparativoArrayFactory($salBaseVarViaje, $tipoCambio);
        
        
        $adicionalTotal = collect();
        $this->segmenterComparativo( $adicionalTotal, 
                                     $dbDetalle,
                                     $dbDetalleAnt, 
                                     $dbClienteEnc,
                                     $dbClienteEncAnt, 
                                     "ATA",
                                     $dbCargo
                                    ); 
        $detalleATA = $this->comparativoArrayFactory($adicionalTotal, $tipoCambio);

        $resultado = collect([  
                                "detalle_salario_base"=> $detalleSalarioBase, 
                                "detalle_efectivo_total_anual" => $detalleETA,
                                "detalle_variable" => $detalleVar,
                                "detalle_variable_viaje" => $detalleVarViaje,
                                "detalle_sb_vv" => $detalleSBVV,
                                "detalle_adicional_total_anual" => $detalleATA
                            ]);

        return $resultado;         
        

    }

    public function comparativoArrayFactory($collection, $tipoCambio, $contNuevo = false){
        foreach ($collection as $value) {

            $brechaMin = $this->calcBrecha($value['min_ant'], $value["min"]);
            $brecha25P = $this->calcBrecha($value['per25_ant'], $value["per25"]);
            $brechaProm = $this->calcBrecha($value['prom_ant'], $value["prom"]);
            $brechaMed = $this->calcBrecha($value['med_ant'], $value["med"]);
            $brecha75P = $this->calcBrecha($value['per75_ant'], $value["per75"]);
            $brechaMax = $this->calcBrecha($value['max_ant'], $value["max"]);

            if($value["min"]){
                $min = $value["min"];
            }else{
                $min = 0;
            }

            if($value["max"]){
                $max = $value["max"];
            }else{
                $max = 0;
            }

            if($tipoCambio > 1){
                $min = round(($min * 1000) / $tipoCambio, 2);
                $per25 = round(($value["per25"] * 1000) / $tipoCambio, 2);
                $prom = round(($value["prom"] * 1000) / $tipoCambio, 2);
                $med = round(($value["med"] * 1000) / $tipoCambio, 2);
                $per75 = round(($value["per75"] * 1000) / $tipoCambio, 2);
                $max = round(($max * 1000) / $tipoCambio, 2);
            }else{
                $per25 = $value["per25"];
                $prom = $value["prom"];
                $med = $value["med"];
                $per75 = $value["per75"];
            }

            if($contNuevo){
                $response[] = array(    "Concepto"      => $value["concepto"],
                                        "Ocupantes"     => $value["ocupantes"],
                                        "Min"           => $min, 
                                        "25 Percentil"  => $per25, 
                                        "Promedio"      => $prom, 
                                        "Mediana"       => $med, 
                                        "75 Percentil"  => $per75, 
                                        "Max"           => $max,
                                        "Empresa"       => $value["empresa"]
                                    );
            }else{
                if($value["min_ant"]){
                    $minAnt = $value["min_ant"];
                }else{
                    $minAnt = 0;
                }
    
                if($value["max_ant"]){
                    $maxAnt = $value["max_ant"];
                }else{
                    $maxAnt = 0;
                }

                if($tipoCambio > 1){
                    $minAnt = round(($minAnt * 1000)/$tipoCambio, 2);
                    $per25Ant = round(($value["per25_ant"] * 1000) / $tipoCambio, 2);
                    $promAnt = round(($value["prom_ant"] * 1000) / $tipoCambio, 2);
                    $medAnt = round(($value["med_ant"] * 1000) / $tipoCambio, 2);
                    $per75Ant = round(($value["per75_ant"] * 1000) / $tipoCambio, 2);
                    $maxAnt = round(($maxAnt * 1000) / $tipoCambio, 2);
                }else{
                    $per25Ant = $value["per25_ant"];
                    $promAnt = $value["prom_ant"];
                    $medAnt = $value["med_ant"];
                    $per75Ant = $value["per75_ant"];
                }
                $response[] = array(    "Concepto"      => $value["concepto"],
                                        "Min Ant"       => $minAnt, 
                                        "25P Ant"       => $per25Ant, 
                                        "Prom Ant"      => $promAnt, 
                                        "Med Ant"       => $medAnt, 
                                        "75P Ant"       => $per75Ant, 
                                        "Max Ant"       => $maxAnt, 
                                        "Min"           => $min, 
                                        "25 Percentil"  => $per25, 
                                        "Promedio"      => $prom, 
                                        "Mediana"       => $med, 
                                        "75 Percentil"  => $per75, 
                                        "Max"           => $max, 
                                        "Empresa"       => $value["empresa"],
                                        "Brecha Min"    => $brechaMin,
                                        "Brecha 25P"    => $brecha25P,
                                        "Brecha Prom"   => $brechaProm,
                                        "Brecha Med"    => $brechaMed,
                                        "Brecha 75P"    => $brecha75P,
                                        "Brecha Max"    => $brechaMax,
                                    );
            }
            
        }

        return $response;
    }

    public function calcBrecha($anterior, $actual){
        if($anterior == 0){
            if($actual > 0){
                $brecha = -100;
            }else{
                $brecha = 0;
            }
        }else{
            $brecha = round((($actual/$anterior) -1)*100, 2);
        }

        return $brecha;
    }

    public function median($arr) {
        if($arr->count() <= 0){
            return 0;
        }
        $sorted = $arr->sort()->values();
        $count = count($sorted); //total numbers in array
        $middleval = floor(($count-1)/2); // find the middle value, or the lowest middle value
        if($count % 2) { // odd number, middle is the median
            $median = $sorted[$middleval];
        } else { // even number, calculate avg of 2 medians
            $low = $sorted[$middleval];
            $high = $sorted[$middleval+1];
            $median = (($low+$high)/2);
        }
        return $median;
    }

    public function percentile($percentile, $arr) {
        if($arr->count() <= 1){
            return 0;
        }
        $sorted = $arr->sort()->values();
   
        $count = $sorted->count();

        // percentil.exc -- método de cálculo de la fórmula percentil.exc en excel
        //$perN = (($count+1) * ($percentile/100));
        
        // percentil.inc -- método de cálculo de la fórmula percentil.inc en excel
        $perN = (($count-1)* ($percentile/100) + 1 );
        
        if (is_int($perN)) {
           $result = $sorted[$perN - 1];
        }
        else {
           $int = floor($perN);
           $dec = $perN - $int;

           $result = $dec * ($sorted[$int] - $sorted[$int - 1]) + $sorted[$int-1];
        }
        return $result;
    }

    public function getCargosHomologados($rubro, $periodo){
        $empresasId = Empresa::where('rubro_id', $rubro)
                             ->pluck('id');

        $encuestasRubro = Cabecera_encuesta::whereIn('empresa_id', $empresasId)
                                           ->where('periodo', $periodo)
                                           ->get()
                                           ->pluck('id');
        
        $encuestasCargos = Encuestas_cargo::whereIn('cabecera_encuesta_id', $encuestasRubro)
                                          ->whereNotNull('cargo_id')
                                          ->where('incluir', 1)
                                          ->get();

        $cargosEmpresas = collect();
        foreach ($encuestasCargos as $encuestaCargo) {
            if($encuestaCargo->detalleEncuestas){
                if($encuestaCargo->detalleEncuestas->cantidad_ocupantes > 0){
                    $cargosEmpresas->push(["cargo"=> $encuestaCargo->cargo_id, "empresa"=>$encuestaCargo->cabeceraEncuestas->empresa_id]);
                }
            }
        }
        
        $groupedCargosEmpresas = $cargosEmpresas->groupBy('cargo');
        
        // lista de cargos existentes en la encuesta (excluyendo los que hay en una sola empresa)
        $cargosIds = $groupedCargosEmpresas->map(function($item, $key){
            if($item->groupBy('empresa')->count() > 1){
                return $key;
            }
        })->values()->reject(function($value, $key){
            return is_null($value); 
        })->sort();

        return $cargosIds;
    }

    public function excelClubCargos($detalleUniverso, $detalleNacional, $detalleInternacional, $rubro, $filename, $cubo = false){

        Excel::create($filename, function($excel) use($detalleUniverso, $detalleNacional, $detalleInternacional, $rubro, $cubo) {
            // hoja universo
            $excel->sheet("universo", function($sheet) use($detalleUniverso, $rubro, $cubo){       
                
                if(!$cubo){
                    $rango = $this->cabeceraExcelFactory($sheet, $rubro);
                    
                    $sheet->rows($detalleUniverso);
                    $sheet->cells($rango, function($cells){
                        $cells->setBackground('#a7ffeb');
                    });                 
                    $sheet->setFreeze('A7');  
                }else{
                    $rango = $this->cabeceraExcelFactoryCubo($sheet, $rubro);

                    $sheet->rows($detalleUniverso);
                    $sheet->cells($rango, function($cells){
                        $cells->setBackground('#a7ffeb');
                    });                 
                    $sheet->setFreeze('A6');  
                }
                
                       
                
            });
            // hoja nacional
            $excel->sheet("nacional", function($sheet) use($detalleNacional, $rubro, $cubo){
                if(!$cubo){
                    $rango = $this->cabeceraExcelFactory($sheet, $rubro);

                    $sheet->rows($detalleNacional);
                    $sheet->cells($rango, function($cells){
                        $cells->setBackground('#a7ffeb');
                    });
                    $sheet->setFreeze('A7');
                }else{
                    $rango = $this->cabeceraExcelFactoryCubo($sheet, $rubro);

                    $sheet->rows($detalleNacional);
                    $sheet->cells($rango, function($cells){
                        $cells->setBackground('#a7ffeb');
                    });                 
                    $sheet->setFreeze('A6');
                }
                
            });
            // hoja internacional
            $excel->sheet("internacional", function($sheet) use($detalleInternacional, $rubro, $cubo){
               
                if(!$cubo){
                    $rango = $this->cabeceraExcelFactory($sheet, $rubro);
                
                    $sheet->rows($detalleInternacional);
                    $sheet->cells($rango, function($cells){
                        $cells->setBackground('#a7ffeb');
                    });
                    $sheet->setFreeze('A7');
                }else{
                    $rango = $this->cabeceraExcelFactoryCubo($sheet, $rubro);

                    $sheet->rows($detalleInternacional);
                    $sheet->cells($rango, function($cells){
                        $cells->setBackground('#a7ffeb');
                    });                 
                    $sheet->setFreeze('A6');
                }
                
            });

            $excel->setActiveSheetIndex(0);    

        })->export('xlsx');
    }

    public function excelNivelClub($detalleUniverso, $detalleNacional, $detalleInternacional, $rubro, $filename){

        Excel::create($filename, function($excel) use($detalleUniverso, $detalleNacional, $detalleInternacional, $rubro) {
            //Hoja Universo
            $excel->sheet("Universo", function($sheet) use($detalleUniverso, $rubro){
                
                $this->cabeceraNivelExcelFactory($sheet, $rubro);
                $sheet->rows($detalleUniverso);
                $sheet->setFreeze('A7');

            });

            //Hoja Nacional
            $excel->sheet("Nacional", function($sheet) use($detalleNacional, $rubro){
    
                $this->cabeceraNivelExcelFactory($sheet, $rubro);
                $sheet->rows($detalleNacional);
                $sheet->setFreeze('A7');

            });

            //Hoja Internacional
            $excel->sheet("Internacional", function($sheet) use($detalleInternacional, $rubro){
    
                $this->cabeceraNivelExcelFactory($sheet, $rubro);
                $sheet->rows($detalleInternacional);
                $sheet->setFreeze('A7');

            });
            
            $excel->setActiveSheetIndex(0);    
        })->export('xlsx');
    
    }
    private function cabeceraExcelFactory($sheet, $rubro){

        $objDrawing = new PHPExcel_Worksheet_Drawing;
        $objDrawing->setPath(public_path('images/logo.jpg')); //your image path
        $objDrawing->setCoordinates('A1');
        $objDrawing->setWidthAndHeight(304,60);
        $objDrawing->setWorksheet($sheet);     

        $sheet->cell('A5', function($cell){
            $cell->setValue('CARGO');
        });
        $sheet->mergeCells('A5:D5');
        $sheet->cells('A5:D5', function($cells){
            $cells->setBackground('#00897b');
            $cells->setFontColor("#FFFFFF");
            $cells->setFontWeight("bold");
           // $cells->setValignment('center');
            $cells->setAlignment('center');
        });
        // Salario Base Header
        $sheet->cell('E5', function($cell){
            $cell->setValue('SALARIO BASE');
        });
        $sheet->mergeCells('E5:J5');
        $sheet->cells('E5:J5', function($cells){
            $cells->setBackground('#0288d1');
            $cells->setFontColor("#FFFFFF");
            $cells->setFontWeight("bold");
           // $cells->setValignment('center');
            $cells->setAlignment('center');
        });
        if($rubro == 1){
                   
            // Salario Efectivo Anual Garantizado
            $sheet->cell('K5', function($cell){
                $cell->setValue('EFECTIVO ANUAL GARANTIZADO');
            });
            $sheet->mergeCells('K5:P5');
            $sheet->cells('K5:P5', function($cells){
                $cells->setBackground('#388e3c');
                $cells->setFontColor("#FFFFFF");
                $cells->setFontWeight("bold");
            // $cells->setValignment('center');
                $cells->setAlignment('center');
            });                
                            
            // Total Adicional Anual
            $sheet->cell('Q5', function($cell){
                $cell->setValue('TOTAL ADICIONAL ANUAL');
            });
            $sheet->mergeCells('Q5:V5');
            $sheet->cells('Q5:V5', function($cells){
                $cells->setBackground('#fbc02d');
                $cells->setFontColor("#FFFFFF");
                $cells->setFontWeight("bold");
            // $cells->setValignment('center');
                $cells->setAlignment('center');
            });                
            // Bono Anual
            $sheet->cell('W5', function($cell){
                $cell->setValue('BONO ANUAL');
            });
            $sheet->mergeCells('W5:AB5');
            $sheet->cells('W5:AB5', function($cells){
                $cells->setBackground('#ffa000');
                $cells->setFontColor("#FFFFFF");
                $cells->setFontWeight("bold");
            // $cells->setValignment('center');
                $cells->setAlignment('center');
            });    
            
            // Comision
            
            $sheet->cell('AC5', function($cell){
                $cell->setValue('COMISION');
            });
            $sheet->mergeCells('AC5:AH5');
            $sheet->cells('AC5:AH5', function($cells){
                $cells->setBackground('#6a1b9a');
                $cells->setFontColor("#FFFFFF");
                $cells->setFontWeight("bold");
            // $cells->setValignment('center');
                $cells->setAlignment('center');
            });
            
             
            // Compensación Total Anual
            $sheet->cell('AI5', function($cell){
                $cell->setValue('COMPENSACION TOTAL ANUAL');
            });
            $sheet->mergeCells('AI5:AN5');
            $sheet->cells('AI5:AN5', function($cells){
                $cells->setBackground('#0288d1');
                $cells->setFontColor("#FFFFFF");
                $cells->setFontWeight("bold");
            // $cells->setValignment('center');
                $cells->setAlignment('center');
            });                
            // Salario Variable Anual comp.
            $sheet->cell('AO5', function($cell){
                $cell->setValue('SALARIO BASE COMPARATIVO ORGANIZACION VS MERCADO');
            });
            $sheet->mergeCells('AO5:AT5');
            $sheet->cells('AO5:AT5', function($cells){
                $cells->setBackground('#afb42b');
                $cells->setFontColor("#FFFFFF");
                $cells->setFontWeight("bold");
            // $cells->setValignment('center');
                $cells->setAlignment('center');
            });           
            
            // Salario Variable Anual comp.
            $sheet->cell('AU5', function($cell){
                $cell->setValue('RATIO SALARIO BASE ANUAL / TOTAL EFECTIVO ANUAL GARANTIZADO');
            });
            $sheet->mergeCells('AU5:AZ5');
            $sheet->cells('AU5:AZ5', function($cells){
                $cells->setBackground('#388e3c');
                $cells->setFontColor("#FFFFFF");
                $cells->setFontWeight("bold");
            // $cells->setValignment('center');
                $cells->setAlignment('center');
            });

            $topeHeader = 8;
            $rango = 'A6:AZ6';

        }elseif($rubro == 4){
            // Salario Variable Anual
            $sheet->cell('K5', function($cell){
                $cell->setValue('VARIABLE ANUAL');
            });
            $sheet->mergeCells('K5:P5');
            $sheet->cells('K5:P5', function($cells){
                $cells->setBackground('#afb42b');
                $cells->setFontColor("#FFFFFF");
                $cells->setFontWeight("bold");
            // $cells->setValignment('center');
                $cells->setAlignment('center');
            });            
            // Total Adicional Anual
            $sheet->cell('Q5', function($cell){
                $cell->setValue('TOTAL ADICIONAL ANUAL');
            });
            $sheet->mergeCells('Q5:V5');
            $sheet->cells('Q5:V5', function($cells){
                $cells->setBackground('#388e3c');
                $cells->setFontColor("#FFFFFF");
                $cells->setFontWeight("bold");
            // $cells->setValignment('center');
                $cells->setAlignment('center');
            });                
                            
            // Bono Anual
            $sheet->cell('W5', function($cell){
                $cell->setValue('BONO ANUAL');
            });
            $sheet->mergeCells('W5:AB5');
            $sheet->cells('W5:AB5', function($cells){
                $cells->setBackground('#fbc02d');
                $cells->setFontColor("#FFFFFF");
                $cells->setFontWeight("bold");
            // $cells->setValignment('center');
                $cells->setAlignment('center');
            });                
            // Comisión
            $sheet->cell('AC5', function($cell){
                $cell->setValue('COMISION');
            });
            $sheet->mergeCells('AC5:AH5');
            $sheet->cells('AC5:AH5', function($cells){
                $cells->setBackground('#ffa000');
                $cells->setFontColor("#FFFFFF");
                $cells->setFontWeight("bold");
            // $cells->setValignment('center');
                $cells->setAlignment('center');
            });    
            
            // Efectivo Total Anual
            
            $sheet->cell('AI5', function($cell){
                $cell->setValue('EFECTIVO TOTAL ANUAL');
            });
            $sheet->mergeCells('AI5:AN5');
            $sheet->cells('AI5:AN5', function($cells){
                $cells->setBackground('#6a1b9a');
                $cells->setFontColor("#FFFFFF");
                $cells->setFontWeight("bold");
            // $cells->setValignment('center');
                $cells->setAlignment('center');
            });
            
            // Compensación Total Anual
            $sheet->cell('AO5', function($cell){
                $cell->setValue('COMPENSACION ANUAL TOTAL');
            });
            $sheet->mergeCells('AO5:AT5');
            $sheet->cells('AO5:AT5', function($cells){
                $cells->setBackground('#f57c00');
                $cells->setFontColor("#FFFFFF");
                $cells->setFontWeight("bold");
            // $cells->setValignment('center');
                $cells->setAlignment('center');
            });                
            // Salario Base Comparativo Header
            $sheet->cell('AU5', function($cell){
                $cell->setValue('SALARIO BASE COMPARATIVO ORGANIZACION VS MERCADO');
            });
            $sheet->mergeCells('AU5:AZ5');
            $sheet->cells('AU5:AZ5', function($cells){
                $cells->setBackground('#0288d1');
                $cells->setFontColor("#FFFFFF");
                $cells->setFontWeight("bold");
            // $cells->setValignment('center');
                $cells->setAlignment('center');
            });                
            // Salario Variable Anual comp.
            $sheet->cell('BA5', function($cell){
                $cell->setValue('VARIABLE ANUAL COMPARATIVO ORGANIZACION VS MERCADO');
            });
            $sheet->mergeCells('BA5:BF5');
            $sheet->cells('BA5:BF5', function($cells){
                $cells->setBackground('#afb42b');
                $cells->setFontColor("#FFFFFF");
                $cells->setFontWeight("bold");
            // $cells->setValignment('center');
                $cells->setAlignment('center');
            });           
            
            // Salario Variable Anual comp.
            $sheet->cell('BG5', function($cell){
                $cell->setValue('RATIO SALARIO BASE ANUAL / TOTAL EFECTIVO ANUAL');
            });
            $sheet->mergeCells('BG5:BL5');
            $sheet->cells('BG5:BL5', function($cells){
                $cells->setBackground('#fbc02d');
                $cells->setFontColor("#FFFFFF");
                $cells->setFontWeight("bold");
            // $cells->setValignment('center');
                $cells->setAlignment('center');
            });


            $topeHeader = 10;
            $rango = 'A6:BG6';

        }else{
            // Salario Efectivo Anual Garantizado
            $sheet->cell('K5', function($cell){
                $cell->setValue('EFECTIVO ANUAL GARANTIZADO');
            });
            $sheet->mergeCells('K5:P5');
            $sheet->cells('K5:P5', function($cells){
                $cells->setBackground('#388e3c');
                $cells->setFontColor("#FFFFFF");
                $cells->setFontWeight("bold");
            // $cells->setValignment('center');
                $cells->setAlignment('center');
            });                
                            
            // Total Adicional Anual
            $sheet->cell('Q5', function($cell){
                $cell->setValue('TOTAL ADICIONAL ANUAL');
            });
            $sheet->mergeCells('Q5:V5');
            $sheet->cells('Q5:V5', function($cells){
                $cells->setBackground('#fbc02d');
                $cells->setFontColor("#FFFFFF");
                $cells->setFontWeight("bold");
            // $cells->setValignment('center');
                $cells->setAlignment('center');
            });                
            // Bono Anual
            $sheet->cell('W5', function($cell){
                $cell->setValue('BONO ANUAL');
            });
            $sheet->mergeCells('W5:AB5');
            $sheet->cells('W5:AB5', function($cells){
                $cells->setBackground('#ffa000');
                $cells->setFontColor("#FFFFFF");
                $cells->setFontWeight("bold");
            // $cells->setValignment('center');
                $cells->setAlignment('center');
            });    

            // Comision

            $sheet->cell('AC5', function($cell){
                $cell->setValue('COMISION');
            });
            $sheet->mergeCells('AC5:AH5');
            $sheet->cells('AC5:AH5', function($cells){
                $cells->setBackground('#6a1b9a');
                $cells->setFontColor("#FFFFFF");
                $cells->setFontWeight("bold");
            // $cells->setValignment('center');
                $cells->setAlignment('center');
            });

            // Efectivo Total Anual
            $sheet->cell('AI5', function($cell){
                $cell->setValue('EFECTIVO TOTAL ANUAL');
            });
            $sheet->mergeCells('AI5:AN5');
            $sheet->cells('AI5:AN5', function($cells){
                $cells->setBackground('#f57c00');
                $cells->setFontColor("#FFFFFF");
                $cells->setFontWeight("bold");
            // $cells->setValignment('center');
                $cells->setAlignment('center');
            });                
            // Compensación total anual
            $sheet->cell('AO5', function($cell){
                $cell->setValue('COMPENSACION TOTAL ANUAL');
            });
            $sheet->mergeCells('AO5:AT5');
            $sheet->cells('AO5:AT5', function($cells){
                $cells->setBackground('#0288d1');
                $cells->setFontColor("#FFFFFF");
                $cells->setFontWeight("bold");
            // $cells->setValignment('center');
                $cells->setAlignment('center');
            });                
            // Salario Variable Anual comp.
            $sheet->cell('AU5', function($cell){
                $cell->setValue('SALARIO BASE COMPARATIVO ORGANIZACION VS MERCADO');
            });
            $sheet->mergeCells('AU5:AZ5');
            $sheet->cells('AU5:AZ5', function($cells){
                $cells->setBackground('#afb42b');
                $cells->setFontColor("#FFFFFF");
                $cells->setFontWeight("bold");
            // $cells->setValignment('center');
                $cells->setAlignment('center');
            });           

            // Salario Variable Anual comp.
            $sheet->cell('BA5', function($cell){
                $cell->setValue('RATIO SALARIO BASE ANUAL / TOTAL EFECTIVO ANUAL');
            });
            $sheet->mergeCells('BA5:BF5');
            $sheet->cells('BA5:BF5', function($cells){
                $cells->setBackground('#fbc02d');
                $cells->setFontColor("#FFFFFF");
                $cells->setFontWeight("bold");
            // $cells->setValignment('center');
                $cells->setAlignment('center');
            });

            // Total Efectivo Anual Empresa vs Mercado comp.
            $sheet->cell('BG5', function($cell){
                $cell->setValue('RATIO TOTAL EFECTIVO ANUAL EMPRESA / TOTAL EFECTIVO ANUAL MERCADO');
            });
            $sheet->mergeCells('BG5:BL5');
            $sheet->cells('BG5:BL5', function($cells){
                $cells->setBackground('#f57c00');
                $cells->setFontColor("#FFFFFF");
                $cells->setFontWeight("bold");
            // $cells->setValignment('center');
                $cells->setAlignment('center');
            });

            $topeHeader = 10;
            $rango = 'A6:BL6';
        }
               
        $itemsHeader = array("Mínimo", "25 Perc.", "Promedio", "Mediana", "75 Perc.", "Máximo");
        $cargoHeader = array("Cargo Company", "Oficial", "Ocupantes", "Casos");

        for ($i= 0; $i < $topeHeader; $i++) {
            foreach ($itemsHeader as $key => $value) {
                array_push($cargoHeader, $value);
            }
            
        }
           
        $sheet->row(6, $cargoHeader);

        return $rango;

    }

    private function cabeceraExcelFactoryCubo($sheet, $rubro){

        $objDrawing = new PHPExcel_Worksheet_Drawing;
        $objDrawing->setPath(public_path('images/logo.jpg')); //your image path
        $objDrawing->setCoordinates('A1');
        $objDrawing->setWidthAndHeight(304,60);
        $objDrawing->setWorksheet($sheet);     

        
        
        
        $header = array("CARGO OFICIAL", "OCUPANTES", "CASOS");  
        
        
        $sbHeader = array("SB Min", "SB 25P", "SB Prom", "SB Med", "SB 75P", "SB Max");

        $header = $this->headerFactory($header, $sbHeader);

        if($rubro == 1){
                   
            $eagHeader = array("EAG Min", "EAG 25P", "EAG Prom", "EAG Med", "EAG 75P", "EAG Max");

            $header = $this->headerFactory($header, $eagHeader);            
                            
            $taHeader = array("TA Min", "TA 25P", "TA Prom", "TA Med", "TA 75P", "TA Max");

            $header = $this->headerFactory($header, $taHeader);                            
            
            $baHeader = array("BA Min", "BA 25P", "BA Prom", "BA Med", "BA 75P", "BA Max");

            $header = $this->headerFactory($header, $baHeader);                
            
            $caHeader = array("CA Min", "CA 25P", "CA Prom", "CA Med", "CA 75P", "CA Max");

            $header = $this->headerFactory($header, $caHeader);            
            
             
            $ctaHeader = array("CTA Min", "CTA 25P", "CTA Prom", "CTA Med", "CTA 75P", "CTA Max");

            $header = $this->headerFactory($header, $ctaHeader);            
            
            $sbmHeader = array("sbm Min", "sbm 25P", "sbm Prom", "sbm Med", "sbm 75P", "sbm Max");

            $header = $this->headerFactory($header, $sbmHeader);                       
            
            $sbRatioHeader = array("sbRatio Min", "sbRatio 25P", "sbRatio Prom", "sbRatio Med", "sbRatio 75P", "sbRatio Max");

            $header = $this->headerFactory($header, $sbRatioHeader);            

            $topeHeader = 8;
            $rango = 'A5:AZ5';

        }elseif($rubro == 4){
            $varHeader = array("VAR Min", "VAR 25P", "VAR Prom", "VAR Med", "VAR 75P", "VAR Max");

            $header = $this->headerFactory($header, $varHeader);            

            $taHeader = array("TA Min", "TA 25P", "TA Prom", "TA Med", "TA 75P", "TA Max");

            $header = $this->headerFactory($header, $taHeader);                            
                            
            $baHeader = array("BA Min", "BA 25P", "BA Prom", "BA Med", "BA 75P", "BA Max");

            $header = $this->headerFactory($header, $baHeader);                
            
            $caHeader = array("CA Min", "CA 25P", "CA Prom", "CA Med", "CA 75P", "CA Max");

            $header = $this->headerFactory($header, $caHeader);  
            
            $etaHeader = array("ETA Min", "ETA 25P", "ETA Prom", "ETA Med", "ETA 75P", "ETA Max");

            $header = $this->headerFactory($header, $etaHeader);  
            
            $ctaHeader = array("CTA Min", "CTA 25P", "CTA Prom", "CTA Med", "CTA 75P", "CTA Max");

            $header = $this->headerFactory($header, $ctaHeader);            
            
            $sbmHeader = array("sbm Min", "sbm 25P", "sbm Prom", "sbm Med", "sbm 75P", "sbm Max");

            $header = $this->headerFactory($header, $sbmHeader);                       
            
            $varCompHeader = array("VARCOMP Min", "VARCOMP 25P", "VARCOMP Prom", "VARCOMP Med", "VARCOMP 75P", "VARCOMP Max");

            $header = $this->headerFactory($header, $varCompHeader);    

            $header = $this->headerFactory($header, $sbmHeader);                       
            
            $sbRatioHeader = array("sbRatio Min", "sbRatio 25P", "sbRatio Prom", "sbRatio Med", "sbRatio 75P", "sbRatio Max");

            $header = $this->headerFactory($header, $sbRatioHeader);                
            

            $topeHeader = 10;
            $rango = 'A5:BL5';

        }else{
            $eagHeader = array("EAG Min", "EAG 25P", "EAG Prom", "EAG Med", "EAG 75P", "EAG Max");

            $header = $this->headerFactory($header, $eagHeader);                         
                            
            $taHeader = array("TA Min", "TA 25P", "TA Prom", "TA Med", "TA 75P", "TA Max");

            $header = $this->headerFactory($header, $taHeader);            
           
            $baHeader = array("BA Min", "BA 25P", "BA Prom", "BA Med", "BA 75P", "BA Max");

            $header = $this->headerFactory($header, $baHeader);            

            $caHeader = array("CA Min", "CA 25P", "CA Prom", "CA Med", "CA 75P", "CA Max");

            $header = $this->headerFactory($header, $caHeader);            


            $etaHeader = array("ETA Min", "ETA 25P", "ETA Prom", "ETA Med", "ETA 75P", "ETA Max");

            $header = $this->headerFactory($header, $etaHeader);            
              
            $ctaHeader = array("CTA Min", "CTA 25P", "CTA Prom", "CTA Med", "CTA 75P", "CTA Max");

            $header = $this->headerFactory($header, $ctaHeader);            
          
            $sbmHeader = array("SBM Min", "SBM 25P", "SBM Prom", "SBM Med", "SBM 75P", "SBM Max");

            $header = $this->headerFactory($header, $sbmHeader);            
         

            $sbRatioHeader = array("SBRATIO Min", "SBRATIO 25P", "SBRATIO Prom", "SBRATIO Med", "SBRATIO 75P", "SBRATIO Max");

            $header = $this->headerFactory($header, $sbRatioHeader);            


            $topeHeader = 9;
            $rango = 'A5:BF5';
        }
               
        $sheet->row(5, $header);

        return $rango;

    }

    private function headerFactory($header, $itemHeader){

        foreach ($itemHeader as $key => $value) {
            array_push($header, $value);
        }

        return $header;
    }

    private function cabeceraNivelExcelFactory($sheet, $rubro){
        $objDrawing = new PHPExcel_Worksheet_Drawing;
        $objDrawing->setPath(public_path('images/logo.jpg')); //your image path
        $objDrawing->setCoordinates('A1');
        $objDrawing->setWidthAndHeight(304,60);
        $objDrawing->setWorksheet($sheet);  

        $sheet->cell('A5', function($cell){
            $cell->setValue('NIVEL');
        });
        $sheet->mergeCells('A5:D5');
        $sheet->cells('A5:D5', function($cells){
            $cells->setBackground('#00897b');
            $cells->setFontColor("#FFFFFF");
            $cells->setFontWeight("bold");
           // $cells->setValignment('center');
            $cells->setAlignment('center');
        });
        // Salario Base Header
        $sheet->cell('E5', function($cell){
            $cell->setValue('SALARIO BASE');
        });
        $sheet->mergeCells('E5:J5');
        $sheet->cells('E5:J5', function($cells){
            $cells->setBackground('#0288d1');
            $cells->setFontColor("#FFFFFF");
            $cells->setFontWeight("bold");
           // $cells->setValignment('center');
            $cells->setAlignment('center');
        });                
                        
        // Bono Anual
        $sheet->cell('K5', function($cell){
            $cell->setValue('BONO ANUAL');
        });
        $sheet->mergeCells('K5:P5');
        $sheet->cells('K5:P5', function($cells){
            $cells->setBackground('#ffa000');
            $cells->setFontColor("#FFFFFF");
            $cells->setFontWeight("bold");
           // $cells->setValignment('center');
            $cells->setAlignment('center');
        });

        // Total efectivo anual
        $sheet->cell('Q5', function($cell){
            $cell->setValue('TOTAL EFECTIVO ANUAL');
        });
        $sheet->mergeCells('Q5:V5');
        $sheet->cells('Q5:V5', function($cells){
            $cells->setBackground('#4a148c');
            $cells->setFontColor("#FFFFFF");
            $cells->setFontWeight("bold");
        // $cells->setValignment('center');
            $cells->setAlignment('center');
        });                                
        $itemsHeader = ["SB Min", 
                        "SB 25P", 
                        "SB Prom", 
                        "SB Med", 
                        "SB 75P.", 
                        "SB Max",
                        "BA Min", 
                        "BA 25P", 
                        "BA Prom", 
                        "BA Med", 
                        "BA 75P.", 
                        "BA Max",
                        "TEA Min", 
                        "TEA 25P", 
                        "TEA Prom", 
                        "TEA Med", 
                        "TEA 75P.", 
                        "TEA Max",
                    ];
        $cargoHeader = ["Cargo Company", "Oficial", "Ocupantes", "Casos"];
        foreach ($itemsHeader as $key => $value) {
            array_push($cargoHeader, $value);
        }
            
        $sheet->row(6, $cargoHeader); 

        $sheet->cells('A6:V6', function($cells){
            $cells->setBackground('#a7ffeb');
        });

    }
}