@extends('report.layout')

@section('content')
	<div class="row">
		<div class="col s12">
			<h4>Club de {{$club}}</h4>
			<div class="hoverable bordered">
				<div class="card">
					<div class="card-content white-text" style="text-align: center;">
						<img src="{{asset($imagen)}}">
					</div>
				</div>

			</div>
		</div>
	</div>
@stop