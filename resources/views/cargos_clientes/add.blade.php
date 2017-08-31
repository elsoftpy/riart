@extends('layout')
@section('content')
	<div class="row">
		<div class="browser-window">
			<div class="top-bar">
	          <h4>Crear Nuevo Cargo</h4>
	        </div>
	        <div class="content">
				<form class="col s12" action="{{route('encuestas_cargos.update', $id)}}" method="POST">
					<h6 class="red-text"> Los montos se cargan en moneda local y en miles de Guaraníes </h6>
					<div class="row">
						<div class="input-field col s12">
							<input type="text" class="validate" id="descripcion" name="descripcion" must/>
							<label for="descripcion">Cargo</label>

						</div>	
					</div>

					<div class="row">
						<div class="input-field col s12">
							<select name="area_id" id="area_id"> 
						        <option value="" disabled selected>Elija una opción</option>
						        @foreach($dbArea as $id=>$descripcion)	
						        	<option value="{{$id}}">{{$descripcion}}</option>
						        @endforeach
						    </select>
					    	<label for="area_id">Area</label>
							
						</div>
					</div>
					<div class="row">
						<div class="input-field col s12 select-container">
						    
						    <select name="nivel_id" id="nivel_id" must> 
						      	<option value="" disabled selected>Elija una opción</option>
						        @foreach($dbNivel as $id=>$descripcion)	
						        	<option value="{{$id}}">{{$descripcion}}</option>
						        @endforeach

						     </select>
						 	 <label for="nivel_id">Nivel</label>
						 </div>
					</div>
					<div class="row">
						<div class="input-field col s12">
					        <input type="text" class="validate number"   id="cantidad_ocupantes" name="cantidad_ocupantes"/>
					        <label for="cantidad_ocupantes">Cantidad de Personas</label>
					    </div>
					</div>
					<div class="row">
						<div class="input-field col s4">
					        <input type="text" class="validate number"  id="salario_base" name="salario_base" must/>
					        <label for="salario_base">Salario</label>
					    </div>
						<div class="input-field col s4">
					        <input type="text" class="validate number"  id="gratificacion" name="gratificacion"/>
					        <label for="gratificacion">Gratificación</label>
					    </div>
						<div class="input-field col s4">
					        <input type="text" class="validate number" placeholder="Ingrese el aguinaldo" id="aguinaldo" name="aguinaldo"/>
					        <label for="aguinaldo">Aguinaldo</label>
					    </div>        
					</div>
					<div class="row">
						<div class="input-field col s4">
					        <input type="text" class="validate number" id="comision" name="comision"/>
					        <label for="comision">Comisión Mensual</label>
					    </div>
						<div class="input-field col s4">
					        <input type="text"  class="validate number"  id="plus_rendimiento" name="plus_rendimiento"  />
					        <label  for="plus_rendimiento" >Plus por Rendimiento</label>
					    </div>
						<div class="input-field col s4">
					        <input type="text"  class="validate number"   id="fallo_caja" name="fallo_caja"  />
					        <label  for="fallo_caja" >Fallo de Caja</label>
					    </div>        
					</div>
					<div class="row">
						<div class="input-field col s4">
					        <input type="text" class="validate number" id="fallo_caja_ext" name="fallo_caja_ext"  />
					        <label  for="fallo_caja_ext" >Fallo de Caja Moneda Extranjera</label>
					    </div>
						<div class="input-field col s4">
					        <input type="text"  class="validate number"   id="adicional_nivel_cargo" name="adicional_nivel_cargo"  />
					        <label  for="adicional_nivel_cargo" >Adicional por Nivel de Cargo</label>
					    </div>
						<div class="input-field col s4">
					        <input type="text"  class="validate number"   id="adicional_titulo" name="adicional_titulo"  />
					        <label  for="adicional_titulo" >Adicional por Título</label>
					    </div>        
					</div>
					<div class="row">
						<div class="input-field col s4">
					        <input type="text"  class="validate number"   id="gratificacion_contrato" name="gratificacion_contrato"  />
					        <label  for="gratificacion_contrato" >Gratificación por Contrato</label>
					    </div>        

						<div class="input-field col s4">
					        <input type="text"  class="validate number"  id="adicional_amarre" name="adicional_amarre"  />
					        <label  for="adicional_amarre" >Adicional por Amarre</label>
					    </div>
						<div class="input-field col s4">
					        <input type="text"  class="validate number"  id="adicional_tipo_combustible" name="adicional_tipo_combustible"  />
					        <label  for="adicional_tipo_combustible" >Adicional por tipo de combustible</label>
					    </div>
					</div>
					<div class="row">
						<div class="input-field col s4">
					        <input type="text"  class="validate number"   id="adicional_embarque" name="adicional_embarque"  />
					        <label  for="adicional_embarque" >Adicional por Embarque</label>
					    </div>        

						<div class="input-field col s4">
					        <input type="text"  class="validate number"   id="adicional_carga" name="adicional_carga"  />
					        <label  for="adicional_carga" >Adicional por Carga</label>
					    </div>
						<div class="input-field col s4">
					        <input type="text"  class="validate number"   id="bono_anual" name="bono_anual"  />
					        <label  for="bono_anual" >Bono Anual (Monto)</label>
					    </div>
					</div>
					<div class="row">
						<div class="input-field col s4">
					        <input type="text"  class="validate number"   id="bono_anual_salarios" name="bono_anual_salarios"  />
					        <label  for="bonoanual_salarios" >Bono Anual (Cantidad)</label>
					    </div>        

						<div class="input-field col s4">
					        <input type="text"  class="validate number"   id="incentivo_largo_plazo" name="incentivo_largo_plazo"  />
					        <label  for="incentivo_largo_plazo" >Incentivo a Largo Plazo</label>
					    </div>
						<div class="input-field col s4">
					        <input type="text"  class="validate number"   id="refrigerio" name="refrigerio"  />
					        <label  for="refrigerio" >Refrigerio</label>
					    </div>
					</div>
					<div class="row">
						<div class="input-field col s4">
					        <input type="text"  class="validate number"   id="costo_seguro_medico" name="costo_seguro_medico"  />
					        <label  for="costo_seguro_medico" >Seguro Médico</label>
					    </div>        

						<div class="input-field col s4">
					        <input type="text"  class="validate porcentaje"   id="cobertura_seguro_medico" name="cobertura_seguro_medico"  />
					        <label  for="cobertura_seguro_medico" >Cobertura del Seguro</label>
					    </div>
						<div class="input-field col s4">
					        <input type="text"  class="validate number" id="costo_seguro_vida" name="costo_seguro_vida"  />
					        <label  for="costo_seguro_vida" >Seguro de Vida</label>
					    </div>
					</div>
					<div class="row">
						<div class="input-field col s12">
						    <select name="aseguradora_id" id="aseguradora_id"> 
						      	<option value="" disabled selected>Elija una opción</option>
						        @foreach($dbAseguradora as $id=>$descripcion)	
						        	<option value="{{$id}}">{{$descripcion}}</option>
						        @endforeach

						     </select>
						     <label for="aseguradora_id">Aseguradora</label>
						</div>
					</div>
					<div class="row"> 
						<div class="input-field col s4">
					    	<label>Automóvil Empresa (Car Company)</label><br>

					        <input name="car_company" id="car_company_si" value="S" type="radio" class="with-gap"  />
					        <label for="car_company_si">Sí</label>

					        <input name="car_company" id="car_company_no" value="N" type="radio" class="with-gap"  />
					        <label for="car_company_no">No</label>
						</div>
						<div class="input-field col s4">
							<label>Tarjeta Flota</label><br>
						    <input name="tarjeta_flota" id="tarjeta_flota_si" value="S" type="radio" class="with-gap"  />
						    <label for="tarjeta_flota_si">Sí</label>

						    <input name="tarjeta_flota" id="tarjeta_flota_no" value="N" type="radio" class="with-gap"  />
						    <label for="tarjeta_flota_no">No</label>
						</div>
						<div class="input-field col s4">
					        <input type="text"  class="validate number"   id="flota" name="flota"  />
					        <label  for="flota" >Monto de la Tarjeta Flota</label>
					    </div>	
					</div>
					<div class="row">
						 <div class="input-field col s12">
							 <select name="autos_marca_id" id="autos_marca_id"> 
						      	<option value="" disabled selected>Elija una opción</option>
						        @foreach($dbMarca as $id=>$descripcion)	
						        	<option value="{{$id}}">{{$descripcion}}</option>
						        @endforeach

						     </select>
						     <label for="autos_marca_id">Marca del Automóvil</label>
						</div>
					</div>
					<div class="row">				        
						 <div class="input-field col s12">
							 <select name="autos_modelo_id" id="autos_modelo_id"> 
						      	<option value="" disabled selected>Elija una opción</option>
						        @foreach($dbMarca as $id=>$descripcion)	
						        	<option value="{{$id}}">{{$descripcion}}</option>
						        @endforeach

						     </select>
						     <label for="autos_modelo_id">Modelo del Automóvil</label>
						</div>
					</div>
					<div class="row">
						<div class="input-field col s4">
					        <input type="text"  class="validate number"   id="monto_movil" name="monto_movil"  />
					        <label  for="monto_movil" >Valor del Automóvil</label>
					    </div>        

						<div class="input-field col s4">
					        <input type="text"  class="validate number"   id="seguro_movil" name="seguro_movil"  />
					        <label  for="seguro_movil" >Seguro del Automóvil</label>
					    </div>
						<div class="input-field col s4">
					        <input type="text"  class="validate number"   id="mantenimiento_movil" name="mantenimiento_movil"  />
					        <label  for="mantenimiento_movil" >Costo de Mantenimiento Automóvil</label>
					    </div>
					</div>
					<div class="row">
						<div class="input-field col s12">
					        <input type="text"  class="validate number"   id="monto_km_recorrido" name="monto_km_recorrido"  />
					        <label  for="monto_km_recorrido" >Monto por Km Recorrido</label>
					    </div>
					</div>
					<div class="row">
						<div class="input-field col s4">
					        <input type="text"  class="validate number"   id="ayuda_escolar" name="ayuda_escolar"  />
					        <label  for="ayuda_escolar" >Ayuda Escolar</label>
					    </div>        

						<div class="input-field col s4">
					        <input type="text"  class="validate number"   id="comedor_interno" name="comedor_interno"  />
					        <label  for="comedor_interno" >Comedor Interno</label>
					    </div>
						<div class="input-field col s4">
					        <input type="text"  class="validate number"   id="monto_celular_corporativo" name="monto_celular_corporativo"  />
					        <label  for="monto_celular_corporativo" >Celular Corporativo</label>
					    </div>
					</div>
					<div class="row">
						<div class="input-field col s4">
					        <input type="text"  class="validate number"   id="monto_curso_idioma" name="monto_curso_idioma"  />
					        <label  for="monto_curso_idioma" >Curso de Idiomas</label>
					    </div>        

						<div class="input-field col s4">
					        <input type="text"  class="validate porcentaje"   id="cobertura_curso_idioma" name="cobertura_curso_idioma"  />
					        <label  for="cobertura_curso_idioma" >Cobertura del curso de idiomas</label>
					    </div>
						<div class="input-field col s4">
					    	<label>Tipo de Curso de Idioma</label><br>

					        <input name="tipo_clase_idioma" id="tipo_clase_si" value="G" type="radio" class="with-gap"  />
					        <label for="tipo_clase_si">Grupal</label>

							<input name="tipo_clase_idioma" id="tipo_clase_no" value="I" type="radio" class="with-gap"  />
					        <label for="tipo_clase_no">Individual</label>
					    </div>
					</div>
					<div class="row">
						<div class="input-field col s6">
					        <input type="text"  class="validate number"   id="monto_post_grado" name="monto_post_grado"  />
					        <label  for="monto_post_grado" >Post Grado o Maestría</label>
					    </div>        

						<div class="input-field col s6">
					        <input type="text"  class="validate porcentaje"   id="cobertura_post_grado" name="cobertura_post_grado"  />
					        <label  for="cobertura_post_grado" >Cobertura de la Maestría</label>
					    </div>
					</div>
					<div class="row">
						<div class="input-field col s4">
					        <input type="text"  class="validate number"   id="monto_vivienda" name="monto_vivienda"  />
					        <label  for="monto_vivienda" >Importe de Cobertura por Vivienda</label>
					    </div>        

						<div class="input-field col s4">
					        <input type="text"  class="validate number"   id="monto_colegiatura_hijos" name="monto_colegiatura_hijos"  />
					        <label  for="monto_colegiatura_hijos" >Importe por Cobertura de Colegiatura</label>
					    </div>
						<div class="input-field col s4">
					    	<label class="select-label">Condición del Ocupante</label><br>

					        <input name="condicion_ocupante" id="condicion_si" value="L" type="radio" class="with-gap"  />
					        <label for="condicion_si">Local</label>

					        <input name="condicion_ocupante" id="condicion_no" value="E" type="radio" class="with-gap"  />
					        <label for="condicion_no">Expatriado</label>
					    </div>
					</div>
					<div class="row">
						<div class="input-field col s12">
							<select name="zona_id" id="zona_id"> 
						      	<option value="" disabled selected>Elija una opción</option>
						        @foreach($dbZona as $id=>$descripcion)	
						        	<option value="{{$id}}">{{$descripcion}}</option>
						        @endforeach

						    </select>
						    <label for="zona_id">Región</label><br>
					    </div>
					</div>
				<!--	<div class="row">
						<input ng-model="carCompany" name="excluir" id="excluir" value="1" type="checkbox" class="with-gap"  />
						<label for="excluir">Excluir</label>
					</div> -->
					<input type="hidden" name="_token" value="{{{ csrf_token() }}}" />
					{{ method_field('PUT') }}
					<button class="btn waves-effect waves-light" type="submit" name="submit">Guardar
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
		    $('select').material_select();
		    


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


	</script>
@endpush