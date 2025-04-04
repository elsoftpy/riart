@extends('layout')

@section('content')
	<div class="row">
		<div class="browser-window col s12">
			<div class="top-bar">
	          <h4>{{ $club }}</h4>
	        </div>
	        <div class="content">
	        	<table id="Listado" class="highlight" data-step="1" data-intro="<p class='intro-title'><strong>DATOS DE SU EMPRESA/BANCO</strong></p> En la pantalla principal Ud. podrá visualizar los datos básicos</br> de su organización y los comandos que contiene la plataforma." >
	        		<thead>
	                  <tr>
	                  	 <th>Id</th>
	                  	 <th>@lang('homepage.table_description')</th>
	                  	 <th>@lang('homepage.table_club')</th>
	                  	 <th>@lang('homepage.table_branches')</th>
	                  	 <th>@lang('homepage.table_headcount')</th>
	                  	 <th>@lang('homepage.table_origin')</th>
	                  	 <th></th>
						 <th></th>
	                  </tr>
	                </thead>
	                <tbody>
                		@if(!is_null($dbEmpresa))
                		<tr>
                    		<td>{{ $dbEmpresa->id }}</td>
                    		<td>{{ $dbEmpresa->descripcion}}</td>
                    		<td>{{ $club}}</td>
                    		<td>{{ $dbEmpresa->cantidad_sucursales}}</td>
                    		<td>{{ $dbEmpresa->cantidad_empleados}}</td>
                    		<td>@if($dbEmpresa->tipo == 0)
                    				@lang('homepage.table_data_local')
                    			@else
                    				@lang('homepage.table_data_inter')
                    			@endif
                    		</td>
                    		<td>
                    			<a href="{{ route('cargos_clientes.show', $dbEmpresa->id) }}" class="btn waves-light waves-effect white-text" style="margin-bottom: 1em;" data-intro="<p class='intro-title'><strong>LISTAR LOS CARGOS</strong></p> Click para acceder a su listado de cargos y a la </br> información de los mismos." data-step="2">
                    				<i class="material-icons left">list</i>@lang('homepage.button_list')
                    			</a>
                    			<a href="{{ route('empresas.edit', $dbEmpresa->id) }}" class="btn waves-light waves-effect amber white-text" style="margin-bottom: 1em;" data-step="7" data-intro="<p class='intro-title'><strong>EDITAR</strong></p> Función que permite la modificación de los datos de su empresa/banco." data-position="left">
                    				<i class="material-icons left">edit</i>@lang('homepage.button_edit')
								</a><br/>
                    			@if($dbEmpresa->rubro_id == 4)
                        			<a href="#" class="btn waves-light waves-effect amber white-text" style="margin-bottom: 1em;" id="select_encuesta" data-step="8" data-intro="<p class='intro-title'><strong>REPORTE - RESULTADO</strong></p>Contiene información del mercado investigado: </br> podrá acceder a búsquedas por niveles de cargo y visualizar los resultados por cargo, permite la exportación de todos los cargos de su organizacion y del mercado a Excel." data-position="left">
                    					<i class="material-icons left">dashboard</i>@lang('homepage.button_report')
                    				</a>                    			
                    			@else
	                    			<a href="{{ route('encuestas.show', $dbEmpresa->id) }}" class="btn waves-light waves-effect amber white-text" style="margin-bottom: 1em;" data-step="8" data-intro="<p class='intro-title'><strong>REPORTE - RESULTADO</strong></p>Contiene información del mercado investigado: </br> podrá acceder a búsquedas por niveles de cargo y visualizar los resultados por cargo, permite la exportación de todos los cargos de su organizacion y del mercado a Excel." data-position="left">
	                    				<i class="material-icons left">dashboard</i>@lang('homepage.button_report')
	                    			</a>                    			
                    			@endif

								<a href="{{ route('encuestas.update', $dbEncuesta->id) }}" class="btn waves-effect waves-light lighten-1 red white-text" style="margin-bottom: 1em;" onclick="update_row({{$dbEncuesta->id}})" data-step="29" data-intro="<p class='intro-title'><strong>FINALIZAR</strong></p>Realizar click cuando haya finalizado la carga de todos los cargos. No admite posteriores ediciones para el periodo.">
									<i class="material-icons left">exit_to_app</i>@lang('homepage.button_done')
								</a>

								<form id="update-form{{$dbEncuesta->id}}" action="{{ route('encuestas.update', $dbEncuesta->id) }}" method="POST" style="display: none;">
					                {{ csrf_field() }}
					                {{ method_field('PUT') }}
								</form>
								@if ($dbEmpresa->sub_rubro_id == 25)
									<a href="#" class="btn waves-light waves-effect blue white-text" style="margin-bottom: 1em;" id="select_encuesta_esp">
										<i class="material-icons left">dashboard</i>Reporte Especial
									</a>
								@endif
                    		</td>
                		</tr>
                		@endif
	                </tbody>
	            </table>
	        </div>
		</div>
	</div>
	<div class="modal" id="modal-options">
		<div class="modal-content">
			<h5> @lang('homepage.modal_survey') </h5>
			@if($dbEncuesta)
				@if($dbEncuestaAnt)
					@if ($dbEncuestaOld)
						<a class="waves-light waves-effect btn blue darken-3" id="encuesta-vieja" >
							{{$dbEncuestaOld->periodo}}
						</a>						
					@endif
					<a class="waves-light waves-effect btn lime darken-3" id="encuesta-anterior" >
						{{$dbEncuestaAnt->periodo}}
					</a>	
					<a class="waves-light waves-effect btn green" id="encuesta-actual" periodo="{{$dbEncuesta->periodo}}">
						{{$dbEncuesta->periodo}}
					</a>	
					<input type="hidden" id="periodo_viejo" name="periodo_viejo" value="{{$dbEncuestaOld->periodo}}"/>
					<input type="hidden" id="periodo_ant" name="periodo_anterior" value="{{$dbEncuestaAnt->periodo}}"/>
					<input type="hidden" id="periodo" name="periodo" value="{{$dbEncuesta->periodo}}"/>
				@else
					<a class="waves-light waves-effect btn green" id="encuesta-actual" periodo="{{$dbEncuesta->periodo}}">
						{{$dbEncuesta->periodo}}
					</a>				
					<input type="hidden" id="periodo" name="periodo" value="{{$dbEncuesta->periodo}}"/>				
				@endif
			@endif
		</div>
		<div class="modal-footer">
			<a class="modal-close waves-light waves-effect btn " id="close-modal">@lang('homepage.modal_button_close')</a>
		</div>
	</div>
	<div class="modal" id="modal-options-esp">
		<div class="modal-content">
			<h5> @lang('homepage.modal_survey') </h5>
			@php
				$showPeriodoAnt = false;
				if($dbEncuestaAnt){
					$periodoString = $dbEncuestaAnt->periodo;
					// extract last four characters
					$periodoAnt = substr($periodoString, -4);
					// convert $periodoAnt to integer
					if( (int) $periodoAnt > 2023){
						$showPeriodoAnt = true;
					}
				} 
			@endphp
			@if($dbEncuesta)
				@if($dbEncuestaAnt)
					@if($showPeriodoAnt)

						<a class="waves-light waves-effect btn lime darken-3" id="encuesta-anterior_esp" >
							{{$dbEncuestaAnt->periodo}}
						</a>	
						<a class="waves-light waves-effect btn green" id="encuesta-actual_esp" periodo="{{$dbEncuesta->periodo}}">
							{{$dbEncuesta->periodo}}
						</a>	
						<input type="hidden" id="periodo_ant_esp" name="periodo_anterior" value="{{$dbEncuestaAnt->periodo}}"/>
						<input type="hidden" id="periodo_esp" name="periodo" value="{{$dbEncuesta->periodo}}"/>
					@else	
						<a class="waves-light waves-effect btn green" id="encuesta-actual_esp" periodo="{{$dbEncuesta->periodo}}">
							{{$dbEncuesta->periodo}}
						</a>	
						<input type="hidden" id="periodo_esp" name="periodo" value="{{$dbEncuesta->periodo}}"/>
					@endif
				@endif

				{{-- @if($dbEncuestaAnt)
					@if ($dbEncuestaOld)
						<a class="waves-light waves-effect btn blue darken-3" id="encuesta-vieja_esp" >
							{{$dbEncuestaOld->periodo}}
						</a>						
					@endif
					<a class="waves-light waves-effect btn lime darken-3" id="encuesta-anterior_esp" >
						{{$dbEncuestaAnt->periodo}}
					</a>	
					<a class="waves-light waves-effect btn green" id="encuesta-actual_esp" periodo="{{$dbEncuesta->periodo}}">
						{{$dbEncuesta->periodo}}
					</a>	
					<input type="hidden" id="periodo_viejo_esp" name="periodo_viejo" value="{{$dbEncuestaOld->periodo}}"/>
					<input type="hidden" id="periodo_ant_esp" name="periodo_anterior" value="{{$dbEncuestaAnt->periodo}}"/>
					<input type="hidden" id="periodo_esp" name="periodo" value="{{$dbEncuesta->periodo}}"/>
				@else
					<a class="waves-light waves-effect btn green" id="encuesta-actual_esp" periodo="{{$dbEncuesta->periodo}}">
						{{$dbEncuesta->periodo}}
					</a>				
					<input type="hidden" id="periodo_esp" name="periodo" value="{{$dbEncuesta->periodo}}"/>				
				@endif --}}
			@endif
		</div>
		<div class="modal-footer">
			<a class="modal-close waves-light waves-effect btn " id="close-modal_esp">@lang('homepage.modal_button_close')</a>
		</div>
	</div>
