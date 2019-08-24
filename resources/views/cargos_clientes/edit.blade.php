@extends('layout')
@section('content')
	<div class="row">
		<div class="browser-window">
			<div class="top-bar">
	          <h4>@lang('cargosClientesEdit.title_edit_position')</h4>
	        </div>
	        <div class="content">
				<form class="col s12" action="{{route('cargos_clientes.update', $dbData)}}" method="POST">
					<div class="row">

					<h6 class="red-text"> @lang('cargosClientesEdit.label_amount_disclaimer') </h6>
					<div class="row">
						<div class="input-field col s12">
							<input type="text" class="validate" id="descripcion" name="descripcion" value="{{$dbData->descripcion}}" />
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
						<div class="input-field col s12">
						    <select name="nivel_id" id="nivel_id"> 
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
					        <input type="number" class="validate" id="cantidad_ocupantes" name="cantidad_ocupantes" value="{{$dbDetalle->cantidad_ocupantes}}"/>
					        <label for="cantidad_ocupantes">@lang("cargosClientesEdit.form_label_incumbents")</label>
					    </div>
					</div>
					<div class="row">
						<div class="input-field col s4">
					        <input type="text"  id="salario_base" name="salario_base" value="{{$dbDetalle->salario_base}}" />
					        <label for="salario_base">@lang("cargosClientesEdit.form_label_salary")</label>
					    </div>
						<div class="input-field col s4">
					        <input type="text" class="validate" id="gratificacion" name="gratificacion" value="{{$dbDetalle->gratificacion}}"/>
					        <label for="gratificacion">@lang("cargosClientesEdit.form_label_allowance")</label>
					    </div>
						<div class="input-field col s4">
					        <input type="text" class="validate" placeholder="Ingrese el aguinaldo" id="aguinaldo" name="aguinaldo" value="{{$dbDetalle->aguinaldo}}"/>
					        <label for="aguinaldo">@lang("cargosClientesEdit.form_label_13th_month")</label>
					    </div>        
					</div>
					<div class="row">
						<div class="input-field col s4">
					        <input class="validate" type="text" id="comision" name="comision" value="{{$dbDetalle->comision}}"/>
					        <label for="comision">@lang("cargosClientesEdit.form_label_commission")</label>
					    </div>
						<div class="input-field col s4">
					        <input type="text"  class="validate" id="plus_rendimiento" name="plus_rendimiento"  value="{{$dbDetalle->plus_rendimiento}}" />
					        <label  for="plus_rendimiento" >@lang("cargosClientesEdit.form_label_variable_pay")</label>
					    </div>
						<div class="input-field col s4">
					        <input type="text"  class="validate" id="fallo_caja" name="fallo_caja"  value="{{$dbDetalle->fallo_caja}}"/>
					        <label  for="fallo_caja" >@lang("cargosClientesEdit.form_label_cash_failure")</label>
					    </div>        
					</div>
					<div class="row">
						<div class="input-field col s4">
					        <input type="text"  class="validate" id="fallo_caja_ext" name="fallo_caja_ext"  value="{{$dbDetalle->fallo_caja_ext}}"/>
					        <label  for="fallo_caja_ext" >@lang("cargosClientesEdit.form_label_cash_failre_fc")</label>
					    </div>
						<div class="input-field col s4">
					        <input type="text"  class="validate" id="adicional_nivel_cargo" name="adicional_nivel_cargo" value="{{$dbDetalle->adicional_nivel_cargo}}" />
					        <label  for="adicional_nivel_cargo">@lang("cargosClientesEdit.form_label_additional_level")</label>
					    </div>
						<div class="input-field col s4">
					        <input type="text"  class="validate" id="adicional_titulo" name="adicional_titulo" value="{{$dbDetalle->adicional_titulo}}" />
					        <label  for="adicional_titulo">@lang("cargosClientesEdit.form_label_additional_degree")</label>
					    </div>        
					</div>
					<div class="row">
						<div class="input-field col s4">
					        <input type="text"  class="validate" id="gratificacion_contrato" name="gratificacion_contrato" value="{{$dbDetalle->gratificacion_contrato}}" />
					        <label  for="gratificacion_contrato">@lang("cargosClientesEdit.form_label_fix_bonus")</label>
					    </div>        

						<div class="input-field col s4">
					        <input type="text"  class="validate" id="adicional_amarre" name="adicional_amarre"  value="{{$dbDetalle->adicional_amarre}}"/>
					        <label  for="adicional_amarre">@lang("cargosClientesEdit.form_label_plus_mooring")</label>
					    </div>
						<div class="input-field col s4">
					        <input type="text"  class="validate" id="adicional_tipo_combustible" name="adicional_tipo_combustible"  value="{{$dbDetalle->adicional_tipo_combustible}}"/>
					        <label  for="adicional_tipo_combustible">@lang("cargosClientesEdit.form_label_plus_fuel")</label>
					    </div>
					</div>
					<div class="row">
						<div class="input-field col s4">
					        <input type="text"  class="validate" id="adicional_embarque" name="adicional_embarque"  value="{{$dbDetalle->adicional_embarque}}"/>
					        <label  for="adicional_embarque">@lang("cargosClientesEdit.form_label_plus_shipping")</label>
					    </div>        

						<div class="input-field col s4">
					        <input type="text"  class="validate" id="adicional_carga" name="adicional_carga"  value="{{$dbDetalle->adicional_carga}}"/>
					        <label  for="adicional_carga">@lang("cargosClientesEdit.form_label_plus_type_load")</label>
					    </div>
						<div class="input-field col s4">
					        <input type="text"  class="validate" id="bono_anual" name="bono_anual"  value="{{$dbDetalle->bono_anual}}"/>
					        <label  for="bono_anual">@lang("cargosClientesEdit.form_label_annual_bonus_amount")</label>
					    </div>
					</div>
					<div class="row">
						<div class="input-field col s4">
					        <input type="text"  class="validate" id="bono_anual_salarios" name="bono_anual_salarios" value="{{$dbDetalle->bono_anual_salarios}}" />
					        <label  for="bonoanual_salarios">@lang("cargosClientesEdit.form_label_annual_bonus_qty")</label>
					    </div>        

						<div class="input-field col s4">
					        <input type="text"  class="validate" id="incentivo_largo_plazo" name="incentivo_largo_plazo"  value="{{$dbDetalle->incentivo_largo_plazo}}"/>
					        <label  for="incentivo_largo_plazo">@lang("cargosClientesEdit.form_label_long_term_incentive")</label>
					    </div>
						<div class="input-field col s4">
					        <input type="text"  class="validate" id="refrigerio" name="refrigerio"  value="{{$dbDetalle->refrigerio}}"/>
					        <label  for="refrigerio">@lang("cargosClientesEdit.form_label_lunch")</label>
					    </div>
					</div>
					<div class="row">
						<div class="input-field col s4">
					        <input type="text"  class="validate" id="costo_seguro_medico" name="costo_seguro_medico"  value="{{$dbDetalle->costo_seguro_medico}}"/>
					        <label  for="costo_seguro_medico">@lang("cargosClientesEdit.form_label_health_insurance")</label>
					    </div>        

						<div class="input-field col s4">
					        <input type="text"  class="validate" id="cobertura_seguro_medico" name="cobertura_seguro_medico"  value="{{$dbDetalle->cobertura_seguro_medico}}"/>
					        <label  for="cobertura_seguro_medico">@lang("cargosClientesEdit.form_label_hi_coverage")</label>
					    </div>
						<div class="input-field col s4">
					        <input type="text"  class="validate" id="costo_seguro_vida" name="costo_seguro_vida"  value="{{$dbDetalle->costo_seguro_vida}}"/>
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
						<div class="col s4">
					    	<div class="row">
					    		<label>@lang("cargosClientesEdit.form_label_car_company")</label>
					    	</div>
					    	<div class="col s6">
					    		<label for="car_company_si">
							        <input name="car_company" id="car_company_si" value="S" type="radio" class="with-gap" {{ $dbDetalle->car_company == 1 ? 'checked' : '' }} />
					    			<span>@lang("cargosClientesEdit.option_label_yes")</span>
					    		</label>
					    	</div>
					    	<div class="col s6">
					    		<label for="car_company_no">
							        <input name="car_company" id="car_company_no" value="N" type="radio" class="with-gap"  {{ $dbDetalle->car_company == 0 ? 'checked' : '' }}/>
					    			<span>@lang("cargosClientesEdit.option_label_no")</span>
					    		</label>
					    	</div>
						</div>
						<div class="col s4">
							<div class="row">
								<label>@lang("cargosClientesEdit.form_label_fuel_card")</label>	
							</div>
							<div class="col s6">
								<label for="tarjeta_flota_si">
						    		<input name="tarjeta_flota" id="tarjeta_flota_si" value="S" type="radio" class="with-gap"  {{ $dbDetalle->tarjeta_flota == 1 ? 'checked' : '' }} />
									<span>@lang("cargosClientesEdit.option_label_yes")</span>
								</label>
							</div>
							<div class="col s6">
								<label for="tarjeta_flota_no">
								    <input name="tarjeta_flota" id="tarjeta_flota_no" value="N" type="radio" class="with-gap" {{ $dbDetalle->tarjeta_flota == 0 ? 'checked' : '' }} />
									<span>@lang("cargosClientesEdit.option_label_no")</span>
								</label>								
							</div>
						</div>
						<div class="input-field col s4">
					        <input type="text"  class="validate" id="flota" name="flota"  value="{{$dbDetalle->flota}}"/>
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
					        <input type="text"  class="validate" id="monto_movil" name="monto_movil" value="{{$dbDetalle->monto_movil}}" />
					        <label  for="monto_movil">@lang("cargosClientesEdit.form_label_car_price")</label>
					    </div>        

						<div class="input-field col s4">
					        <input type="text"  class="validate" id="seguro_movil" name="seguro_movil" value="{{$dbDetalle->seguro_movil}}" />
					        <label  for="seguro_movil">@lang("cargosClientesEdit.form_label_car_insurance")</label>
					    </div>
						<div class="input-field col s4">
					        <input type="text"  class="validate"   id="mantenimiento_movil" name="mantenimiento_movil" value="{{$dbDetalle->mantenimiento_movil}}"  />
					        <label  for="mantenimiento_movil" >@lang("cargosClientesEdit.form_label_car_maintenance")</label>
					    </div>
					</div>
					<div class="row">
						<div class="input-field col s12">
					        <input type="text"  class="validate" id="monto_km_recorrido" name="monto_km_recorrido" value="{{$dbDetalle->monto_km_recorrido}}"  />
					        <label  for="monto_km_recorrido">@lang("cargosClientesEdit.form_label_amount_km")</label>
					    </div>
					</div>
					<div class="row">
						<div class="input-field col s4">
					        <input type="text"  class="validate" id="monto_ayuda_escolar" name="monto_ayuda_escolar"  value="{{$dbDetalle->monto_ayuda_escolar}}"/>
					        <label  for="ayuda_escolar">@lang("cargosClientesEdit.form_label_children_education")</label>
					    </div>        

						<div class="input-field col s4">
					        <input type="text"  class="validate" id="monto_comedor_interno" name="monto_comedor_interno" value="{{$dbDetalle->monto_comedor_interno}}"  />
					        <label  for="comedor_interno">@lang("cargosClientesEdit.form_label_meals_furnished")</label>
					    </div>
						<div class="input-field col s4">
					        <input type="text"  class="validate" id="monto_celular_corporativo" name="monto_celular_corporativo" value="{{$dbDetalle->monto_celular_corporativo}}"  />
					        <label  for="monto_celular_corporativo">@lang("cargosClientesEdit.form_label_cellular")</label>
					    </div>
					</div>
					<div class="row">
						<div class="input-field col s4">
					        <input type="text"  class="validate" id="monto_curso_idioma" name="monto_curso_idioma" value="{{$dbDetalle->monto_curso_idioma}}" />
					        <label  for="monto_curso_idioma">@lang("cargosClientesEdit.form_label_language_course")</label>
					    </div>        

						<div class="input-field col s4">
					        <input type="text"  class="validate" id="cobertura_curso_idioma" name="cobertura_curso_idioma" value="{{$dbDetalle->cobertura_curso_idioma}}" />
					        <label  for="cobertura_curso_idioma">@lang("cargosClientesEdit.form_label_lc_coverage")</label>
					    </div>
						<div class="col s4">
					    	<div class="row">
					    		<label>@lang("cargosClientesEdit.form_label_lc_type")</label>
					    	</div>
					    	<div class="col s6">
					        	<label for="tipo_clase_si">
					        		<input name="tipo_clase_idioma" id="tipo_clase_si" value="G" type="radio" class="with-gap" {{ $dbDetalle->tipo_clase_idioma == 'G' ? 'checked' : '' }} />
					        		<span>@lang("cargosClientesEdit.option_label_group")</span>
					    		</label>
					    	</div>
					    	<div class="col s6">
					    		<label for="tipo_clase_no">
					    			<input name="tipo_clase_idioma" id="tipo_clase_no" value="I" type="radio" class="with-gap" {{ $dbDetalle->tipo_clase_idioma == 'I' ? 'checked' : '' }} />
					    			<span>@lang("cargosClientesEdit.option_label_single")</span>
					    		</label>
					    	</div>
					    </div>
					</div>
					<div class="row">
						<div class="input-field col s6">
					        <input type="text"  class="validate" id="monto_post_grado" name="monto_post_grado" value="{{$dbDetalle->monto_post_grado}}" />
					        <label  for="monto_post_grado">@lang("cargosClientesEdit.form_label_education_tuition")</label>
					    </div>        

						<div class="input-field col s6">
					        <input type="text"  class="validate" id="cobertura_post_grado" name="cobertura_post_grado" value="{{$dbDetalle->cobertura_post_grado}}" />
					        <label  for="cobertura_post_grado">@lang("cargosClientesEdit.form_label_et_coverage")</label>
					    </div>
					</div>
					<div class="row">
						<div class="input-field col s4">
					        <input type="text"  class="validate" id="monto_vivienda" name="monto_vivienda" value="{{$dbDetalle->monto_vivienda}}"  />
					        <label  for="monto_vivienda">@lang("cargosClientesEdit.form_label_house_rental")</label>
					    </div>        

						<div class="input-field col s4">
					        <input type="text"  class="validate" id="monto_colegiatura_hijos" name="monto_colegiatura_hijos" value="{{$dbDetalle->monto_colegiatura_hijos}}" />
					        <label  for="monto_colegiatura_hijos">@lang("cargosClientesEdit.form_label_expat_children_edu")</label>
					    </div>
						<div class="col s4">
					    	<div class="row">
					    		<label>@lang("cargosClientesEdit.form_label_incumbent_condition")</label></br>	
					    	</div>
					    	
					        <div class="col s6">
						        <label for="condicion_si">
						        	<input name="condicion_ocupante" id="condicion_si" value="L" type="radio" class="with-gap"  {{ $dbDetalle->condicion_ocupante == 'L' ? 'checked' : '' }}/>
						        	<span>@lang("cargosClientesEdit.option_label_local")</span>
						    	</label>
					        </div>
					        <div class="col s6">
								<label for="condicion_no">
						        	<input name="condicion_ocupante" id="condicion_no" value="E" type="radio" class="with-gap"  {{ $dbDetalle->condicion_ocupante == 'E' ? 'checked' : '' }}/>
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
					<div class="row">
						<div class="col s4">
							<label for="excluir">
								@if ($dbData->incluir)
									<input name="excluir" id="excluir" value="1" type="checkbox">	
								@else
									<input name="excluir" id="excluir" value="1" type="checkbox" checked>
								@endif								
								<span>@lang("cargosClientesEdit.checkbox_label_exclude")</span>
							</label>
						</div>
						<div class="col s4">
							<label for="es_contrato_periodo">
								@if ($dbData->es_contrato_periodo)
									<input name="es_contrato_periodo" id="es_contrato_periodo" value="1" type="checkbox" checked/>
								@else
									<input name="es_contrato_periodo" id="es_contrato_periodo" value="1" type="checkbox"/>
								@endif
								<span>Contratado en periodo actual</span>
							</label>
						</div>
					</div> 
					<div class="row">
						<input type="hidden" name="_token" value="{{{ csrf_token() }}}" />
						{{ method_field('PUT') }}
						<button class="btn waves-effect waves-light" type="submit" name="submit">@lang("cargosClientesEdit.button_label_save")
	    					<i class="material-icons left">save</i>
	      				</button>
					</div>

				</form>
	        </div>
		</div>
	</div>
@stop
@push('scripts')
	<script type="text/javascript">
		$(document).ready(function() {
   			 $('select').select2();
		});

	 	$('#area_id').val('{{ $dbDetalle->area_id}}');
	 	$('#nivel_id').val('{{ $dbDetalle->nivel_id}}');
	 	$('#aseguradora_id').val('{{ $dbDetalle->aseguradora_id}}');
	 	$('#autos_marca_id').val('{{ $dbDetalle->autos_marca_id}}');
	 	$('#autos_modelo_id').val('{{ $dbDetalle->autos_modelo_id}}');
	 	$('#zona_id').val('{{ $dbDetalle->zona_id}}');

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
