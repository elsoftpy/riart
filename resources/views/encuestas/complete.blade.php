@extends('layout')
@section('content')
	<div class="content">
		<form  name="cuestionario" id="cuestionario" action="{{route('encuestas.save', $token)}}" method="POST" >
			<div class="row">
				<div class="hoverable col s12">
					@foreach ($dbDetalle as $key=>$value)
						<div class="row">
							<p style="padding-left: 1em;">
								<strong>{{$value->nro_pregunta}} ) {{$value->pregunta}}</strong>
							</p>
						</div>

						@if($value->abierta == 'S')
							<div class="input-field col-s12">
								<textarea class="materialize-textarea" name="pregunta_{{$value->id}}_{{$value->abierta}}"></textarea>	
							</div>
							
							
						@else
							@if( count($value->opcionesRespuesta) == 0)
								<input name="pregunta_{{$value->id}}_{{$value->abierta}}" id="btn5" type="radio" value="5"/>
								<label class="black-text" for="btn5"><strong> Muy de acuerdo </strong></label>
								<input name="pregunta_{{$value->id}}_{{$value->abierta}}" id="btn4" type="radio" value="4"/>
								<label class="black-text" for="btn4"><strong> De acuerdo </strong></label>					
								<input name="pregunta_{{$value->id}}_{{$value->abierta}}" id="btn3" type="radio" value="3"/>
								<label class="black-text" for="btn3"><strong> Ni de acuerdo/ Ni en desacuerdo </strong></label>						
								<input name="pregunta_{{$value->id}}_{{$value->abierta}}" id="btn2" type="radio" value="2"/>
								<label class="black-text" for="btn2"><strong> En Desacuerdo </strong></label>									
								<input name="pregunta_{{$value->id}}_{{$value->abierta}}" id="btn1" type="radio" value="1"/>
								<label class="black-text" for="btn1"><strong> Muy en Desacuerdo </strong></label>
							@else	
								@foreach( $value->opcionesRespuesta as $key => $option)
									@if ($option->control == 'R')
										@foreach($arrayEscala[$option->id] as $key => $escala)
										  	<input name="pregunta" id="btn{{$escala['id']}}" type="radio" value="{{$escala['calificacion']}}" />
											<label class="black-text" for="btn{{$escala['id']}}" style="margin-right: 1em;"><strong> {{$escala['label']}} </strong>
											</label> 

										@endforeach									
									@elseif($option->control == 'C')
										<input name="pregunta_{{$value->id}}_{{$value->abierta}}_{{$option->id}}" id="check{{$option->id}}" type="checkbox"/>
										<label class="black-text" style="margin-right: 1em;" for="check{{$option->id}}"><strong> {{$option->label}} </strong></label>
									@endif
								@endforeach
							@endif										

						@endif

					@endforeach			
				</div>
			</div>	

			<input type="hidden" name="_token" value="{{{ csrf_token() }}}">
			
			<div class="row">
				<div class="button-group">
					<button class="btn waves-effect waves-light right" type="submit" name="submit">Guardar
	    					<i class="material-icons left">save</i>
	      				</button>
				</div>
			</div>
		</form>
	</div>
@endsection
