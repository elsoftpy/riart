@extends('report.layout')
<style type="text/css">
	.tabs .indicator{
    background-color:teal !important;
    }
    ul.tabs > li.tab > a, a.active {
    	color: teal !important;
    }
</style>
@section('content')
	<div class="row" data-intro="" data-step="19">
		<div class="col s2" data-intro="" data-step="12">
			<form id="excel_form" action="{{ route('reportes.cargoExcel') }}" method="POST">
				<input type="hidden" name="_token" value="{{{ csrf_token() }}}" />
				<input type="hidden" name="empresa_id" value="{{$dbEmpresa->id}}"/>
				<input type="hidden" name="cargo_id" value="{{$dbCargo->id}}"/>
				<button class="btn waves-effect waves-light lighten-1 white-text" type="submit" name="submit">
					<i class="material-icons left">cloud_download</i>Excel
				</button>
			</form>		
		</div>
		<div class="col s3" data-intro="<p class='intro-title'><strong>MONEDA DE VISUALIZACION</strong></p>Click para cambiar la visualización en moneda local o Dólares Americanos" data-step="18">
			<form id="filter_form" action="{{route('reportes.cargos')}}" method="POST">
				@if ($convertir)
					<input type="hidden" name="_token" value="{{{ csrf_token() }}}" />
					<input type="hidden" name="empresa_id" value="{{$dbEmpresa->id}}"/>
					<input type="hidden" name="cargo_id" value="{{$dbCargo->id}}"/>
					<input type="hidden" name="moneda" value="local"/>
					<input type="hidden" name="periodo" value="{{$periodo}}"/>
					<button class="btn waves-effect waves-light lighten-1 red white-text" type="submit" name="submitFilter" id="submitFilter">
						<i class="material-icons left">monetization_on</i>@lang('reportReport.button_currency_gs')
					</button>
				@else
					<input type="hidden" name="_token" value="{{{ csrf_token() }}}" />
					<input type="hidden" name="empresa_id" value="{{$dbEmpresa->id}}"/>
					<input type="hidden" name="cargo_id" value="{{$dbCargo->id}}"/>
					<input type="hidden" name="moneda" value="extranjera"/>
					<input type="hidden" name="periodo" value="{{$periodo}}"/>
					<button class="btn waves-effect waves-light lighten-1 red white-text" type="submit" name="submitFilter" id="submitFilter">
						@if ($dbEmpresa->id == 95)
							<i class="material-icons left">monetization_on</i>@lang('reportReport.button_currency_cas')	
						@else
							<i class="material-icons left">monetization_on</i>@lang('reportReport.button_currency_us')	
						@endif
						
					</button>

				@endif
			</form>
		</div>
		<div class="col s12">
			<h4>{{$dbCargo->descripcion}}</h4>
			@if ($convertir)
				<p style="color: red;">@lang('reportReport.p_currency_us')</p>
			@else
				<p style="color: red;">@lang('reportReport.p_currency_gs')</p>
			@endif
			
			<div class="row">
				<ul class="tabs teal lighten-5">
					<li class="tab col s3" data-intro="<p class='intro-title'><strong>UNIVERSO</strong></p>Se observan los resultados del cargo en todos los segmentos" data-step="13">
						<a href="#universo">
							@lang('reportReport.label_tab_universe')
						</a>
					</li>
					<li class="tab col s3" data-intro="<p class='intro-title'><strong>UNIVERSO</strong></p>Se observan los resultados del cargo en el segmento nacional" data-step="14">
						<a href="#nacional">
							@lang('reportReport.label_tab_national')
						</a>
					</li>					
					<li class="tab col s3" data-intro="<p class='intro-title'><strong>UNIVERSO</strong></p>Se observan los resultados del cargo en el segmento internacional" data-step="15">
						<a href="#internacional">
							@lang('reportReport.label_tab_international')
						</a>
					</li>					
				</ul>
				<div class="browser-window" id="universo">
	                <div class="content">
	                	<table id="listado-universo" class="highlight">
	                		<thead>
								@include('includes.report_table_headers')
							</thead>
			                <tbody>
			                	@foreach ($universo as $item)
		                    		<tr>
				                    		<td>{{ $item['concepto'] }}</td>
				                    		<td>{{ $item['casos'] }}</td>
				                    		<td> {{$countOcupantes}}</td>
			                    		@if (!$convertir)
				                   			@php
				                   				if($item['min'] == ""){
				                   					$min = "";
				                   				}else{
				                   					$min = number_format($item['min'], 0, ",", ".");
				                   				}
				                   				if($item['per25'] == ""){
				                   					$per25 = "";
				                   				}else{
				                   					$per25 = number_format($item['per25'], 0, ",", ".");
				                   				}
				                   				if($item['prom'] == ""){
				                   					$prom = "";
				                   				}else{
				                   					$prom = number_format($item['prom'], 0, ",", ".");
				                   				}
				                   				if($item['med'] == ""){
				                   					$med = "";
				                   				}else{
				                   					$med = number_format($item['med'] , 0, ",", ".");
				                   				}								
				                   				if($item['per75'] == ""){
				                   					$per75 = "";
				                   				}else{
				                   					$per75 = number_format($item['per75'] , 0, ",", ".");
				                   				}   
				                   				if($item['max'] == ""){
				                   					$max = "";
				                   				}else{
				                   					$max = number_format($item['max'] , 0, ",", ".");
				                   				}   
				                   				if($item['empresa'] == ""){
				                   					$empresa = "";
				                   				}else{
				                   					$empresa = number_format($item['empresa'] , 0, ",", ".");
				                   				}   
				                   			@endphp
				                    		<td>{{ $min }} </td>
				                    		<td>{{ $per25 }}</td>
				                    		<td>{{ $prom }}</td>
				                    		<td>{{ $med }}</td>
				                    		<td>{{ $per75 }}</td>
				                    		<td>{{ $max }}</td>
				                    		<td>
				                    			<input type="text" name="" value="{{ $empresa }}" id="empresa{{$loop->iteration}}">
				                    		</td>
			                    		@else
				                    		@php
				                    			if($item['min'] == ""){
				                   					$min = "";
				                   				}else{
				                    				$min = $item['min'] * 1000 / $tipoCambio;
				                    				$min = number_format($min, 2, ",", ".");
				                    			}
				                    			if($item['per25'] == ""){
				                   					$per25 = "";
				                   				}else{
				                    				$per25 = $item['per25'] * 1000 / $tipoCambio;
				                    				$per25 = number_format($per25, 2, ",", ".");
				                    			}
				                    			if($item['prom'] == ""){
				                   					$prom = "";
				                   				}else{
				                    				$prom = $item['prom'] * 1000 / $tipoCambio;
				                    				$prom = number_format($prom, 2, ",", ".");
				                    			}
				                    			if($item['med'] == ""){
				                   					$med = "";
				                   				}else{
				                    				$med = $item['med'] * 1000 / $tipoCambio;
				                    				$med = number_format($med, 2, ",", ".");
				                    			}
				                    			if($item['per75'] == ""){
				                   					$per75 = "";
				                   				}else{
				                    				$per75 = $item['per75'] * 1000 / $tipoCambio;
				                    				$per75 = number_format($per75, 2, ",", ".");
				                    			}
				                    			if($item['max'] == ""){
				                   					$max = "";
				                   				}else{
				                   					$max = $item['max'] * 1000 / $tipoCambio;
				                   					$max = number_format($max, 2, ",", ".");
				                   				}
				                   				if($item['empresa'] == ""){
				                   					$empresa = "";
				                   				}else{
				                    				$empresa = $item['empresa'] * 1000 / $tipoCambio;
				                    				$empresa = number_format($empresa, 2, ",", ".");
				                    			}
				                    		@endphp
				                    		<td>{{ $min }} </td>
				                    		<td>{{ $per25 }}</td>
				                    		<td>{{ $prom }}</td>
				                    		<td>{{ $med }}</td>
				                    		<td>{{ $per75 }}</td>
				                    		<td>{{ $max }}</td>
				                    		<td>
				                    			<input type="text" name="" value="{{ $empresa }}" id="empresa{{$loop->iteration}}">
				                    		</td>
			                    		@endif
			                    		<td></td>
			                    		<td></td>
			                    		<td></td>
			                    		<td></td>
			                    		<td style="display: none;">{{$item['segmento']}}</td>
		                    		</tr>
			                	@endforeach
		                    </tbody>
		                </table>
						<div class="col s4" id="salario-universo" data-step="17" data-intro="<p class='intro-title'><strong>GRAFICOS COMPARATIVOS</strong></p>Gráficos comparativos de su cargo vs. mercado">
							<h5>@lang('reportReport.chart_salary')</h5>
							<canvas id="salario-base"></canvas>
						</div>
						<div class="col s4" id="efectivo-universo" >
							<h5>@lang('reportReport.chart_annual_cash')</h5>
							<canvas id="efectivo-anual"></canvas>
						</div>
						<div class="col s4" id="compensacion-universo" >
							<h5>@lang('reportReport.chart_total_comp')</h5>
							<canvas id="compensacion-anual"></canvas>
						</div>
					</div>

				</div>
				<div class="browser-window" id="nacional">
	                <div class="content">
	                	<table id="listado-nacional" class="highlight">
	                		<thead>
		                    	<tr>
		                      	 	@include('includes.report_table_headers')
		                      	</tr>
		                    </thead>
		                    <tbody>
			                	@foreach ($nacional as $item)
		                    		<tr>
			                    		<td>{{ $item['concepto'] }}</td>
			                    		<td>{{ $item['casos'] }}</td>
			                    		<td> {{$countOcupantesNac}}</td>
			                    		@if (!$convertir)
				                   			@php
				                   				if($item['min'] == ""){
				                   					$min = "";
				                   				}else{
				                   					$min = number_format($item['min'], 0, ",", ".");
				                   				}
				                   				if($item['per25'] == ""){
				                   					$per25 = "";
				                   				}else{
				                   					$per25 = number_format($item['per25'], 0, ",", ".");
				                   				}
				                   				if($item['prom'] == ""){
				                   					$prom = "";
				                   				}else{
				                   					$prom = number_format($item['prom'], 0, ",", ".");
				                   				}
				                   				if($item['med'] == ""){
				                   					$med = "";
				                   				}else{
				                   					$med = number_format($item['med'] , 0, ",", ".");
				                   				}								
				                   				if($item['per75'] == ""){
				                   					$per75 = "";
				                   				}else{
				                   					$per75 = number_format($item['per75'] , 0, ",", ".");
				                   				}   
				                   				if($item['max'] == ""){
				                   					$max = "";
				                   				}else{
				                   					$max = number_format($item['max'] , 0, ",", ".");
				                   				}   
				                   				if($item['empresa'] == ""){
				                   					$empresa = "";
				                   				}else{
				                   					$empresa = number_format($item['empresa'] , 0, ",", ".");
				                   				}   
				                   			@endphp
				                    		<td>{{ $min }} </td>
				                    		<td>{{ $per25 }}</td>
				                    		<td>{{ $prom }}</td>
				                    		<td>{{ $med }}</td>
				                    		<td>{{ $per75 }}</td>
				                    		<td>{{ $max }}</td>
				                    		<td>
				                    			<input type="text" name="" value="{{ $empresa }}" id="empresa{{$loop->iteration}}">
				                    		</td>
			                    		@else
				                    		@php
				                    			if($item['min'] == ""){
				                   					$min = "";
				                   				}else{
				                    				$min = $item['min'] * 1000 / $tipoCambio;
				                    				$min = number_format($min, 2, ",", ".");
				                    			}
				                    			if($item['per25'] == ""){
				                   					$per25 = "";
				                   				}else{
				                    				$per25 = $item['per25'] * 1000 / $tipoCambio;
				                    				$per25 = number_format($per25, 2, ",", ".");
				                    			}
				                    			if($item['prom'] == ""){
				                   					$prom = "";
				                   				}else{
				                    				$prom = $item['prom'] * 1000 / $tipoCambio;
				                    				$prom = number_format($prom, 2, ",", ".");
				                    			}
				                    			if($item['med'] == ""){
				                   					$med = "";
				                   				}else{
				                    				$med = $item['med'] * 1000 / $tipoCambio;
				                    				$med = number_format($med, 2, ",", ".");
				                    			}
				                    			if($item['per75'] == ""){
				                   					$per75 = "";
				                   				}else{
				                    				$per75 = $item['per75'] * 1000 / $tipoCambio;
				                    				$per75 = number_format($per75, 2, ",", ".");
				                    			}
				                    			if($item['max'] == ""){
				                   					$max = "";
				                   				}else{
				                   					$max = $item['max'] * 1000 / $tipoCambio;
				                   					$max = number_format($max, 2, ",", ".");
				                   				}
				                   				if($item['empresa'] == ""){
				                   					$empresa = "";
				                   				}else{
				                    				$empresa = $item['empresa'] * 1000 / $tipoCambio;
				                    				$empresa = number_format($empresa, 2, ",", ".");
				                    			}
				                    		@endphp
				                    		<td>{{ $min }} </td>
				                    		<td>{{ $per25 }}</td>
				                    		<td>{{ $prom }}</td>
				                    		<td>{{ $med }}</td>
				                    		<td>{{ $per75 }}</td>
				                    		<td>{{ $max }}</td>
				                    		<td>
				                    			<input type="text" name="" value="{{ $empresa }}" id="empresa{{$loop->iteration}}">
				                    		</td>
			                    		@endif
			                    		<td></td>
			                    		<td></td>
			                    		<td></td>
			                    		<td></td>
			                    		<td style="display: none;">{{$item['segmento']}}</td>

		                    		</tr>
			                	@endforeach
		                    </tbody>
		                </table>
						<div class="col s4" id="salario-nacional">
							<h5>@lang('reportReport.chart_salary')</h5>
							<canvas id="salario-base-nacional"></canvas>
						</div>
						<div class="col s4" id="efectivo-nacional">
							<h5>@lang('reportReport.chart_annual_cash')</h5>
							<canvas id="efectivo-anual-nacional"></canvas>
						</div>
						<div class="col s4" id="compensacion-nacional">
							<h5>@lang('reportReport.chart_total_comp')</h5>
							<canvas id="compensacion-anual-nacional"></canvas>
						</div>

	                </div>
				</div>
				<div class="browser-window" id="internacional">
	                <div class="content">
	                	<table id="listado-internacional" class="highlight">
	                		<thead>
		                      	<tr>
		                      		@include('includes.report_table_headers')
		                      	</tr>
		                    </thead>
		                    <tbody>
			                	@foreach ($internacional as $item)
		                    		<tr>
			                    		<td>{{ $item['concepto'] }}</td>
			                    		<td>{{ $item['casos'] }}</td>
			                    		<td> {{$countOcupantesInt}}</td>
			                    		@if (!$convertir)
				                   			@php
				                   				if($item['min'] == ""){
				                   					$min = "";
				                   				}else{
				                   					$min = number_format($item['min'], 0, ",", ".");
				                   				}
				                   				if($item['per25'] == ""){
				                   					$per25 = "";
				                   				}else{
				                   					$per25 = number_format($item['per25'], 0, ",", ".");
				                   				}
				                   				if($item['prom'] == ""){
				                   					$prom = "";
				                   				}else{
				                   					$prom = number_format($item['prom'], 0, ",", ".");
				                   				}
				                   				if($item['med'] == ""){
				                   					$med = "";
				                   				}else{
				                   					$med = number_format($item['med'] , 0, ",", ".");
				                   				}								
				                   				if($item['per75'] == ""){
				                   					$per75 = "";
				                   				}else{
				                   					$per75 = number_format($item['per75'] , 0, ",", ".");
				                   				}   
				                   				if($item['max'] == ""){
				                   					$max = "";
				                   				}else{
				                   					$max = number_format($item['max'] , 0, ",", ".");
				                   				}   
				                   				if($item['empresa'] == ""){
				                   					$empresa = "";
				                   				}else{
				                   					$empresa = number_format($item['empresa'] , 0, ",", ".");
				                   				}   
				                   			@endphp
				                    		<td>{{ $min }} </td>
				                    		<td>{{ $per25 }}</td>
				                    		<td>{{ $prom }}</td>
				                    		<td>{{ $med }}</td>
				                    		<td>{{ $per75 }}</td>
				                    		<td>{{ $max }}</td>
				                    		<td>
				                    			<input type="text" name="" value="{{ $empresa }}" id="empresa{{$loop->iteration}}">
				                    		</td>	                    		
			                    		@else
				                    		@php
				                    			if($item['min'] == ""){
				                   					$min = "";
				                   				}else{
				                    				$min = $item['min'] * 1000 / $tipoCambio;
				                    				$min = number_format($min, 2, ",", ".");
				                    			}
				                    			if($item['per25'] == ""){
				                   					$per25 = "";
				                   				}else{
				                    				$per25 = $item['per25'] * 1000 / $tipoCambio;
				                    				$per25 = number_format($per25, 2, ",", ".");
				                    			}
				                    			if($item['prom'] == ""){
				                   					$prom = "";
				                   				}else{
				                    				$prom = $item['prom'] * 1000 / $tipoCambio;
				                    				$prom = number_format($prom, 2, ",", ".");
				                    			}
				                    			if($item['med'] == ""){
				                   					$med = "";
				                   				}else{
				                    				$med = $item['med'] * 1000 / $tipoCambio;
				                    				$med = number_format($med, 2, ",", ".");
				                    			}
				                    			if($item['per75'] == ""){
				                   					$per75 = "";
				                   				}else{
				                    				$per75 = $item['per75'] * 1000 / $tipoCambio;
				                    				$per75 = number_format($per75, 2, ",", ".");
				                    			}
				                    			if($item['max'] == ""){
				                   					$max = "";
				                   				}else{
				                   					$max = $item['max'] * 1000 / $tipoCambio;
				                   					$max = number_format($max, 2, ",", ".");
				                   				}
				                   				if($item['empresa'] == ""){
				                   					$empresa = "";
				                   				}else{
				                    				$empresa = $item['empresa'] * 1000 / $tipoCambio;
				                    				$empresa = number_format($empresa, 2, ",", ".");
				                    			}
				                    		@endphp
				                    		<td>{{ $min }} </td>
				                    		<td>{{ $per25 }}</td>
				                    		<td>{{ $prom }}</td>
				                    		<td>{{ $med }}</td>
				                    		<td>{{ $per75 }}</td>
				                    		<td>{{ $max }}</td>
				                    		<td>
				                    			<input type="text" name="" value="{{ $empresa }}" id="empresa{{$loop->iteration}}">
				                    		</td>
			                    		@endif
			                    		<td></td>
			                    		<td></td>
			                    		<td></td>
			                    		<td></td>
			                    		<td style="display: none;">{{$item['segmento']}}</td>
		                    		</tr>
			                	@endforeach
		                    </tbody>
		                </table>
						<div class="col s4" id="salario-internacional">
							<h5>@lang('reportReport.chart_salary')</h5>
							<canvas id="salario-base-internacional"></canvas>
						</div>
						<div class="col s4" id="efectivo-internacional">
							<h5>@lang('reportReport.chart_annual_cash')</h5>
							<canvas id="efectivo-anual-internacional"></canvas>
						</div>
						<div class="col s4" id="compensacion-internacional">
							<h5>@lang('reportReport.chart_total_comp')</h5>
							<canvas id="compensacion-anual-internacional"></canvas>
						</div>
	                </div>
				</div>
			</div>
		</div>
	</div>
	<form id="locale_es_form" action="{{route('reportes.cargos')}}" method="POST">
			<input type="hidden" name="_token" value="{{{ csrf_token() }}}" />
			<input type="hidden" name="empresa_id" value="{{$dbEmpresa->id}}"/>
			<input type="hidden" name="cargo_id" value="{{$dbCargo->id}}"/>
			<input type="hidden" name="periodo" value="{{$periodo}}"/>
			<input type="hidden" name="locale" value="es">
	</form>
	<form id="locale_en_form" action="{{route('reportes.cargos')}}" method="POST">
			<input type="hidden" name="_token" value="{{{ csrf_token() }}}" />
			<input type="hidden" name="empresa_id" value="{{$dbEmpresa->id}}"/>
			<input type="hidden" name="cargo_id" value="{{$dbCargo->id}}"/>
			<input type="hidden" name="periodo" value="{{$periodo}}"/>
			<input type="hidden" name="locale" value="en">
	</form>
	
