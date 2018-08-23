@extends('layout')
@section('content')
	<div class="row">
		<div class="browser-window">
			<div class="top-bar">
	          <h4>Crear Nueva Pregunta</h4>
	        </div>
	        <div class="content">
				<form class="col s12" id="realForm" action="{{route('beneficios_preguntas.store')}}" method="POST">
					<div class="row">
						<div class="input-field col s12">
							<textarea id="pregunta" type="text" class="materialize-textarea" name="pregunta"></textarea> 
							<label for="pregunta">Pregunta</label>
						</div>					
					</div>					
					<div class="row">
						<div class="input-field col s4">
							<p>
								<label for="cerrada">
									<input  type="checkbox" id="cerrada" name="cerrada" checked="checked" >
									<span>Cerrada</span>
								</label>
							</p>
						</div>					
						<div class="input-field col s4">
							<div class="row" style="margin-bottom: 2em;">
								<label for="control">Opciones de Respuesta</label>
							</div>
							<select id="multiple" name="multiple">
								<option value="0">Selección única</option>
								<option value="1">Las que apliquen</option>
							</select>
						</div>											
						<div class="input-field col s4" id="opcion-div">
							<div class="row" style="margin-bottom: 2em;">
								<label for="control">Beneficio</label>
							</div>
							<select id="beneficio" name="beneficio">
								<option value="0">No es Beneficio</option>
								<option value="1">Es Beneficio</option>
							</select>
						</div>											
					</div>		
					<div class="row" >
						<div class="input-field col s12">
							<div class="row" style="margin-bottom: 2em;">
								<label for="beneficios_pregunta_id">Beneficio/Práctica Padre</label>
							</div>
							<select id="beneficios_pregunta_id" name="beneficios_pregunta_id">
									<option value="">Elija una Opción</option>
								@foreach ($dbData as $element)
									<option value="{{$element->id}}">{{$element->orden."-".$element->pregunta}}</option>	
								@endforeach
							</select>
						</div>											
					</div>
					<div class="row">
						<div class="input-field col s8">
							<textarea id="opcion" type="text" class="materialize-textarea" name="opcion"></textarea> 
							<label for="opcion">Opción</label>
						</div>					
						<div class="input-field col s4">
							<input type="text" name="orden" id="orden"/>
							<label for="orden">Nro de Orden</label>
						</div>
					</div>

					<input id="options" type="hidden" name="options">
					<input type="hidden" name="_token" value="{{{ csrf_token() }}}" />
					<div class="button-group">
					<a class="btn waves-effect waves-light amber" href="#" id="add">Opción
    					<i class="material-icons left">add</i>
      				</a>
					<button class="btn waves-effect waves-light" type="submit" name="submit">Guardar
    					<i class="material-icons left">save</i>
      				</button>
						
					</div>
					<div class="row">
						<div class="browser-window">
							<div class="top-bar">
			                  	<div class="row">
				                	<p>
				                  	<h6>Opciones de Respuesta</h6>
				                  	</p>
				              	</div>
			                </div>
			                <div class="content">
			                	<table id="detlist" class="highlight">
			                		<thead>
				                      <tr>
				                      	 <th style="width:70%;">Respuesta</th>
										 <th style="width:30%">Opción</th>
				                      </tr>
				                    </thead>
				                    <tbody>
				                    	<tr></tr>
				                    </tbody>
				                </table>
			                </div>
						</div>
					</div>

				</form>
	        </div>
		</div>
	</div>
	<div class="modal" id="modal-edit-question">
		<div class="modal-content">
			<label for="edit_question">Opción</label>
			<textarea name="edit_question" id="edit_question" type="text" class="materialize-textarea"></textarea> 
			<input type="hidden" id="row_edit_question"/>
			<input type="hidden" id="item_row_edit_question"/>
		</div>
		<div class="modal-footer">
			<a class="waves-light waves-effect btn left" id="save_edit_question">Aceptar</a>
			<a class="waves-light waves-effect btn red right modal-close" id="close_edit_question">Cerrar</a>
		</div>
	</div>	
@stop
@push('scripts')
	<script>
		
		var opciones = [];
		$('#realForm').submit(function(e){
			if( $("#pregunta").val() === '' ){
				e.preventDefault();				
			}else{
				if( $('#cerrada').is(":checked") && $("#options").val() === ''){
					e.preventDefault();	
				} 

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

		
		$('#add').click(function(e){
			e.preventDefault();
			if(!$("#opcion").val() == ''){
				var	item = {};
				$('#options').val(JSON.stringify(opciones));
			    var rowCount = $('#detlist .ingData').length;
			    rowCount++;
				item.opcion = $("#opcion").val();
				item.row = 
				opciones.push(item);
				$('#options').val(JSON.stringify(opciones));
			    var outHTML =
			        '<tr class="ingData" id="ingRow' + rowCount + '">'
			            + '<td style="width:40%"><div id="opcion' + rowCount + '">' + item.opcion + '</div></td>'
			            + '<td>'
			                + '<button class="btn waves-effect waves-light amber " onClick="editItem('+rowCount+')" type="button" style = "margin-right: 0.5em;">Editar</button>'
			                + '<button class="btn waves-effect waves-light red" onClick="removeItem('+rowCount+')" type="button">Borrar</button>'
			            + '</td>'

			        + '</tr>';
			    $('#detlist tr:last').after(outHTML);

			    $('#ingRow'+rowCount).children('td,div').hide().slideDown(300);
			    $('#ingRow'+rowCount).children('td, div').animate({'backgroundColor' : '#00A65A'}, 300);
			    $('#ingRow'+rowCount).children('td, div').animate({'backgroundColor' : '#FFFFFF'}, 300);
			    // clear detail form
			    $('#opcion').val('');
			    item.length = 0;

			}
		});
		

		function removeItem(row, numero){
			var actualRow = arrayObjectIndexOf(opciones, row, "row"); 
			$('#ingRow'+row).children('td, div').animate({'backgroundColor':'#fb6c6c'},300);
			opciones.splice(actualRow, 1);
			$('#fields').val(JSON.stringify(opciones));
			$('#ingRow'+row).children('td, div').slideUp(300, function(){
				this.remove();
			});
			

			
		}

		function editItem(row){
			var actualRow = arrayObjectIndexOf(opciones, row, "row"); 
			var numero = $("#numero"+row).text();
			$('#edit_question').val(opciones[actualRow].opcion);
			$('#item_row_edit_question').val(actualRow);
			$('#row_edit_question').val(row);
			$('#modal-edit-question').modal('open');
		}

		function arrayObjectIndexOf(myArray, searchTerm, property) {
		    for(var i = 0, len = myArray.length; i < len; i++) {
		        if (myArray[i][property] === searchTerm){
		        	return i;	
		        } 
		    }
		    return -1;
		}

		$('#save_edit_question').click(function(e){
			e.preventDefault();
			var actualRow = $('#item_row_edit_question').val();
			var row = $('#row_edit_question').val();
			var numero = $('#numero_edit_question').val();
			var pregunta = $('#edit_question').val();
			opciones[actualRow].opcion = pregunta;
			$("#opcion"+row).text(pregunta);
			$('#modal-edit-question').modal('close');
		});




	</script>	
@endpush