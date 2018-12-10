@extends('layout')
@section('content')
	<div class="row">
		<div class="browser-window">
			<div class="top-bar">
	          <h4>Elegir Empresa</h4>
	        </div>
	        <div class="content">
				<form class="col s12" id="realForm" action="{{route('reportes.cargos')}}" method="POST">
					<div class="row">
						<div class="col s12">
							<label>Empresa</label>
						</div>
						<div class="input-field col s6">
							<select id="empresa_id"  name="empresa_id">
								<option>Elija una opción</option>
								@foreach($dbEmpresas as $id => $descripcion)
									<option value = {{$id}}>{{$descripcion}}</option>
								@endforeach
							</select>
						                                                                                                                                                                                 </div>					
						<div class="input-field col s6">
							<select id="periodo"  name="periodo">
								<option>Elija una opción...</option>
							</select>
							<label for="periodo" class="active">Periodo</label>
						</div>
					</div>
					<div class="row">
						<div class="col s12">
							<label>Cargo Oficial</label>
						</div>
						<div class="input-field col s6">
							<select id="cargo_id"  name="cargo_id">
								<option>Elija una opción</option>
								@foreach($dbCargos as $id => $descripcion)
									<option value = {{$id}}>{{$descripcion}}</option>
								@endforeach
							</select>
						</div>					

					</div>
					<input type="hidden" name="_token" value="{{{ csrf_token() }}}" />
					<div class="button-group">
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
	<script>
  		$(document).ready(function() {
   			$('select').select2();
		});

		$("#empresa_id").change(function(){
			var selectPeriodo = $("#periodo");
			var empresaId = $(this).val();
			selectPeriodo.empty();
			$.post('{{route('admin.reporte.filter.periodos')}}', {"empresa_id": empresaId, "_token": "{{csrf_token()}}"}, 
				function(json){
					var data = $.map(json, function(text, id){
                    	return {text:text, id:id};
                    });
            		for(i = 0; i < data.length; i++){
            			selectPeriodo.append(
              			$("<option></option>").attr("value", data[i].id)
                                    		  .text(data[i].text));
					}

					$("select").select2();
				}
			);
		});

	</script>

@endpush