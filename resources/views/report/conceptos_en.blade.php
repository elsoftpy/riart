@extends('report.layout')

@section('content')
	@include('report.title')
	<div class="row">
		<div class="col s12">
			<div class="hoverable bordered">
				<div class="card">
					<div class="card-content">
						<h5>STATISTICAL CONCEPTS</h5>								
						<div class="conceptos_estadisticos">
							<p><strong>Minimum:</strong> minimum value of a list of the series.</p> 
							<p><strong>Quartile:</strong> the first quartile of a numerically ordered list is a number such that a quarter of the data in the list is below it.</p>
							<p><strong>3rd Quartile:</strong> the third quartile of a numerically ordered list is a number below which three quarters of the data are located.</p>
							<p><strong>2nd Quartile or Median:</strong> number that divides a group of numerically ordered data into a lower and a higher half. The second quartile is the same as the median.</p>
							<p><strong>Mean (average):</strong> a mean score is an average score. It is the sum of individual scores divided by the number of individuals.</p> 
							<p><strong>Maximum:</strong> the maximum value of a list of the series
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
@stop
@push('scripts')
	<script type="text/javascript">
		if (RegExp('multipage=true', 'gi').test(window.location.search)) {
		    tour.goToStepNumber(26).start();
	    }

     	tour.onafterchange(function(step){
			if($(step).attr("data-step") == 27){
				window.location.href="{{URL::route('reportes.panel', $dbEmpresa)}}?multipage=true";
			}
      	});    		    	    



	</script>
@endpush