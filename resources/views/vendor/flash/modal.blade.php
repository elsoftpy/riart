<div id="flash-overlay-modal" class="modal {{ $modalClass or '' }}">
    <div class="modal-content">
        <div>
            <h4 class="white-text"> {{ $title}} </h4>
        </div>
        <div class="modal-body">
            <p style="margin-left: 1em;"> {{ $number }} </p>    
            <p style="margin-left: 1em;" class="black-text"> {!! $body !!} </p>    
            <div class="modal-footer">
                <a href="#!" class="modal-action modal-close waves-effect btn">Cerrar </a>
            </div>
        </div>
    </div>
</div>