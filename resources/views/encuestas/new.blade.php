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
						<div class="input-field col s6">
							<select id="empresa_id"  name="empresa_id">
								<option>Elija una opción</option>
								@foreach($dbEmpresas as $id => $descripcion)
									<option value = {{$id}}>{{$descripcion}}</option>
								@endforeach
							</select>
							<label for="empresa_id">Empresa</label>
						</div>					
					</div>
					<div class="row">
						<div class="input-field col s6">
							<input type="text" class="validate" id="periodo" name="periodo"/>
							<label for="fec_ini">Periodo</label>
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
		
		var item = [];
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
   			$('select').material_select();
   			$('.datepicker').pickadate({
    			selectMonths: true, // Creates a dropdown to control month
    			selectYears: 15, // Creates a dropdown of 15 years to control year
 				monthsFull: [ 'enero', 'febrero', 'marzo', 'abril', 'mayo', 'junio', 'julio', 'agosto', 'septiembre', 'octubre', 'noviembre', 'diciembre' ],
    			monthsShort: [ 'ene', 'feb', 'mar', 'abr', 'may', 'jun', 'jul', 'ago', 'sep', 'oct', 'nov', 'dic' ],
    			weekdaysFull: [ 'domingo', 'lunes', 'martes', 'miércoles', 'jueves', 'viernes', 'sábado' ],
    			weekdaysShort: [ 'dom', 'lun', 'mar', 'mié', 'jue', 'vie', 'sáb' ],
    			weekdaysLetter: ['D', 'L', 'M', 'M', 'J', 'V', 'S'],
    			today: 'hoy',
    			clear: 'borrar',
    			close: 'cerrar',
    			firstDay: 1,
    			format: 'dddd d !de mmmm !de yyyy',
    			formatSubmit: 'yyyy-mm-dd'    			
  			});

		});

		// number format
		$('input.number').keyup(function(event) {

			// skip for arrow keys
		  	if(event.which >= 37 && event.which <= 40) return;

		  	// format number
		  	$(this).val(function(index, value) {
		    	return value
		    	.replace(/\D/g, "")
		    	.replace(/\B(?=(\d{3})+(?!\d))/g, "3");
		  	});
		});
	</script>
	<script type="text/javascript">

		$('#opcion-div').on('click', '#modal_option', function(e){
			console.log("fired");
			$('#modal-options').openModal();
		});
		
		$('#add').click(function(e){
			e.preventDefault();
			console.log($("#pregunta").val());
			if(!$("#pregunta").val() == ''){
				var fields = {};
				if($("#control").val() == 'R'){
					option = {};
					option.etiqueta = $("#escalas option:selected").text();
					option.control = $("#control").val();
					option.cabeceraId = $("#escalas").val();
					options.push(option);
					 $('#options').val(JSON.stringify(options));
				}
				if($('#options').val() == ''){
					fields.opciones = [];	
				}else{
					fields.opciones = $('#options').val();
				}
				

			    var formatter = new Intl.NumberFormat('es');
			    var pregunta =$( "#pregunta").val();
			    var abierta = 'N';
			    var abiertaDesc = 'No';
			    if($('#abierta').is(":checked")){
			    	abierta = 'S';
			    	abiertaDesc = 'Sí';
			    }
			    var rowCount = $('#detlist .ingData').length;
			    rowCount++;

			    var outHTML =
			        '<tr class="ingData" id="ingRow' + rowCount + '">'
			            + '<td style="width:40%"><div id="pregunta' + rowCount + '">' + pregunta + '</div></td>'
			            + '<td style="width:30%"><div id="abierta' + rowCount + '">' + abiertaDesc + '</div></td>'
			            + '<td style="width:30%;">'
			                + '<button class="btn waves-effect waves-light amber " onClick="editItem('+rowCount+')" type="button">Editar</button>'
			            + '</td>'
			            + '<td style="width:30%;">'
			                + '<button class="btn waves-effect waves-light red" onClick="removeItem('+rowCount+')" type="button">Borrar</button>'
			            + '</td>'

			        + '</tr>';

			    fields.numero = rowCount;
			    fields.pregunta = pregunta;
			    fields.abierta = abierta;
			    fields.row = rowCount;
			    item.push(fields);

			    $('#fields').val(JSON.stringify(item));
			    $('#detlist tr:last').after(outHTML);

			    $('#ingRow'+rowCount).children('td,div').hide().slideDown(300);
			    $('#ingRow'+rowCount).children('td, div').animate({'backgroundColor' : '#00A65A'}, 300);
			    $('#ingRow'+rowCount).children('td, div').animate({'backgroundColor' : '#FFFFFF'}, 300);

			    // clear detail form
			    $('#pregunta').val('');
			    $('#abierta').attr('checked', false);
			    $('#options').val('');
			    options.length = 0;
			    removeAllOptions();

			}
		});
		

		function removeItem(row){
			var actualRow = arrayObjectIndexOf(item, row, "row"); 
			$('#ingRow'+row).children('td, div').animate({'backgroundColor':'#fb6c6c'},300);
			item.splice(actualRow, 1);
			$('#fields').val(JSON.stringify(item));
			$('#ingRow'+row).children('td, div').slideUp(300, function(){
				this.remove();
			});
			

			
		}

		function editItem(row){
			var actualRow = arrayObjectIndexOf(item, row, "row"); 
			$('#edit_question').val(item[actualRow].pregunta);
			$('#item_row_edit_question').val(actualRow);
			$('#row_edit_question').val(row);
			$('#modal-edit-question').openModal();
		}

		function arrayObjectIndexOf(myArray, searchTerm, property) {
		    for(var i = 0, len = myArray.length; i < len; i++) {
		        if (myArray[i][property] === searchTerm){
		        	console.log(i);
		        	return i;	
		        } 
		    }
		    return -1;
		}

		$('#save_edit_question').click(function(e){
			e.preventDefault();
			var actualRow = $('#item_row_edit_question').val();
			var row = $('#row_edit_question').val();
			var pregunta = $('#edit_question').val();
			item[actualRow].pregunta = pregunta;
			$('#fields').val(JSON.stringify(item));
			$("#pregunta"+row).text(pregunta);
			$('#modal-edit-question').closeModal();
		});

		$('#add_option').click(function(e){
			e.preventDefault();
			label = $('#option-label').val();
			console.log(label);
			if(label !== ''){
				var option = {};
			    var rowCount = $('#optlist .optData').length;
			    rowCount++;

			    var outHTML =
			        '<tr class="optData" id="optRow' + rowCount + '">'
			            + '<td style="width:70%;"><div id="etiqueta' + rowCount + '">' + label + '</div></td>'
			            + '<td style="width:30%;">'
			                + '<button class="btn waves-effect waves-light red" onClick="removeOption('+rowCount+')" type="button">Borrar</button>'
			            + '</td>'
			        + '</tr>';

			    
			    option.etiqueta = label;
			    option.control = $('#control').val();
			    option.row = rowCount;
			    options.push(option);

			    $('#options').val(JSON.stringify(options));
			    $('#optlist tr:last').after(outHTML);

			    $('#optRow'+rowCount).children('td,div').hide().slideDown(300);
			    $('#optRow'+rowCount).children('td, div').animate({'backgroundColor' : '#00A65A'}, 300);
			    $('#optRow'+rowCount).children('td, div').animate({'backgroundColor' : '#FFFFFF'}, 300);

			    // clear detail form
			    $('#option-label').val('');
			}
			$('#modal-options').closeModal();
		});

		function removeOption(row){
			var actualRow = arrayObjectIndexOf(options, row, "row"); 
			$('#optRow'+row).children('td, div').animate({'backgroundColor':'#fb6c6c'},300);
			console.log(actualRow);
			options.splice(actualRow, 1);
			$('#options').val(JSON.stringify(options));
			$('#optRow'+row).children('td, div').slideUp(300, function(){
				this.remove();
			});
			
			function arrayObjectIndexOf(myArray, searchTerm, property) {
			    for(var i = 0, len = myArray.length; i < len; i++) {
			        if (myArray[i][property] === searchTerm){
			        	console.log(i);
			        	return i;	
			        } 
			    }
			    return -1;
			}

			
		}		

		function removeAllOptions(){
			var rowCount = $("#optlist .optData").length;
			for( var i = 0; i < rowCount + 1 ; i++){
				$("#optRow"+i).children('td, div').slideUp(300, function (){
					this.remove();
				});
			}
		}

		$('#preview').click(function(){
			 field = $('#fields').val();
			 $.post('{!! route('encuestas.preview') !!}', { "_token": "{{ csrf_token() }}", "fields": field}, 
			 	function(json) {
		            var win = window.open('about:blank');
		            with(win.document){
		            	open();
		            	write(json);
		            	close();
		            }

	           	});
		});

		$("#control").on("change", function(){
			if($("#control").val()== "C"){
				outHTML = '<p>'+
								'<a class="btn waves-effect waves-light amber right" id="modal_option" href="#modal-options">Opción<i class="material-icons left">add</i></a>'+
							'</p>';
			$("#opcion-div").html(outHTML);														
			}else{
				outHTML = '<p><select id="escalas">'
				$.get('{!! route('encuestas.escalas') !!}', function(json){
					var data = $.map(json, function(text, id){
						return {text:text, id:id};
					});
					console.log(data[0].text);
					for(i = 0; i < data.length; i++){
						outHTML += '<option value="'+data[i].id+'">'+data[i].text+'</option>';
					}
					outHTML += '</select></p>'
					$("#opcion-div").html(outHTML);		
					$('select').material_select();
					console.log(outHTML);												
				});
			}
			

		});

	</script>	
@endpush