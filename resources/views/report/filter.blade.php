@extends('report.layout')
@section('content')
	<div class="row">
		<div class="browser-window">
			<div class="top-bar">
	          <h4>Filtrar Cargo</h4>
	        </div>
	        <div class="content" data-step="13" data-intro="">
				<form class="col s12" action="{{route('reportes.cargos')}}" method="POST" id="filter_form">
					<div class="row">
						<div class="input-field col s4">
							<select id="nivel_id"  name="nivel_id">
								<option>Elija una opción</option>
								@foreach($dbNiveles as $id => $descripcion)
									<option value = {{$id}}>{{$descripcion}}</option>
								@endforeach
							</select>
							<label for="nivel_id" class="active">Nivel</label>
						</div>																	
						<div class="input-field col s4" id="intro-cargo">
							<select id="cargo_id"  name="cargo_id">
								<option>Elija una opción</option>
								@foreach($dbCargos as $id => $descripcion)
									<option value = {{$id}}>{{$descripcion}}</option>
								@endforeach
							</select>
							<label for="cargo_id" class="active">Cargo</label>
						</div>													
						<input type="hidden" name="empresa_id" value="{{$dbEmpresa}}"/>
						<input type="hidden" name="moneda" value="local">
					</div>
					<div class="row">
						<div class="input-field">
							<label for="detalle" id="label-detalle">Descripción del Cargo</label>
							<textarea name="detalle" id="detalle" class="materialize-textarea"></textarea> 
						</div>
					</div>
					<input type="hidden" name="_token" value="{{{ csrf_token() }}}" />
					<button class="btn waves-effect waves-light" type="submit" name="submitbtn" id="intro-reporte">Reporte
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

			var test = RegExp('multipage=3', 'gi').test(window.location.search);

			var back = RegExp('multipage=back', 'gi').test(window.location.search);

	     	$("#intro-cargo").attr("data-step", "11").attr("data-intro", "<p class='intro-title'><strong>CARGO<strong></p>Click y escriba el cargo o palabra clave");

	     	$("#intro-reporte").attr("data-step", "12").attr("data-intro", "<p class='intro-title'><strong>BUSCAR POR CARGOS<strong></p>Click para visualizar resultados por cargo a través de palabras claves.").attr("data-position", "left");
			
			if (RegExp('multipage=true', 'gi').test(window.location.search)) {
			    tour.goToStepNumber(11).start();
		    }

		    if(back){
		    	tour.goToStepNumber(12).start();
		    }

	     	tour.onafterchange(function(step){
	      		if($(step).attr("data-step") == 13){
	      			$("#cargo_id").val("68");
				    $('<input />').attr('type', 'hidden')
				          .attr('name', "tour")
				          .attr('value', "true")
				          .appendTo('#filter_form');

	      			document.getElementById("filter_form").submit();
	      			tour.exit();	
	      		}
	      	});    		    
		});			

		$("#nivel_id").change(function(){
			var selectCargo = $("#cargo_id");
			var nivelId = $(this).val();
			selectCargo.empty();
			$.post('{{route('reportes.getcargos')}}', {"nivel_id": nivelId, "_token": "{{csrf_token()}}"}, 
				function(json){
					var data = $.map(json, function(text, id){
                    	return {text:id, id:text};
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
			  M.textareaAutoResize($('#detalle'));
			  $('#label-detalle').addClass( "active" );
		  	}
		  ); 
		});
		
	</script>
@endpush