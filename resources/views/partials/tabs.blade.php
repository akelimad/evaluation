<ul class="nav nav-tabs">
    <li class="{{ !Request::segment(5) ? 'active': '' }}"">
        <a href="{{url('entretiens/'.$e->id.'/u/'.$user->id)}}">Synthèse</a>
    </li>
    @if((App\Entretien::answered($e->id, $user->id) && $user->id != Auth::user()->id) )

	    @foreach($evaluations as $evaluation)
	    <li class="{{ Request::segment(5) == App\Evaluation::unaccented($evaluation->title) ? 'active':'' }}">
	        <a href="{{url('entretiens/'.$e->id.'/u/'.$user->id.'/'.App\Evaluation::unaccented($evaluation->title))}}">{{ $evaluation->title }}</a>
	    </li>
	    @endforeach

    @endif


    @if( $user->id == Auth::user()->id)

      @foreach($evaluations as $evaluation)
      <li class="{{ Request::segment(5) == App\Evaluation::unaccented($evaluation->title) ? 'active':'' }}">
          <a href="{{url('entretiens/'.$e->id.'/u/'.$user->id.'/'.App\Evaluation::unaccented($evaluation->title))}}">{{ $evaluation->title }}</a>
      </li>
      @endforeach

    @endif

</ul>