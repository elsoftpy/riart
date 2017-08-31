@extends('layout')

@section('content')
	<div class="row">
		<div class="col l3">
			<a href="{{ route('usuarios.create') }}" class="btn waves-effect waves-light lighten-1 white-text"><i class="material-icons left">add</i>Usuario</a>
		</div>
	</div>	
		<div class="row">
			<div class="browser-window">
				<div class="top-bar">
                  <h4>Listado de Usuarios</h4>
                </div>
                <div class="content">
                	<table id="Listado" class="highlight">
                		<thead>
	                      <tr>
	                      	 <th>Id</th>
	                      	 <th>Nombre</th>
	                      	 <th>Empresa</th>
	                      	 <th>Email</th>
	                      	 <th>Rol</th>
	                      	 <th>Estado</th>
	                      	 <th></th>
							 <th></th>
	                      </tr>
	                    </thead>
	                    <tbody>
	                    	@foreach($dbData as $est) 
	                    		<tr>
		                    		<td>{{ $est->id }}</td>
		                    		<td>{{ $est->username}}</td>
		                    		<td>{{ $est->empresa->descripcion}}</td>
		                    		<td>{{ $est->email}}</td>
		                    		<td>
		                    			@if ($est->is_admin)
		                    				{{"Consultora"}}
		                    			@else
		                    				{{"Cliente"}}
		                    			@endif
		                    		</td>	
		                    		<td>{{ $est->estado}}</td>
		                    		<td>
		                    			<a href="{{ route('usuarios.edit', $est->id) }}" class="btn waves-light waves-effect amber white-text ">
		                    				<i class="material-icons left">edit</i> Editar
		                    			</a>
		                    			<a href="{{ route('usuarios.edit', $est->id) }}" class="btn waves-light waves-effect white-text ">
		                    				<i class="material-icons left">email</i> Email
		                    			</a>
										<a href="{{ route('usuarios.destroy', $est->id) }}" class="btn waves-effect waves-light lighten-1 red white-text" onclick="delete_row({{$est->id}})">
											<i class="material-icons left">delete</i>Borrar
										</a>
				                        <form id="delete-form{{$est->id}}" action="{{ route('usuarios.destroy', $est->id) }}" method="POST" style="display: none;">
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