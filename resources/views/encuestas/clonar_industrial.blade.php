@extends('layout')
@section('content')
	<div class="row">
		<div class="browser-window">
			<div class="top-bar">
	          <h4>Clonar Periodo Industrial</h4>
	        </div>
	        <div class="content">
				<form class="col s12" id="realForm" action="{{route('clonar.industrialAction')}}" method="POST">
					<div class="row">
						<div class="input-field col s6">
							<input type="text" class="validate" id="periodo_original" name="periodo_original"/>
							<label for="periodo_original">Periodo Original</label>
						</div>
						<div class="input-field col s6">
							<input type="text" class="validate" id="periodo_nuevo" name="periodo_nuevo"/>
							<label for="periodo_nuevo">Periodo Nuevo</label>
						</div>
					</div>
					<input type="hidden" name="_token" value="{{{ csrf_token() }}}" />
					<div class="button-group">
						<button class="btn waves-effect waves-light" type="submit" name="submit">Guardar
	    					<i class="material-icons left">save</i>
	      				</button>
					</div>
				</form>
	        </div>
		</div>
	</div>
	@if($toast)
		<div id="toast"></div>
	@endif
@stop
@push('scripts')
	<script>
		if($("#toast").length > 0){
			M.toast({html: 'Archivo Procesado'});	
		}

		var options = [];
		$('#realForm').submit(function(e){
			if($('#fields').val() === ''){
				e.preventDefault();
			}
		});

		$('#realForm').keypress(function(event){
    		if (event.keyCode === 10 || event.keyCode === 13){ 
        		event.preventDefault();
        	}
  		});

  		$(document).ready(function() {
   			$('select').select2();

		});

	</script>
@endpush