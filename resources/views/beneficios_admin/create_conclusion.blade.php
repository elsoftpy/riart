@extends('layout')
@section('content')
	<div class="row">
		<div class="browser-window">
			<div class="top-bar">
	          <h4>Escribir Conclusión</h4>
	        </div>
	        <div class="content">
				<form class="col s12" id="realForm" action="{{route('beneficios.admin.conclusion.store')}}" method="POST">
					<div class="row">
						<div class="col s8">
							<label>Rubro</label>
						</div>
						<div class="col s4">
							<label for="periodo">Periodo</label>
						</div>
					</div>
					<div class="row">
						<div class="col s8">
							<select id="rubro"  name="rubro" class="select2">
								@foreach($rubros as $id => $descripcion)
									<option value = {{$id}}>{{$descripcion}}</option>
								@endforeach
							</select>
						</div>					
						<div class="col s4">
							<select id="periodo"  name="periodo" class="select2">
								@foreach($periodos as $periodo)
									<option value = {{$periodo}}>{{$periodo}}</option>
								@endforeach
							</select>
						</div>
					</div>
					<div class="row">
						<div class="col s12">
							<label>Pregunta</label>
						</div>
					</div>
					<div class="row">
						<div class="col s12">
							<select id="pregunta"  name="pregunta" class="select2">
								@foreach($preguntas as $id => $descripcion)
									<option value = {{$id}}>{{$descripcion}}</option>
								@endforeach
							</select>
						</div>					
					</div>
					<div class="row">
						<div class="input-field cols 12">
							<label for="conclusion">Conclusión</label>
							<textarea name="conclusion" id="conclusion" class="materialize-textarea"></textarea>
						</div>
					</div>
					<div class="row">
						<div class="input-field cols 12">
							<label for="conclusion_en">Conclusión (Inglés)</label>
							<textarea name="conclusion_en" id="conclusion_en" class="materialize-textarea"></textarea>
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
  		$(document).ready(function() {
   			$('select').select2();
   			getConclusion();
		});

		$("#rubro").change(function(){
			getConclusion();
		});

		$("#periodo").change(function(){
			getConclusion();
		});

		$("#pregunta").change(function(){
			getConclusion();
		});		
		function getConclusion(){
  			var pregunta = $("#pregunta").val();
  			var rubro = $("#rubro").val();
  			var periodo = $("#periodo").val();
  			$.post(	'{{route('beneficios.admin.conclusion.get')}}', 
  					{	'pregunta': pregunta, 
  						'rubro': rubro, 
  						'periodo': periodo,
  						'_token':'{{ csrf_token() }}'
  					},
  					function(data){
						console.log(data);
  						$("#conclusion").text(data.conclusion);
						$('label[for="conclusion"]').addClass('active');
						$("#conclusion_en").text(data.conclusion_en);  
						$('label[for="conclusion_en"]').addClass('active');
  					}
  			);

		}

	</script>
@endpush