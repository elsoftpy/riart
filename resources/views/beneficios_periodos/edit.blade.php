@extends('layout')
@section('content')
	<div class="row">
		<div class="browser-window">
			<div class="top-bar">
	          <h4>Editar Ficha</h4>
	        </div>
	        <div class="content">
				<form class="col s12" action="{{route('periodos_activos.update', $dbData->id)}}" method="POST">
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
						<div class="input-field col s4">
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
						<div class="input-field col s2">
								<label for="activo">
										@if ($dbData->activo)
											<input type="checkbox" name="activo" id="activo" checked="checked">
										@else
											<input type="checkbox" name="activo" id="activo">	
										@endif
										<span>Activo</span>
									</label>
						</div>

					</div>
					<input type="hidden" name="_token" value="{{{ csrf_token() }}}" />
					{{ method_field('PUT') }}
					<div class="row">
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
			//$("select").select2();
			$("select").select2();
			//updatePeriodo();
		});

		$("#rubro_id").change(function(){
			updatePeriodo();
		});

		function updatePeriodo(){
			var selectPeriodo = $("#periodo");
			var rubroId = $("#rubro_id").val();
			selectPeriodo.empty();
			$.post('{{route('file_attachment.periodos')}}', {"rubro_id": rubroId, "_token": "{{csrf_token()}}"}, 
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
		}

		$("#emergentes").click(function(e){
			e.preventDefault();
			var rubroId = $("#rubro_id").val();
			var periodo = $("#periodo").val();
			$.post("{{route('admin.ficha.contar')}}", {"rubro_id": rubroId, "periodo": periodo, "_token": "{{csrf_token()}}"},
			function(json){
				$("#cargos_emergentes").val(json);
				$("#emergentes_label").addClass("active");
			});
		});

		
	</script>
@endpush