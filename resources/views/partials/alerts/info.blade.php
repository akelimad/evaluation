<div class="chm-alerts alert alert-info alert-white rounded">
    @if( !isset($dismissible) || $dismissible === true )
	    <button type="button" data-dismiss="alert" aria-hidden="true" class="close">x</button>
	@endif
    <div class="icon"><i class="fa fa-info-circle"></i></div>
    @if(is_array($messages))
        <ul>
        @foreach ($messages as $key => $message)
            <li><strong>{{ $message }}</strong></li>
        @endforeach
        </ul>
    @else
        <strong>{{ $messages }}</strong>
    @endif
</div>