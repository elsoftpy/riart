<div id="flash-overlay-modal" class="modal {{ $modalClass or '' }}">
    <div class="modal-dialog">
        <div class="modal-content">
            <h4> {{ $title}} </h4>
            <p> {{ $body }} </p>

            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>