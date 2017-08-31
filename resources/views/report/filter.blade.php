@extends('report.layout')
@section('content')
	<div class="row">
		<div class="browser-window">
			<div class="top-bar">
	          <h4>Filtrar Cargo</h4>
	        </div>
	        <div class="content">
				<form class="col s12" action="{{route('reportes.cargos')}}" method="POST">
					<div class="row">
						<div class="input-field col s6">
							<select id="nivel_id"  name="nivel_id">
								<option>Elija una opción</option>
								@foreach($dbNiveles as $id => $descripcion)
									<option value = {{$id}}>{{$descripcion}}</option>
								@endforeach
							</select>
							<label for="nivel_id">Nivel</label>
						</div>																	
						<div class="input-field col s6">
							<select id="cargo_id"  name="cargo_id">
								<option>Elija una opción</option>
								@foreach($dbCargos as $id => $descripcion)
									<option value = {{$id}}>{{$descripcion}}</option>
								@endforeach
							</select>
							<label for="cargo_id">Area</label>
						</div>																	
						<input type="hidden" name="empresa_id" value="{{$dbEmpresa}}"/>
					</div>
					<div class="row">
						<div class="input-field">
							<label for="detalle" id="label-detalle">Descripción del Cargo</label>
							<textarea name="detalle" id="detalle" class="materialize-textarea"></textarea> 
						</div>
					</div>
					<input type="hidden" name="_token" value="{{{ csrf_token() }}}" />
					<button class="btn waves-effect waves-light" type="submit" name="submit">Reporte
    					<i class="material-icons left">insert_chart</i>
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
		$("#nivel_id").change(function(){
			var selectCargo = $("#cargo_id");
			var nivelId = $(this).val();
			selectCargo.empty();
			$.post('{{route('reportes.getcargos')}}', {"nivel_id": nivelId, "_token": "{{csrf_token()}}"}, 
				function(json){
					var data = $.map(json, function(text, id){
                    	return {text:text, id:id};
                    });
					selectCargo.append($("<option></option>").attr("value", 0).text("Elija una Opción"));            		
            		for(i = 0; i < data.length; i++){
            			selectCargo.append(
              			$("<option></option>").attr("value", data[i].id)
                                    .text(data[i].text));
					}

					$("select").select2();
				}
			);
		});

		$("#cargo_id").change(function(){
		  var id = $(this).val();
		  $.post('{{route('cargos.detalle')}}', {id: id, "_token": "{{csrf_token()}}"}, 
		  	function(json) {
			  $('#detalle').val(json);
			  $('#detalle').trigger('autoresize');
			  $('#label-detalle').addClass( "active" );
		  	}
		  ); 
		});
		
	</script>
@endpush