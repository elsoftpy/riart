    <!-- Selected language badge -->    
    <div style="position:fixed; top:70px; right:66px;">
        @if (app()->getLocale() == "es")
          <span class="new badge" data-badge-caption="Español" style="box-shadow: 0 2px 2px 0 rgba(0,0,0,0.14),0 3px 1px -2px rgba(0,0,0,0.12),0 1px 5px 0 rgba(0,0,0,0.2);">Idioma seleccionado: </span>    
        @else
          <span class="new badge" data-badge-caption="English" style="box-shadow: 0 2px 2px 0 rgba(0,0,0,0.14),0 3px 1px -2px rgba(0,0,0,0.12),0 1px 5px 0 rgba(0,0,0,0.2);">Selected language: </span>
        @endif      
    </div>
    <!-- Language selector button -->
    <div class="fixed-action-btn" style="bottom:50px !important;">
        @if (app()->getLocale() == 'es')
          <a class="btn-floating btn-large tooltipped amber" data-position="left" data-tooltip="Cambiar idioma">    
        @else
          <a class="btn-floating btn-large tooltipped red" data-position="left" data-tooltip="Change language">  
        @endif                   
            <i class="large material-icons">language</i>
          </a>
          <ul>
                <li>
                    <a class="btn-floating amber" href="{{route('lang.switch', 'es')}}" id="lang_switch_es">
                        <strong>ES</strong>
                    </a>
                    <a href="" class="language-switch-tooltip btn-floating">Español</a>
                </li>
                <li>
                    <a class="btn-floating red" href="{{route('lang.switch', 'en')}}" id="lang_switch_en">
                        <strong>EN</strong>
                    </a>
                    <a href="" class="language-switch-tooltip btn-floating">English</a>
                </li>
          </ul>
    </div>

