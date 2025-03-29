@extends('report.layout')

@section('content')
    @include('report.title')
    <div class="row">
        <div class="col s12">
            <div class="hoverable bordered">
                <div class="card">
                    <div class="card-content">
                        {{-- <h5>STATISTICAL CONCEPTS</h5>								
						<div class="conceptos_estadisticos">
							<p><strong>Minimum:</strong> minimum value of a list of the series.</p> 
							<p><strong>Quartile:</strong> the first quartile of a numerically ordered list is a number such that a quarter of the data in the list is below it.</p>
							<p><strong>3rd Quartile:</strong> the third quartile of a numerically ordered list is a number below which three quarters of the data are located.</p>
							<p><strong>2nd Quartile or Median:</strong> number that divides a group of numerically ordered data into a lower and a higher half. The second quartile is the same as the median.</p>
							<p><strong>Mean (average):</strong> a mean score is an average score. It is the sum of individual scores divided by the number of individuals.</p> 
							<p><strong>Maximum:</strong> the maximum value of a list of the series
						</div> --}}
                        <h5><span>MONTHLY BASIC</span></h5>

                        <p><span>It is the stipulated gross base salary that the employee receives monthly, without
                                including other additional income.</span></p>

                        <h5><span>ANNUAL BASIC</span></h5>

                        <p><span>Gross Monthly Basic corresponding to 12 calendar months.</span></p>

                        <h5><span>GUARANTEED ANNUAL CASH</span></h5>

                        <p><span>It is equivalent to the Annual Base Salary + Guaranteed Fixed Bonus (e.g., fixed gratuity,
                                year-end bonus, or Christmas bonus).</span></p>
						<br>
                        <p><span style="font-weight:bold;">Annual Base Salary: </span><span> corresponds to the annual cash basic salary that the employee receives for holding a
                                specific position.</span></p>
						<br>
                        <p><span style="font-weight:bold;">Complementary Annual Compensation or Christmas Bonus:</span>
							<span> equivalent to one-twelfth of the remuneration accrued during the calendar year in favor
                                of the employee.</span></p>
                        <h5><span>VARIABLE</span></h5>

                        <p><span>Variable salary is the part of an employee's compensation that varies based on their
                                performance. It is an incentive paid in recognition of the employee's contribution.</span>
                        </p>

                        <h5><span>ANNUAL VARIABLE</span></h5>

                        <p><span>It is the Variable Salary received during a calendar year.</span></p>

                        <h5><span>ADDITIONAL</span></h5>

                        <p><span>It is the extra remuneration paid to an employee to complement their base salary (salary
                                supplement).</span></p>

                        <h5><span>ANNUAL ADDITIONAL</span></h5>

                        <p><span>It is the Additional received during a calendar year.</span></p>

                        <h5><span>TOTAL ADDITIONAL</span></h5>

                        <p><span>Annual Variable + Annual Additional</span></p>

                        <h5><span>BONUS</span></h5>

                        <p><span>It is an additional payment to an employee's regular salary.</span></p>

                        <h5><span>TOTAL ANNUAL CASH</span></h5>

                        <p><span>Annual Base Salary + Christmas Bonus + Total Annual Additional + Annual Bonus</span></p>

                        <h5><span>VALUED BENEFITS - ANNUAL</span></h5>

                        <p><span>Corresponds to the annualized sum of all concepts involving benefits assigned to the
                                position.</span></p>

                        <h5><span>TOTAL ANNUAL COMPENSATION</span></h5>

                        <p><span>It is equivalent to the Annual Base Salary + Effective Christmas Bonus + Total Annual
                                Additional + Bonus + Valued Benefits.</span></p>

                        <h5><span>STATISTICAL CONCEPTS</span></h5>

                        <p><span>MINIMUM</span></p>

                        <p><span>It is the lowest value in a given set of observations. In our case, it is the lowest
                                remuneration observed for each position.</span></p>
						<br>
                        <p><span>25TH PERCENTILE</span></p>

                        <p><span>It is the value that separates a series of observations so that 75% are higher and 25% are
                                lower than that value. In survey terms, it means that 75% of the remunerations granted for
                                the surveyed position are higher than this value.</span></p>
						<br>
                        <p><span>ARITHMETIC AVERAGE</span></p>

                        <p><span>It is a measure of central tendency obtained by summing the values of the concept
                                corresponding to all observations of each position and dividing the result of this sum by
                                the number of terms that compose it.</span></p>
						<br>
                        <p><span>MEDIAN</span></p>

                        <p><span>It is the value that separates the set of observations so that 50% are higher and 50% are
                                lower than that value.</span></p>
						<br>
                        <p><span>75TH PERCENTILE</span></p>

                        <p><span>It is the value that separates the set of observations so that 25% are higher and 75% are
                                lower than that value. In survey terms, it means that 75% of the remunerations granted for
                                the surveyed position are lower than this value.</span></p>
						<br>
                        <p><span>MAXIMUM</span></p>

                        <p><span>It is the highest value in the set of observations. In our case, it is the highest
                                remuneration observed for each position.</span></p>

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

        tour.onafterchange(function(step) {
            if ($(step).attr("data-step") == 27) {
                window.location.href = "{{ URL::route('reportes.panel', $dbEmpresa) }}?multipage=true";
            }
        });
    </script>
@endpush
