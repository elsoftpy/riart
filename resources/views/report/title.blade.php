@if ($locale == "en")
	<div class="col s8">
		<h4>{!! $club !!} Club</h4>	
	</div>    
@else
	<div class="col s8">
		<h4>Club {!! $club !!}</h4>	
	</div>    
@endif

<div class="col s4">
    <img class="hoverable bordered" align="right" style="width:60%; margin-top: 1em;display: block;" src="{{URL::asset('/images/logo.jpg')}}"/>	
</div>
