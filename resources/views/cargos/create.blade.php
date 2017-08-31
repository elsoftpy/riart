@extends('layout')
@section('content')
	<div class="row">
		<div class="browser-window">
			<div class="top-bar">
	          <h4>Crear Nuevo Cargo</h4>
	        </div>
	        <div class="content">
				<form class="col s12" action="{{route('cargos.store')}}" method="POST">
					<div class="row">
						<div class="input-field col s12">
							<input id="descripcion" type="text" class="validate" name="descripcion" >
							<label for="nombres">Descripción</label>
						</div>					
					</div>
					<div class="row">
						<div class="input-field col s6">
							<select id="area_id"  name="area_id">
								<option>Elija una opción</option>
								@foreach($dbArea as $id => $descripcion)
									<option value = {{$id}}>{{$descripcion}}</option>
								@endforeach
							</select>
							<label for="rubro_id">Area</label>
						</div>																	
						<div class="input-field col s6">
							<select id="nivel_id"  name="nivel_id">
								<option>Elija una opción</option>
								@foreach($dbNivel as $id => $descripcion)
									<option value = {{$id}}>{{$descripcion}}</option>
								@endforeach
							</select>
							<label for="nivel_id">Nivel</label>
						</div>																	
					</div>
					<div class="row">
						<div class="input-field">
							<label for="detalle" id="label-detalle">Descripción del Cargo</label>
							<textarea name="detalle" id="detalle" class="materialize-textarea"></textarea> 
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
			$("select").material_select();
		});

		
	</script>
@endpush