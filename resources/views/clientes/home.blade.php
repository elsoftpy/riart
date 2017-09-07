@extends('layout')

@section('content')
	<div class="row">
		<div class="browser-window">
			<div class="top-bar">
	          <h4>{{ $dbEmpresa->rubro->descripcion}}</h4>
	        </div>
	        <div class="content">
	        	<table id="Listado" class="highlight">
	        		<thead>
	                  <tr>
	                  	 <th>Id</th>
	                  	 <th>Descripcion</th>
	                  	 <th>Rubro</th>
	                  	 <th>Segmento</th>
	                  	 <th>Nro. Suc.</th>
	                  	 <th>Nro. Emp.</th>
	                  	 <th>Origen</th>
	                  	 <th></th>
						 <th></th>
	                  </tr>
	                </thead>
	                <tbody>
                		@if(!is_null($dbEmpresa))
                		<tr>
                    		<td>{{ $dbEmpresa->id }}</td>
                    		<td>{{ $dbEmpresa->descripcion}}</td>
                    		<td>{{ $dbEmpresa->rubro->descripcion}}</td>
                    		<td>{{ $dbEmpresa->subrubro->descripcion}}</td>
                    		<td>{{ $dbEmpresa->cantidad_sucursales}}</td>
                    		<td>{{ $dbEmpresa->cantidad_empleados}}</td>
                    		<td>@if($dbEmpresa->tipo === 0)
                    				{{"Nacional"}}
                    			@else
                    				{{"Internacional"}}
                    			@endif
                    		</td>
                    		<td>
                    			<a href="{{ route('cargos_clientes.show', $dbEmpresa->id) }}" class="btn waves-light waves-effect white-text" style="margin-bottom: 1em;">
                    				<i class="material-icons left">list</i>Listar
                    			</a>
                    			<a href="{{ route('empresas.edit', $dbEmpresa->id) }}" class="btn waves-light waves-effect amber white-text" style="margin-bottom: 1em;">
                    				<i class="material-icons left">edit</i>Editar
                    			</a><br/>
                    			@if($dbEmpresa->rubro_id == 4)
                        			<a href="#" class="btn waves-light waves-effect amber white-text" style="margin-bottom: 1em;" id="select_encuesta">
                    					<i class="material-icons left">dashboard</i>Reportes
                    				</a>                    			
                    			@else
	                    			<a href="{{ route('encuestas.show', $dbEmpresa->id) }}" class="btn waves-light waves-effect amber white-text" style="margin-bottom: 1em;">
	                    				<i class="material-icons left">dashboard</i>Reportes
	                    			</a>                    			
                    			@endif

								<a href="{{ route('encuestas.update', $dbEncuesta->id) }}" class="btn waves-effect waves-light lighten-1 red white-text" style="margin-bottom: 1em;" onclick="update_row({{$dbEncuesta->id}})">
									<i class="material-icons left">exit_to_app</i>Finalizar
								</a>
								<form id="update-form{{$dbEncuesta->id}}" action="{{ route('encuestas.update', $dbEncuesta->id) }}" method="POST" style="display: none;">
					                {{ csrf_field() }}
					                {{ method_field('PUT') }}
					            </form>
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
			<h5>Eligir Encuesta</h5>
				<a class="waves-light waves-effect btn lime darken-3" id="encuesta-anterior" >
					{{$dbEncuestaAnt->periodo}}
				</a>	
				<a class="waves-light waves-effect btn green" id="encuesta-actual" periodo="{{$dbEncuesta->periodo}}">
					{{$dbEncuesta->periodo}}
				</a>	
				<input type="hidden" id="periodo_ant" name="periodo_anterior" value="{{$dbEncuestaAnt->periodo}}"/>
				<input type="hidden" id="periodo" name="periodo" value="{{$dbEncuesta->periodo}}"/>
		</div>
		<div class="modal-footer">
			<a class="waves-light waves-effect btn " id="close-modal">Cerrar</a>
		</div>
	</div>

@stop
@push('scripts')
	<script type="text/javascript">
		function update_row(row){
			event.preventDefault(); 
			if (confirm('Seguro que desea cerrar la Encuesta?')){
				document.getElementById('update-form'+row).submit();
			} else {
				return false;
			}
		}

		$("#select_encuesta").click(function(){
			$("#modal-options").openModal();	
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
	</script>
@endpush