@extends('layout')
@section('content')
	<div class="row">
		<div class="browser-window">
			<div class="top-bar">
				<h4>Subir Archivo</h4>
			</div>
			<div class="content">
				 <form action="{{route('file_attachment.upload')}}" method="POST" id="upload_form"  enctype="multipart/form-data">
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
						<div class="file-field input-field col s6">
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
	@if ($errorUploading)
	<div id="toast_error"></div>
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

		if($("#toast").length > 0){
			M.toast({html: 'Archivo Procesado'});	
		}

		if($("#toast_error").length > 0){
			M.toast({html: 'Error al subir archivo'});
		}
		
	</script>
@endpush