@stop
@push('scripts')
	<script type="text/javascript">
		var valueStr = '';
		var value = 0;
		var promedioStr = '';
		var promedio = 0;
		var medianaStr = '';
		var mediana = 0;
		var per75Str = '';
		var per75 = 0;
		var maxStr = '';
		var max = 0;
		var compProm = 0;
		var compMed = 0;
		var comp75 = 0;	
		var compMax = 0;	
		
		$(document).ready(function(){
    		$('.tabs').tabs();

    		setTimeout(function(){
	    		$('input[type="text').each(function(){
	    			if($(this).val() != ""){
	    				calculation($(this));		
	    			}
	    			
	    		});
    		}, 1000);
  		});

		$("#listado-universo").on('change', 'tbody td :input[type="text"]', function(e){
				calculation($(this));			
			}
		);

		$("#listado-internacional").on('change', 'tbody td :input[type="text"]',
			function(e){
				calculation($(this));
			}
		);

		$("#listado-nacional").on('change', 'tbody td :input[type="text"]',
			function(e){
				calculation($(this));
			}
		);

		$("#submitFilter").click(function(){
			$("#filter_form").submit();
		});
			
		$("#lang_switch_es").click(function(e){
			e.preventDefault();
			var url = "{{route('switch.lang.report', 'es')}}";
			$.get(url, function(){
				$("#filter_form").submit();
			});
			
			
		});

		$("#lang_switch_en").click(function(e){
			e.preventDefault();
			var url = "{{route('switch.lang.report', 'en')}}";
			$.get(url, function(){
				$("#filter_form").submit();
			});
			
		});
		function calculation(element){
			//var table = $("#listado-universo");
			var table = element;
			var row = element.closest('tr');
			valueStr = element.val();
			value = parseInt(valueStr.replace(".", ""));
			promedioStr = row.find('td:nth-child(6)').text();
			promedio = parseInt(promedioStr.replace(".", ""));
			medianaStr = row.find('td:nth-child(7)').text();
			mediana = parseInt(medianaStr.replace(".", ""));
			per75Str = row.find('td:nth-child(8)').text();
			per75 = parseInt(per75Str.replace(".", ""));
			maxStr = row.find('td:nth-child(9)').text();
			max = parseInt(maxStr.replace(".", ""));
			// promedio
			if(promedio > 0){
				compProm = value/promedio*100 - 100;
			}else{
				compProm = 0;
			}
			row.find('td:nth-child(11)').text(compProm.toLocaleString(undefined, { maximumFractionDigits: 2 }) + '%');

			// mediana
			if(mediana > 0){
				compMed = value/mediana*100 - 100;
			}else{
				compMed = 0;
			}
			row.find('td:nth-child(12)').text(compMed.toLocaleString(undefined, { maximumFractionDigits: 2 }) + '%');

			// percentil 75
			if(per75 > 0){
				comp75 = value/per75*100 - 100;
			}else{
				comp75 = 0;
			}
			row.find('td:nth-child(13)').text(comp75.toLocaleString(undefined, { maximumFractionDigits: 2 }) + '%');

			// Maximo
			if(max > 0){
				compMax = value/max*100 - 100;
			}else{
				compMax = 0;
			}
			row.find('td:nth-child(14)').text(compMax.toLocaleString(undefined, { maximumFractionDigits: 2 }) + '%');
			
			var label = row.find('td:nth-child(1)').text();
			var segmento = row.find('td:nth-child(15)').text();
			if(segmento == "universo"){
				itemSalario = "salario-base";
				itemEfectivo = "efectivo-anual"
				itemCompensacion = "compensacion-anual";
				divSalario = "#salario-universo";
				divEfectivo = "#efectivo-universo";
				divCompensacion = "#compensacion-universo";
				divChartSalario = "salario-base";		
				divChartEfectivo = "efectivo-anual";
				divChartCompensacion = "compensacion-anual";
			}else if(segmento == "nacional"){
				itemSalario = "salario-base-nacional";
				itemEfectivo = "efectivo-anual-nacional"
				itemCompensacion = "compensacion-anual-nacional";
				divSalario = "#salario-nacional";
				divEfectivo = "#efectivo-nacional";
				divCompensacion = "#compensacion-nacional";
				divChartSalario = "salario-base-nacional";		
				divChartEfectivo = "efectivo-anual-nacional";
				divChartCompensacion = "compensacion-anual-nacional";
			}else if(segmento == "internacional"){
				itemSalario = "salario-base-internacional";
				itemEfectivo = "efectivo-anual-internacional"
				itemCompensacion = "compensacion-anual-internacional";
				divSalario = "#salario-internacional";
				divEfectivo = "#efectivo-internacional";
				divCompensacion = "#compensacion-internacional";
				divChartSalario = "salario-base-internacional";		
				divChartEfectivo = "efectivo-anual-internacional";
				divChartCompensacion = "compensacion-anual-internacional";


			}
			if(label == "Salario Base" || label == "Monthly Base Salary"){
				if(value > 0){
					$(divSalario).html(`<h5>${label}</h5><canvas id="${divChartSalario}"></canvas>`);
					chart(promedio, per75, max, value, itemSalario);	
				}else{
					$(divSalario).empty();
				}
				
			}else if(label == "Efectivo Anual Garantizado" || label == "Annual Guaranteed Cash"){
				if(value > 0){
					$(divEfectivo).html(`<h5>${label}</h5><canvas id="${divChartEfectivo}"></canvas>`);
					chart(promedio, per75, max, value, itemEfectivo);
				}else{
					$(divEfectivo).empty();
				}
			}else if(label == "Compensación Anual Total" || label == "Compensación Efectiva Anual Total" ||
					 label == "Annual Total Compensation"){
				if(value > 0){
					$(divCompensacion).html(`<h5>${label}</h5><canvas id="${divChartCompensacion}"></canvas>`);
					chart(promedio, per75, max, value, itemCompensacion);
				}else{
					$(divCompensacion).empty();	
				}
			}
		}

		function chart(prom, perc, max, empresa, item){
			var ctx = $("#"+item);
			var chart = new Chart(ctx, {
				type: 'bar', 
				data: {
					labels: ['Promedio', '75 Perc.', 'Máximo', '{{$dbEmpresa->descripcion}}'],
					datasets:[{
						data:[prom, perc, max, empresa],
						backgroundColor: ["#03a9f4", "#ff9800", "#f44336", "#4caf50"],
						borderColor: ["#01579b", "#e65100", "#d50000", "#1b5e20" ],
						borderWidth: 3
					}]
				},
				options: {
					legend: {
						display: false
					}
				}/*, 
				scales: {
		            yAxes: [{
		                ticks: {
		                    beginAtZero:true
		                }
		            }]
				}	*/			
			});
		}

		// tour
     	$("#empresa1").attr("data-step", "16").attr("data-intro", "<p class='intro-title'><strong>COMPARATIVOS</strong></p>Le permite visualizar la brecha del cargo en su organización con respecto al mercado o bien, simule su escenario deseado.").attr("data-position", "left");
     	@if($tour)
			if ({{$tour}}) {
			    tour.goToStepNumber(13).start();
		    }
		@endif
     	tour.onafterchange(function(step){
      		if($(step).attr("data-step") == 19){
  				window.location.href="{{route('reportes.show', $dbEmpresa->id)}}?multipage=3";
  				tour.exit();
      		}      		

      		if($(step).attr("data-step") == 12){
      			window.location.href="{{ URL::route('reportes.filter', $dbEmpresa) }}?multipage=back";
      			tour.exit();	
      		}

      	});    		    

		
	</script>
@endpush