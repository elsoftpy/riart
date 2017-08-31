@extends('layout')

@section('content')
	<div class="row">
		<div class="browser-window">
			<div class="top-bar">
              <h4>Listado de Encuestas</h4>
            </div>
            <div class="content">
            	<table id="Listado" class="highlight">
            		<thead>
                      <tr>
                      	 <th>Id</th>
                      	 <th>Descripcion</th>
                      	 <th>Inicio</th>
                      	 <th>Fin</th>
                      	 <th>Estado</th>
                      	 <th></th>
						 <th></th>
                      </tr>
                    </thead>
                    <tbody>
                    	@foreach($dbData as $est) 
                    		<tr>
	                    		<td>{{ $est->id }}</td>
	                    		<td>{{ $est->descripcion}}</td>
	                    		<td>{{ $est->fecha_vigencia_ini->format('d/m/Y')}}</td>
	                    		<td>{{ $est->fecha_vigencia_fin->format('d/m/Y')}}</td>
	                    		<td>{{ $est->estado}}</td>
	                    		<td>
	                    			<a href="{{ route('aplicantes.create', ["id"=>$est->id]) }}" class="btn waves-light waves-effect lighten-1 white-text ">
	                    				<i class="material-icons left">assignment</i>Asignar
	                    			</a>
	                    		</td>
                    		</tr>
                    	@endforeach
                    </tbody>
                </table>
            </div>
		</div>
	</div>
@endsection
@push('scripts')
	<script type="text/javascript">
		function delete_row(row){
			event.preventDefault(); 
			if (confirm('Seguro que desea eliminar el registro?')){
				document.getElementById('delete-form'+row).submit();
			} else {
				return false;
			}
		}
	</script>
@endpush