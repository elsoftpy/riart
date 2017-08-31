@extends('layout')
@section('content')
	<div class="row">
		<div class="browser-window">
			<div class="top-bar">
	          <h5>Asignar Funcionarios - {{$dbData->descripcion}}</h5>
	        </div>
	        <div class="content">
				<form class="col s12" id="filterForm" action="{{route('aplicantes.store')}}" method="POST">
					<div class="row">
						<div class="input-field col s12">
							<select id="gerencia_id" name="gerencia_id">
								<option value="0" selected>Elija una opción</option>
								@foreach($dbGerencia as $id=>$descripcion)
									<option value="{{$id}}">{{ $descripcion}}</option>
								@endforeach
							</select>
							<label for="gerencia_id">Gerencia</label>
						</div>					
					</div>
					<div class="row">
						<div class="input-field col s6">
							<select id="departamento_id" name="departamento_id">
								<option value="0" selected>Elija una opción</option>
							</select>
							<label for="departamento_id">Area</label>
						</div>
						<div class="input-field col s6 ">
							<select id="sector_id" name="sector_id">
								<option value="0" selected>Elija una opción</option>
							</select>
							<label for="sector_id">Sub Area</label>
						</div>	
					</div>
					<div class="row">
						<div class="form-control">
							<p>
								<a class="btn waves-effect waves-light amber right" id="filter" href="#">
									Filtrar<i class="material-icons left">filter_list</i>
								</a>
							</p>
						</div>											
					</div>		
					<div class="row">
						<div class="browser-window">
							<div class="top-bar">
			                  	<div class="row">
				                	<p>
				                  	<h6>Funcionarios</h6>
				                  	</p>
				              	</div>
			                </div>
			                <div class="content">
			                	<table id="funcList" class="highlight">
			                		<thead>
				                      <tr>
				                      	 <th style="width:10%;">Id</th>
				                      	 <th style="width:65%;">Nombres</th>
				                      	 <th style="width:65%;">Cargo</th>
				                      	 <th style="width:30%;"><input  type="checkbox" id="all" name="all"/>
								<label for="all">Todos</label></th>
				                      </tr>
				                    </thead>
				                    <tbody>
				                    </tbody>
				                </table>
			                </div>
						</div>
					</div>

					<input id="funcionarios" type="hidden" name="funcionarios">
					<input type="hidden" name="_token" value="{{{ csrf_token() }}}" />
					<div class="button-group">
						<button class="btn waves-effect waves-light right" type="submit" name="submit">Guardar
	    					<i class="material-icons left">save</i>
	      				</button>
					</div>
				</form>
	        </div>
		</div>
	</div>
	<div class="modal" id="modal-check">
		<div class="modal-content">
			<h4>Atención</h4>
			<p>No seleccionaste ningún aplicante</p>
		</div>
		<div class="modal-footer">
			<a class="waves-light waves-effect btn" id="closeMsg">Cerrar</a>
		</div>
	</div>
	<div class="modal" id="modal-notfound">
		<div class="modal-content amber">
			<h4>Lo sentimos</h4>
			<p>No encontramos ningún resultado</p>
		</div>
		<div class="modal-footer">
			<a class="waves-light waves-effect btn" id="closeNotFound">Cerrar</a>
		</div>
	</div>	
