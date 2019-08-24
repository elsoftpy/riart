@extends('layout')
@section('content')
	<div class="row">
		<div class="browser-window">
			<div class="top-bar">
	          <h4>Editar Segmento</h4>
	        </div>
	        <div class="content">
				<form class="col s12" action="{{route('sub_rubros.update', $dbData)}}" method="POST">
					<div class="row">
						<div class="input-field col s6">
							<input id="descripcion" type="text" class="validate" name="descripcion" value="{{ $dbData->descripcion}}">
							<label for="descripcion">Descripción</label>
						</div>				
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
							<label for="rubro_id" class="active">@lang('editEmpresa.label_club')</label>
						</div>																																
					</div>
					<div class="clearfix"></div>
					<div class="row">
						<input type="hidden" name="_token" value="{{{ csrf_token() }}}" />
						{{ method_field('PUT') }}
						<button class="btn waves-effect waves-light" type="submit" name="submit">@lang('editEmpresa.button_save')
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