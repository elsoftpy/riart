@extends('layout')

@section('content')
	<div class="row">
		<div class="col l4">
			<a href="{{ route('periodos_activos.create') }}" class="btn waves-effect waves-light lighten-1 white-text"><i class="material-icons left">add</i>Periodo</a>
		</div>
	</div>	
		<div class="row">
			<div class="browser-window">
				<div class="top-bar">
                  <h4>Configuración de Periodos Activos</h4>
                </div>
                <div class="content">
                	<table id="Listado" class="highlight">
                		<thead>
	                      <tr>
	                      	 <th>Id</th>
	                      	 <th>Rubro</th>
	                      	 <th>Periodo</th>
	                      	 <th>Activo</th>
	                      	 <th>Opciones</th>
	                    </thead>
	                    <tbody>
	                    	@foreach($dbData as $est) 
	                    		<tr>
		                    		<td>{{ $est->id }}</td>
		                    		<td>{{ $est->rubro->descripcion }}</td>
		                    		<td>{{ $est->periodo }}</td>
									<td>
										@if ($est->activo)
											Sí
										@else
											No
										@endif
									</td>
		                    		<td><a href="{{ route('periodos_activos.edit', $est->id) }}" class="btn waves-light waves-effect lighten-1 white-text ">
		                    			<i class="material-icons left">edit</i>Editar
		                    			</a>
										
										<a href="#" class="btn waves-light waves-effect lighten-1 red white-text" onclick="delete_row({{$est->id}})">
		                    			<i class="material-icons left">delete</i>Borrar
		                    			</a>		                    			
										<form id="delete-form{{$est->id}}" action="{{ route('periodos_activos.destroy', $est->id) }}" method="POST" style="display: none;">
				                            {{ csrf_field() }}
				                            {{ method_field('DELETE') }}
				                        </form>                    			
		                    		</td>
	                    		</tr>
	                    	@endforeach
	                    </tbody>
	                </table>
                </div>
			</div>
		</div>
	
@endsection
@push('scripts')
	<script type="text/javascript">
   		$(function(){
	   		$('#Listado').DataTable({
   				"scrollX": false,
            	"scrollCollapse": false,
            	"lengthChange": false,

   	           	"columnDefs": [
        			{	"targets": [2], 
        				"orderable": false, 
        				"searchable": false 
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