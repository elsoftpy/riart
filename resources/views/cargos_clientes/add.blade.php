@extends('layout')
@section('content')
	<div class="row">
		<div class="browser-window">
			<div class="top-bar">
	          <h4>@lang('cargosClientesEdit.title_add_position')</h4>
	        </div>
	        <div class="content">
				<form class="col s12" action="{{route('encuestas_cargos.update', $id)}}" method="POST">
					<h6 class="red-text"> @lang('cargosClientesEdit.label_amount_disclaimer') </h6>
					<div class="row">
						<div class="input-field col s12">
							<input type="text" class="validate" id="descripcion" name="descripcion" must/>
							<label for="descripcion">@lang("cargosClientesEdit.form_label_position")</label>
						</div>	
					</div>
					<div class="row">
						<div class="input-field col s12">
							<select name="area_id" id="area_id"> 
						        <option value="" disabled selected>@lang("cargosClientesEdit.select_label_choose")</option>
						        @foreach($dbArea as $id=>$descripcion)	
						        	<option value="{{$id}}">{{$descripcion}}</option>
						        @endforeach
						    </select>
					    	<label for="area_id" class="active">@lang("cargosClientesEdit.form_label_area")</label>						
						</div>
					</div>
					<div class="row">
						<div class="input-field col s12 select-container">
						    
						    <select name="nivel_id" id="nivel_id" must> 
						      	<option value="" disabled selected>@lang("cargosClientesEdit.select_label_choose")</option>
						        @foreach($dbNivel as $id=>$descripcion)	
						        	<option value="{{$id}}">{{$descripcion}}</option>
						        @endforeach
						    </select>
						 	<label for="nivel_id" class="active">@lang("cargosClientesEdit.form_label_level")</label>
						 </div>
					</div>
					<div class="row">
						<div class="input-field col s12">
					        <input type="text" class="validate number" id="cantidad_ocupantes" name="cantidad_ocupantes"/>
					        <label for="cantidad_ocupantes">@lang("cargosClientesEdit.form_label_incumbents")</label>
					    </div>
					</div>
					<div class="row">
						<div class="input-field col s4">
					        <input type="text" class="validate number" id="salario_base" name="salario_base" must/>
					        <label for="salario_base">@lang("cargosClientesEdit.form_label_salary")</label>
					    </div>
						<div class="input-field col s4">
					        <input type="text" class="validate number" id="gratificacion" name="gratificacion"/>
					        <label for="gratificacion">@lang("cargosClientesEdit.form_label_allowance")</label>
					    </div>
						<div class="input-field col s4">
					        <input type="text" class="validate number" placeholder="Ingrese el aguinaldo" id="aguinaldo" name="aguinaldo"/>
					        <label for="aguinaldo">@lang("cargosClientesEdit.form_label_13th_month")</label>
					    </div>        
					</div>
					<div class="row">
						<div class="input-field col s4">
					        <input type="text" class="validate number" id="comision" name="comision"/>
					        <label for="comision">@lang("cargosClientesEdit.form_label_commission")</label>
					    </div>
						<div class="input-field col s4">
					        <input type="text"  class="validate number" id="plus_rendimiento" name="plus_rendimiento"  />
					        <label  for="plus_rendimiento">@lang("cargosClientesEdit.form_label_variable_pay")</label>
					    </div>
						<div class="input-field col s4">
					        <input type="text"  class="validate number" id="fallo_caja" name="fallo_caja"  />
					        <label  for="fallo_caja">@lang("cargosClientesEdit.form_label_cash_failure")</label>
					    </div>        
					</div>
					<div class="row">
						<div class="input-field col s4">
					        <input type="text" class="validate number" id="fallo_caja_ext" name="fallo_caja_ext"  />
					        <label  for="fallo_caja_ext">@lang("cargosClientesEdit.form_label_cash_failre_fc")</label>
					    </div>
						<div class="input-field col s4">
					        <input type="text"  class="validate number" id="adicional_nivel_cargo" name="adicional_nivel_cargo"  />
					        <label  for="adicional_nivel_cargo">@lang("cargosClientesEdit.form_label_additional_level")</label>
					    </div>
						<div class="input-field col s4">
					        <input type="text"  class="validate number" id="adicional_titulo" name="adicional_titulo"  />
					        <label  for="adicional_titulo">@lang("cargosClientesEdit.form_label_additional_degree")</label>
					    </div>        
					</div>
					<div class="row">
						<div class="input-field col s4">
					        <input type="text"  class="validate number" id="gratificacion_contrato" name="gratificacion_contrato"  />
					        <label  for="gratificacion_contrato">@lang("cargosClientesEdit.form_label_fix_bonus")</label>
					    </div>        

						<div class="input-field col s4">
					        <input type="text"  class="validate number" id="adicional_amarre" name="adicional_amarre"  />
					        <label  for="adicional_amarre">@lang("cargosClientesEdit.form_label_plus_mooring")</label>
					    </div>
						<div class="input-field col s4">
					        <input type="text"  class="validate number" id="adicional_tipo_combustible" name="adicional_tipo_combustible"  />
					        <label  for="adicional_tipo_combustible">@lang("cargosClientesEdit.form_label_plus_fuel")</label>
					    </div>
					</div>
					<div class="row">
						<div class="input-field col s4">
					        <input type="text"  class="validate number" id="adicional_embarque" name="adicional_embarque"  />
					        <label  for="adicional_embarque">@lang("cargosClientesEdit.form_label_plus_shipping")</label>
					    </div>        

						<div class="input-field col s4">
					        <input type="text"  class="validate number" id="adicional_carga" name="adicional_carga"  />
					        <label  for="adicional_carga">@lang("cargosClientesEdit.form_label_plus_type_load")</label>
					    </div>
						<div class="input-field col s4">
					        <input type="text"  class="validate number" id="bono_anual" name="bono_anual"  />
					        <label  for="bono_anual">@lang("cargosClientesEdit.form_label_annual_bonus_amount")</label>
					    </div>
					</div>
					<div class="row">
						<div class="input-field col s4">
					        <input type="text"  class="validate number" id="bono_anual_salarios" name="bono_anual_salarios"  />
					        <label  for="bonoanual_salarios">@lang("cargosClientesEdit.form_label_annual_bonus_qty")</label>
					    </div>        

						<div class="input-field col s4">
					        <input type="text"  class="validate number" id="incentivo_largo_plazo" name="incentivo_largo_plazo"  />
					        <label  for="incentivo_largo_plazo">@lang("cargosClientesEdit.form_label_long_term_incentive")</label>
					    </div>
						<div class="input-field col s4">
					        <input type="text"  class="validate number" id="refrigerio" name="refrigerio"  />
					        <label  for="refrigerio">@lang("cargosClientesEdit.form_label_lunch")</label>
					    </div>
					</div>
					<div class="row">
						<div class="input-field col s4">
					        <input type="text"  class="validate number" id="costo_seguro_medico" name="costo_seguro_medico"  />
					        <label  for="costo_seguro_medico">@lang("cargosClientesEdit.form_label_health_insurance")</label>
					    </div>        
						<div class="input-field col s4">
					        <input type="text"  class="validate porcentaje" id="cobertura_seguro_medico" name="cobertura_seguro_medico"  />
					        <label  for="cobertura_seguro_medico">@lang("cargosClientesEdit.form_label_hi_coverage")</label>
					    </div>
						<div class="input-field col s4">
					        <input type="text"  class="validate number" id="costo_seguro_vida" name="costo_seguro_vida"  />
					        <label  for="costo_seguro_vida">@lang("cargosClientesEdit.form_label_life_insurance")</label>
					    </div>
					</div>
					<div class="row">
						<div class="input-field col s12">
						    <select name="aseguradora_id" id="aseguradora_id"> 
						      	<option value="" disabled selected>@lang("cargosClientesEdit.select_label_choose")</option>
						        @foreach($dbAseguradora as $id=>$descripcion)	
						        	<option value="{{$id}}">{{$descripcion}}</option>
						        @endforeach
						    </select>
						    <label for="aseguradora_id" class="active">@lang("cargosClientesEdit.form_label_li_provider")</label>
						</div>
					</div>
					<div class="row"> 
					<div class="row"> 
						<div class="col s4">
					    	<div class="row">
					    		<label>@lang("cargosClientesEdit.form_label_car_company")</label>
					    	</div>
					    	<div class="col s6">
					    		<label for="car_company_si">
					        		<input name="car_company" id="car_company_si" value="S" type="radio" class="with-gap"  />
					    			<span>@lang("cargosClientesEdit.option_label_yes")</span>
					    		</label>
					    	</div>
					    	<div class="col s6">
					    		<label for="car_company_no">
							        <input name="car_company" id="car_company_no" value="N" type="radio" class="with-gap"  />
					    			<span>@lang("cargosClientesEdit.option_label_no")</span>
					    		</label>
					    	</div>
						</div>
						<div class="input-field col s4">
							<div class="row">
								<label>@lang("cargosClientesEdit.form_label_fuel_card")</label><br>	
							</div>
					    	<div class="col s6">
					    		<label for="tarjeta_flota_si">
								    <input name="tarjeta_flota" id="tarjeta_flota_si" value="S" type="radio" class="with-gap"  />
					    			<span>@lang("cargosClientesEdit.option_label_yes")</span>
					    		</label>
					    	</div>
					    	<div class="col s6">
					    		<label for="tarjeta_flota_no">
								    <input name="tarjeta_flota" id="tarjeta_flota_no" value="N" type="radio" class="with-gap"  />
					    			<span>@lang("cargosClientesEdit.option_label_no")</span>
					    		</label>
					    	</div>
						</div>
						<div class="input-field col s4">
					        <input type="text"  class="validate number" id="flota" name="flota"  />
					        <label  for="flota">@lang("cargosClientesEdit.form_label_fuel_card_amount")</label>
					    </div>	
					</div>
					<div class="row">
						 <div class="input-field col s12">
							<select name="autos_marca_id" id="autos_marca_id"> 
						      	<option value="" disabled selected>@lang("cargosClientesEdit.select_label_choose")</option>
						        @foreach($dbMarca as $id=>$descripcion)	
						        	<option value="{{$id}}">{{$descripcion}}</option>
						        @endforeach
					     	</select>
						    <label for="autos_marca_id" class="active">@lang("cargosClientesEdit.form_label_car_brand")</label>
						</div>
					</div>
					<div class="row">				        
						 <div class="input-field col s12">
							<select name="autos_modelo_id" id="autos_modelo_id"> 
						      	<option value="" disabled selected>@lang("cargosClientesEdit.select_label_choose")</option>
						        @foreach($dbModelo as $id=>$descripcion)	
						        	<option value="{{$id}}">{{$descripcion}}</option>
						        @endforeach
						    </select>
						    <label for="autos_modelo_id" class="active">@lang("cargosClientesEdit.form_label_car_model")</label>
						</div>
					</div>
					<div class="row">
						<div class="input-field col s4">
					        <input type="text"  class="validate number" id="monto_movil" name="monto_movil"  />
					        <label  for="monto_movil">@lang("cargosClientesEdit.form_label_car_price")</label>
					    </div>        
						<div class="input-field col s4">
					        <input type="text"  class="validate number" id="seguro_movil" name="seguro_movil"  />
					        <label  for="seguro_movil">@lang("cargosClientesEdit.form_label_car_insurance")</label>
					    </div>
						<div class="input-field col s4">
					        <input type="text"  class="validate number" id="mantenimiento_movil" name="mantenimiento_movil"  />
					        <label  for="mantenimiento_movil">@lang("cargosClientesEdit.form_label_car_maintenance")</label>
					    </div>
					</div>
					<div class="row">
						<div class="input-field col s12">
					        <input type="text"  class="validate number" id="monto_km_recorrido" name="monto_km_recorrido"  />
					        <label  for="monto_km_recorrido">@lang("cargosClientesEdit.form_label_amount_km")</label>
					    </div>
					</div>
					<div class="row">
						<div class="input-field col s4">
					        <input type="text"  class="validate number" id="ayuda_escolar" name="ayuda_escolar"  />
					        <label  for="ayuda_escolar">@lang("cargosClientesEdit.form_label_children_education")</label>
					    </div>        
						<div class="input-field col s4">
					        <input type="text"  class="validate number" id="monto_comedor_interno" name="monto_comedor_interno"  />
					        <label  for="comedor_interno">@lang("cargosClientesEdit.form_label_meals_furnished")</label>
					    </div>
						<div class="input-field col s4">
					        <input type="text"  class="validate number" id="monto_celular_corporativo" name="monto_celular_corporativo"  />
					        <label  for="monto_celular_corporativo">@lang("cargosClientesEdit.form_label_cellular")</label>
					    </div>
					</div>
					<div class="row">
						<div class="input-field col s4">
					        <input type="text"  class="validate number" id="monto_curso_idioma" name="monto_curso_idioma"  />
					        <label  for="monto_curso_idioma">@lang("cargosClientesEdit.form_label_language_course")</label>
					    </div>        
						<div class="input-field col s4">
					        <input type="text"  class="validate porcentaje" id="cobertura_curso_idioma" name="cobertura_curso_idioma"  />
					        <label  for="cobertura_curso_idioma">@lang("cargosClientesEdit.form_label_lc_coverage")</label>
					    </div>
						<div class="input-field col s4">
					    	<div class="row">
					    		<label>@lang("cargosClientesEdit.form_label_lc_type")</label><br>	
					    	</div>
							<div class="col s6">
								<label for="tipo_clase_si">
							        <input name="tipo_clase_idioma" id="tipo_clase_si" value="G" type="radio" class="with-gap"  />
									<span>@lang("cargosClientesEdit.option_label_group")</span>
								</label>
							</div>
							<div class="col s6">
								<label for="tipo_clase_no">
									<input name="tipo_clase_idioma" id="tipo_clase_no" value="I" type="radio" class="with-gap"  />									
									<span>@lang("cargosClientesEdit.option_label_single")</span>
								</label>
							</div>
					    </div>
					</div>
					<div class="row">
						<div class="input-field col s6">
					        <input type="text"  class="validate number" id="monto_post_grado" name="monto_post_grado"  />
					        <label  for="monto_post_grado">@lang("cargosClientesEdit.form_label_education_tuition")</label>
					    </div>        
						<div class="input-field col s6">
					        <input type="text"  class="validate porcentaje" id="cobertura_post_grado" name="cobertura_post_grado"  />
					        <label  for="cobertura_post_grado">@lang("cargosClientesEdit.form_label_et_coverage")</label>
					    </div>
					</div>
					<div class="row">
						<div class="input-field col s4">
					        <input type="text"  class="validate number" id="monto_vivienda" name="monto_vivienda"  />
					        <label  for="monto_vivienda">@lang("cargosClientesEdit.form_label_house_rental")</label>
					    </div>        
						<div class="input-field col s4">
					        <input type="text"  class="validate number" id="monto_colegiatura_hijos" name="monto_colegiatura_hijos"  />
					        <label  for="monto_colegiatura_hijos">@lang("cargosClientesEdit.form_label_expat_children_edu")</label>
					    </div>
						<div class="input-field col s4">
					    	<div class="row">
					    		<label>@lang("cargosClientesEdit.form_label_incumbent_condition")</label>	
					    	</div>
							<div class="col s6">
								<label for="condicion_si">
							        <input name="condicion_ocupante" id="condicion_si" value="L" type="radio" class="with-gap"  />
									<span>@lang("cargosClientesEdit.option_label_local")</span>
								</label>						
							</div>					    	
							<div class="col s6">
								<label for="condicion_no">
							        <input name="condicion_ocupante" id="condicion_no" value="E" type="radio" class="with-gap"  />
									<span>@lang("cargosClientesEdit.option_label_expatriate")</span>
								</label>						
							</div>					    	
					    </div>
					</div>
					<div class="row">
						<div class="input-field col s12">
							<select name="zona_id" id="zona_id"> 
						      	<option value="" disabled selected>@lang("cargosClientesEdit.select_label_choose")</option>
						        @foreach($dbZona as $id=>$descripcion)	
						        	<option value="{{$id}}">{{$descripcion}}</option>
						        @endforeach

						    </select>
						    <label for="zona_id" class="active">@lang("cargosClientesEdit.form_label_region")</label><br>
					    </div>
					</div>
					<input type="hidden" name="_token" value="{{{ csrf_token() }}}" />
					{{ method_field('PUT') }}
					<button class="btn waves-effect waves-light" type="submit" name="submit">@lang("cargosClientesEdit.button_label_save")
    					<i class="material-icons left">save</i>
      				</button>
				</form>
	        </div>
		</div>
	</div>
