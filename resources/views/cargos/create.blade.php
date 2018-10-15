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
							<label for="descripcion">Cargo (En español)</label>
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
							<label for="rubro_id" class="active">Area</label>
						</div>																	
						<div class="input-field col s6">
							<select id="nivel_id"  name="nivel_id">
								<option>Elija una opción</option>
								@foreach($dbNivel as $id => $descripcion)
									<option value = {{$id}}>{{$descripcion}}</option>
								@endforeach
							</select>
							<label for="nivel_id" class="active">Nivel</label>
						</div>																	
					</div>
					<div class="row">
						<div class="input-field col s12">
							<label for="detalle" id="label-detalle">Descripción del Cargo (en español)</label>
							<textarea name="detalle" id="detalle" class="materialize-textarea"></textarea> 
						</div>
					</div>
					<div class="row">
						<label>
							<input name="is_temporal" id="is_temporal" value="0" type="checkbox" class="filled-in"  />
							 <span>Temporal</span>
						</label>
					</div> 
					<div class="row">
						<div class="input-field col s12">
							<input id="descripcion_en" type="text" class="validate" name="descripcion_en" >
							<label for="descripcion_en">Cargo (en inglés)</label>
						</div>					
					</div>
					<div class="row">
						<div class="input-field col s12">
							<label for="detalle_en" id="label-detalle_en">Descripción del Cargo (en inglés)</label>
							<textarea name="detalle_en" id="detalle_en" class="materialize-textarea"></textarea> 
						</div>
					</div>
					<div class="row">
						<div class="input-field col s12">
							<select id="rubros"  name="rubros[]" multiple>
								@foreach ($dbRubros as $id => $descripcion)
									<option value = {{$id}}>{{$descripcion}}</option>	
								@endforeach
											
							</select>
							<label for="rubros" class="active">Rubros (para los que aplica el cargo)</label>
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