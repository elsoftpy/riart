@extends('layout')
@section('content')
	<div class="row">
		<div class="browser-window">
			<div class="top-bar">
	          <h4>Crear Nuevo Segmento</h4>
	        </div>
	        <div class="content">
				<form class="col s12" action="{{route('sub_rubros.store')}}" method="POST">
					<div class="row">
						<div class="input-field col s6">
							<input id="descripcion" type="text" class="validate" name="descripcion" >
							<label for="descripcion">Descripción</label>
						</div>					
						<div class="input-field col s6">
							<select id="rubro_id"  name="rubro_id">
								<option>Elija una opción</option>
								@foreach($dbRubro as $id => $descripcion)
									<option value = {{$id}}>{{$descripcion}}</option>
								@endforeach
							</select>
							<label for="rubro_id" class="active">Rubro</label>
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
		$(function(){
			$("select").select2();
		});

		
	</script>
@endpush