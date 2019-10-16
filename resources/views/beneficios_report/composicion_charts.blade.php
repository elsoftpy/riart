@extends('layouts.report')
@include('includes.benefit_nav')
@section('breadcrumbs')
  <nav>
    <div class="nav-wrapper teal lighten-3">
      <div class="col s12">
        <a href="{{route('home')}}" class="breadcrumb">
        	<i class="material-icons left">home</i>Inicio
        </a>
        <a href="{{ route('beneficios.show', $dbEmpresa->id) }}" class="breadcrumb">
        	Reporte
        </a>
        <a class="breadcrumb">
        	Composición de la Muestra
        </a>
      </div>
    </div>
  </nav>
@endsection
@section('content')	
	@if (App::isLocale('en'))
	<h5><strong>Date:</strong>{{$encuesta->periodo}}</h5>
	@else
		<h5><strong>Fecha de Corte:</strong>{{$encuesta->periodo}}</h5>	
	@endif
	
	<form style="display: none" action="POST">
		<input type="hidden" name="pregunta" id="pregunta" value="{{$item->pregunta->id}}">
	</form>
	<div class="row">
		@php
			if(App::isLocale('en')){
				$english = true;
			}else{
				$english = false;
			}
		@endphp
		<div class="hoverable bordered" data-intro="" data-step="5">
			<div class=" col s11 offset-s1">
				@if (!$english)
					<h4 id="titulo">{{$item->titulo_en}}</h4>	
				@else
					<h4 id="titulo">{{$item->titulo}}</h4>		
				@endif
				
			</div>
			<canvas id="myChart" height="85%"></canvas>	
		</div>
		<div class="container">
			<ul class="collapsible" data-collapsible="expandable" id="indicadores-ul">
				<li data-intro="<p class='intro-title'><strong>INDICADORES</strong></p>Click aquí para desplegar los diferentes indicadores de composición de la muestra" data-step="3" data-position="top">
					<div class="collapsible-header center">
						@if ($english)
							<h5><strong>Universe composition</strong></h5>
						@else
							<h5><strong>Composición de la Muestra</strong></h5>	
						@endif
						
					</div>
					<div class="collapsible-body teal lighten-4">
						<ul class="items">
							@foreach ($otrosItems as $element)
								<div class="col s6 teal lighten-4">
									<li style="padding-top: 0.5em;">
										<div class="flat" id="btn_{{$element->id}}">
											@if ($english)
												<button class="flat" value="{{$element->pregunta->id}}" id="{{$element->id}}}">
													{{$element->titulo_en}}
												</button>
											@else
												<button class="flat" value="{{$element->pregunta->id}}" id="{{$element->id}}}">
													{{$element->titulo}}
												</button>	
											@endif
											
					      				</div>
										<div class="clearfix"></div>
									</li>
								</div>
							@endforeach
						</ul>
					</div>
				</li>
			</ul>
		</div>
	</div>
@endsection
@push('scripts')
	<script src="{{asset('plugins/chartjs/Chart.bundle.js')}}"></script>
	<script type="text/javascript">
		$('.collapsible').collapsible();
		var chartUrl = "{{route('beneficios.data')}}";
		var token = '{{{ csrf_token() }}}';
		var collapsible = M.Collapsible.getInstance($("#indicadores-ul"));

     	$(document).ready(function(){
	     	$("#btn_5").attr("data-step", "4").attr("data-intro", "<p class='intro-title'><strong>INDICADOR</strong></p>Click para cambiar la visualización del gráfico según el indicador seleccionado");

	    });

		var tourist = RegExp('multipage=true', 'gi').test(window.location.search);
		var tourist2 = false;

	</script>
	<script src="{{asset('js/drawCharts.js')}}"></script>
	<script type="text/javascript">


		tour.onafterchange(function(step){
			if($(step).attr("data-step") == 5){
				window.location.href ="{{ route('beneficios.show', $dbEmpresa->id)}}?multipage=2";
	      		tour.exit();
			}
		});
		
	</script>
@endpush