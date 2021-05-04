@extends('layout')
@section('content')
	<div class="row">
		<div class="col s12 m6 offset-m3">
			<div class="hoverable bordered">
				<div class="card red lighten-2">
					<div class="card-content white-text" style="margin-bottom:2.5em;">
						<span class="card-title"><strong>Los sentimos</strong></span>
						<p>No encontramos datos para el campo: {{ $field }}</p>
					</div>
					<div class="card-action white">
						<a href="{{route('clonar.club')}}">Volver al formulario de clonación</a>
					</div>
				</div>
			</div>
		</div>
	</div>
@stop