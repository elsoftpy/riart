@extends('layout')
@section('content')
	<div class="row">
		<div class="browser-window">
			<div class="top-bar">
	          <h4>Editar Cargo</h4>
	        </div>
	        <div class="content">
				<form class="col s12" action="{{route('cargos_clientes.update', $dbData)}}" method="POST">
					<div class="row">

					<h6 class="red-text"> Los montos se cargan en moneda local y en miles de Guaraníes </h6>
					<div class="row">
						<div class="input-field col s12">
							<input type="text" class="validate" id="descripcion" name="descripcion" value="{{$dbData->descripcion}}" />
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
						<div class="input-field col s12">
						    <select name="nivel_id" id="nivel_id"> 
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
					        <input type="number" class="validate"   id="cantidad_ocupantes" name="cantidad_ocupantes" value="{{$dbDetalle->cantidad_ocupantes}}"/>
					        <label for="cantidad_ocupantes">Cantidad de Personas</label>
					    </div>
					</div>
					<div class="row">
						<div class="input-field col s4">
					        <input type="text"  id="salario_base" name="salario_base" value="{{$dbDetalle->salario_base}}" />
					        <label for="salario_base">Salario</label>
					    </div>
						<div class="input-field col s4">
					        <input type="text" class="validate"  id="gratificacion" name="gratificacion" value="{{$dbDetalle->gratificacion}}"/>
					        <label for="gratificacion">Gratificación</label>
					    </div>
						<div class="input-field col s4">
					        <input type="text" class="validate" placeholder="Ingrese el aguinaldo" id="aguinaldo" name="aguinaldo" value="{{$dbDetalle->aguinaldo}}"/>
					        <label for="aguinaldo">Aguinaldo</label>
					    </div>        
					</div>
					<div class="row">
						<div class="input-field col s4">
					        <input class="validate" type="text"  id="comision" name="comision" value="{{$dbDetalle->comision}}"/>
					        <label for="comision">Comisión Mensual</label>
					    </div>
						<div class="input-field col s4">
					        <input type="text"  class="validate"  id="plus_rendimiento" name="plus_rendimiento"  value="{{$dbDetalle->plus_rendimiento}}" />
					        <label  for="plus_rendimiento" >Plus por Rendimiento</label>
					    </div>
						<div class="input-field col s4">
					        <input type="text"  class="validate"   id="fallo_caja" name="fallo_caja"  value="{{$dbDetalle->fallo_caja}}"/>
					        <label  for="fallo_caja" >Fallo de Caja</label>
					    </div>        
					</div>
					<div class="row">
						<div class="input-field col s4">
					        <input type="text"  class="validate" id="fallo_caja_ext" name="fallo_caja_ext"  value="{{$dbDetalle->fallo_caja_ext}}"/>
					        <label  for="fallo_caja_ext" >Fallo de Caja Moneda Extranjera</label>
					    </div>
						<div class="input-field col s4">
					        <input type="text"  class="validate"   id="adicional_nivel_cargo" name="adicional_nivel_cargo" value="{{$dbDetalle->adicional_nivel_cargo}}" />
					        <label  for="adicional_nivel_cargo" >Adicional por Nivel de Cargo</label>
					    </div>
						<div class="input-field col s4">
					        <input type="text"  class="validate"   id="adicional_titulo" name="adicional_titulo" value="{{$dbDetalle->adicional_titulo}}" />
					        <label  for="adicional_titulo" >Adicional por Título</label>
					    </div>        
					</div>
					<div class="row">
						<div class="input-field col s4">
					        <input type="text"  class="validate"   id="gratificacion_contrato" name="gratificacion_contrato" value="{{$dbDetalle->gratificacion_contrato}}" />
					        <label  for="gratificacion_contrato" >Gratificación por Contrato</label>
					    </div>        

						<div class="input-field col s4">
					        <input type="text"  class="validate"  id="adicional_amarre" name="adicional_amarre"  value="{{$dbDetalle->adicional_amarre}}"/>
					        <label  for="adicional_amarre" >Adicional por Amarre</label>
					    </div>
						<div class="input-field col s4">
					        <input type="text"  class="validate"  id="adicional_tipo_combustible" name="adicional_tipo_combustible"  value="{{$dbDetalle->adicional_tipo_combustible}}"/>
					        <label  for="adicional_tipo_combustible" >Adicional por tipo de combustible</label>
					    </div>
					</div>
					<div class="row">
						<div class="input-field col s4">
					        <input type="text"  class="validate"   id="adicional_embarque" name="adicional_embarque"  value="{{$dbDetalle->adicional_embarque}}"/>
					        <label  for="adicional_embarque" >Adicional por Embarque</label>
					    </div>        

						<div class="input-field col s4">
					        <input type="text"  class="validate"   id="adicional_carga" name="adicional_carga"  value="{{$dbDetalle->adicional_carga}}"/>
					        <label  for="adicional_carga" >Adicional por Carga</label>
					    </div>
						<div class="input-field col s4">
					        <input type="text"  class="validate"   id="bono_anual" name="bono_anual"  value="{{$dbDetalle->bono_anual}}"/>
					        <label  for="bono_anual" >Bono Anual (Monto)</label>
					    </div>
					</div>
					<div class="row">
						<div class="input-field col s4">
					        <input type="text"  class="validate"   id="bono_anual_salarios" name="bono_anual_salarios" value="{{$dbDetalle->bono_anual_salarios}}" />
					        <label  for="bonoanual_salarios" >Bono Anual (Cantidad)</label>
					    </div>        

						<div class="input-field col s4">
					        <input type="text"  class="validate"   id="incentivo_largo_plazo" name="incentivo_largo_plazo"  value="{{$dbDetalle->incentivo_largo_plazo}}"/>
					        <label  for="incentivo_largo_plazo" >Incentivo a Largo Plazo</label>
					    </div>
						<div class="input-field col s4">
					        <input type="text"  class="validate"   id="refrigerio" name="refrigerio"  value="{{$dbDetalle->refrigerio}}"/>
					        <label  for="refrigerio" >Refrigerio</label>
					    </div>
					</div>
					<div class="row">
						<div class="input-field col s4">
					        <input type="text"  class="validate"   id="costo_seguro_medico" name="costo_seguro_medico"  value="{{$dbDetalle->costo_seguro_medico}}"/>
					        <label  for="costo_seguro_medico" >Seguro Médico</label>
					    </div>        

						<div class="input-field col s4">
					        <input type="text"  class="validate"   id="cobertura_seguro_medico" name="cobertura_seguro_medico"  value="{{$dbDetalle->cobertura_seguro_medico}}"/>
					        <label  for="cobertura_seguro_medico" >Cobertura del Seguro</label>
					    </div>
						<div class="input-field col s4">
					        <input type="text"  class="validate" id="costo_seguro_vida" name="costo_seguro_vida"  value="{{$dbDetalle->costo_seguro_vida}}"/>
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

					        <input name="car_company" id="car_company_si" value="S" type="radio" class="with-gap" {{ $dbDetalle->car_company == 1 ? 'checked' : '' }} />
					        <label for="car_company_si">Sí</label>

					        <input name="car_company" id="car_company_no" value="N" type="radio" class="with-gap"  {{ $dbDetalle->car_company == 0 ? 'checked' : '' }}/>
					        <label for="car_company_no">No</label>
						</div>
						<div class="input-field col s4">
							<label>Tarjeta Flota</label><br>
						    <input name="tarjeta_flota" id="tarjeta_flota_si" value="S" type="radio" class="with-gap"  {{ $dbDetalle->tarjeta_flota == 1 ? 'checked' : '' }} />
						    <label for="tarjeta_flota_si">Sí</label>

						    <input name="tarjeta_flota" id="tarjeta_flota_no" value="N" type="radio" class="with-gap" {{ $dbDetalle->tarjeta_flota == 0 ? 'checked' : '' }} />
						    <label for="tarjeta_flota_no">No</label>
						</div>
						<div class="input-field col s4">
					        <input type="text"  class="validate"   id="flota" name="flota"  value="{{$dbDetalle->flota}}"/>
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
					        <input type="text"  class="validate"   id="monto_movil" name="monto_movil" value="{{$dbDetalle->monto_movil}}" />
					        <label  for="monto_movil" >Valor del Automóvil</label>
					    </div>        

						<div class="input-field col s4">
					        <input type="text"  class="validate"   id="seguro_movil" name="seguro_movil" value="{{$dbDetalle->seguro_movil}}" />
					        <label  for="seguro_movil" >Seguro del Automóvil</label>
					    </div>
						<div class="input-field col s4">
					        <input type="text"  class="validate"   id="mantenimiento_movil" name="mantenimiento_movil" value="{{$dbDetalle->mantenimiento_movil}}"  />
					        <label  for="mantenimiento_movil" >Costo de Mantenimiento Automóvil</label>
					    </div>
					</div>
					<div class="row">
						<div class="input-field col s12">
					        <input type="text"  class="validate"   id="monto_km_recorrido" name="monto_km_recorrido" value="{{$dbDetalle->monto_km_recorrido}}"  />
					        <label  for="monto_km_recorrido" >Monto por Km Recorrido</label>
					    </div>
					</div>
					<div class="row">
						<div class="input-field col s4">
					        <input type="text"  class="validate"   id="ayuda_escolar" name="ayuda_escolar"  value="{{$dbDetalle->ayuda_escolar}}"/>
					        <label  for="ayuda_escolar" >Ayuda Escolar</label>
					    </div>        

						<div class="input-field col s4">
					        <input type="text"  class="validate"   id="comedor_interno" name="comedor_interno" value="{{$dbDetalle->comedor_interno}}"  />
					        <label  for="comedor_interno" >Comedor Interno</label>
					    </div>
						<div class="input-field col s4">
					        <input type="text"  class="validate"   id="monto_celular_corporativo" name="monto_celular_corporativo" value="{{$dbDetalle->monto_celular_corporativo}}"  />
					        <label  for="monto_celular_corporativo" >Celular Corporativo</label>
					    </div>
					</div>
					<div class="row">
						<div class="input-field col s4">
					        <input type="text"  class="validate"   id="monto_curso_idioma" name="monto_curso_idioma" value="{{$dbDetalle->monto_curso_idioma}}" />
					        <label  for="monto_curso_idioma" >Curso de Idiomas</label>
					    </div>        

						<div class="input-field col s4">
					        <input type="text"  class="validate"   id="cobertura_curso_idioma" name="cobertura_curso_idioma" value="{{$dbDetalle->cobertura_curso_idioma}}" />
					        <label  for="cobertura_curso_idioma" >Cobertura del curso de idiomas</label>
					    </div>
						<div class="input-field col s4">
					    	<label>Tipo de Curso de Idioma</label><br>

					        <input name="tipo_clase_idioma" id="tipo_clase_si" value="G" type="radio" class="with-gap" {{ $dbDetalle->tipo_clase_idioma == 'G' ? 'checked' : '' }} />
					        <label for="tipo_clase_si">Grupal</label>

							<input name="tipo_clase_idioma" id="tipo_clase_no" value="I" type="radio" class="with-gap" {{ $dbDetalle->tipo_clase_idioma == 'I' ? 'checked' : '' }} />
					        <label for="tipo_clase_no">Individual</label>
					    </div>
					</div>
					<div class="row">
						<div class="input-field col s6">
					        <input type="text"  class="validate"   id="monto_post_grado" name="monto_post_grado" value="{{$dbDetalle->monto_post_grado}}" />
					        <label  for="monto_post_grado" >Post Grado o Maestría</label>
					    </div>        

						<div class="input-field col s6">
					        <input type="text"  class="validate"   id="cobertura_post_grado" name="cobertura_post_grado" value="{{$dbDetalle->cobertura_post_grado}}" />
					        <label  for="cobertura_post_grado" >Cobertura de la Maestría</label>
					    </div>
					</div>
					<div class="row">
						<div class="input-field col s4">
					        <input type="text"  class="validate"   id="monto_vivienda" name="monto_vivienda" value="{{$dbDetalle->monto_vivienda}}"  />
					        <label  for="monto_vivienda" >Importe de Cobertura por Vivienda</label>
					    </div>        

						<div class="input-field col s4">
					        <input type="text"  class="validate"   id="monto_colegiatura_hijos" name="monto_colegiatura_hijos" value="{{$dbDetalle->monto_colegiatura_hijos}}" />
					        <label  for="monto_colegiatura_hijos" >Importe por Cobertura de Colegiatura</label>
					    </div>
						<div class="input-field col s4">
					    	<label class="select-label">Condición del Ocupante</label><br>

					        <input name="condicion_ocupante" id="condicion_si" value="L" type="radio" class="with-gap"  {{ $dbDetalle->condicion_ocupante == 'L' ? 'checked' : '' }} />
					        <label for="condicion_si">Local</label>

					        <input name="condicion_ocupante" id="condicion_no" value="E" type="radio" class="with-gap"  {{ $dbDetalle->condicion_ocupante == 'E' ? 'checked' : '' }} />
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
					<div class="row">
						<input ng-model="carCompany" name="excluir" id="excluir" value="1" type="checkbox" class="with-gap"  />
						<label for="excluir">Excluir</label>
					</div> 
					<div class="row">
						<input type="hidden" name="_token" value="{{{ csrf_token() }}}" />
						{{ method_field('PUT') }}
						<button class="btn waves-effect waves-light" type="submit" name="submit">Guardar
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
	</script>
@endpush
