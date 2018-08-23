@extends('layout')

@section('content')
	<div class="row" data-intro="" data-step="7">
		<div class="col l4">
			@if($dbData->first()->cabeceraEncuestas->finalizada == "N")
				<a href="{{ route('cargos_clientes.create') }}" class="btn waves-effect waves-light lighten-1 white-text" data-intro="<p class='intro-title'><strong>AGREGAR POSICION</strong></p>Habilita una plantilla en blanco para sumar</br> los datos de un nuevo cargo." data-step="4"><i class="material-icons left">add</i>Cargo</a>
			@endif
		</div>
	</div>	
	@if($dbData)
		<div class="row">
			<div class="browser-window">
				<div class="top-bar" data-intro="<p class='intro-title'><strong>LISTADO DE CARGOS</strong></p>Aquí  visualiza su listado de cargos del periodo actual." data-step="3">
                  <h4>Listado de Cargos - {{$dbEmpresa or ''}} {{$dbPeriodo or ''}}</h4>
                </div>
                <div class="content">
                	<table id="listado" class="highlight">
                		<thead>
	                      <tr id="header_row">
	                      	 <th>Id</th>
	                      	 <th>Descripción</th>
	                      	 <th>Salario</th>
	                      	 <th>Opciones</th>
	                    </thead>
	                    <tbody id="detalle">
	                    	@foreach($dbData as $est) 
	                    		<tr>
		                    		<td>{{ $est->id }}</td>
		                    		<td>{{ $est->descripcion }}</td>
		                    		<td>{{ DB::table('detalle_encuestas')
		                    		         ->where('encuestas_cargo_id', $est->id)
		                    		         ->where('cabecera_encuesta_id', $est->cabecera_encuesta_id)
		                    		                 ->value('salario_base')}}</td>
		                    		<td><a href="{{ route('cargos_clientes.edit', $est->id) }}" class="btn waves-light waves-effect lighten-1 white-text ">
		                    			<i class="material-icons left">edit</i>Revisar</a></td>
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
	   		$('#listado').DataTable({
   				"scrollX": false,
            	"scrollCollapse": false,
            	"lengthChange": false,
   	           	"columnDefs": [
        			{	"targets": [3], 
        				"orderable": false, 
        				"searchable": false 
        			},
        			{	"targets":[2], 
        			    "render": $.fn.dataTable.render.number( ".", ",", 0)					
						
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
	    	$("#detalle").find("tr:first-child a").attr("data-intro", "<p class='intro-title'><strong>REVISAR</strong></p>Datos de su Empresa/Banco</br>Acceso para verificar/modificar información cuantitativa de cada cargo.").attr("data-step", "5");
	    	$("#listado_paginate").attr("data-intro", "<p class=''><strong>RECORRIDO POR PAGINA</strong></p>Click en N° de página para realizar el recorrido de cargos por página").attr("data-step", "6");

			if (RegExp('multipage=true', 'gi').test(window.location.search)) {
			    tour.goToStepNumber(3).start();
		    }

			function delete_row(row){
				event.preventDefault(); 
				if (confirm('Seguro que desea eliminar el registro?')){
					document.getElementById('delete-form'+row).submit();
				} else {
					return false;
				}
			}


	     	tour.onafterchange(function(step){
	      		if($(step).attr("data-step") == 7){
	      			window.location.href ="{{ URL::route('home', ["multipage"=>"2"])}}";
	      			tour.exit();
	      		}
	      	});          
	    });
	</script>
@endpush