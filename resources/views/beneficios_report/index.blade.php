@extends('layouts.report')
@include('includes.benefit_nav')
@section('breadcrumbs')
  <nav>
    <div class="nav-wrapper teal lighten-3">
      <div class="col s12">
        <a href="{{route('home')}}" class="breadcrumb">
        	<i class="material-icons left">home</i>@lang('beneficiosReportIndex.breadcrumb_home')
        </a>
        <a href="{{ route('beneficios.show', $dbEmpresa->id) }}" class="breadcrumb">
        	@lang('beneficiosReportIndex.breadcrumb_report')
        </a>
      </div>
    </div>
  </nav>
@endsection
<style type="text/css">

</style>
@section('content')
	@if(!$tour)
		@include('includes.preloader')
	@endif
	<div class="row" id="content" data-intro="" data-step="8">
		<div class="col s12">
			<h4>@lang('beneficiosReportIndex.title_list')</h4>
			<div class="hoverable bordered" data-intro="<p class='intro-title'><strong>LISTADO DE INDICADORES</strong></p>Las diferentes tarjetas despliegan el listado de Beneficios/Prácticas referentes a cada grupo." data-step="5">
				<!-- first row benefits -->
				<div class="row" style="display:flex; flex-wrap:wrap;">
					@foreach ($dbCategorias as $element)
						<div class="col s12 m3" style="display:flex";>
							<div class="card">
								<div class="card-image">
									<img src="{{asset($element->file_path.$element->file_name)}}">
								</div>				
								<div class="card-content">
									{{$element->descripcion}}
								</div>
								<div class="card-action">
									<ul class="collapsible collapsible-inCard" data-collapsible="expandable" id="collapsible_{{$element->id}}">
										<li>
											<div class="collapsible-header" style="height:5em;">
												<strong>{{$element->titulo}}</strong>
											</div>
											<div class="collapsible-body teal lighten-4">
												<ul class="items">
													@foreach ($element->item as $item)
														@if(!$item->pregunta->beneficios_pregunta_id)
															@if(!$item->rubro || $item->rubro_id == $dbRubro)
																<li style="padding-top: 0.5em;">
																	@if($tour)
																		<form class="flat-form col s12" action="{{route('beneficios.reportes')}}?multipage=true" method="POST" id="form-beneficio_{{$item->id}}" >
																			<input type="hidden" name="item_id" value="{{$item->id}}"/>
																			<input type="hidden" name="_token" value="{{{ csrf_token() }}}" />
																			<div class="flat" id="btn_{{$item->id}}">
																				<button class="flat wrap" type="submit" name="submitForm">{{$item->titulo}}
													      						</button>
													      					</div>
																		</form>
																	@else
																		<form class="flat-form col s12" action="{{route('beneficios.reportes')}}" method="POST" id="form-beneficio" >
																			<input type="hidden" name="item_id" value="{{$item->id}}"/>
																			<input type="hidden" name="_token" value="{{{ csrf_token() }}}" />
																			<div class="flat" id="btn_{{$item->id}}">
																				<button class="flat wrap" type="submit" name="submitForm">{{$item->titulo}}
													      						</button>
													      					</div>
																		</form>
																	@endif
																	<div class="clearfix"></div>
																</li>
															@endif
														@endif
													@endforeach
												</ul>
											</div>
										</li>
									</ul>
								</div>
							</div>
						</div>
					@endforeach
					<div class="col s12 composicion">
						@if($tour)
							<form action="{{route('beneficios.reportes.composicion')}}?multipage=true" method="POST" id="form-composicion">
								<input type="hidden" name="item_id" value="1"/>
								<input type="hidden" name="_token" value="{{{ csrf_token() }}}" />
								<button class="btn waves-light waves-effect teal" type="submit" data-intro="<p class='intro-title'><strong>COMPOSICION DE LA MUESTRA</strong></p>Click para ver los datos de la composición de la muestra" data-step="2" data-position="left">
									@lang('beneficiosReportIndex.button_sample')
								</button>	
							</form>

						@else
							<form action="{{route('beneficios.reportes.composicion')}}" method="POST">
								<input type="hidden" name="item_id" value="1"/>
								<input type="hidden" name="_token" value="{{{ csrf_token() }}}" />
								<button class="btn waves-light waves-effect teal" type="submit">
									@lang('beneficiosReportIndex.button_sample')
								</button>	
							</form>
						@endif
					</div>
					<div class="clearfix"></div>
				</div>	
			</div>
		</div>
	</div>
@stop
@push('scripts')
 <script type="text/javascript">
	var tourist = RegExp('multipage=true', 'gi').test(window.location.search);
	var tourist2 = RegExp('multipage=2', 'gi').test(window.location.search);
	$(document).ready(function() {
	 
		$('.collapsible').collapsible({
			accordion : true // A setting that changes the collapsible behavior to expandable instead of the default accordion style
		});
		var collapsible = M.Collapsible.getInstance($("#collapsible_1"));
     	
     	$("#collapsible_1").attr("data-step", "6").attr("data-intro", "<p class='intro-title'><strong>INDICADORES</strong></p>Click para desplegar la lista de Beneficios/Prácticas");

		$("#btn_1").attr("data-step", "7").attr("data-intro", "Click para visualizar los datos referentes al Beneficio/Práctica");

		if(!tourist){
			if(!tourist2){
				$(window).load(function(){
					$('.preloader').fadeOut('slow',function(){
						$(this).hide();
						$("#content").fadeIn('slow');
					});
				});
			}else{
				$("#content").fadeIn('slow');
				tour.goToStepNumber(5).start();								
			}
		}else{
			$("#content").fadeIn('slow');
			tour.goToStepNumber(2).start();

		}

		tour.onafterchange(function(step){
			if(!tourist2){
				if($(step).attr("data-step") == 5){
					$("#form-composicion").submit();
		      		tour.exit();
				}
			}else{
				if($(step).attr("data-step") == 6){
					collapsible.open();
				}
				if($(step).attr("data-step") == 8){
					$("#form-beneficio_1").submit();
					tour.exit();
				}

			}

		});

	});	

 </script>
 
@endpush