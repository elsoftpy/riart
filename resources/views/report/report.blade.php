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
	<div class="row">
		<div class="col s12">
			<h4>{{$dbCargo->descripcion}}</h4>
			<div class="row">
				<ul class="tabs teal lighten-5">
					<li class="tab col s3">
						<a href="#universo">
							Universo
						</a>
					</li>
					<li class="tab col s3">
						<a href="#nacional">
							Nacional
						</a>
					</li>					
					<li class="tab col s3">
						<a href="#internacional">
							Internacional
						</a>
					</li>					

				</ul>
				<div class="browser-window" id="universo">
					<div class="top-bar">
	                  <h6>UNIVERSO DEL SEGMENTO</h6>
	                </div>
	                <div class="content">
	                	<table id="listado-universo" class="highlight">
	                		<thead>
		                      <tr>
		                      	 <th>Conceptos</th>
		                      	 <th>Casos</th>
		                      	 <th>Ocupantes</th>
		                      	 <th>Mínimo</th>
		                      	 <th>25 Perc.</th>
		                      	 <th>Promedio</th>
		                      	 <th>Mediana</th>
		                      	 <th>75 Perc.</th>
		                      	 <th>Máximo</th>
		                      	 <th>{{$dbEmpresa->descripcion}}</th>
								<th>Comparación Promedio</th>
		                      	 <th>Comparación Mediana</th>
		                      	 <th>Comparación 75 Percentil</th>
		                      	 <th>Comparación Máximo</th>
		                      	 <th style="display: none;"></th>
		                      </tr>
		                    </thead>
			                <tbody>
			                	@foreach ($universo as $item)
		                    		<tr>
			                    		<td>{{ $item['concepto'] }}</td>
			                    		<td>{{ $item['casos'] }}</td>
			                    		<td> {{$countOcupantes}}</td>
			                    		<td>{{ $item['min'] }}</td>
			                    		<td>{{ $item['per25'] }}</td>
			                    		<td>{{ $item['prom'] }}</td>
			                    		<td>{{ $item['med'] }}</td>
			                    		<td>{{ $item['per75'] }}</td>
			                    		<td>{{ $item['max'] }}</td>
			                    		<td>
			                    			<input type="text" name="" value="{{ $item['empresa'] }}" id="empresa">
			                    		</td>
			                    		<td></td>
			                    		<td></td>
			                    		<td></td>
			                    		<td></td>
			                    		<td style="display: none;">{{$item['segmento']}}</td>
		                    		</tr>
			                	@endforeach
		                    </tbody>
		                </table>
						<div class="col s4" id="salario-universo">
							<h5>Salario Base</h5>
							<canvas id="salario-base"></canvas>
						</div>
						<div class="col s4" id="efectivo-universo">
							<h5>Efectivo Anual Garantizado</h5>
							<canvas id="efectivo-anual"></canvas>
						</div>
						<div class="col s4" id="compensacion-universo">
							<h5>Compensación Anual Total</h5>
							<canvas id="compensacion-anual"></canvas>
						</div>
	                </div>
				</div>
				<div class="browser-window" id="nacional">
					<div class="top-bar">
	                  <h6>SEGMENTO NACIONAL</h6>
	                </div>
	                <div class="content">
	                	<table id="listado-nacional" class="highlight">
	                		<thead>
		                      <tr>
		                      	 <th>Conceptos</th>
		                      	 <th>Casos</th>
		                      	 <th>Ocupantes</th>
		                      	 <th>Mínimo</th>
		                      	 <th>25 Perc.</th>
		                      	 <th>Promedio</th>
		                      	 <th>Mediana</th>
		                      	 <th>75 Perc.</th>
		                      	 <th>Máximo</th>
		                      	 <th>Su Empresa</th>
								<th>Comparación Promedio</th>
		                      	 <th>Comparación Mediana</th>
		                      	 <th>Comparación 75 Percentil</th>
		                      	 <th>Comparación Máximo</th>
		                      	 <th style="display: none;"></th>

		                      	 </tr>
		                    </thead>
		                    <tbody>
			                	@foreach ($nacional as $item)
		                    		<tr>
			                    		<td>{{ $item['concepto'] }}</td>
			                    		<td>{{ $item['casos'] }}</td>
			                    		<td> {{$countOcupantesNac}}</td>
			                    		<td>{{ $item['min'] }}</td>
			                    		<td>{{ $item['per25'] }}</td>
			                    		<td>{{ $item['prom'] }}</td>
			                    		<td>{{ $item['med'] }}</td>
			                    		<td>{{ $item['per75'] }}</td>
			                    		<td>{{ $item['max'] }}</td>
			                    		<td>
			                    			<input type="text" name="" value="{{ $item['empresa'] }}" id="empresa">
			                    		</td>
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
							<h5>Salario Base</h5>
							<canvas id="salario-base-nacional"></canvas>
						</div>
						<div class="col s4" id="efectivo-nacional">
							<h5>Efectivo Anual Garantizado</h5>
							<canvas id="efectivo-anual-nacional"></canvas>
						</div>
						<div class="col s4" id="compensacion-nacional">
							<h5>Compensación Anual Total</h5>
							<canvas id="compensacion-anual-nacional"></canvas>
						</div>

	                </div>
				</div>
				<div class="browser-window" id="internacional">
					<div class="top-bar">
	                  <h6>SEGMENTO INTERNACIONAL</h6>
	                </div>
	                <div class="content">
	                	<table id="listado-internacional" class="highlight">
	                		<thead>
		                      <tr>
		                      	 <th>Conceptos</th>
		                      	 <th>Casos</th>
		                      	 <th>Ocupantes</th>
		                      	 <th>Mínimo</th>
		                      	 <th>25 Perc.</th>
		                      	 <th>Promedio</th>
		                      	 <th>Mediana</th>
		                      	 <th>75 Perc.</th>
		                      	 <th>Máximo</th>
		                      	 <th>Su Empresa</th>
								<th>Comparación Promedio</th>
		                      	 <th>Comparación Mediana</th>
		                      	 <th>Comparación 75 Percentil</th>
		                      	 <th>Comparación Máximo</th>
								 <th style="display: none;"></th>
		                      	</tr>
		                    </thead>
		                    <tbody>
			                	@foreach ($internacional as $item)
		                    		<tr>
			                    		<td>{{ $item['concepto'] }}</td>
			                    		<td>{{ $item['casos'] }}</td>
			                    		<td> {{$countOcupantesInt}}</td>
			                    		<td>{{ $item['min'] }}</td>
			                    		<td>{{ $item['per25'] }}</td>
			                    		<td>{{ $item['prom'] }}</td>
			                    		<td>{{ $item['med'] }}</td>
			                    		<td>{{ $item['per75'] }}</td>
			                    		<td>{{ $item['max'] }}</td>
			                    		<td>
			                    			<input type="text" name="" value="{{ $item['empresa'] }}" id="empresa">
			                    		</td>
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
							<h5>Salario Base</h5>
							<canvas id="salario-base-internacional"></canvas>
						</div>
						<div class="col s4" id="efectivo-internacional">
							<h5>Efectivo Anual Garantizado</h5>
							<canvas id="efectivo-anual-internacional"></canvas>
						</div>
						<div class="col s4" id="compensacion-internacional">
							<h5>Compensación Anual Total</h5>
							<canvas id="compensacion-anual-internacional"></canvas>
						</div>

	                </div>
				</div>
			</div>
		</div>
	</div>
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
    		$('ul.tabs').tabs();
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
		});

		function calculation(element){
			var table = $("#listado-universo");
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
			 compProm = value/promedio*100 - 100;
			row.find('td:nth-child(11)').text(compProm.toLocaleString(undefined, { maximumFractionDigits: 2 }) + '%');
			compMed = value/mediana*100 - 100;
			row.find('td:nth-child(12)').text(compMed.toLocaleString(undefined, { maximumFractionDigits: 2 }) + '%');
			comp75 = value/per75*100 - 100;
			row.find('td:nth-child(13)').text(comp75.toLocaleString(undefined, { maximumFractionDigits: 2 }) + '%');
			compMax = value/max*100 - 100;
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
			if(label == "Salario Base"){
				if(value > 0){
					$(divSalario).html('<h5>Salario Base</h5><canvas id="'+divChartSalario+'"></canvas>');
					chart(promedio, per75, max, value, itemSalario);	
				}else{
					$(divSalario).empty();
				}
				
			}else if(label == "Efectivo Anual Garantizado"){
				if(value > 0){
					$(divEfectivo).html('<h5>Efectivo Anual Garantizado</h5><canvas id="'+divChartEfectivo+'"></canvas>');
					chart(promedio, per75, max, value, itemEfectivo);
				}else{
					$(divEfectivo).empty();
				}
			}else if(label == "Compensación Anual Total"){
				if(value > 0){
					$(divCompensacion).html('<h5>Compensación Anual</h5><canvas id="'+divChartCompensacion+'"></canvas>');
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
		
	</script>
@endpush