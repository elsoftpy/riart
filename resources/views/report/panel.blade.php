@extends('report.layout')

@section('content')
		<div class="row">
			<div class="browser-window">
				<div class="top-bar">
                  <h4>{{$club}} Participantes</h4>
                </div>
                <div class="content col s12">
                	<table id="Listado" class="highlight">
                		<thead>
	                      <tr>
	                      	 <th>Nro.</th>
	                      	 <th>Descripcion</th>
	                      	 <th>Cant. Suc.</th>
	                      	 <th>Cant. Emp.</th>
	                      	 <th>Origen</th>
	                      </tr>
	                    </thead>
	                    <tbody>
	                    	@foreach($dbData as $est) 
	                    		<tr>
		                    		<td>{{ $loop->iteration }}</td>
		                    		<td>{{ $est->descripcion}}</td>
		                    		<td>{{ $est->cantidad_sucursales}}</td>
		                    		<td>{{ $est->cantidad_empleados}}</td>
		                    		<td>@if($est->tipo == "0")
		                    				{{"Nacional"}}
		                    			@else
		                    				{{"Internacional"}}
		                    			@endif
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
            	pageLength: 20,
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
