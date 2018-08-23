@extends('report.layout')

@section('content')
	@include('report.title')
	<div class="row">
		<div class="col s12" data-intro="" data-step="29">
			<div class="hoverable bordered">
				<div class="card">
					<div class="card-content">
						<h5>RECOPILACION DE LA INFORMACION</h5>
						<p>La información necesaria para la realización del estudio fue recopilada directamente por la consultora, en reuniones específicas realizados con cada responsable representativo de  Recursos Humanos y Compensaciones, de cada empresa  participante. Dicha recopilación básicamente se desarrolló en dos momentos:</p> 

						<p><strong>Momento 1.</strong> Análisis y homologación de cada cargo con la estructura organizacional de cada Empresa del Panel Participante,  teniendo como base el contenido de las funciones y responsabilidades asignadas a cada cargo, el nivel de reporte y el desafio del cargo (contribucion del cargo a la cadena de valor y valores de los que se hace responsable), el grado de libertad del cargo, grado de solucion y complejidad de problemas que el cargo debe resolver, grado  de relacionamiento y comunicacion interno/externo, y grado de dificultad en el ambiente de desempeno del cargo.</p> 

						@if(Auth::user()->empresa->rubro->id == 4)
							<p><strong>Momento 1.1.</strong> Analisis de cantidad de incorporaciones modalidad contrato fijo, contrato por viaje y contrato por  tiempo determinado, para todos los cargos de tripulacion "Oficiales" y "Sub Alternos".</p> 
						@endif

						<p><strong>Momento 2.</strong> Obtención de información salarial en forma abarcativa y en detalle incluyendo todos los  beneficios-adicionales monetarios que impactan en el salario efectivo anual de los cargos, así como los atributos en todos los niveles.</p>

						<p><strong>Momento 2.1.</strong> La informacion fue obtenida al 100% de todos los ocupantes por cargo, de manera a contar con el universo salarial por cargo, para identificar la realidad que sostiene salarialmente al cargo, por empresa.</p> 

						<h5>PRESENTACION DE LOS RESULTADOS</h5>
						<p>Los datos se presentan en los siguientes estadígrafos: mínimo, 25 percentil, promedio, mediana, 75 percentil y máximo.</p> 
						<p>Los datos se procesaron para generar los resultados en términos de salario básico mensual, salario efectivo mensual, salario efectivo anual, salario efectivo total anual.</p> 
						 
						<p>Cada concepto salarial se presenta en forma independiente, es decir, no precisamente coincide que al indicador mediana en el salario básico mensual le corresponda el mínimo del bono y viceversa. La razón por la cual se presenta cada concepto en forma independiente se debe a presentar la información con mayor grado de precisión por concepto y además aplica para mantener la confidencialidad de la información.</p> 

						<p>Los datos recopilados en cada entrevista de la encuesta, fueron procesados por medio de un analisis metodologico y estadistico de investigacion cuantitativa y cualitativa, que convierte los mismos en informacion precisa, concreta y contundente para la toma de decisiones en materia de compensaciones y beneficios.</p> 

					</div>
				</div>

			</div>
		</div>
	</div>
@stop
@push('scripts')
	<script type="text/javascript">
		if (RegExp('multipage=true', 'gi').test(window.location.search)) {
		    tour.goToStepNumber(28).start();
	    }

     	tour.onafterchange(function(step){
			if($(step).attr("data-step") == 29){
				window.location.href="{{URL::route('home')}}?multipage=4";
				tour.exit();
			}
      	});    		    	    



	</script>
@endpush