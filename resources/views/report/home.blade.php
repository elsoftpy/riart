@extends('report.layout')

@section('content')
@include('report.title')	
<div class="row">
		<div class="col s12">
			
			<div class="hoverable bordered">
				<div class="card">
					<div class="card-content white-text" style="text-align: center;">
						<img src="{{asset($imagen)}}">
					</div>
				</div>

			</div>
		</div>
	</div>
@stop
@push('scripts')
	<script type="text/javascript">
		$(function(){
			
			var test = RegExp('multipage=3', 'gi').test(window.location.search);

	     	$("#intro-cargos").attr("data-step", "9").attr("data-intro", "<p class='intro-title'><strong>MENU DE CARGOS</strong></p>Click para desplegar las opciones de búsqueda de cargos");

	     	$("#intro-buscar").attr("data-step", "10").attr("data-intro", "<p class='intro-title'><strong>BUSCAR POR CARGOS</strong></p>Click para visualizar resultados por cargo a través de palabras claves.").attr("data-position", "left");
	     	
	     	$("#intro-universo").attr("data-step", "19").attr("data-intro", "<p class='intro-title'><strong>UNIVERSO DE CARGOS</strong></p>En este apartado podrá realizar la búsqueda de su cargo por nivel dentro del sector.").attr("data-position", "left");	     	
	     	
	     	
			
			if (RegExp('multipage=true', 'gi').test(window.location.search)) {
			    tour.goToStepNumber(9).start();
			    dropdown.open();
		    }

		    if(test){
			    dropdown.open();
			    tour.goToStepNumber(19).start();
		    }

	     	tour.onafterchange(function(step){
	      		if(!test){
		      		if($(step).attr("data-step") == 18){
		      			window.location.href="{{ URL::route('reportes.filter', $dbEmpresa) }}?multipage=true";
		      			tour.exit();	
		      		}
						      			
	      		}else{
		      		if($(step).attr("data-step") == 21){
		      			window.location.href="{{URL::route('reportes.cargosRubro', $dbEmpresa)}}?multipage=true";
		      			dropdown.close();
		      			tour.exit();	
		      		}
	      		}
	      	} );    		    
		});
	</script>
@endpush