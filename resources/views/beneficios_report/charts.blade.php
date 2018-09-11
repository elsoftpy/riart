@extends('layouts.report')
@include('includes.benefit_nav')
@section('breadcrumbs')
  <nav>
    <div class="nav-wrapper teal lighten-3" data-intro=""  data-step="10">
      <div class="col s12">
        <a href="{{route('home')}}" class="breadcrumb">
        	<i class="material-icons left">home</i>@lang('beneficiosReportCharts.breadcrumb_home')
        </a>
        <a href="{{ route('beneficios.show', $dbEmpresa->id) }}" class="breadcrumb">
        	@lang('beneficiosReportCharts.breadcrumb_report')
        </a>
        <a class="breadcrumb">
        	@lang('beneficiosReportCharts.breadcrumb_results')
        </a>
      </div>
    </div>
  </nav>
@endsection

@section('content')
	<form style="display: none" action="POST">
		<input type="hidden" name="pregunta" id="pregunta" value="{{$item->pregunta->id}}">
	</form>
	
	<div class="row">
		<div class="hoverable bordered"  >
			<div class=" col s11 offset-s1">
				<h4 id="titulo">{{$item->titulo}}</h4>	
			</div>
			<div id="chart-canvas">
				<canvas id="myChart" height= "80%"></canvas>		
			</div>
			
			<div id="div-conclusion">
				
			</div>
		</div>
		@if($practicas->count() > 0)
			<div class="container">
				<ul class="collapsible popout" data-collapsible="accordion" id="indicadores-ul">
					<li data-intro="<p class='intro-title'><strong>INDICADORES</strong></p>Click aquí para desplegar las diferentes prácticas" data-step="8" data-position="top">
						<div class="collapsible-header center">
							<h5><strong>@lang('beneficiosReportCharts.title_practices')</strong></h5>
						</div>
						<div class="collapsible-body teal lighten-4">
							<ul class="items">
								@foreach ($practicas as $element)
									<li style="padding-top: 0.5em;">
										<div class="flat" id="btn_{{$element->id}}">
											<button class="flat" value="{{$element->id}}" id="{{$element->id}}}">
												{{$element->item->titulo}}
					      					</button>
					      				</div>
										<div class="clearfix"></div>
									</li>
								@endforeach
							</ul>
						</div>
					</li>
				</ul>
			</div>
		@endif
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
	     	$("#btn_17").attr("data-step", "9").attr("data-intro", "<p class='intro-title'><strong>INDICADOR</strong></p>Click para cambiar la visualización del gráfico según el indicador seleccionado");

	    });
     	var tourist = false;
		var tourist2 = RegExp('multipage=true', 'gi').test(window.location.search);	
		tour.onafterchange(function(step){
			if(tourist2){
				if($(step).attr("data-step") == 10){
					window.location.href ="{{ route('home')}}";
					tour.exit();
				}
			}
		});			
	</script>
	<script src="{{asset('js/drawCharts.js')}}"></script>
@endpush