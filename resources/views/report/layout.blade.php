<html>
    <head>
        
        <title>RiartConsulting</title>
        <link rel="shortcut icon" href="{{ asset('elsoft-white-favicon.ico') }}">
        <!-- Styles -->
        {!! MaterializeCSS::include_css() !!}
        <link rel="stylesheet" href="{{ asset('css/flash.css')}}">
        <style type="text/css"></style>
        <link href="http://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
        <!-- Datatables css -->
        <link href="{{ asset('/plugins/datatables/dataTables-materialize.css') }}" rel="stylesheet"/>
        <!-- Select 2 Materialize -->
        <link href="{{ asset('/plugins/select2/select2-materialize.css') }}" rel="stylesheet"/>
        <!-- Intro JS -->
        <link href="{{ asset('/plugins/intro.js-2.7.0/introjs.css') }}" rel="stylesheet"/>     
        <link rel="stylesheet" href="{{asset('css/introCustom.css')}}" rel="stylesheet"/>
        <!-- Translation -->
        <link href="{{ asset('/css/translation.css') }}" rel="stylesheet">
    </head>
 
    <body>
      <div class="container" style="width: 90% !important">
          <ul id="dropdown1" class="dropdown-content">
              <li><a href="{{ route('reportes.filter', $dbEmpresa) }}" id="intro-buscar"> @lang('reportLayout.menu_search') </a></li>
              <li><a href="{{ route('reportes.cargosRubro', $dbEmpresa) }}" id="intro-universo"> @lang('reportLayout.menu_universe') </a></li>
          </ul>

          <nav>
              <div class="nav-wrapper teal">
                   <a href="{{route('home.page')}}" class="brand-logo"><i class="material-icons left">poll</i>S&B</a> 
                  @if(Auth::check())
                      <ul id="nav-mobile" class="right hide-on-med-and-down">
                        <li>
                          <a href="{{ route('reportes.panel', $dbEmpresa) }}" data-intro="<p class='intro-title'><strong>PANEL</strong></p>Acceda al listado de Empresas/Bancos participantes." data-step="27">@lang('reportLayout.menu_panel') </a>
                        </li>
                        <li>
                          <a href="{{ route('reportes.conceptos', $dbEmpresa) }}" data-intro="<p class='intro-title'><strong>CONCEPTOS TECNICOS</strong></p>Acceda al diccionario de conceptos utilizados en la plataforma." data-step="26">@lang('reportLayout.menu_concepts')</a>
                        </li>                      
                        <li>
                          <a href="{{ route('reportes.metodologia', $dbEmpresa) }}" data-intro="<p class='intro-title'><strong>METODOLOGIA</strong></p>Descripción del procedimiento realizado para la recolección de la información y la presentación de los resultados." data-step="28">@lang('reportLayout.menu_methodology')</a>
                        </li>
                        <li>
                          <a href="{{ route('reportes.ficha', $dbEmpresa) }}" data-intro="Ficha Técnica" data-step="21">@lang('reportLayout.menu_sheet')</a>
                        </li>
                        <li>
                          <a href="#!" class="dropdown-trigger" data-target="dropdown1" id="intro-cargos">
                            @lang('reportLayout.menu_indicators')
                            <i class="material-icons right">arrow_drop_down</i>
                          </a>                          
                        </li>
                        <li>
                          <a href="#">
                            <i class="material-icons left">account_circle</i> 
                            {{ Auth::user()->username }}
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
                  @else
                    <ul class="right hide-on-med-and-on-down">
                      <li><a href="{{ route('login')}}"> Login </a></li>
                    </ul>
                  @endif
              </div>
          </nav>
         <!-- End TOP MENU -->
         
         <!-- BODY OF PAGE -->
          <div class="row">
                @include('flash::message') 
              <div class="col s12" style="margin-top:10px;">
                @yield('content')
                @include('includes.translation')
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
    <!-- ChartJs -->
    <script src="{{ asset('/plugins/chartjs/Chart.bundle.js') }}" type="text/javascript"></script> 
    <!-- Intro JS -->
    <script src="{{ asset('/plugins/intro.js-2.7.0/intro.js') }}" type="text/javascript"></script>         

    <script type="text/javascript">
          var tour = introJs().setOptions({ "skipLabel": "Lo tengo", 
                                        "nextLabel": "Continue", 
                                        "prevLabel": "Anterior", 
                                        "doneLabel": "Gracias", 
                                        "showBullets": false, 
                                        "showProgress": true, 
                                        "tooltipClass": "customIntro"
                                    });      
          var dropdown = '';
          var elemDropdown = '';
      $(document).ready(function(){
          elemDropdown = $('.dropdown-trigger').dropdown({coverTrigger:false});
          dropdown = M.Dropdown.getInstance(elemDropdown);
          
          $('div.alert').delay(5000).slideUp(300);
          if ($('#flash-overlay-modal').length){
            $('#flash-overlay-modal').modal('open');  
          }
      });
    </script>
    @include('includes.translation_script')
    @stack('scripts')
</html>