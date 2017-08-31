@extends('layout')

@section('content')
	<div class="row">
		<div class="col l4">
			<a href="{{ route('encuestas_cargos.edit', $id) }}" class="btn waves-effect waves-light lighten-1 white-text"><i class="material-icons left">add</i>Cargo</a>
		</div>
	</div>	

	@if($dbData)
		<div class="col m12">
			<div class="browser-window">
				<div class="top-bar">
                  <h4>Listado de Cargos - {{$dbEmpresa or ''}} {{$dbPeriodo or ''}}</h4>
                </div>

                	<table id="listado" class="highlight responsive-table">
                		<thead>
	                      <tr>
	                      	 <th style="width: 30%">Descripcion</th>
	                      	 <th style="width: 10%">Cargo Oficial</th>
	                      	 <th style="width: 5%">Salario</th>
	                      	 <th style="width: 5%">Incluir</th>
	                      	 <th style="width: 20%"></th>
	                      	 <th style="width: 20%"></th>
	                      	 <th style="display: none"></th>
	                    </thead>
	                    <tbody id="details">
	                    	@foreach($dbData as $est) 
	                    		<tr>
		                    		<td>{{ $est->descripcion }}</td>
		                    		<td>
		                    			<select id="cargos" class="select2">
		                    				@foreach($dbCargos as $key=>$cargo)
		                    					@if($est->cargo_id == $key)
		                    						<option value={{$key}}  selected>{{$cargo}}</option>
		                    					@else
													<option value={{$key}}>{{$cargo}}</option>
		                    					@endif
		                    					
		                    				@endforeach
		                    			</select>
		                    		</td>
		                    		<td>
		                    			{{ DB::table('detalle_encuestas')
		                    		         ->where('encuestas_cargo_id', $est->id)
		                    		         ->where('cabecera_encuesta_id', $est->cabecera_encuesta_id)
		                    		                 ->value('salario_base')}}
		                    		</td>
		                    		<td>
		                    			@if($est->incluir)
		                    				Sí
		                    			@else
		                    				No
		                    			@endif
		                    			
		                    		</td>
		                    		<td>
		                    			<a href="{{ route('cargos_clientes.edit', $est->id) }}" class="btn waves-light waves-effect lighten-1 white-text amber">
		                    				<i class="material-icons left">edit</i>Revisar
		                    			</a>
									</td>
									<td>
										<a href="" class="guardar btn waves-light waves-effect lighten-1 white-text" id="guardar" index="{{$loop->index}}">
		                    				<i class="material-icons left">save</i>Guardar
		                    			</a>		                    			
		                    		</td>
		                    		<td><input type="hidden" name="cargo_id" value="{{$est->id}}"></td>
	                    		</tr>
	                    	@endforeach
	                    </tbody>
	                </table>
                </div>
			</div>
		</div>
	@else
		<div class="row">
			<div class="col s12">
				<h4>Listado de Cargos</h4>
				<div class="hoverable bordered">
					<div class="card cyan light-blue-lighten-2">
						<div class="card-content white-text">
							<span class="card-title"><strong>Saludos</strong> </span>
							<p>Esta es la primera vez que participa de la Encuesta, por favor ingrese un cargo nuevo</p>
						</div>
					</div>

				</div>
			</div>
		</div>
	@endif
@endsection
@push('scripts')
	<script type="text/javascript">
   		$(function(){
	   		$('select').select2();
	   		
	   		$('#listado').DataTable({
   				"scrollX": false,
            	"scrollCollapse": false,
            	"lengthChange": false,
   	           	"columnDefs": [
        			{	"targets": [3], 
        				"width":"10%" 
        			},
        			{	"targets":[2], 
        			    "render": $.fn.dataTable.render.number( ".", ",", 0),
        			    "width":"10%" 					
						
        			},
        			{	"targets":[4,5], 
        			    "width":"15%", 					
        				"orderable": false, 
        				"searchable": false,
        			}

        			
        		],

	            "language": {
	                "decimal": ",",
	                "thousands": ".",
	                "zeroRecords": "No hay registros - Lo sentimos",
	                "info": "Página _PAGE_ de _PAGES_",
	                "infoEmpty": "No hay registros disponibles",
	                "infoFiltered": "(Filtrado de un total de _MAX_ registros)"	        
	            }

	    	});
	    	
   		});

		/*$("#listado").on('change', '.cargo_select', function(e){
			var id = $(this).val();
			var input = $(this).closest("tr").find("input[name=cargo_id]");
			input.val(id);
		});*/

		$("#listado").on('click', '.guardar', function(e){
			e.preventDefault();
			var id = $(this).closest("tr").find("input[name=cargo_id]").val();
			var cargoId = $(this).closest("tr").find("#cargos").val();
			console.log(cargoId);
			$.post("{{route('homologacion.store')}}", {"id": id, "cargo_id": cargoId, "_token": "{{csrf_token()}}"},
				function(data){
					alert(data);
				}
			);
		});

		function delete_row(row){
			event.preventDefault(); 
			if (confirm('Seguro que desea eliminar el registro?')){
				document.getElementById('delete-form'+row).submit();
			} else {
				return false;
			}
		}


	</script>
@endpush