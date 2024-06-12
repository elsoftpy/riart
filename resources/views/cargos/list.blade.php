@extends('layout')

@section('content')
	<div class="row">
		<div class="col l4">
			<a href="{{ route('cargos.create') }}" class="btn waves-effect waves-light lighten-1 white-text"><i class="material-icons left">add</i>Cargo</a>
		</div>
		<form id="excel_form" action="{{ route('cargos.excel') }}" method="POST">	
			{{ csrf_field() }}
			<button class="btn waves-effect waves-light lighten-1 white-text" type="submit" name="submit_excel" id="btn_excel">
				<i class="material-icons left">cloud_download</i>Excel
			</button>
		</form>	
	</div>	
		<div class="row">
			<div class="browser-window">
				<div class="top-bar">
                  <h4>Listado de Cargos</h4>
                </div>
                <div class="content">
                	<table id="Listado" class="highlight">
                		<thead>
	                      	<tr>
	                      	 	<th>Id</th>
	                      	 	<th>Descripción</th>
								<th>Descripción (en inglés)</th>   
								<th>Opciones</th>
	                      	</tr>
	                    </thead>
	                    <tbody>
	                    	@foreach($dbData as $est) 
	                    		<tr>
		                    		<td>{{ $est->id }}</td>
									<td>{{ $est->descripcion }}</td>
									@if ($est->cargoEn)
										<td>{{ $est->cargoEn->descripcion }}</td>
									@else
										<td></td>
									@endif
		                    		<td><a href="{{ route('cargos.edit', $est->id) }}" class="btn waves-light waves-effect lighten-1 white-text ">
		                    			<i class="material-icons left">edit</i>Editar
		                    			</a>
										@if(!$est->cargosRubro->count() && !$est->encuestasCargo->count())
										<a href="{{ route('cargos.destroy', $est->id) }}" class="btn waves-light waves-effect lighten-1 red white-text" onclick="delete_row({{$est->id}})">
		                    			<i class="material-icons left">delete</i>Borrar
		                    			</a>		                    			
										<form id="delete-form{{$est->id}}" action="{{ route('cargos.destroy', $est->id) }}" method="POST" style="display: none;">
				                            {{ csrf_field() }}
				                            {{ method_field('DELETE') }}
				                        @else
											<a href="{{ route('cargos.destroy', $est->id) }}" class="btn disabled waves-light waves-effect lighten-1 red white-text ">
			                    			<i class="material-icons left">delete</i>Borrar
			                    			</a>		                    			

				                        @endif
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