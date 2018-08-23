@extends('layout')
@section('content')
	<div class="row">
		<div class="browser-window">
			<div class="top-bar">
	          <h4>Configurar nuevo periodo</h4>
	        </div>
	        <div class="content">
				<form class="col s12" action="{{route('admin_ficha.store')}}" method="POST">
					<div class="row">
						<div class="input-field col s6">
							<select id="rubro_id"  name="rubro_id">
								<option>Elija una opción</option>
								@foreach($rubros as $id => $descripcion)
									<option value = {{$id}}>{{$descripcion}}</option>
								@endforeach
							</select>
							<label for="rubro_id" class="active">Rubro</label>
						</div>																	
						<div class="input-field col s6">
							<select id="periodo"  name="periodo">
								<option>Elija una opción</option>
								@foreach($periodos as $id => $descripcion)
									<option value = {{$id}}>{{$descripcion}}</option>
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

			/*$("#tipo_cambio").inputmask("decimal", {
	          placeholder: "0",
	          digitsOptional: true,
	          radixPoint: ",",
	          groupSeparator: ".",
	          autoGroup: true,
	          allowPlus: false,
	          allowMinus: false,
	          clearMaskOnLostFocus: false,
	          removeMaskOnSubmit: true,
	          //autoUnmask: true,
			});*/

		});

		
	</script>
@endpush