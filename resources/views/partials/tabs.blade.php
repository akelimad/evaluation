<ul class="nav nav-tabs">
    <li class="{{ !Request::segment(5) ? 'active': '' }}"">
        <a href="{{url('entretiens/'.$e->id.'/u/'.$user->id)}}">Synthèse</a>
    </li>
    @foreach($evaluations as $evaluation)
    <li class="{{ Request::segment(5) == App\Evaluation::unaccented($evaluation->title) ? 'active':'' }}">
        <a href="{{url('entretiens/'.$e->id.'/u/'.$user->id.'/'.App\Evaluation::unaccented($evaluation->title))}}">{{ $evaluation->title }}</a>
    </li>
    @endforeach
</ul>