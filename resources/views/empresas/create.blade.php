@extends('layout')
@section('content')
	<div class="row">
		<div class="browser-window">
			<div class="top-bar">
	          <h4>Crear Nueva Empresa</h4>
	        </div>
	        <div class="content">
				<form class="col s12" action="{{route('empresas.store')}}" method="POST">
					<div class="row">
						<div class="input-field col s12">
							<input id="descripcion" type="text" class="validate" name="descripcion" >
							<label for="descripcion">Descripción</label>
						</div>					
					</div>
					<div class="row">
						<div class="input-field col s6">
							<select id="rubro_id"  name="rubro_id">
								<option>Elija una opción</option>
								@foreach($dbRubro as $id => $descripcion)
									<option value = {{$id}}>{{$descripcion}}</option>
								@endforeach
							</select>
							<label for="rubro_id" class="active">Rubro</label>
						</div>																	
						<div class="input-field col s6">
							<select id="sub_rubro_id"  name="sub_rubro_id">
								<option>Elija una opción</option>
								@foreach($dbSubRubro as $id => $descripcion)
									<option value = {{$id}}>{{$descripcion}}</option>
								@endforeach
							</select>
							<label for="sub_rubro_id" class="active">Sub Rubro</label>
						</div>																	


					</div>
					<div class="row">
						<div class="input-field col s4">
							<input id="cantidad_sucursales" type="number" class="validate" name="cantidad_sucursales">
							<label for="cantidad_sucursales">Cant. Suc.</label>
						</div>					
						<div class="input-field col s4">
							<input id="cantidad_empleados" type="number" class="validate" name="cantidad_empleados">
							<label for="cantidad_empleados">Cant. Empleados</label>
						</div>					
						<div class="input-field col s4">
							<select id="tipo"  name="tipo">
								<option>Elija una opción</option>
								<option value="0">Nacional</option>
								<option value="1">Internacional</option>
							</select>
							<label for="tipo" class="active">Origen</label>
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