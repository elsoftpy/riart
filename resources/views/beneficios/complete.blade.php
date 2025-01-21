@extends('layouts.report')
@include('includes.benefit_nav')
@section('breadcrumbs')
  <nav>
    <div class="nav-wrapper teal lighten-3">
      <div class="col s12">
        <a href="{{route('home')}}" class="breadcrumb">
        	<i class="material-icons left">home</i>@lang('beneficiosReportIndex.breadcrumb_home')
        </a>
        <a class="breadcrumb">
        	@lang('beneficiosReportIndex.breadcrumb_complete')
        </a>
      </div>
    </div>
  </nav>
@endsection
@section('content')
<h5>
  <strong>@lang('beneficiosReportIndex.title_date')</strong> {{$dbData->periodo}}
</h5>
<div class="search-container">
  <input type="text" 
         id="searchInput" 
         placeholder="@lang('beneficiosReportIndex.search_question')">
  <div id="searchResults" class="search-results"></div>
</div>
  <div class="content">
    <form  name="cuestionario" id="cuestionario" action="{{route('beneficios.update', $dbData->id)}}" method="POST" >
      <div class="row">
        <div class="hoverable col s12">
          @foreach ($dbDetalle as $detalle)
            <div class="row question-container" id="{{$detalle->id}}">
              @if(App::isLocale('en'))
                <span class="anchor-tag" style="display:none">#{{ str_slug($detalle->pregunta_en) }}</span>
              @else
                <span class="anchor-tag" style="display:none">#{{ str_slug($detalle->pregunta) }}</span>
              @endif
              <p style="padding-left: 1em;">
                @if (App::isLocale('en'))
                  <strong>{{$detalle->orden}} ) {{$detalle->pregunta_en}}</strong>
                @else 
                  <strong>{{$loop->iteration}} ) {{$detalle->pregunta}}</strong>
                @endif
                
              </p>
            @if($detalle->cerrada != 'S')
              <div class="input-field col s11" style="width: 88% !important">
                @if($dbData->detalleBeneficio->where('beneficios_pregunta_id', $detalle->id)->first())
                  <textarea class="materialize-textarea" name="{{$detalle->id}}" style="margin-left: 3em; margin-right: 1em;">{{$dbData->detalleBeneficio->where('beneficios_pregunta_id', $detalle->id)->first()->abierta}}</textarea>         
                @else
                  <textarea class="materialize-textarea" name="{{$detalle->id}}" style="margin-left: 3em; margin-right: 1em;"></textarea>         
                @endif
                
              </div>
            @else
              @if($detalle->id == 66)
                <div class="input-field col s11">
                  <select name="{{$detalle->id}}" class="select2" id="autos_64"> 
                          @foreach($dbMarca as $id=>$descripcion) 
                            @if($dbData->detalleBeneficio->where('beneficios_pregunta_id', $detalle->id)->first())
                              @if($dbData->detalleBeneficio->where('beneficios_pregunta_id', $detalle->id)->first()->beneficios_opcion_id == $id)
                                <option value="{{$id}}" selected="selected">{{$descripcion}}</option>
                              @else                         
                            <option value="{{$id}}">{{$descripcion}}</option>
                              @endif
                            @else
                          <option value="{{$id}}">{{$descripcion}}</option>
                            @endif
                          @endforeach
                    </select>
                </div>
              @elseif($detalle->id == 67)
                <div class="input-field col s11">
                  <select name="{{$detalle->id}}" class="select2" id="modelos_65"> 
                          @foreach($dbModelo as $id=>$descripcion)  
                            @if($dbData->detalleBeneficio->where('beneficios_pregunta_id', $detalle->id)->first())
                              @if($dbData->detalleBeneficio->where('beneficios_pregunta_id', $detalle->id)->first()->beneficios_opcion_id == $id)
                                <option value="{{$id}}" selected="selected">{{$descripcion}}</option>
                              @else                         
                            <option value="{{$id}}">{{$descripcion}}</option>
                              @endif
                            @else
                          <option value="{{$id}}">{{$descripcion}}</option>
                            @endif
                          @endforeach
                    </select>
                </div>
              @elseif($detalle->id == 74)
                <div class="input-field col s11">
                  <select name="{{$detalle->id}}" class="select2" id="autos_74"> 
                          @foreach($dbMarca as $id=>$descripcion) 
                            @if($dbData->detalleBeneficio->where('beneficios_pregunta_id', $detalle->id)->first())
                              @if($dbData->detalleBeneficio->where('beneficios_pregunta_id', $detalle->id)->first()->beneficios_opcion_id == $id)
                                <option value="{{$id}}" selected="selected">{{$descripcion}}</option>
                              @else                         
                            <option value="{{$id}}">{{$descripcion}}</option>
                              @endif
                            @else
                          <option value="{{$id}}">{{$descripcion}}</option>
                            @endif

                          @endforeach
                    </select>
                </div>

              @elseif($detalle->id == 80)
                <div class="input-field col s11">
                  <select name="{{$detalle->id}}" class="select2" id="seguros_80"> 
                          @foreach($dbAseguradora as $id=>$descripcion) 
                            @if($dbData->detalleBeneficio->where('beneficios_pregunta_id', $detalle->id)->first())
                              @if($dbData->detalleBeneficio->where('beneficios_pregunta_id', $detalle->id)->first()->beneficios_opcion_id == $id)
                                <option value="{{$id}}" selected="selected">{{$descripcion}}</option>
                              @else                         
                            <option value="{{$id}}">{{$descripcion}}</option>
                              @endif
                            @else
                          <option value="{{$id}}">{{$descripcion}}</option>
                            @endif
                          @endforeach
                    </select>
                </div>

              @elseif( $detalle->beneficiosOpcion->count() == 0)
                consultar
              @else 
                <div class="input-field col s11">
                  @if($detalle->multiple)
                    @foreach( $detalle->beneficiosOpcion as $option)
                            @php
                              $existe = $dbData->detalleBeneficio->where('beneficios_pregunta_id', $detalle->id)->first();
                              $opcionId = $dbData->detalleBeneficio->where('beneficios_pregunta_id', $detalle->id)->where('beneficios_opcion_id', $option->id)->first();
                              if(App::isLocale('en')){
                                $opcion = $option->opcion_en;
                              }else{
                                $opcion = $option->opcion;
                              }
                            @endphp
                            @if($opcionId)
                              @if ($opcionId->beneficios_opcion_id == $option->id)
                                <p>
                                  <label for="{{$option->id}}" style="padding-right: 1em;">
                                    <input type="checkbox" value="{{$option->id}}" checked="checked" id="{{$option->id}}" name="checks[]" question="{{$detalle->id}}"/>
                                    <span>{{$opcion}}</span>    
                                  </label></br>
                                </p>
                              @else
                                <p>
                                  <label for="{{$option->id}}" style="padding-right: 1em;">
                                    <input type="checkbox" value="{{$option->id}}" id="{{$option->id}}" name="checks[]" question="{{$detalle->id}}"/>
                                    <span>{{$opcion}}</span>
                                  </label></br>
                                </p>
                              @endif
                      @else
                        <p>
                          <label for="{{$option->id}}">      
                            <input type="checkbox" value="{{$option->id}}" id="{{$option->id}}" name="checks[]" question="{{$detalle->id}}"/>
                            <span>{{$opcion}}</span>
                          </label></br>
                        </p>
                      @endif

                    @endforeach
                  @else
                    <select name="{{$detalle->id}}" class="select2" style="padding-right: 2em;" placeholder="Elija una opción" > 
                    <option></option> 
                    @foreach( $detalle->beneficiosOpcion as $option)
                        @php
                            if(App::isLocale('en')){
                              $opcion = $option->opcion_en;
                            }else{
                              $opcion = $option->opcion;
                            }
                        @endphp
                            @if($dbData->detalleBeneficio->where('beneficios_pregunta_id', $detalle->id)->first())
                              @if ($dbData->detalleBeneficio->where('beneficios_pregunta_id', $detalle->id)->first()->beneficios_opcion_id == $option->id)
                                <option value="{{$option->id}}" selected="selected">{{$opcion}}</option>
                              @else
                                <option value="{{$option->id}}">{{$opcion}}</option>
                              @endif
                          @else
                              <option value="{{$option->id}}">{{$opcion}}</option>
                          @endif 
                            
                        @endforeach
                    </select>
                    @endif
                </div>
              @endif                    
            @endif
            </div>
          @endforeach     
        </div>
      </div>  
      <input type="hidden" name="_token" value="{{{ csrf_token() }}}">
      {{ method_field('PUT') }}
      
      <div class="row">
        <div class="button-group">
          <button class="btn waves-effect waves-light right" type="submit" name="submit">Guardar
                <i class="material-icons left">save</i>
                </button>
        </div>
      </div>
    </form>
  </div>
