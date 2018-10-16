@extends('report.layout')

@section('content')
		<div class="row">
			<div class="browser-window">
					@include('report.title')
                <div class="content col s12">
                	<table id="Listado" class="highlight">
                		<thead>
	                      <tr>
	                      	 <th>@lang('reportPanel.table_id')</th>
	                      	 <th>@lang('reportPanel.table_description')</th>
	                      	 <th>@lang('reportPanel.table_branches')</th>
	                      	 <th>@lang('reportPanel.table_employees')</th>
	                      	 <th>@lang('reportPanel.table_origin')</th>
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
											@lang('reportPanel.label_origin_national')
		                    			@else
											@lang('reportPanel.label_origin_international')
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
   		var locale = '{{$locale}}';
		console.log(locale);
		$(function(){
			if(locale == "es"){
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
			}else{
				$('#Listado').DataTable({
					"scrollX": false,
					"scrollCollapse": false,
					"lengthChange": false,
					pageLength: 20,
					"language": {
						"decimal": ".",
						"thousands": ",",
						"zeroRecords": "No records found - We're sorry",
						"info": "Page _PAGE_ of _PAGES_",
						"infoEmpty": "No records found",
						"infoFiltered": "(Filtered out of _MAX_ rows)",
						"search": "Search",
						"paginate":{
							"first": "First",
							"last": "Last",
							"next": "Next",
							"previous": "Previous",
						},        
					}
					
				});
			}
		   
   		});


		function delete_row(row){
			event.preventDefault(); 
			if (confirm('Seguro que desea eliminar el registro?')){
				document.getElementById('delete-form'+row).submit();
			} else {
				return false;
			}
		}

		if (RegExp('multipage=true', 'gi').test(window.location.search)) {
		    tour.goToStepNumber(27).start();
	    }

     	tour.onafterchange(function(step){
			if($(step).attr("data-step") == 28){
				window.location.href="{{URL::route('reportes.metodologia', $dbEmpresa)}}?multipage=true";
			}
      	});    		    	    

	</script>
@endpush