@stop
@push('scripts')
	<script type="text/javascript">
		var modal = M.Modal.getInstance($("#modal-options"));
		var modalEsp = M.Modal.getInstance($("#modal-options-esp"));

		var test = RegExp('multipage=2', 'gi').test(window.location.search);
      	var finalizar = RegExp('multipage=4', 'gi').test(window.location.search);
      	$("#tour").click(function(e){
        	e.preventDefault();
			console.log("hello");
        	tour.start();
      	});

      	
      	tour.onafterchange(function(step){      		
      		if(!test){
	      		if($(step).attr("data-step") == 7){
	      			window.location.href ="{{ route('cargos_clientes.show', $dbEmpresa->id)}}?multipage=true";
	      			tour.exit();
      			}
      		}
      		if(!finalizar){
      			if($(step).attr("data-step") == 29){
      				window.location.href="{{route('reportes.show', $dbEmpresa->id)}}?multipage=true";
      				tour.exit();
      			}
     		}
      		
      	});          

		if (test) {
		    tour.goToStepNumber(7).start();
	    }

	    if(finalizar){
	    	tour.goToStepNumber(29).start();	
	    }

	    tour.onexit(function() {
  			if(finalizar){
  				window.location.href ="{{ route('home.page')}}";	
  			}
  			
		});

		function update_row(row){
			event.preventDefault(); 
			if (confirm('Seguro que desea cerrar la Encuesta?')){
				document.getElementById('update-form'+row).submit();
			} else {
				return false;
			}
		}

		$("#select_encuesta").click(function(){
			modal.open();	
		});

		$("#select_encuesta_esp").click(function(){
			modalEsp.open();	
		});

		$('#close-modal').click(function(e){
			$("#modal-options").closeModal();	
		});

		$("#encuesta-actual").click(function(e){
			e.preventDefault();
			var periodo = $("#periodo").val();
			$.post('{{route('periodo')}}', {periodo: periodo, "_token": "{{ csrf_token() }}"}, function(){
				window.location.href = "{{route('encuestas.show', $dbEmpresa->id)}}";
			});
		});
		$("#encuesta-anterior").click(function(e){
			e.preventDefault();
			var periodo = $("#periodo_ant").val();
			$.post('{{route('periodo')}}', {periodo: periodo, "_token": "{{ csrf_token() }}"}, function(){
				window.location.href = "{{route('encuestas.show', $dbEmpresa->id)}}";
			});
		});
		$("#encuesta-vieja").click(function(e){
			e.preventDefault();
			var periodo = $("#periodo_viejo").val();
			console.log(periodo);
			$.post('{{route('periodo')}}', {periodo: periodo, "_token": "{{ csrf_token() }}"}, function(json){
				console.log(json);
				window.location.href = "{{route('encuestas.show', $dbEmpresa->id)}}";
			});
		});		

		$("#encuesta-actual_esp").click(function(e){
			e.preventDefault();
			var periodo = $("#periodo_esp").val();
			$.post('{{route('periodo_especial')}}', {periodo: periodo, "_token": "{{ csrf_token() }}"}, function(){
				window.location.href = "{{route('encuestas.show', $dbEmpresa->id)}}";
			});
		});
		$("#encuesta-anterior_esp").click(function(e){
			e.preventDefault();
			var periodo = $("#periodo_ant").val();
			$.post('{{route('periodo_especial')}}', {periodo: periodo, "_token": "{{ csrf_token() }}"}, function(){
				window.location.href = "{{route('encuestas.show', $dbEmpresa->id)}}";
			});
		});
		$("#encuesta-vieja_esp").click(function(e){
			e.preventDefault();
			var periodo = $("#periodo_viejo").val();
			console.log(periodo);
			$.post('{{route('periodo_especial')}}', {periodo: periodo, "_token": "{{ csrf_token() }}"}, function(json){
				console.log(json);
				window.location.href = "{{route('encuestas.show', $dbEmpresa->id)}}";
			});
		});		

		$('#close-modal-esp').click(function(e){
			$("#modal-options-esp").closeModal();	
		});
	</script>
@endpush