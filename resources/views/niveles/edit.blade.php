@extends('layout')
@section('content')
	<div class="row">
		<div class="browser-window">
			<div class="top-bar">
	          <h4>Editar Nivel</h4>
	        </div>
	        <div class="content">
				<form class="col s12" action="{{route('niveles.update', $dbData->id)}}" method="POST">
					<div class="row">
						<div class="input-field col s6">
							<input id="descripcion" type="text" class="validate" name="descripcion" value="{{$dbData->descripcion}}">
							<label for="descripcion">Descripción (En español)</label>
						</div>
						<div class="input-field col s6">
							@if ($dbData->nivelEn)
								<input id="descripcion_en" type="text" class="validate" name="descripcion_en" value="{{$dbData->nivelEn->descripcion}}">	
							@else
								<input id="descripcion_en" type="text" class="validate" name="descripcion_en">	
							@endif
							
							<label for="descripcion_en">Descripción (en inglés)</label>
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