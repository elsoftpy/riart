@extends('layout')
@section('content')
	<div class="row">
		<div class="col s12 m6 offset-m3">
			<div class="hoverable bordered">
				<div class="card red lighten-2">
					<div class="card-content white-text" style="margin-bottom:2.5em;">
						<span class="card-title"><strong>@lang('attachmentNotFound.label_sorry')</strong></span>
						<p>@lang('attachmentNotFound.label_not_found')</p>
					</div>
					<div class="card-action white">
						<a href="{{route('home')}}">@lang('attachmentNotFound.button_return_home')</a>
					</div>
				</div>
			</div>
		</div>
	</div>
@stop