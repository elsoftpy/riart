@extends('report.layout')

@section('content')
	@include('report.title')
	<div class="row">
		<div class="col s12">
			<div class="hoverable bordered">
				<div class="card">
					<div class="card-content">
						<h5>BASICO MENSUAL</h5>								
						<p>Es el salario base bruto estipulado que el colaborador empleado percibe mensualmente, sin incluir otros ingresos adicionales.</p>								
														
						<h5>BASICO ANUAL</h5> 								
						<p>Basico Mensual bruto correspondiente a 12 meses.</p>
														
						<h5>EFECTIVO  ANUAL GARANTIZADO</h5>
						<p>Es el equivalente al Salario Base Anual +  Bonificacion  fija garantizada  (aguinaldo, en el caso de Paraguay)</p>  								
						<p>Definición: Corresponde salario basico anual  en efectivo que percibe el colaborador  por mantener una posición específica y no depende del resultado del desempeño ya sea de la organización o individual +  la doceava parte del salario base anual (aguinaldo).</p> 																
						<h5>VARIABLE/ADICIONAL ANUAL </h5>								
						<p>El concepto VARIABLE corresponde a un concepto remunerativo cuyo determinante es el por desempeño medido a traves de indicadores pre establecidos, con frecuencia por viaje, incluyendo oficiales y subalternos. </p>
						<p>El concepto ADICIONAL corresponde a un pago fijo  por decision exclusiva de la compañia, para cubrir aspectos que considera necesarios  efectivizar por viaje. El concepto  no esta sujeto exactamente al  desempeño o metas del ocupante del cargo. </p> 								
														
						<h5>BONO ANUAL</h5>								
						<p>Corresponde a la bonificacion otorgada como consecuencia del desempeño y  los resultados de la compañía. Con frecuencia anual.</p> 								
						<h5>EFECTIVO TOTAL ANUAL</h5>
						<p>Es el equivalente al Salario Base Anual +  Aguinaldo efectivo  +  Total Adicional Anual + Comision Anual + Bono</p> 								
						<h5>BENEFICIOS VALORIZADOS - ANUAL</h5>
						<p>Corresponde a la sumatoria anualizada de todos los conceptos que involucran beneficios asignados para el cargo.</p>
						<h5>COMPENSACION TOTAL ANUAL</h5> 								
						<p>Es el equivalente al Salario Base Anual +  Aguinaldo efectivo  +  Total Adicional Anual + Comision Anual + Bono + Beneficios Valorizados</p>								
						<h5>CONCEPTOS ESTADISTICOS</h5> 								
														
						<h5>MINIMO</h5>
						<p>Es el menor valor de un determinado conjunto de observaciones. En nuestro caso, es la remuneración menor de las observadas para cada cargo.</p>
														
						<h5>25 PERCENTIL</h5>
						<p>Es el valor que separa a una serie de observaciones de forma tal que el 75% de éstas es mayor y el 25% es menor a dicho valor. En términos de la encuesta, significa que el 75% de las remuneraciones otorgadas al cargo encuestado son superiores a este valor.	</p>		
														
						<h5>PROMEDIO ARITMETICO </h5>
						<p>Es un estadígrafo de tendencia central que se obtiene sumando los valores del concepto correspondientes a todas las observaciones de cada cargo y dividiendo el resultado de esta suma entre el número de términos que componen.</p>								
						
						<h5>MEDIANA</h5>
						<p>Es el valor que separa al conjunto de observaciones en forma tal que el 50% de éstas sea mayor y el 50% es menor a dicho valor.</p>
						<h5>75 PERCENTIL</h5>								
						<p>Es el valor que separa al conjunto de observaciones en forma tal que el 25% de éstas es mayor y el 75% es menor a dicho valor. En términos de la encuesta, significa que el 75% de las remuneraciones otorgadas al cargo encuestado es inferior a dicho valor.</p>		
														
						<h5>MAXIMO</h5>								
						<p>Es el mayor valor del conjunto de observaciones. En nuestro caso, es la remuneración mayor de las observadas para cada cargo.</p>								
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

     	tour.onafterchange(function(step){
			if($(step).attr("data-step") == 27){
				window.location.href="{{URL::route('reportes.panel', $dbEmpresa)}}?multipage=true";
			}
      	});    		    	    



	</script>
@endpush