@extends('layout')
@section('content')
	<div class="row">
		<div class="browser-window">
			<div class="top-bar">
	          <h4>Editar Empresa</h4>
	        </div>
	        <div class="content">
				<form class="col s12" action="{{route('empresas.update', $dbData)}}" method="POST">
					<div class="row">
						<div class="input-field col s12">
							<input id="descripcion" type="text" class="validate" name="descripcion" value="{{ $dbData->descripcion}}">
							<label for="nombres">Descripción</label>
						</div>					
					</div>
					<div class="row">
						<div class="input-field col s6">
							<select id="rubro_id"  name="rubro_id">
								@foreach($dbRubro as $id => $descripcion)
									@if ($dbData->rubro_id == $id)
										<option value = {{$id}} selected="selected">{{$descripcion}}</option>
									@else
										<option value = {{$id}}>{{$descripcion}}</option>
									@endif

								@endforeach
							</select>
							<label for="rubro_id" class="active">Rubro</label>
						</div>																	
						<div class="input-field col s6">
							<select id="sub_rubro_id"  name="sub_rubro_id">
								@foreach($dbSubRubro as $id => $descripcion)
									@if ($dbData->sub_rubro_id == $id)
										<option value = {{$id}} selected="selected">{{$descripcion}}</option>
									@else
										<option value = {{$id}}>{{$descripcion}}</option>
									@endif								
								@endforeach
							</select>
							<label for="sub_rubro_id" class="active">Sub Rubro</label>
						</div>																	


					</div>
					<div class="row">
						<div class="input-field col s4">
							<input id="cantidad_sucursales" type="number" class="validate" name="cantidad_sucursales" value="{{ $dbData->cantidad_sucursales}}">
							<label for="cantidad_sucursales">Cant. Suc.</label>
						</div>					
						<div class="input-field col s4">
							<input id="cantidad_empleados" type="number" class="validate" name="cantidad_empleados" value="{{ $dbData->cantidad_empleados}}">
							<label for="cantidad_empleados">Cant. Emp.</label>
						</div>					
						<div class="input-field col s4">
							<select id="tipo"  name="tipo">
								@if ($dbData->origen == 0)
									<option value="0" selected="selected">Nacional</option>
									<option value="1">Internacional</option>			
								@else
									<option value="0">Nacional</option>
									<option value="1" selected="selected">Internacional</option>
								@endif

							</select>
							<label for="tipo" class="active">Origen</label>
						</div>																	
					</div>
					@if (Auth::user()->username == 'admin')
						<div class="row col s6">
							<label for="listable">
								@if ($dbData->listable)
									<input type="checkbox" name="listable" id="listable" checked="checked">
								@else
									<input type="checkbox" name="listable" id="listable">	
								@endif
								<span>Listar en Panel de Empresas</span>
							</label>
						</div>
						<div class="row col s6">
							<label for="listable_beneficios">
								@if ($dbData->listable_beneficios)
									<input type="checkbox" name="listable_beneficios" id="listable_beneficios" checked="checked">
								@else
									<input type="checkbox" name="listable_beneficios" id="listable_beneficios">	
								@endif
								<span>Listar en Panel de Beneficios</span>
							</label>
						</div>

					@endif
					<div class="clearfix"></div>
				<!--	<div class="row">
						<div class="input-field col s12">
							 
							<label for="estado">Estado</label>
						</div>					
					</div>					-->
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
@push('scripts')
	<script type="text/javascript">
		$(function(){
			$("select").select2();
		});
		$("#tipo").val('{{$dbData->tipo}}');
		$("#rubro").val('{{$dbData->rubro_id}}');
		$("#sub_rubro").val('{{$dbData->sub_rubro_id}}');

	</script>
@endpush