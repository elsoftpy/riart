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
							<label for="descripcion">Cargo (Español)</label>
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
							<label for="rubro_id" class="active">Area</label>
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
							<label for="nivel_id" class="active">Nivel</label>
						</div>																	
					</div>
					<div class="row">
						<div class="input-field col s12">
							<label for="detalle" id="label-detalle">Descripción del Cargo (en español)</label>
							<textarea name="detalle" id="detalle" class="materialize-textarea">{{$dbData->detalle}}</textarea> 
						</div>
					</div>
					<div class="row col s12">
						@if ($dbData->is_temporal == "1")
							<label>
								<input name="is_temporal" id="is_temporal" checked="checked" type="checkbox" class="filled-in"/>
								<span>Temporal</span>
							</label>
						@else	
							<label>
								<input name="is_temporal" id="is_temporal" type="checkbox" class="filled-in"/>
								<span>Temporal</span>
							</label>
						@endif
					</div> 
					<div class="row">
						<div class="input-field col s12">
							<input id="descripcion_en" type="text" class="validate" name="descripcion_en" value="{{$dbData->cargoEn->descripcion}}" >
							<label for="descripcion_en">Cargo (Inglés)</label>
						</div>					
					</div>
					<div class="row">
						<div class="input-field col s12">
							<label for="detalle_en" id="label-detalle_en">Descripción del Cargo (Inglés)</label>
							<textarea name="detalle_en" id="detalle_en" class="materialize-textarea">{{$dbData->cargoEn->detalle}}</textarea> 
						</div>
					</div>
					<div class="row">
						<div class="input-field col s12">
							<select id="rubros"  name="rubros[]" multiple>
								@foreach($dbRubros as $id => $descripcion)
										@php
											$cargoRubro = $dbData->cargosRubro->where('cargo_id', $dbData->id)
																		 	  ->where('rubro_id', $id)
																		 	  ->first();
											if($cargoRubro){
												$found = true;
											}else{
												$found = false;
											}
										@endphp			
										@if ($found)
											<option value = {{$id}} selected="selected">{{$descripcion}}</option>	
										@else
											<option value = {{$id}}>{{$descripcion}}</option>			
										@endif

								@endforeach
							</select>
							<label for="rubros" class="active">Rubros (para los que aplica el cargo)</label>
						</div>
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
			$("select").select2();
		});

		
	</script>
@endpush