@stop
@push('scripts')
	<script>
		
		var item = [];
		var options = [];
		var funcionarios = [];
		// prevent unwanted form submit
		$('#filterForm').submit(function(e){
			
			var rowCount = $("#funcList .funcData").length + 1;
			var checked = 0;
	    	for (var i = 1; i < rowCount; i++) {
	    		if($("#check" + i).is(":checked")){
	    			$("#check" + i).prop("checked", this.checked);
	    			addItem(i);
	    			checked++;
	    		}
	    	}
	    	if(checked === 0){
	    		e.preventDefault();
	    		$("#modal-check").openModal();
	    	}			

		});

		// prevent unwanted form submit (by keyboard)
		$('#filterForm').keypress(function(event){
    		if (event.keyCode === 10 || event.keyCode === 13){ 
        		event.preventDefault();
        	}
  		});

  		// initialize select components
  		$(document).ready(function() {
   			$('select').material_select();
		});
	</script>
	<script type="text/javascript">

		//select gerencia selected action
		$('#gerencia_id').on('change', function(e){
	        var gerencia_id = e.target.value;
	        var $selectDepartamento = $('#departamento_id').empty().html(' ');
	        var $selectSector = $('#sector_id').empty().html(' ');
    		$selectDepartamento.append(	$("<option></option>")
    			.attr("value", "0")
    			.text("Elija una opción")
    		);

    		$selectSector.append(	$("<option></option>")
    			.attr("value", "0")
    			.text("Elija una opción")
    		);

	        if(gerencia_id !== '0'){
		        $.post('{!! route('aplicantes.departamentos') !!}', { 
		        		"_token": "{{ csrf_token() }}", 
		        		"gerencia_id": gerencia_id 
		        	}, function(json) {
		            		var data = $.map(json, function(text, id){
			            					return { text: text, id: id}
			            				});
			            	console.log(data);
			            	/*$selectDepartamento.append($("<option></option>")
			            		.attr("value", "0")
			            		.text("Elija una opción")
			            	);*/

			            	for(i = 0; i < data.length; i++){
			            		$selectDepartamento.append(
			            			$("<option></option>")
			            			.attr("value", data[i].text.id)
			            			.text(data[i].text.descripcion)
			            		);
			            	}
			            	$selectDepartamento.trigger('contentChanged');
			            	$selectSector.trigger('contentChanged');
		            	}	
		        );
	        	
	        }else{

	        	$selectDepartamento.trigger('contentChanged');
	        	$selectSector.trigger('contentChanged');
	        }
        	
	    });

		// select departamento selected action
		$('#departamento_id').on('change', function(e){
	        var gerencia_id = $("#gerencia_id").val();
	        var departamento_id = e.target.value;
	        var $selectSector = $('#sector_id').empty().html(' ');
        	$selectSector.append($("<option></option>")
        		.attr("value", "0")
        		.text("Elija una opción")
        	);
	        if(gerencia_id !== '0'){
		        $.post('{!! route('aplicantes.sectores') !!}', { 
		        		"_token": "{{ csrf_token() }}", 
		        		"gerencia_id": gerencia_id, 
		        		"departamento_id": departamento_id
		        	}, function(json) {
		            		var data = $.map(json, function(text, id){
			            					return { text: text, id: id}
			            				});
			            	console.log(data);

			            	for(i = 0; i < data.length; i++){
			            		$selectSector.append(
			            			$("<option></option>")
			            			.attr("value", data[i].text.id)
			            			.text(data[i].text.descripcion)
			            		);
			            	}
			            	$selectSector.trigger('contentChanged');
		            	}	
		        );
	        	
	        }else{
        		$selectSector.trigger('contentChanged');
	        }
        	
	    });

	    $('select').on('contentChanged', function(){
	    	$(this).material_select();
	    });

		// filter action
		$('#filter').click(function(e){
			e.preventDefault();
			var rowCount = $('#funcList .funcData').length;
			console.log("columnas", rowCount);
			if(rowCount > 0){
				removeAllItems();
			}
			var gerencia_id = $("#gerencia_id").val();
			var departamento_id = $("#departamento_id").val();
			var sector_id = $("#sector_id").val();
			$.post('{!! route('aplicantes.funcionarios') !!}', { 
        		"_token": "{{ csrf_token() }}", 
        		"gerencia_id": gerencia_id, 
        		"departamento_id": departamento_id, 
        		"sector_id": sector_id
	        }, 
	        	function(data) {
	            	for (var i = 0; i < data.length; i++) {
	            		var id = data[i].id;
	            		var nombre = data[i].nombre;
	            		var cargo = data[i].cargo;
				    	if(nombre === "not found"){
				    		$("#modal-notfound").openModal();
				    	}else{
					    	rowCount++;	            		
				    		var outHTML =
						        '<tr class="funcData" id="funcRow' + rowCount + '">'
						            + '<td style="width:10%"><div id="idFunc' + rowCount + '">' + id + '</div></td>'
						            + '<td style="width:65%"><div id="nombre' + rowCount + '">' + nombre + '</div></td>'
						            + '<td style="width:65%"><div id="cargo' + rowCount + '">' + cargo + '</div></td>'
						            + '<td style="width:30%;">'
						                + '<input  type="checkbox" id="check'+ rowCount + '" class="aplica"/><label for="check' + rowCount +  '">Aplica</label>'
						            + '</td>'
						        + '</tr>';
						    $('#funcList tr:last').after(outHTML);
						    $('#funcRow'+rowCount).children('td,div').hide().slideDown(300);
				    		$('#funcRow'+rowCount).children('td, div').animate({'backgroundColor' : '#00A65A'}, 300);
				    		$('#funcRow'+rowCount).children('td, div').animate({'backgroundColor' : '#FFFFFF'}, 300);
				    	}
	            	}

            	}	
	        );

		});

		// check all items
		
		$("#all").click(function(e){
			$(".aplica").prop("checked", this.checked);
		});

		function addItem(row){
			var funcionario = {};
			var id = $("#idFunc"+row).text();
			var nombre = $("#nombre"+row).text();
			var cargo = $("#cargo"+row).text();
			funcionario.id = id;
			funcionario.nombre = nombre;
			funcionario.cargo = cargo;
			funcionario.index = row;
			funcionario.idEncuesta = {{$idEncuesta}};
			funcionarios.push(funcionario);
			$('#funcionarios').val(JSON.stringify(funcionarios));
		}
		
		/*
		function removeItem(row){
			
			var actualRow = arrayObjectIndexOf(funcionarios, row, "index"); 
			$("#check" + row).prop("checked", this.checked);
			
			funcionarios.splice(actualRow, 1);
			$('#funcionarios').val(JSON.stringify(funcionarios));
			
			function arrayObjectIndexOf(myArray, searchTerm, property) {
			    for(var i = 0, len = myArray.length; i < len; i++) {
			        if (myArray[i][property] === searchTerm){
			        	console.log(i);
			        	return i;	
			        } 
			    }
			    return -1;
			}

			
		}*/

		$('#closeMsg').click(function(e){
			e.preventDefault();
			$('#modal-check').closeModal();
		});

		$('#closeNotFound').click(function(e){
			e.preventDefault();
			$('#modal-notfound').closeModal();
		});

		function removeAllItems(){
			var rowCount = $("#funcList .funcData").length;
			for( var i = 0; i < rowCount + 1 ; i++){
				$("#funcRow"+i).children('td, div').slideUp(300, function (){
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


	</script>	
@endpush