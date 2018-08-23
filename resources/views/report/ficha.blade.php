@extends('report.layout')
<style type="text/css">
	.tabs .indicator{
    background-color:white !important;
    }
    ul.tabs > li.tab > a, a.active {
    	color: white !important;
    }
</style>
@section('content')
	@include('report.title')
	<div class="row">
		<div class="col s12">
			<div class="hoverable bordered">
				<div class="card blue">
					<div class="card-tabs">
				      <ul class="tabs tabs-fixed-width">
				       
				        <li class="tab teal" data-intro="<p class='intro-title'><strong>FECHA/CORTE</strong></p>Indica la fecha/corte de la información" data-step="22"><a class="active" href="#test4">Fecha/Corte</a></li>
				        <li class="tab teal"><a href="#test5" data-intro="<p class='intro-title'><strong>CARGOS EMERGENTES</strong></p>Indica la cantidad de cargos contenidos en la encuesta" data-step="23">Cargos Emergentes</a></li>
				        <li class="tab teal" data-intro="<p class='intro-title'><strong>CANTIDAD DE PARTICIPANTES</strong></p>La cantidad de empresas/bancos participantes" data-step="24"><a href="#test6">Participantes N°</a></li>
				        <li class="tab teal" data-intro="<p class='intro-title'><strong>TIPO DE CAMBIO</strong></p>Indica el tipo de cambio en U$S" data-step="25"><a href="#test7">Tipo de Cambio</a></li>
				      </ul>
					</div>
					<div class="card-content teal lighten-4 center-align">
					    <div id="test4"><h1> {{$periodo}} </h1></div>
					    <div id="test5"><h1> {{$cargos}} </h1></div>
					    <div id="test6"><h1> {{$participantes}} </h1></div>
					    <div id="test7"><h1> Gs. 5.600 </h1></div>
					</div>					
				</div>

			</div>
		</div>
	</div>
@stop
@push('scripts')
	<script type="text/javascript">
		 $('.tabs').tabs();
		if (RegExp('multipage=4', 'gi').test(window.location.search)) {
		    tour.goToStepNumber(21).start();
	    }

     	tour.onafterchange(function(step){
			if($(step).attr("data-step") == 26){
				window.location.href="{{URL::route('reportes.conceptos', $dbEmpresa)}}?multipage=true";
			}
      	});    		    	    



	</script>
@endpush