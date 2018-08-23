		var canvas = document.getElementById("myChart");
		var ctx = canvas.getContext('2d');
		var pregunta = $("#pregunta").val();
		var chartType = "";
		var labels = [];
		var chartData = [];
		var colores = [];
		var dataSet = {};
		var text = "";
		$(document).ready(function() {	 
			//$("#content").fadeIn('fast');
			/*$(window).load(function(){
				$('.preloader').fadeOut('slow',function(){
					$(this).hide();
					$("#content").fadeIn('slow');
				});
			});*/
	
			postChart();

		});	

		function chart(chartType){

			if(chartType == 'bar'){
				var chart = new Chart(	ctx, 
									{	type: chartType,
    									data: dataSet,
    									options:{
    										legend: {
    											display: false
    										},
    										scales: {
												yAxes: [{
												  	gridLines: {
												        display: false,
												        drawBorder: false
												    },
												    ticks: {
												    	beginAtZero: true,
												      	callback: 
															function(value) {
																return  value + '%';
															}
												        }
												     }],
												    xAxes: [{
												    	gridLines: {
												        	display: false,
												          	drawBorder: false
												        },
												        ticks: {
												          	display: false
												        }
												    }]
												},
												tooltips: {
													callbacks: {
														label: function(tooltipItem, data) {

															var allData = data.datasets[tooltipItem.datasetIndex].data;
															var tooltipLabel = data.labels[tooltipItem.index];
															var tooltipData = allData[tooltipItem.index];
															var total = 0;
																
															return  tooltipData + '%';
														}
													}
												}

    										}
										}    								
    								);
			}else if(chartType == "text"){
				var chart = new Chart(	ctx, 
								{	type: 'bar',
									data: dataSet
								}    								
							);		  		
		  		resetCanvas();
				//ctx.canvas.width = $('#chart-canvas').width(); // resize to parent width
  				//ctx.canvas.height = $('#chart-canvas').height(); // resize to parent height		  		
				var maxWidth = $("#chart-canvas").width() - 20;
      			var lineHeight = 25;
      			//var x = (canvas.width - maxWidth) / 2;
      			var x = 10;
      			var y = 30;		  		
  				ctx.font = '16pt Roboto';
  				wrapText(ctx, text, x, y, maxWidth, lineHeight);
			}else{
				var chart = new Chart(	ctx, 
								{	type: chartType,
									data: dataSet,
									options:{
										legend: {
											display: true
										},
										tooltips: {
											callbacks: {
												label: function(tooltipItem, data) {

													var allData = data.datasets[tooltipItem.datasetIndex].data;
													var tooltipLabel = data.labels[tooltipItem.index];
													var tooltipData = allData[tooltipItem.index];
													var total = 0;
															
													return  tooltipData + '%';
												}
											}
										}

									}
								}    								
							);
				if(tourist){
					tour.goToStepNumber(3).start();
					collapsible.open();
				}
				if(tourist2){
					tour.goToStepNumber(8).start();
					collapsible.open();
				}

			}
	   	}

    	$(".flat").on("click", function(e){
			e.stopPropagation();
			e.preventDefault();
			pregunta = $(this).val();
			collapseAll();

			postChart();
		});

		function collapseAll(){
 			$(".collapsible-header").removeClass(function(){
    			return "active";
  			});
  			$(".collapsible").collapsible({accordion: true});
  			$(".collapsible").collapsible({accordion: false});	
		}

		function postChart(){
			$.post(	chartUrl, 
					{ pregunta: pregunta, 
					  _token: token},
					function(result) {  
    					if(result.cerrada == "S"){
	    					i = 0;
	    					dataSet.labels  = result.labels;
	    					var datasets = [];
	    					data = {};
	    					data.data = result.respuesta;
	    					data.backgroundColor = result.colores;
	    					//data.borderColor = result.colores;
	    					data.fill = false,
	    					datasets.push(data);
	    					dataSet.datasets = datasets;
	    					if(result.respuesta.length > 15){
	    						chartType = 'bar';
	    					}else{
	    						chartType = 'doughnut';
	    					}
	    					$("#titulo").text(result.titulo);
	       					chart(chartType);	
    					}else{
    						text = result.respuesta;
    						$("#titulo").text(result.titulo);
    						chart("text");
    					}
		    });
		}	

		function resetCanvas(){
  			var height = $('#myChart').height();
  			var width = $('#myChart').width();
  			$('#myChart').remove(); // this is my <canvas> element
  			$('#chart-canvas').append('<canvas id="myChart" height="80%"><canvas>');
			canvas = document.getElementById("myChart");
			ctx = canvas.getContext('2d');
  			ctx.canvas.width = width; // resize to parent width
			ctx.canvas.height = height; // resize to parent height

		};	
		
		function wrapText(context, text, x, y, maxWidth, lineHeight) {
        	var words = text.split(' ');
        	var line = '';

	        for(var n = 0; n < words.length; n++) {
	          var testLine = line + words[n] + ' ';
	          var metrics = context.measureText(testLine);
	          var testWidth = metrics.width;
	          if (testWidth > maxWidth && n > 0) {
	            context.fillText(line, x, y);
	            line = words[n] + ' ';
	            y += lineHeight;
	          }
	          else {
	            line = testLine;
	          }
	        }
	        context.fillText(line, x, y);
      	}