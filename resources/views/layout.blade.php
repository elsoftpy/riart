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
        <link href="{{ asset('/plugins/datatables/dataTables.bootstrap.css') }}" rel="stylesheet"/>
        <!-- Select 2 Materialize -->
        <link href="{{ asset('/plugins/select2/select2-materialize.css') }}" rel="stylesheet"/>
    </head>
 
    <body>
      <div class="container" style="width: 90% !important">
          <!-- TOP MENU -->
          <ul id="dropdown1" class="dropdown-content">
              <li><a href="{{ route('cargos.index')}}">Formulario</a></li>

          </ul>
          <nav>
              <div class="nav-wrapper teal">
                   <a href="{{route('home.page')}}" class="brand-logo"><i class="material-icons left">poll</i>S&B</a> 
                  @if(Auth::check())
                    @if(Auth::user()->is_admin)
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
                          <a href="{{ route('cargos.index') }}">Cargos Oficiales</a>
                        </li>

<!--
                        <li>
                          <a class="dropdown-button" href="#!" data-activates="dropdown1">Cargos<i class="material-icons right">arrow_drop_down</i></a>
                        </li> 
-->
                        <li><a href="{{ route('resultados') }}">Resultados</a></li>
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
                      <ul id="nav-mobile" class="right hide-on-med-and-down">
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

                    @endif
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
              <div class="col s12 m12 l12" style="margin-top:10px;">
                @yield('content')
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

    
    <script type="text/javascript">
       $(document).ready(function(){
          $('#dropmenu').dropdown({belowOrigin: false});
          $('div.alert').delay(5000).slideUp(300);
          if ($('#flash-overlay-modal').length){
            $('#flash-overlay-modal').openModal();  
          }
          

        });
    </script>
    @stack('scripts')
</html>