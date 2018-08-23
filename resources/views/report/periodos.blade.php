@extends('layout')
@section('content')
	<div class="row">
		<div class="browser-window">
			<div class="top-bar">
	          <h4>Filtrar Periodo</h4>
	        </div>
	        <div class="content">
				<form class="col s12" action="{{route('resultados.excel')}}" method="POST">
					<div class="row">
						<div class="input-field col s6">
							<div class="row col s12">
								<label for="periodo">Periodos</label>	
							</div>
							
							<select id="periodo"  name="periodo">
								<option>Elija una opción</option>
								@foreach($dbData as $key => $data)
									<option value = "{{$data->periodo_rubro_id}}">{{$data->periodo}}</option>
								@endforeach
							</select>
							
						</div>			
					</div>
					<input type="hidden" name="_token" value="{{{ csrf_token() }}}" />
					<button class="btn waves-effect waves-light" type="submit" name="submit">Excel
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
		
	</script>
@endpush