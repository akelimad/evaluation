<ul class="nav nav-tabs">
  <li class="{{ Request::segment(5) == 'synthese' ? 'active':'' }}">
    <a href="{{ route('anglets.synthese', ['e_id' => $e->id, 'uid' => $user->id]) }}">Synthèse</a>
  </li>
  @foreach($evaluations as $evaluation)
    @php($anglet = App\Evaluation::unaccented($evaluation->title))
    <li class="{{ Request::segment(5) == App\Evaluation::unaccented($evaluation->title) ? 'active':'' }}">
      <a href="{{ route('anglets.'.$anglet, ['eid' => $e->id, 'uid' => $user->id]) }}">{{ $evaluation->title }}</a>
    </li>
  @endforeach
</ul>