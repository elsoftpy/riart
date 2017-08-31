@extends('layout')

@section('content')
	<div class="row">
		<div class="col l3">
			<a href="{{ route('empresas.create') }}" class="btn waves-effect waves-light lighten-1 white-text"><i class="material-icons left">add</i>Empresa</a>
		</div>
	</div>	
		<div class="row">
			<div class="browser-window">
				<div class="top-bar">
                  <h4>Listado de Empresas</h4>
                </div>
                <div class="content">
                	<table id="Listado" class="highlight">
                		<thead>
	                      <tr>
	                      	 <th>Descripcion</th>
	                      	 <th>Rubro</th>
	                      	 <th>Segmento</th>
	                      	 <th>Origen</th>
							 <th></th>
	                      </tr>
	                    </thead>
	                    <tbody>
	                    	@foreach($dbData as $est) 
	                    		<tr>
		                    		<td>{{ $est->descripcion}}</td>
		                    		<td>{{ $est->rubro->descripcion}}</td>
		                    		<td>{{ $est->subrubro->descripcion}}</td>
		                    		<td>@if($est->tipo == "0")
		                    				{{"Nacional"}}
		                    			@else
		                    				{{"Internacional"}}
		                    			@endif
		                    		</td>
		                    		<td>
		                    			<a href="{{ route('encuestas_cargos.show', $est->id) }}" class="btn waves-light waves-effect white-text ">
		                    				<i class="material-icons left">assignment</i> Cargar
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