@extends('layout')
@include('includes.benefit_nav')
@section('breadcrumbs')
  <nav>
    <div class="nav-wrapper teal lighten-3">
      <div class="col s12">
        <a href="{{route('home')}}" class="breadcrumb"><i class="material-icons left">home</i>@lang('beneficiosReportIndex.breadcrumb_home')</a>
      </div>
    </div>
  </nav>
@endsection
@section('content')
	<div class="row">
		<div class="browser-window" data-intro="" data-step="2">
			<div class="top-bar">
	          <h4>{{ $club}}</h4>
	        </div>
	        <div class="content">
				<div class="hoverable bordered">
	        		<div class="row center">
						@if (App::isLocale('en'))
							<img src="{{asset('/images/benefit-cover_en.png')}}">	
						@else
							<img src="{{asset('/images/benefit-cover.png')}}">		
						@endif
	        		</div>
	        		
	        	</div>
	        </div>
		</div>
	</div>
@stop
@push('scripts')
	<script type="text/javascript">
		function update_row(row){
			event.preventDefault(); 
			if (confirm('Seguro que desea cerrar la Encuesta?')){
				document.getElementById('update-form'+row).submit();
			} else {
				return false;
			}
		}

      	$("#tour").click(function(e){
        	e.preventDefault();
        	tour.start();
      	});

      	tour.onafterchange(function(step){
      		if($(step).attr("data-step") == 2){
				window.location.href ="{{ route('beneficios.show', $dbEmpresa->id)}}?multipage=true";
	      		tour.exit();      			
      		}
      	})

	</script>
@endpush