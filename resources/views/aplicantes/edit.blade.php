@extends('layout')
@section('content')
	<div class="row">
		<div class="browser-window">
			<div class="top-bar">
	          <h4>Editar Persona</h4>
	        </div>
	        <div class="content">
				<form class="col s12" action="{{route('personas.update', $dbData)}}" method="POST">
					<div class="row">
						<div class="input-field col s6">
							<input id="nombres" type="text" class="validate" name="nombres" value="{{ $dbData->nombres}}">
							<label for="nombres">Nombres</label>
						</div>					
						<div class="input-field col s6">
							<input id="apellidos" type="text" class="validate" name="apellidos" value="{{ $dbData->apellidos}}">
							<label for="apellidos">Apellidos</label>
						</div>											
					</div>
					<div class="row">
						<div class="input-field col s12">
							<input id="email" type="email" class="validate" name="email" value="{{ $dbData->email}}">
							<label for="nombres">Email</label>
						</div>					
					</div>
					<div class="row">
						<div class="input-field col s12">
							<input id="estado" type="checkbox" name="estado" @if ($estado) {{{ 'checked = "checked"' }}} @endif>
							<label for="estado">Estado</label>
						</div>					
					</div>					
					<div class="row">
						<input type="hidden" name="_token" value="{{{ csrf_token() }}}" />
						{{ method_field('PUT') }}
						<button class="btn waves-effect waves-light" type="submit" name="submit">Guardar
	    					<i class="material-icons left">save</i>
	      				</button>
					</div>

				</form>
	        </div>
		</div>
	</div>
@stop