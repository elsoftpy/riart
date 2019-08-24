@extends('layout')

@section('content')
	<div class="row">
		<div class="col l3">
			<a href="{{ route('sub_rubros.create') }}" class="btn waves-effect waves-light lighten-1 white-text"><i class="material-icons left">add</i>Segmento</a>
		</div>
	</div>	
		<div class="row">
			<div class="browser-window">
				<div class="top-bar">
                  <h4>Listado de Segmentos</h4>
                </div>
                <div class="content">
                	<table id="Listado" class="highlight">
                		<thead>
	                      <tr>
	                      	 <th>Id</th>
	                      	 <th>Descripcion</th>
	                      	 <th>Rubro</th>
	                      	 <th></th>
	                      </tr>
	                    </thead>
	                    <tbody>
	                    	@foreach($dbData as $est) 
	                    		<tr>
		                    		<td>{{ $est->id }}</td>
		                    		<td>{{ $est->descripcion}}</td>
		                    		<td>{{ $est->rubro->descripcion}}</td>
		                    		<td>
		                    			<a href="{{ route('sub_rubros.edit', $est->id) }}" class="btn waves-light waves-effect amber white-text ">
		                    				<i class="material-icons left">edit</i> Editar
		                    			</a>
										<a href="{{ route('reportes.lista', $est->id) }}" class="btn waves-light waves-effect blue white-text ">
		                    				<i class="material-icons left">list</i> Listar
		                    			</a>		                    			
										<a href="{{ route('sub_rubros.destroy', $est->id) }}" class="btn waves-effect waves-light lighten-1 red white-text" onclick="delete_row({{$est->id}})">
											<i class="material-icons left">delete</i>Borrar
										</a>
				                        <form id="delete-form{{$est->id}}" action="{{ route('sub_rubros.destroy', $est->id) }}" method="POST" style="display: none;">
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
        			{	"targets": [3], 
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