@endsection
@push('scripts')
  <script type="text/javascript">
    $(document).ready(function() {
        $('.select2').select2({placeholder:"Elija una opción"});
    });

    $("#autos_64").change(function(){
      var selectModelos = $("#modelos_65");
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

          $(".select2").select2();
        }
      );
    });

    $(document).on('change', '[type=checkbox]', function(){
      
      var name = $(this).attr('question');
      var value = this.value;
      var id = 'input_'+this.value;

      if(!$('#'+id).length){
        if($(this).is(':checked')){
          $('<input>').attr({type: 'hidden', name:name+'[]', value:value, id:value}).appendTo('#cuestionario'); 
        }else{
          $('<input>').attr({type: 'hidden', name:name+'[]', value:"remove_"+value, id:value}).appendTo('#cuestionario');
        }
      }

    });

    document.addEventListener('DOMContentLoaded', function() {
        const searchInput = document.getElementById('searchInput');
        const searchResults = document.getElementById('searchResults');
        
        // Get all questions
        const questions = Array.from(document.querySelectorAll('.question-container')).map(container => ({
            id: container.id,
            text: container.querySelector('strong').textContent,
            tag: container.querySelector('.anchor-tag').textContent
        }));

        // Search functionality
        searchInput.addEventListener('input', function(e) {
            const searchTerm = e.target.value.toLowerCase();
            searchResults.innerHTML = '';
            
            if (searchTerm.trim() === '') {
                searchResults.style.display = 'none';
                return;
            }

            const matches = questions.filter(question => 
                question.text.toLowerCase().includes(searchTerm) ||
                question.tag.toLowerCase().includes(searchTerm)
            );

            if (matches.length > 0) {
                matches.forEach(match => {
                    const div = document.createElement('div');
                    div.className = 'search-result-item';
                    div.textContent = match.text;
                    div.addEventListener('click', () => scrollToQuestion(match.id));
                    searchResults.appendChild(div);
                });
                searchResults.style.display = 'block';
            } else {
                searchResults.style.display = 'none';
            }
        });

        // Improved scroll to question function
        function scrollToQuestion(questionId) {
            const element = document.getElementById(questionId);
            if (element) {
                // Get the navbar height (if you have a fixed navbar)
                const navbarHeight = document.querySelector('nav') ? document.querySelector('nav').offsetHeight : 0;
                
                // Calculate the element's position relative to the viewport
                const elementPosition = element.getBoundingClientRect().top;
                
                // Calculate the offset from the top of the page
                const offsetPosition = elementPosition + window.pageYOffset - navbarHeight - 20; // 20px extra padding
                
                // Smooth scroll to the element
                window.scrollTo({
                    top: offsetPosition,
                    behavior: 'smooth'
                });
                
                // Add highlight effect
                element.classList.add('highlight');
                setTimeout(() => {
                    element.classList.remove('highlight');
                }, 2000);

                // Clear search
                searchInput.value = '';
                searchResults.style.display = 'none';
            }
        }

        // Close search results when clicking outside
        document.addEventListener('click', function(e) {
            if (!searchResults.contains(e.target) && e.target !== searchInput) {
                searchResults.style.display = 'none';
            }
        });
    });




  </script>
@endpush