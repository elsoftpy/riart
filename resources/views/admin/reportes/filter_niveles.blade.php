@extends('layout')
@section('content')
	<div class="row">
		<div class="browser-window">
			<div class="top-bar">
	          <h4>Reporte de Niveles</h4>
	        </div>
	        <div class="content">
				<form class="col s12" action="{{route('reportes.nivelesClubExcel')}}" method="POST">
					<div class="row">
						<div class="input-field col s4">
							<select id="rubro_id"  name="rubro_id">
								@foreach($rubros as $id => $descripcion)
									<option value = {{$id}}>{{$descripcion}}</option>
								@endforeach
							</select>
							<label for="rubro_id" class="active">Rubro</label>
						</div>																	
						<div class="input-field col s4">
							<select id="periodo"  name="periodo">
								@foreach($periodos as $id => $descripcion)
									<option value = {{$id}}>{{$descripcion}}</option>
								@endforeach
							</select>
							<label for="periodo" class="active">Periodo</label>
						</div>
						<div class="input-field col s4">
							<a href="{{route('reportes.update_niveles_table')}}" class="btn waves-effect waves-light" id="actulizar">
								Actualizar Tabla
								<i class="material-icons left">update</i>
							</a>
						</div>																	
					</div>
					<input type="hidden" name="_token" value="{{{ csrf_token() }}}" />
					<button class="btn waves-effect waves-light" type="submit" name="submit">Excel
    					<i class="material-icons left">save</i>
      				</button>
				</form>
	        </div>
		</div>
	</div>
	@if($toast)
		<div id="toast"></div>
	@endif

@stop
@push('scripts')
	<script type="text/javascript">
		$(function(){
			$("select").select2();
		});
		$("#rubro_id").change(function(){
			var selectPeriodo = $("#periodo");
			var rubroId = $(this).val();
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
		});

		$("#actualizar").click(function(e){
			e.preventDefault();
		});
		if($("#toast").length > 0){
			M.toast({html: 'Tabla Actualizada'});	
		}
	</script>
@endpush