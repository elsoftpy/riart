@extends('layout')

@section('content')
	<div class="row">
		<div class="col l4">
			<a href="{{ route('cargos.create') }}" class="btn waves-effect waves-light lighten-1 white-text"><i class="material-icons left">add</i>Cargo</a>
		</div>
	</div>	
	@if($dbData)
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
	                      	 <th>Opciones</th>
	                    </thead>
	                    <tbody>
	                    	@foreach($dbData as $est) 
	                    		<tr>
		                    		<td>{{ $est->id }}</td>
		                    		<td>{{ $est->descripcion }}</td>
		                    		<td><a href="{{ route('cargos.edit', $est->id) }}" class="btn waves-light waves-effect lighten-1 white-text ">
		                    			<i class="material-icons left">edit</i>Editar</a></td>
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