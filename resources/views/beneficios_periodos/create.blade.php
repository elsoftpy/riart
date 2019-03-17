@extends('layout')
@section('content')
	<div class="row">
		<div class="browser-window">
			<div class="top-bar">
	          <h4>Configurar nuevo periodo</h4>
	        </div>
	        <div class="content">
				<form class="col s12" action="{{route('periodos_activos.store')}}" method="POST">
					<div class="row">
						<div class="input-field col s6">
							<select id="rubro_id"  name="rubro_id">
								@foreach($rubros as $id => $descripcion)
									<option value = {{$id}}>{{$descripcion}}</option>
								@endforeach
							</select>
							<label for="rubro_id" class="active">Rubro</label>
						</div>																	
						<div class="input-field col s6">
							<select id="periodo"  name="periodo">
								@foreach($periodos as $id => $descripcion)
									<option value = {{$id}}>{{$descripcion}}</option>
								@endforeach
							</select>
							<label for="periodo" class="active">Periodo</label>
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
		$("#rubro_id").change(function(){
			var selectPeriodo = $("#periodo");
			var rubroId = $(this).val();
			console.log(rubroId);
			selectPeriodo.empty();
			$.post('{{route('periodos_activos.periodos')}}', {"rubro_id": rubroId, "_token": "{{csrf_token()}}"}, 
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