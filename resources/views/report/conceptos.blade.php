@extends('report.layout')

@section('content')
    @include('report.title')
    <div class="row">
        <div class="col s12">
            <div class="hoverable bordered">
                <div class="card">
                    <div class="card-content">
                        <h5><span>BASICO MENSUAL</span></h5>

                        <p><span>Es el salario base bruto estipulado que el colaborador empleado percibe mensualmente, sin
                                incluir otros ingresos adicionales.</span></p>

                        <h5><span>BASICO ANUAL</span></h5>

                        <p><span>Básico Mensual Bruto correspondiente a 12 meses calendario. </span></p>

                        <h5><span>EFECTIVO ANUAL GARANTIZADO</span></h5>

                        <p><span>Es el equivalente al Salario Base Anual + Bonificación Fija garantizada (ejemplo
                                gratificación fija, bono de fin de año o bien aguinaldo)</span></p>
						<br>
                        <p><span style="font-weight: bold;">Salario Base Anual: </span><span>corresponde al salario básico anual en efectivo que percibe el colaborador por mantener
                                una posición específica. </span></p>
						<br>								
                        <p><span style="font-weight: bold;">Remuneración anual complementaria o aguinaldo: </span><span>equivalente a la doceava parte de las remuneraciones devengadas durante el año calendario
                                a favor del colaborador. </span></p>

                        <h5><span>VARIABLE</span></h5>

                        <p><span>El salario variable es la parte de la remuneración de un colaborador que varía en función
                                de su desempeño. Se trata de un incentivo que se paga en reconocimiento a la contribución
                                del empleado.</span></p>

                        <h5><span>VARIABLE ANUAL</span></h5>

                        <p><span>Es el Salario Variable percibido durante un año calendario. </span></p>

                        <h5><span>ADICIONAL</span></h5>

                        <p><span>Es la remuneración extra que se paga a un colaborador para complementar su salario base
                                (complemento salarial). </span></p>

                        <h5><span>ADICIONAL ANUAL</span></h5>

                        <p><span>Es el Adicional percibido durante un año calendario. </span></p>

                        <h5><span>ADICIONAL TOTAL</span></h5>

                        <p><span>Variable Anual + Adicional Anual </span></p>

                        <h5><span>BONO</span></h5>

                        <p><span>Es un pago adicional al salario regular de un colaborador. </span></p>

                        <h5><span>EFECTIVO TOTAL ANUAL</span></h5>

                        <p><span>Salario Base Anual + Aguinaldo + Total Adicional Anual + Bono Anual</span></p>

                        <h5><span>BENEFICIOS VALORIZADOS - ANUAL</span></h5>

                        <p><span>Corresponde a la sumatoria anualizada de todos los conceptos que involucran beneficios
                                asignados para el cargo.</span></p>

                        <h5><span>COMPENSACION TOTAL ANUAL</span></h5>

                        <p><span>Es el equivalente al Salario Base Anual + Aguinaldo Efectivo + Total Adicional Anual + Bono
                                + Beneficios Valorizados</span></p>

                        <h5><span>CONCEPTOS ESTADISTICOS</span></h5>

                        <p><span>MINIMO</span></p>

                        <p><span>Es el menor valor de un determinado conjunto de observaciones. En nuestro caso, es la
                                remuneración menor de las observadas para cada cargo.</span></p>
						<br>
                        <p><span>25 PERCENTIL</span></p>
						
                        <p><span>Es el valor que separa a una serie de observaciones de forma tal que el 75% de éstas es
                                mayor y el 25% es menor a dicho valor. En términos de la encuesta, significa que el 75% de
                                las remuneraciones otorgadas al cargo encuestado son superiores a este valor.</span></p>
						<br>
                        <p><span>PROMEDIO ARITMETICO</span></p>
						
                        <p><span>Es un estadígrafo de tendencia central que se obtiene sumando los valores del concepto
                                correspondientes a todas las observaciones de cada cargo y dividiendo el resultado de esta
                                suma entre el número de términos que componen.</span></p>
						<br>
                        <p><span>MEDIANA</span></p>

                        <p><span>Es el valor que separa al conjunto de observaciones en forma tal que el 50% de éstas sea
                                mayor y el 50% es menor a dicho valor.</span></p>
						<br>	
                        <p><span>75 PERCENTIL</span></p>

                        <p><span>Es el valor que separa al conjunto de observaciones en forma tal que el 25% de éstas es
                                mayor y el 75% es menor a dicho valor. En términos de la encuesta, significa que el 75% de
                                las remuneraciones otorgadas al cargo encuestado es inferior a dicho valor.</span></p>
						<br>
                        <p><span>MAXIMO</span></p>

                        <p><span>Es el mayor valor del conjunto de observaciones. En nuestro caso, es la remuneración mayor
                                de las observadas para cada cargo.</span></p>

                    </div>
                </div>

            </div>
        </div>
    </div>
@stop
@push('scripts')
    <script type="text/javascript">
        if (RegExp('multipage=true', 'gi').test(window.location.search)) {
            tour.goToStepNumber(26).start();
        }

        tour.onafterchange(function(step) {
            if ($(step).attr("data-step") == 27) {
                window.location.href = "{{ URL::route('reportes.panel', $dbEmpresa) }}?multipage=true";
            }
        });
    </script>
@endpush
