@extends('layout')
@section('content')
	<div class="row">
		<div class="browser-window">
			<div class="top-bar">
	          <h4>Editar Area</h4>
	        </div>
	        <div class="content">
				<form class="col s12" action="{{route('rubros.update', $dbData->id)}}" method="POST">
					<div class="row">
						<div class="input-field col s6">
							<input id="descripcion" type="text" class="validate" name="descripcion" value="{{$dbData->descripcion}}">
							<label for="descripcion">Descripción</label>
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