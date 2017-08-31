@extends('layout')
@section('content')
	<div class="content">
		<form  name="cuestionario" id="cuestionario">
			<div class="row">
				<div class="hoverable col s12">
					@foreach ($dbData as $value)
						@if($value->abierta == 'S')
							<div class="row margin-left-1em">
								<p style="padding-left: 1em;">
									<strong>{{$value->index}} ) {{$value->pregunta}}</strong>
								</p>

								
							</div>
							<div class="input-field col-s12">
								<textarea class="materialize-textarea"></textarea>	
							</div>
							
							
						@else
							<div class="row" >
								<p style="padding-left: 1em;">
									<strong>{{$value->index}} ) {{$value->pregunta}}</strong>
								</p>
							</div>

							@if( count($value->opciones) == 0)
								<input name="pregunta" id="btn5" type="radio" value="5"/>
								<label class="black-text" for="btn5"><strong> Muy de acuerdo </strong></label>
								<input name="pregunta" id="btn4" type="radio" value="4"/>
								<label class="black-text" for="btn4"><strong> De acuerdo </strong></label>					
								<input name="pregunta" id="btn3" type="radio" value="3"/>
								<label class="black-text" for="btn3"><strong> Ni de acuerdo/ Ni en desacuerdo </strong></label>						
								<input name="pregunta" id="btn2" type="radio" value="2"/>
								<label class="black-text" for="btn2"><strong> En Desacuerdo </strong></label>									
								<input name="pregunta" id="btn1" type="radio" value="1"/>
								<label class="black-text" for="btn1"><strong> Muy en Desacuerdo </strong></label>
							@else	
								@foreach( $value->opciones as $key => $option)
									@if ($option->control == 'R')
										<input name="pregunta" id="btn{{$key+1}}" type="radio" value="{{$key+1}}"/>
										<label class="black-text" for="btn{{$key+1}}" style="margin-right: 10em !important;"><strong> {{$option->etiqueta}} </strong></label>
									@elseif($option->control == 'C')
										<input name="pregunta" id="check{{$key+1}}" type="checkbox"/>
										<label class="black-text" for="check{{$key+1}}" style="margin-right: 10em !important;"><strong> {{$option->etiqueta}} </strong></label>
									@endif
								@endforeach
							@endif										

						@endif

					@endforeach			
				</div>
			</div>	
			
		</form>
	</div>
@endsection