@stop
@push('scripts')
	<script type="text/javascript">
		$(document).ready(function() {
		    $('select').select2();
		    


		    var check = true;
		    $("input").on("focusout", function(e) {
		      var id = "#"+e.target.id;
		      // validate input
		      if(check){
			      if(!isValid(id) ) { 
		            setTimeout(function(){
		                $(id).addClass('invalid');
		                $(id).focus();
		            }, 1);
		            check = false;
			      }
			   }else{
			   	check = true;
			   }
		      // copiar salario a aguinaldo
		      if(id === "#salario_base"){
		      	var sal = $(id).val();
				$("#aguinaldo").val(sal);

		      }
			});
			

			//fields validation
	        function isValid(element) {
		        var valid = true;
		        var must = false;
		        var val = $(element).val();
		        var numeric = false;

		        console.log("val", val);
		        if ($(element).attr("must") !== undefined){
		        	must = true;
		        }

		        
		        if($(element).hasClass("number") || $(element).hasClass("porcentaje")){
		        	numeric = true;
		        }
		        
		        if(must && val == "") {
		            valid = false;
		            $(element).nextAll('label:first').attr("data-error", "El campo es obligatorio");
		        }else if(numeric){
    				var number = 0;
    				if(val.indexOf(".") !== -1){
		    			number = parseInt(val.split('.').join(''));
		    		}else{
		    			number = parseInt(val);
		    		}
		        	if(val == ""){
			        	$(element).val(0);
			        	$(element).nextAll('label:first').addClass("active");
		        	}else if(number < 0 || number > 999999){
			        	console.log("holo");
			        	valid = false;
			        }
		        }else{
		        	$(element).removeClass("invalid");
		        	$(element).nextAll('label:first').removeAttr("data-error");
		        	valid = true;

		        }
		        return valid;
	    	}		

		});

			// number format
			$.fn.digits = function(type){ 
	    		
	    		var val = $(this).val();
	    		var number = 0;
	    		
	    		if (type == "M"){
					if(val.indexOf(".") !== -1){
		    			number = parseInt(val.split('.').join(''));
		    		}else if(val.indexOf(",") !== -1){
		    			number = parseInt(val.split(',').join(''));
		    		}else{
		    			number = parseInt(val);
		    		}
	    
		    		if(number < 0 || number > 999999){
		    			$(this).addClass("invalid");
		    			$(this).nextAll('label:first').attr("data-error", "Ha superado el valor máximo permitido");
		    			
		    		}else{
		    			if($(this).hasClass("invalid")){
		    				$(this).removeClass("invalid");
		    			}
		    		}
	    			$(this).val(number.toLocaleString());    			
	    		}else if(type == "P"){
					if(val.indexOf("%") !== -1){
		    			number = parseInt(val.split('%').join(''));
		    		}else{
		    			number = parseInt(val);
		    		}
	    
		    		if(number < 0 || number > 100){
		    			$(this).addClass("invalid");
		    			$(this).nextAll('label:first').attr("data-error", "Ha superado el valor máximo permitido");
		    			
		    		}else{
		    			if($(this).hasClass("invalid")){
		    				$(this).removeClass("invalid");
		    			}
		    		}
	    			$(this).val(number+"%");    			

	    		}

			}
			
			// valida que el usuario solo ingrese números
			$('input.number').on('keypress', function (e) {
			    var n = new RegExp("^([0-9]$)");				// regex para los números
			    if (n.test(String.fromCharCode(e.keyCode))) {	// realiza la comparación
			        return;
			    } else {
			        e.preventDefault();
			    }
			});
			
			// valida que el usuario solo ingrese números y da el formato adecuado
			$('input.number').keyup(function (e){
				var id = "#"+e.target.id; 						// id del elemento disparador
				var n = new RegExp("^([0-9]$)"); 				// regex para los números
				var val = $(id).val(); 							// valor del elemento
				var key = e.keyCode; 							// código de la tecla presionada
				if(key < 48 || key > 57){						// rango de los códigos de teclado para números
					key = key - 48;								// si se utiliza el teclado numérico se debe restar 48
				}
				if(n.test(String.fromCharCode(key))){			// realiza la comparación para saber si se ingresó un número
					$(id).digits("M");							// formatea el campo
				}else{
					if(event.which == 8 || event.which == 46){	// si se presiona backspace o del
						if(val !== ""){
							$(id).digits("M");						// formatea el campo	
						}
						
					}else{
						e.preventDefault();						// detiene el proceso de la tecla
					}
					
				}				
			});
			
			// valida que el usuario solo ingrese números
			$('input.porcentaje').on('keypress', function (e) {
			    var n = new RegExp("^([0-9]$)");				// regex para los números
			    if (n.test(String.fromCharCode(e.keyCode))) {	// realiza la comparación
			        return;
			    } else {
			        e.preventDefault();
			    }
			});

			$('input.porcentaje').keyup(function (e){
				var element = "#"+e.target.id;
				var number = 0;
				var val = $(element).val()
				var format = true;
				if(event.which >= 37 && event.which <= 40){
					return;	
				}else{
					if(event.which == 8){
						$(element).val(val.slice(0, -1));
					}
				}
				if(val !== ""){
					if(val == "%"){
						$(element).val("");
					}else{
						$(element).digits("P");		
					}
					
				}
			});
			// end number format
		    $("#autos_marca_id").change(function(){
		      var selectModelos = $("#autos_modelo_id");
		      var id = $(this).val();
		      selectModelos.empty();
		      $.post('{{route('autos.modelos')}}', {"marca_id": id, "_token": "{{csrf_token()}}"}, 
		        function(json){
		          var data = $.map(json, function(id, text){
		                      return {text:id, id:text};
		                    });
		                for(i = 0; i < data.length; i++){
		                  selectModelos.append(
		                    $("<option></option>").attr("value", data[i].id)
		                                    .text(data[i].text));
		          }

		          selectModelos.select2();
		        }
		      );
		    });


	</script>
@endpush