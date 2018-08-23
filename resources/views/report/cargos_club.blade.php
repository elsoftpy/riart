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
		<div class="col l4">
			<form id="excel_form" action="{{ route('reportes.cargosClubExcel') }}" method="POST">
				<input type="hidden" name="_token" value="{{{ csrf_token() }}}" />
				<input type="hidden" name="empresa_id" value="{{$dbEmpresa}}"/>
				<button class="btn waves-effect waves-light lighten-1 white-text" type="submit" name="submit" data-intro="<p class='intro-title'><strong>EXCEL</strong></p>Click para habilitar la descarga de su universo de cargos y del mercado.</br>Una vez descargado, abrir el archivo y guardar" data-step="20">
					<i class="material-icons left">add</i>Excel
				</button>
			</form>		
		</div>		
		<div class="col s12">
			<h4>Club {!! $club !!}</h4>
			<div class="hoverable bordered">
				<ul class="collapsible" data-collapsible="expandable">
					@foreach ($niveles as $element)
						<li>
							<div class="collapsible-header">
								<strong>{{$element->descripcion}}</strong>
							</div>
							<div class="collapsible-body teal lighten-4">
								<ul style="margin-left: 2em;">
									@foreach ($cargos->where('nivel_id', $element->id) as $item)
										<li style="padding-top: 0.5em;">
											<form class="col s12" action="{{route('reportes.cargos')}}" method="POST" target="_blank">
												<input type="hidden" name="nivel_id" value="{{$item->nivel_id}}"/>
												<input type="hidden" name="cargo_id" value="{{$item->cargo_id}}"/>
												<input type="hidden" name="_token" value="{{{ csrf_token() }}}" />		
												<input type="hidden" name="empresa_id" value="{{$dbEmpresa}}"/>																						
												<button class="btn waves-effect waves-light" type="submit" name="submit">{{$item->descripcion}}
    					      					</button>
											</form>
											<div class="clearfix"></div>
										</li>
									@endforeach
								</ul>
							</div>
						</li>
					@endforeach
				</ul>
			</div>
		</div>
	</div>
@stop
@push('scripts')
 <script type="text/javascript">
	 $(document).ready(function() {
	 
	      $('.collapsible').collapsible({
	        accordion : true // A setting that changes the collapsible behavior to expandable instead of the default accordion style
	      });

		if (RegExp('multipage=true', 'gi').test(window.location.search)) {
		    tour.goToStepNumber(20).start();
		    dropdown.open();
	    }

     	tour.onafterchange(function(step){
			window.location.href="{{URL::route('reportes.ficha', $dbEmpresa)}}?multipage=4";
			tour.exit();
      	});    		    	    
	  
	});	
 </script>
 
@endpush