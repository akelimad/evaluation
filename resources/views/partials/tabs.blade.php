<ul class="nav nav-tabs mb-sm-40">
  <li class="{{ Request::segment(5) == 'synthese' ? 'active':'' }} pull-none pull-md-left">
    <a href="{{ route('anglets.synthese', ['e_id' => $e->id, 'uid' => $user->id]) }}">Synthèse</a>
  </li>
  @if ($e->isFeedback360())
    <li class="{{ Request::segment(7) == 'feedback360' ? 'active':'' }} pull-none pull-md-left">
      <a href="{{ route('anglets.feedback360', ['e_id' => $e->id, 'uid' => $user->id, 'mid' => Auth::user()->id]) }}">Feedback 360</a>
    </li>
  @else
    @foreach($evaluations as $evaluation)
      @php($anglet = App\Evaluation::unaccented($evaluation->title))
      <li class="{{ Request::segment(5) == App\Evaluation::unaccented($evaluation->title) ? 'active':'' }} pull-none pull-md-left">
        <a href="{{ route('anglets.'.$anglet, ['eid' => $e->id, 'uid' => $user->id]) }}">{{ $evaluation->title }}</a>
      </li>
    @endforeach
  @endif
</ul>