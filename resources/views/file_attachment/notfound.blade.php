@extends('layout')
@section('content')
	<div class="row">
		<div class="col s12 m6 offset-m3">
			<div class="hoverable bordered">
				<div class="card red lighten-2">
					<div class="card-content white-text" style="margin-bottom:2.5em;">
						<span class="card-title"><strong>Lo sentimos...</strong></span>
						<p>El archivo solicitado no existe o hubo un error en la descarga.</p>
					</div>
					<div class="card-action white">
						<a href="{{route('home')}}">Volver al Inicio</a>
					</div>
				</div>
			</div>
		</div>
	</div>
@stop