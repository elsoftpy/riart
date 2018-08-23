@extends('layout')

@section('content')
	<div class="row">
		<div class="col l3">
			<a href="{{ route('beneficios_admin.create') }}" class="btn waves-effect waves-light lighten-1 white-text"><i class="material-icons left">add</i>Encuesta</a>
		</div>
	</div>	
		<div class="row">
			<div class="browser-window">
				<div class="top-bar">
                  <h4>Listado de Encuestas de Beneficios</h4>
                </div>
                <div class="content">
                	<table id="Listado" class="highlight">
                		<thead>
	                      <tr>
	                      	 <th>Id</th>
	                      	 <th>Descripcion</th>
	                      	 <th>Periodo</th>
	                      	 <th>Finalizada</th>
	                      	 <th></th>
	                      </tr>
	                    </thead>
	                    <tbody>
	                    	@foreach($dbData as $est) 
	                    		<tr>
		                    		<td>{{ $est->id }}</td>
		                    		<td>{{ $est->empresa->descripcion}}</td>
		                    		
		                    		<td>{{ $est->periodo}}</td>
		                    		<td>{{$est->finalizada}}</td>
		                    		<td style="width: 20%">
		                    			<a href="{{ route('beneficios.edit', $est->id) }}" class="btn waves-light waves-effect lighten-1 amber white-text ">
		                    				<i class="material-icons left">edit</i> Completar
		                    			</a>
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
        			{	"targets": [4], 
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