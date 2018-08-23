@section('nav')
	<ul id="nav-mobile" class="right hide-on-med-and-down">
        @if(\Request::is('home'))
          <li>
            <a href="#" id="tour">Tour</a>
          </li>
        @endif
		<li>
		  <a href="{{route('beneficios.edit', $dbEmpresa->id)}}">Completar</a>
		</li>
		<li>
		  <a href="{{route('beneficios.panel', $dbEmpresa->id)}}">Panel de Empresas</a>
		</li>
		<li data-intro="<p class='intro-title'><strong>REPORTE</strong><p>Click aquí para visualizar los resultados de la Encuesta de Prácticas y Beneficios" data-step="1">
		  <a href="{{ route('beneficios.show', $dbEmpresa->id) }}">Reporte</a>
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
@endsection
