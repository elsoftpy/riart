@extends('layout')
@section('content')
	<div class="row">
		<div class="browser-window">
			<div class="top-bar">
	          <h4>Descargar Archivo</h4>
	        </div>
	        <div class="content">
				<form class="col s12" action="{{route('import_export.download')}}" method="POST" id="download_form">
					<div class="row">
						<div class="input-field col s8">
							<select id="empresa_id"  name="empresa_id">
								<option>Elija una opción</option>
								@foreach($empresas as $id => $descripcion)
									<option value = {{$id}}>{{$descripcion}}</option>
								@endforeach
							</select>
							<label for="empresa_id" class="active">Empresa</label>
						</div>																	
						<div class="input-field col s4">						
							<select id="periodo"  name="periodo">
								<option>Elija una opción</option>
								@foreach($periodos as $id => $descripcion)
									<option value = {{$id}}>{{$descripcion}}</option>
								@endforeach
							</select>
							<label for="periodo" class="active">Periodo</label>
						</div>
					</div>
					<input type="hidden" name="_token" value="{{{ csrf_token() }}}" />
					<button class="btn waves-effect waves-light" type="submit" name="submit_down" id="btn_down">Bajar
    					<i class="material-icons left">cloud_download</i>
      				</button>
				</form>
	        </div>
		</div>
	</div>
	<div class="row">
		<div class="browser-window">
			<div class="top-bar">
				<h4>Subir Archivo</h4>
			</div>
			<div class="content">
				 <form action="{{route('import_export.upload')}}" method="POST" id="upload_form"  enctype="multipart/form-data">
				    <div class="row">
					    <div class="file-field input-field">
					      <div class="btn">
					        <span>Archivo<i class="material-icons left">attach_file</i></span>
					        <input type="file" name="file">
					      </div>
					      <div class="file-path-wrapper">
					        <input class="file-path validate" type="text">
					        <input type="hidden" name="_token" value="{{{ csrf_token() }}}" />
					      </div>
					    </div>
				    </div>
				    <div class="row">
						<button class="btn waves-effect waves-light" type="submit" name="submit_up" id="btn_up">Subir
	    					<i class="material-icons left">cloud_upload</i>
	      				</button>
				    </div>

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

		$("#empresa_id").change(function(){
			var selectPeriodo = $("#periodo");
			var empresaId = $(this).val();
			selectPeriodo.empty();
			$.post('{{route('import_export.periodos')}}', {"empresa_id": empresaId, "_token": "{{csrf_token()}}"}, 
				function(json){
					var data = $.map(json, function(text, id){
                    	return {text:id, id:text};
                    });
					selectPeriodo.append($("<option></option>").attr("value", 0).text("Elija una Opción"));            		
            		for(i = 0; i < data.length; i++){
            			selectPeriodo.append(
              			$("<option></option>").attr("value", data[i].id)
                                    .text(data[i].text));
					}

					$("select").select2();
				}
			);
		});
		console.log($("#toast").length);
		if($("#toast").length > 0){
			M.toast({html: 'Archivo Procesado'});	
		}
		
	</script>
@endpush