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
	<div class="row">
		<div class="col s12">
			<h4>Club de {{$club}}</h4>
			<div class="hoverable bordered">
				<div class="card blue">
					<div class="card-tabs">
				      <ul class="tabs tabs-fixed-width">
				       
				        <li class="tab teal"><a class="active" href="#test4">Vigencia de la Información/Corte</a></li>
				        <li class="tab teal"><a href="#test5">Cantidad de Cargos</a></li>
				        <li class="tab teal"><a href="#test6">Cantidad Participantes</a></li>
				        <li class="tab teal"><a href="#test7">Tipo de Cambio</a></li>
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