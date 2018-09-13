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
						<div class="input-field col s6">
							<select id="rubro_id"  name="rubro_id">
								@foreach($rubros as $id => $descripcion)
									@if ($id == $dbData->rubro_id)
										<option value = {{$id}} selected>{{$descripcion}}</option>
									@else
										<option value = {{$id}}>{{$descripcion}}</option>		
									@endif	
								
								@endforeach
							</select>
							<label for="rubro_id" class="active">Rubro</label>
						</div>																	
						<div class="input-field col s6">
							<select id="periodo"  name="periodo">
								@foreach($periodos as $id => $descripcion)
									@if ($id == $dbData->periodo)
										<option value = {{$id}} selected>{{$descripcion}}</option>
									@else
										<option value = {{$id}}>{{$descripcion}}</option>		
									@endif	
								@endforeach
							</select>
							<label for="periodo" class="active">Periodo</label>
						</div>																	
					</div>
					<div class="row">
						<div class="input-field col s6">
							<label for="cargos_emergentes" id="cargos_emergentes">Cargos Emergentes</label>
							<input type="number" name="cargos_emergentes" id="cargos_emergentes" class="validate"/>
						</div>

						<div class="input-field col s6">
							<label for="tipo_cambio" id="tipo_cambio">Tipo de Cambio</label>
							<input type="text" name="tipo_cambio" id="tipo_cambio" class="validate"/>
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