@extends('layout')
@section('content')
	<div class="row">
		<div class="browser-window">
			<div class="top-bar">
	          <h4>Editar Cargo</h4>
	        </div>
	        <div class="content">
				<form class="col s12" action="{{route('cargos.update', $dbData->id)}}" method="POST">
					<div class="row">
						<div class="input-field col s12">
							<input id="descripcion" type="text" class="validate" name="descripcion" value="{{$dbData->descripcion}}" >
							<label for="nombres">Descripción</label>
						</div>					
					</div>
					<div class="row">
						<div class="input-field col s6">
							<select id="area_id"  name="area_id">
								<option>Elija una opción</option>
								@foreach($dbArea as $id => $descripcion)
									@if($id == $dbData->area_id)
										<option value = {{$id}} selected>{{$descripcion}}</option>
									@else
										<option value = {{$id}}>{{$descripcion}}</option>
									@endif
									
								@endforeach
							</select>
							<label for="rubro_id">Area</label>
						</div>																	
						<div class="input-field col s6">
							<select id="nivel_id"  name="nivel_id">
								<option>Elija una opción</option>
								@foreach($dbNivel as $id => $descripcion)
									@if($id == $dbData->nivel_id)
										<option value = {{$id}} selected>{{$descripcion}}</option>
									@else
										<option value = {{$id}}>{{$descripcion}}</option>
									@endif

								@endforeach
							</select>
							<label for="nivel_id">Nivel</label>
						</div>																	
					</div>
					<div class="row">
						<div class="input-field">
							<label for="detalle" id="label-detalle">Descripción del Cargo</label>
							<textarea name="detalle" id="detalle" class="materialize-textarea">{{$dbData->detalle}}</textarea> 
						</div>
					</div>
					<div class="row">
						@if ($dbData->is_temporal == "1")
							<input name="is_temporal" id="is_temporal" checked="checked" type="checkbox" class="with-gap"  />
							<label for="is_temporal">Temporal</label>
						@else	
							<input name="is_temporal" id="is_temporal" type="checkbox" class="with-gap"  />
							<label for="is_temporal">Temporal</label>
						@endif
					</div> 
					<input type="hidden" name="_token" value="{{{ csrf_token() }}}" />
					{{ method_field('PUT') }}
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