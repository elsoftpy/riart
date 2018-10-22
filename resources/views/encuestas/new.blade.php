@extends('layout')
@section('content')
	<div class="row">
		<div class="browser-window">
			<div class="top-bar">
	          <h4>Crear Nueva Encuesta</h4>
	        </div>
	        <div class="content">
				<form class="col s12" id="realForm" action="{{route('encuestas.storeNew')}}" method="POST">
					<div class="row">
						<label>Empresa</label>
					</div>
					<div class="row">
						<div class="col s6">
							<select id="empresa_id"  name="empresa_id">
								<option>Elija una opción</option>
								@foreach($dbEmpresas as $id => $descripcion)
									<option value = {{$id}}>{{$descripcion}}</option>
								@endforeach
							</select>
							
						</div>					
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
	<div class="modal" id="modal-options">
		<div class="modal-content">
			<h4>Etiqueta</h4>
			<input type="text" name="option-label" id="option-label" class="validate" autofocus/>
		</div>
		<div class="modal-footer">
			<a class="waves-light waves-effect btn" id="add_option">Cerrar</a>
		</div>
	</div>
	<div class="modal" id="modal-edit-question">
		<div class="modal-content">
			<h4>Enunciado</h4>
			<input type="text" name="edit_question" id="edit_question" class="validate" autofocus/>
			<input type="hidden" id="row_edit_question"/>
			<input type="hidden" id="item_row_edit_question"/>
		</div>
		<div class="modal-footer">
			<a class="waves-light waves-effect btn left" id="save_edit_question">Aceptar</a>
			<a class="waves-light waves-effect btn red rigth modal-close" id="close_edit_question">Cerrar</a>
		</div>
	</div>	
@stop
@push('scripts')
	<script>
		
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