@extends('report.layout')

@section('content')
	@include('report.title')
	<div class="row">
		<div class="col s12" data-intro="" data-step="29">
			<div class="hoverable bordered">
				<div class="card">
					<div class="card-content">
						<div class="section-metodologia">
							<h5><strong>Methodology</strong></h5>
							<ol>
								<li>
									<p>Steps in the Job Match&Analysis process</p>
									<p>(to indicate degree of similarity with survey´s key jobs)</p>
									<div class="metodologia-parrafo">
										Time 1</span> Comparison of each job´s structure through the following drivers such as: level of report, responsibilities and specifics/ general’s tasks of each job according to seniority, the impact, the accountability and other relevant characteristics of each job.
									</div>
									<div class="metodologia-parrafo">
										Time 2.</span> Is based on what information to be collected. 
										<ol type="a" class="normal">
											<li>Information about the organization.</li>
											<li>Information about the total compensation system, and</li>
											<li>Specific pay data on each incumbent in the job under analysis.</li>
											<li>Turnover data.</li>
										</ol>
									</div>
								</li>
								<li>
									<p>Statistical data</p>
									<ol type="a" class="normal">									
										<li>Frequency distribution to help visualize information and may highlight anomalies.</li>
										<li>Measure central tendency by: mean, median.</li>
										<li>Quartiles:  25th, 75th.</li>
										<li>Other: minimum and maximum</li>
									</ol>
								</li>
								<li>
									<p>Market indicators. Results.</p>
									<ol type="a">
										<li>
											Base Pay: <span class="normal">the monthly base salary is a result of the employee holding a certain position, which is usually the basis for calculations on pension and other forms of cash bonuses; it does not include allowances of any kind, nor 13th month salary in the survey terms.</span> 
										</li>
										<li>
											Annual Base Pay: <span class="normal"> includes guaranteed monthly base salary multiplied by number of months.</span>
										</li>
										<li>
											Annual Guaranteed Cash: <span class="normal"> Annual Base Salary + Allowances + Additional as a Fix Bonus + Fixed Bonus</span>
											<p>Definition:</p>
											<p class="normal">All cash income that comes with the employment in holding a specific position, and is not dependent on the performance result of either the business nor the individual. Commonly it includes monthly base pay by a number of months per year, plus allowances, and fixed bonus.</p>
											<p>Allowances – Definition</p>
											<p class="normal">Serving as a means to supplement the remuneration package, they can be cash or reimbursable with a ceiling, offering the flexibility of being non-pensionable, tax efficient (varies from country to country) and may be easy to be added, adjusted or taken away (flexibility varies from country to country).  They are launched to be in line with market practices or fulfil a legal requirement, and may include some of the following: position allowance, transport allowance, shift allowance etc.</p>
											<p>Additional as a Fix Bonus – Definition</p>
											<p class="normal">Applicable by few companies, equivalent to one base monthly salary.</p>
											<p class="normal">The banking financial sector pays between 1 base monthly salary and 1.5; in the first semester of the year, and other legal additional</p>
											<p class="normal underlined">Both bonuses are not dependent on the individuals or the bank´s performance.</p>
											<p>Fixed Bonus – Definition</p>  
											<p class="normal">Normally included as guaranteed part of the annual package, expressed in terms of base salaries and are a result of legal issues obligations. This bonus is not dependant on the individuals or the company’s performance. Is the result of the annual base salary plus annual additional as a fix bonus divided by 12.</p>
										</li>
										<li>
											<p>
												Annual Total Cash: <span class="normal">Annual Guaranteed Cash + Annual Plus Pay + Annual Variable Pay + Annual Commission + Bonus</span>
											</p>
											<p>Pay for Performance - Short Term Incentives</p> 
												<div class="indented">
													<p>Bonus - Definition</p>
													<p class="normal">Is a lump-sum payment to an employee in recognition of goal achievement. A form of variable pay, which gives additional pay to the employee when the organization or team meet performance target and the amount may vary as per the result of employee and organization performance.</p>
													<p>Plus Pay – Definition</p>
													<p class="normal">Stable and clear performance measures that emphasize monetary rewards with large incentive component: plus, for performance challenging and specific goals.</p>
													<p|>Variable Pay – Definition</p>
													<p class="normal">Rewards employees for partially or completely attaining a predetermined work objective.</p>	
												</div>								
										</li>
										<li>
											<p>
												Long Term Incentives: <span class="normal">stock ownership or options to buy stock as a fixed price. Stock options are often the largest component in a pay package.</span>
											</p>
										</li>
										<li>
											<p>
												Total Annual Benefit:<span class="normal"> medical, dental insurance,  pension plan, life insurance, car company, cellular plan, free choice, learning education (MBA or other specialization in/out of the country), lunch, kindergarten, kinder education, cost of living, etc.</span> 
											</p>
										</li>
										<li>
											<p>
												Total Annual Benefit: <span class="normal"> medical, dental insurance,  pension plan, life insurance, car company, cellular plan, free choice, learning education (MBA or other specialization in/out of the country), lunch, kindergarten, kinder education, cost of living, etc.</span>
											</p>
										</li>
									</ol>
								</li>
							</ol>
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
		    tour.goToStepNumber(28).start();
	    }

     	tour.onafterchange(function(step){
			if($(step).attr("data-step") == 29){
				window.location.href="{{URL::route('home')}}?multipage=4";
				tour.exit();
			}
      	});    		    	    



	</script>
@endpush