@extends('layout')

@section('content')
	<div class="row">
		<div class="col l4">
			<a href="{{ route('encuestas_cargos.edit', $id) }}" class="btn waves-effect waves-light lighten-1 white-text"><i class="material-icons left">add</i>Cargo</a>
		</div>
	</div>	

	@if($dbData)
		<div class="col m12" id="table-container">
			<div class="browser-window">
				<div class="top-bar">
                  <h4>Listado de Cargos - {{$dbEmpresa or ''}} {{$dbPeriodo or ''}}</h4>
                </div>

                	<table id="listado_cargos" class="highlight responsive-table">
                		<thead>
	                      <tr>
	                      	 <th style="width: 30%">Descripcion</th>
	                      	 <th style="width: 10%">Cargo Oficial</th>
	                      	 <th style="width: 5%">Salario</th>
	                      	 <th style="width: 5%">Incluir</th>
							<th style="width: 20%">Opciones</th>
							<th></th>   
	                      	 <th style="display: none;"></th>
	                      </tr>
	                      <tr>
	                      	 <th style="width: 30%" class="searchable-column">Descripcion</th>
	                      	 <th style="width: 10%" class="searchable-column">Cargo Oficial</th>
	                      	 <th style="width: 5%" class="searchable-column">Salario</th>
	                      	 <th style="width: 5%"></th>
	                      	 <th style="width: 20%"></th>
	                      	 <th style="width: 20%"></th>
	                      	 <th style="display: none;"></th>
	                      </tr>
	                    </thead>
	                    
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
	   		//$('select').select2();
	   		// Setup - add a text input to each footer cell
		    $('#listado_cargos thead .searchable-column').each( function () {
		        var title = $(this).text();
		        $(this).html( '<input type="text" placeholder="Buscar '+title+'" />' );
		    } );

			var spinner = '<div class="preloader-wrapper big active" style="position:absolute; top:0; left:0; right:0; bottom:0; margin:auto;">'+
							'<div class="spinner-layer spinner-green-only">'+
								'<div class="circle-clipper left">'+
									'<div class="circle"></div>'+
								'</div>'+
								'<div class="gap-patch">'+
									'<div class="circle"></div>'+
								'</div>'+
								'<div class="circle-clipper right">'+
									'<div class="circle"></div>'+
								'</div>'+
							'</div>'+
						  '</div>';
	   		var table = $('#listado_cargos').DataTable({
				   			processing: true,
							ajax: "{{route('encuestas_cargos.getCargos', $id)}}",
			   				"scrollX": false,
			            	"scrollCollapse": false,
			            	"lengthChange": false,
			            	"aaSorting": [],
			   	           	"columnDefs": [
			        			{	"targets": [0,1],
			        				"orderable": false
			        			},
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
				                "infoFiltered": "(Filtrado de un total de _MAX_ registros)",
								processing: spinner
				            }, 
							 drawCallback: function() {
							     $('.select2').select2();
							  }

	    				});

				table.columns().every(function(){
					var that = this;
					$('input', this.header()).on('keyup change', function(){
						if ( that.search() !== this.value ) {
							that
								.search( this.value )
								.draw();
						}
				});
    		});
	    	
   		});

		$("#listado_cargos").on('click', '.guardar', function(e){
			e.preventDefault();
			var id = $(this).closest("tr").find("input[name=cargo_id]").val();
			var cargoId = $(this).closest("tr").find("#cargos").val();
			console.log(id);
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