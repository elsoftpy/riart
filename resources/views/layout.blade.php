<html>
    <head>
        
        <title>RiartConsulting</title>
        <link rel="shortcut icon" href="{{ asset('elsoft-white-favicon.ico') }}">
        <!-- Styles -->
        {!! MaterializeCSS::include_css() !!}
        <link rel="stylesheet" href="{{ asset('css/flash.css')}}">
        <style type="text/css"></style>
        <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
        
        <!-- Datatables css -->
        <link href="{{ asset('/plugins/datatables/dataTables-materialize.css') }}" rel="stylesheet"/>
        <!-- Select 2 Materialize -->
        <link href="{{ asset('/plugins/select2/select2-materialize.css') }}" rel="stylesheet"/>
        <!-- Selectize -->
        <link href="{{ asset('/plugins/selectize/selectize.css') }}" rel="stylesheet"/>   
        <!-- Auxiliar -->
        <link href="{{ asset('/css/auxiliar.css') }}" rel="stylesheet">   
        <!-- Intro JS -->
        <link href="{{ asset('/plugins/intro.js-2.7.0/introjs.css') }}" rel="stylesheet"/>     
        <link rel="stylesheet" href="{{asset('css/introCustom.css')}}" rel="stylesheet"/>
        <!-- Translation -->
        <link href="{{ asset('/css/translation.css') }}" rel="stylesheet">

    </head>
 
    <body>
      <div class="container" style="width: 90% !important">
          <!-- TOP MENU -->
          <ul id="dropdown1" class="dropdown-content">
              <li><a href="{{ route('cargos.index')}}">Formulario</a></li>

          </ul>
          <ul id="dropdown2" class="dropdown-content">
              <li><a href="{{route('reset.form')}}">Cambiar Contraseña</a></li>
              <li><a href="{{route('generate')}}">Generar contraseñas</a></li>
          </ul>
          <ul id="dropdown3" class="dropdown-content">
              <li><a href="{{ route('beneficios_admin.index') }}">Encuestas</a></li>
              <li><a href="{{ route('beneficios_preguntas.index') }}">Preguntas</a></li>
              <li><a href="{{ route('beneficios.admin.resultados') }}">Resultados</a></li>
              <li><a href="{{ route('beneficios.admin.conclusion') }}">Conclusiones</a></li>
          </ul>
          <ul id="dropdown4" class="dropdown-content">
              <li><a href="{{route('resultados')}}">Excel</a></li>
              <li><a href="{{route('admin.reporte.filter')}}">Reporte - Cargos</a></li>
          </ul>


          <nav class="nav-extended">
              <div class="nav-wrapper teal">
                   <a href="{{route('home.page')}}" class="brand-logo"><i class="material-icons left">poll</i>S&B</a> 
                  @if(Auth::check())
                    @if(Auth::user()->is_admin)
                      <div class="row" style="margin-bottom:0px !important;">
                        <ul id="nav-mobile" class="right hide-on-med-and-down">
                          <li>
                            <a href="{{ route('usuarios.index') }}">Usuarios</a>
                          </li>                      
                          <li>
                            <a href="{{ route('empresas.index') }}">Empresas</a>
                          </li>
                          <li>
                            <a href="{{ route('encuestas.index') }}">Encuestas</a>
                          </li>
                          <li>
                            <a href="{{ route('import_export.index') }}">Importar/Exportar</a>
                          </li>                        
                          <li>
                            <a href="{{ route('admin_ficha.index') }}">Ficha</a>
                          </li>
                          <li>
                          <a href="{{ route('areas.index') }}">Areas</a>
                          </li>
                          <li>
                            <a href="{{ route('niveles.index') }}">Niveles</a>
                          </li>                        
                          <li>  
                            <a href="{{ route('cargos.index') }}">Cargos Oficiales</a>
                          </li>
                          <li>
                            <a href="#!" class="dropdown-trigger" data-target="dropdown4">
                              Resultados
                              <i class="material-icons right">arrow_drop_down</i>
                            </a>
                          </li>
                         

                        </ul>
                      </div>
                      <div class="row">
                        <ul class="right hide-on-med-and-down">
                          <li>
                             <a href="{{ route('file_attachment') }}">Attachment</a>
                          </li>
                          <li>
                              <a href="#!" class="dropdown-trigger" data-target="dropdown3">
                                Beneficios
                                <i class="material-icons right">arrow_drop_down</i>
                              </a>
                          </li> 
                          <li>
                            <a href="#!" class="dropdown-trigger" data-target="dropdown2">
                              <i class="material-icons left">account_circle</i> 
                              {{ Auth::user()->username }}
                              <i class="material-icons right">arrow_drop_down</i>
                            </a>
                          </li>                         
                          <li>
                            <a href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">Logout
                            </a>
                            <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                              {{ csrf_field() }}
                            </form>                    
                          </li>
                        </ul>
                      </div>
                    @elseif(Auth::user()->is_benefit)
                      @yield('nav')
                    @else
                      <ul id="nav-mobile" class="right hide-on-med-and-down">
                        @if(\Request::is('home'))
                          <li>
                            <a href="#" id="tour">Tour</a>
                          </li>
                        @endif
                        <li>
                          <a href="#!" class="dropdown-trigger" data-target="dropdown2">
                            <i class="material-icons left">account_circle</i> 
                            {{ Auth::user()->username }}
                            <i class="material-icons right">arrow_drop_down</i>
                          </a>
                        </li> 
                        <li>
                          <a href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">Logout
                          </a>
                          <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                            {{ csrf_field() }}
                          </form>                    
                        </li>
                      </ul>
                    @endif
                  @else
                    <ul class="right hide-on-med-and-on-down">
                      <li><a href="{{ route('login')}}"> Login </a></li>
                    </ul>
                  @endif
              </div>
          </nav>
         <!-- End TOP MENU -->
         <!-- Breadcrumbs -->
         @yield("breadcrumbs")
         <!-- BODY OF PAGE -->
          <div class="row">
                @include('flash::message') 
              <div class="col s12 m12 l12" style="margin-top:10px;">
                @yield('content')
                @if (Auth::check())
                    @if (!Auth::user()->is_admin)
                      @include('includes.translation')        
                    @endif
                @else
                  @include('includes.translation')
                @endif
                
              </div>
          </div>
          <!-- logo -->
          <div class="col s12">
            <img class="hoverable bordered" style="width:20%; margin-top: 1em;margin-left:auto;margin-right:auto;display: block;" src="{{URL::asset('/images/logo.jpg')}}"/>
          </div>
         <!-- End BODY OF PAGE -->
      </div>
    </body>
    <script src="{{ URL::asset('/jQuery/jQuery-2.1.4.min.js') }}"></script>
    {!! MaterializeCSS::include_js() !!}
    <script src="{{ URL::asset('/jQuery/init.js') }}"></script>

    <!-- JQuery DataTable -->
    <script src="{{ asset('/plugins/datatables/jquery.dataTables.min.js') }}" type="text/javascript"></script>
    <script src="{{ asset('/plugins/datatables/dataTables.bootstrap.min.js') }}" type="text/javascript"></script>
    <script src="{{ asset('/plugins/datatables/dataTables.fixedColumns.min.js') }}" type="text/javascript"></script>
    <script src="{{ asset('/plugins/datatables/extensions/Responsive/js/dataTables.responsive.min.js') }}" type="text/javascript"></script>

    <!-- Select 2 -->
    <script src="{{ asset('/plugins/select2/select2.min.js') }}" type="text/javascript"></script>
    <!-- Selectize -->
    <script src="{{ asset('/plugins/selectize/selectize.js') }}" type="text/javascript"></script>    
    <!-- ChartJs -->
    <script src="{{ asset('/plugins/chartjs/Chart.bundle.js') }}" type="text/javascript"></script>   
    <!-- Intro JS -->
    <script src="{{ asset('/plugins/intro.js-2.7.0/intro.js') }}" type="text/javascript"></script>   
 <!-- InputMask -->
    <script src="{{ asset('plugins/input-mask/jquery.inputmask.bundle.js') }}"></script>
    <!-- mcafee -->
    <script type="text/javascript" src="https://cdn.ywxi.net/js/1.js" async></script>
    <script type="text/javascript">
      var tour = introJs().setOptions({ "skipLabel": "Lo tengo", 
                                        "nextLabel": "Continue", 
                                        "prevLabel": "Anterior", 
                                        "doneLabel": "Gracias", 
                                        "showBullets": false, 
                                        "showProgress": true, 
                                        "tooltipClass": "customIntro"
                                     });
      
      $('.modal').modal();

      $(document).ready(function(){
          $('.dropdown-trigger').dropdown();
          $('div.alert').delay(5000).slideUp(300);
          if ($('#flash-overlay-modal').length){
            $('#flash-overlay-modal').modal('open');  
          }
          

      });
    </script>
    @include('includes.translation_script')
    @stack('scripts')
</html>