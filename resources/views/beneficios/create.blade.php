@extends('layout')
@section('content')
	<div class="row">
		<div class="browser-window">
			<div class="top-bar">
	          <h4>Clonar Encuesta de Beneficios</h4>
	        </div>
	        <div class="content">
				<form class="col s12" id="realForm" action="{{route('beneficios.store')}}" method="POST">
					<div class="row">
						<div class="input-field col s12">
							<input id="descripcion_empresa" type="text" class="validate" name="descripcion_empresa" value="{{$dbData->empresa->descripcion}}">
							<label for="nombres">Descripción</label>
						</div>					
					</div>
					<div class="row">
						<input type="hidden" name="empresa_id" id="empresa_id" value="{{$dbData->empresa_id}}" />
						<input type="hidden" name="encuesta_id" id="encuesta_id" value="{{$dbData->id}}" />
					</div>
					<div class="row">
						<div class="input-field col s6">
							<input type="text" class="validate" id="periodo" name="periodo"/>
							<label for="periodo">Periodo</label>
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
@stop
@push('scripts')
	<script>
		

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