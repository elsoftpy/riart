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
			<div>
				<ul>
					@foreach ($niveles as $element)
						<li>
							<div>
								<strong>{{$element->descripcion}}</strong>
							</div>
							<div>
								<ul style="margin-left: 2em;">
									@foreach ($cargos->where('nivel_id', $element->id) as $item)
										<li style="padding-top: 0.5em;">
											{{$item->descripcion}}
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
	  
	    });	
 </script>
 
@endpush