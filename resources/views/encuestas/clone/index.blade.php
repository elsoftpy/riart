@extends('layout')
@section('content')
	<div class="row">
		<div class="browser-window">
			<div class="top-bar">
	          <h4>Clonar Club</h4>
	        </div>
	        <div class="content">
				<form class="col s12" id="realForm" action="{{route('clonar.club')}}" method="POST">
						
					<div class="row" >
						<div class="input-field col s6">		
							<select class="validate" id="rubro_id" name="rubro_id">
									<option value="">Elija una Opción</option>
								@foreach ($rubros as $id => $descripcion)
									<option value="{{$id}}">{{ $descripcion }}</option>	
								@endforeach
							</select>
                            <label class="active" for="rubro_id">Club Destino</label>
						</div>	
                        <div class="input-field col s6">
							<input type="text" class="validate" id="periodo" name="periodo"/>
							<label for="periodo">Periodo</label>
						</div>										
					</div>
					<div class="row">
						<div class="input-field col s4">		
							<select class="validate" id="empresa_id" name="empresa_id">
									<option value="">Elija una Opción</option>
								@foreach ($empresas as $id => $descripcion)
									<option value="{{$id}}">{{ $descripcion }}</option>	
								@endforeach
							</select>
                            <label class="active" for="empresa_id">Empresa Origen</label>
						</div>
						<div class="input-field col s4">		
							<select class="validate" id="encuesta_id" name="encuesta_id">
								<option value="">Elija una Opción</option>
								
							</select>
                            <label class="active" for="encuesta_id">Periodo origen</label>
						</div>
						<div class="input-field col s4">		
							<select class="validate" id="empresa_destino" name="empresa_destino">
								<option value="">Elija una Opción</option>
								
							</select>
                            <label class="active" for="empresa_destino">Empresa Destino</label>
						</div>
					</div>

					<input id="empresas" type="text" name="empresas">
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
				                  	<h6>Empresas a clonar</h6>
				                  	</p>
				              	</div>
			                </div>
			                <div class="content">
			                	<table id="detlist" class="highlight">
			                		<thead>
				                      <tr>
				                      	<th>Empresa Origen</th>
										<th>Periodo</th>
										<th>Empresa Destino</th>
										<th>Opción</th>
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
	
@stop
@push('scripts')
	<script>
		
		var opciones = [];
		$('#realForm').submit(function(e){
			if( $("#empresas").val() === '' ){
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

		$("#empresa_id").change(function(){
			var selectEncuesta = $("#encuesta_id");
			var empresaId = $(this).val();
			var url = "{{route('empresa.encuesta.list', ':empresa')}}";
			console.log(url);
			url = url.replace(':empresa', empresaId);
			console.log(url); 
			selectEncuesta.empty();
			$.get(url,  
				function(json){
					var data = $.map(json, function(text, id){
                    	return {text:text, id:id};
                    });
            		for(i = 0; i < data.length; i++){
            			selectEncuesta.append(
              			$("<option></option>").attr("value", data[i].id)
                                    		  .text(data[i].text));
					}

					$("select").select2();
				}
			);
		});

		$("#rubro_id").change(function(){
			var selectEmpresa = $("#empresa_destino");
			var rubroId = $(this).val();
			var url = "{{route('rubro.empresas.list', ':rubro')}}";
			url = url.replace(':rubro', rubroId);
			selectEmpresa.empty();
			$.get(url,  
				function(json){
					var data = $.map(json, function(text, id){
                    	return {text:text, id:id};
                    });
            		for(i = 0; i < data.length; i++){
            			selectEmpresa.append(
              			$("<option></option>").attr("value", data[i].id)
                                    		  .text(data[i].text));
					}

					$("select").select2();
				}
			);
		});

		
		$('#add').click(function(e){
			e.preventDefault();
			if(!$("#encuesta_id").val() == ''){
				var	item = {};
			    var rowCount = $('#detlist .ingData').length;
			    rowCount++;
				item.id = $("#empresa_id").val();
				item.encuesta = $("#encuesta_id").val();
				item.destino = $("#empresa_destino").val(); 
				item.row = rowCount;
				opciones.push(item);
				var empresaOrigen = $("#empresa_id option:selected").text();
				var periodo = $("#encuesta_id option:selected").text();
				var empresaDestino = $("#empresa_destino option:selected").text();
				$('#empresas').val(JSON.stringify(opciones));
			    var outHTML =
			        '<tr class="ingData" id="ingRow' + rowCount + '">'
			            + '<td>'
							+'<div id="empresa' + rowCount + '">' + empresaOrigen + '</div>'
						+'</td>'
						+ '<td>'
							+'<div id="periodo' + rowCount + '">' + periodo + '</div>'
						+'</td>'
						+ '<td>'
							+'<div id="empresa_dest' + rowCount + '">' + empresaDestino + '</div>'
						+'</td>'
			            + '<td>'
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
		

		function removeItem(row){
			var actualRow = arrayObjectIndexOf(opciones, row, "row"); 
			$('#ingRow'+row).children('td, div').animate({'backgroundColor':'#fb6c6c'},300);
			opciones.splice(actualRow, 1);
			$('#fields').val(JSON.stringify(opciones));
			$('#ingRow'+row).children('td, div').slideUp(300, function(){
				this.remove();
			});
			$('#empresas').val(JSON.stringify(opciones));

			
		}


		function arrayObjectIndexOf(myArray, searchTerm, property) {
			for(var i = 0, len = myArray.length; i < len; i++) {
		        if (myArray[i][property] === searchTerm){
		        	return i;	
		        } 
		    }
		    return -1;
		}

	</script>	
@endpush