@extends('layout')

@section('content')
	<div class="row">
		<div class="col l4">
			<a href="{{ route('beneficios_preguntas.create') }}" class="btn waves-effect waves-light lighten-1 white-text"><i class="material-icons left">add</i>Preguntas</a>
		</div>
	</div>	
		<div class="row">
			<div class="browser-window">
				<div class="top-bar">
                  <h4>Listado de Preguntas</h4>
                </div>
                <div class="content">
                	<table id="Listado" class="highlight">
                		<thead>
	                      <tr>
	                      	 <th>Id</th>
	                      	 <th>Orden</th>
	                      	 <th>Pregunta</th>
	                      	 <th>Cerrada</th>
	                      	 <th>Multiple</th>
	                      	 <th>Beneficio</th>
	                      	 <th></th>
							 <th></th>
	                      </tr>
	                    </thead>
	                    <tbody>
	                    	@foreach($dbData as $est) 
	                    		<tr>
		                    		<td>{{ $est->id }}</td>
		                    		<td>{{ $est->orden }}</td>
		                    		<td>{{ $est->pregunta}}</td>
		                    		<td>
		                    			@if ($est->cerrada == 'S')
		                    				Sí
		                    			@else
		                    				No
		                    			@endif
		                    			
		                    		</td>
		                    		<td>
		                    			@if ($est->multiple)
		                    				Sí
		                    			@else
		                    				No
		                    			@endif
		                    		</td>
		                    		<td>
		                    			@if ($est->beneficio)
		                    				Sí
		                    			@else
		                    				No
		                    			@endif
		                    		</td>

		                    		<td style="width: 25%">
		                    			<a href="{{ route('beneficios_preguntas.edit', $est->id) }}" class="btn waves-light waves-effect lighten-1 amber white-text " style="margin-bottom: 0.5em;">
		                    				<i class="material-icons left">edit</i> Editar
		                    			</a>
				                    </td>
	                    		</tr>
	                    	@endforeach
	                    </tbody>
	                </table>
	                {!! $dbData->links() !!}
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



		$("#close-check").click(function(e){
			$("#modal-check").closeModal();
		});

	</script>
@endpush