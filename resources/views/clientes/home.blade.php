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
                    			<a href="{{ route('encuestas.show', $dbEmpresa->id) }}" class="btn waves-light waves-effect amber white-text" style="margin-bottom: 1em;">
                    				<i class="material-icons left">dashboard</i>Reportes
                    			</a>                    			
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
	</script>
@endpush