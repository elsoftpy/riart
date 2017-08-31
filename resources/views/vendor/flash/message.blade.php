@if (session()->has('flash_notification.message'))
    @if (session()->has('flash_notification.overlay'))
        @include('flash::modal', [
            'modalClass' => session('flash_modal_class'), 
            'title'      => session('flash_notification.title'), 
            'body'       => session('flash_notification.message'),
            'number'      => session('flash_notification.number')
        ])
    @else
        <div class="alert alert-{{ session('flash_notification.level') }}">
            <button type="button" 
                    class="close" 
                    data-dismiss="alert" 
                    aria-hidden="true">&times;</button>

            {!! session('flash_notification.message') !!}
        </div>
    @endif
@endif