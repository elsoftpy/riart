@extends('layout')
@section('content')
	<div class="row">
		<div class="browser-window">
			<div class="top-bar">
	          <h4>Crear Nuevo Usuario</h4>
	        </div>
	        <div class="content">
				<form class="col s12" action="{{route('usuarios.store')}}" method="POST">
					<div class="row">
						<div class="input-field col s12">
							<input id="username" type="text" class="validate" name="username">
							<label for="username">Nombre de Usuario</label>
						</div>					
					</div>
					<div class="row">
						<div class="input-field col s12">
							<input id="password" type="password" class="validate" name="password">
							<label for="password">Contraseña</label>
						</div>					
					</div>
					<div class="row">
						<div class="input-field col s12">
							<input id="email" type="email" class="validate" name="email">
							<label for="email">Email</label>
						</div>					
					</div>

					<div class="row">
						<div class="input-field col s6">
							<select name="empresa_id" id="empresa_id">
								<option value="">Elija una opción</option>
								@foreach($dbEmpresas as $id=>$descripcion)
									<option value="{{$id}}">{{$descripcion}}</option>
								@endforeach
							</select>
							<label for="empresa_id">Empresa</label>
						</div>

						<div class="input-field col s6">
							<select id="is_admin" name="is_admin">
								<option value="0">Cliente</option>
								<option value="1">Consultora</option>
							</select>
							<label for="is_admin">Tipo de Usuario</label>
						</div>
					</div>
					<input type="hidden" name="_token" value="{{{ csrf_token() }}}" />
					<button class="btn waves-effect waves-light" type="submit" name="submit">Guardar
    					<i class="material-icons left">save</i>
      				</button>
				</form>
	        </div>
		</div>
	</div>
@stop
@push('scripts')
	<script type="text/javascript">
		$(document).ready(function() {
   			 $('select').material_select();
		});
	</script>
@endpush