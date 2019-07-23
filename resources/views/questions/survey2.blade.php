<style>
  .slider.slider-horizontal {
    width: 100% !important;
  }
</style>
<div class="row evaluation-survey">
  @if(!empty($groupes))
    {{--<div class="col-md-12">--}}
      {{--@include('partials.alerts.info', ['messages' => "1 : Insuffisant (I) | 2 : En dessous des attentes (ED) | 3 : En ligne avec les attentes (EL) | 4: Au-dessus des attentes (AD) | 5 : Remarquable (R)" ])--}}
    {{--</div>--}}
    <div class="col-md-6">
      <h4 class="alert alert-info">Auto-évaluation remplie par : {{ $user->name." ".$user->last_name }} </h4>

      <div class="panel-group">
        @foreach($groupes as $g)
          @if(count($g->questions)>0)
            <div class="panel panel-info">
              <div class="panel-heading">{{ $g->name }}</div>
              <div class="panel-body">
                @forelse($g->questions as $q)
                  <div class="form-group">
                    @if($q->parent == null)
                      <label for="" class="questionTitle"><i class="fa fa-caret-right"></i> {{$q->titre}}</label>
                    @endif
                    @if($q->type == 'text')
                      <input type="{{$q->type}}" class="form-control" readonly=""
                             value="{{App\Answer::getCollAnswers($q->id, $user->id, $e->id) ? App\Answer::getCollAnswers($q->id, $user->id, $e->id)->answer : '' }}">
                    @elseif($q->type == 'textarea')
                      <textarea class="form-control"
                                readonly>{{App\Answer::getCollAnswers($q->id, $user->id, $e->id) ? App\Answer::getCollAnswers($q->id, $user->id, $e->id)->answer :''}}</textarea>
                    @elseif($q->type == "checkbox")
                      @foreach($q->children as $child)
                        <div class="survey-checkbox">
                          <input type="{{$q->type}}" value="{{$child->id}}"
                                 {{App\Answer::getCollAnswers($q->id, $user->id, $e->id) && in_array($child->id, json_decode(App\Answer::getCollAnswers($q->id, $user->id, $e->id)->answer)) ? 'checked' : '' }} disabled>
                          <label>{{ $child->titre }}</label>
                        </div>
                      @endforeach
                      <div class="clearfix"></div>
                    @elseif($q->type == "radio")
                      @foreach($q->children as $child)
                        <input type="{{$q->type}}" value="{{$child->id}}"
                               {{App\Answer::getCollAnswers($q->id, $user->id, $e->id) && $child->id == App\Answer::getCollAnswers($q->id, $user->id, $e->id)->answer ? 'checked' : '' }} disabled>
                        <label>{{ $child->titre }}</label>
                      @endforeach
                    @elseif($q->type == "slider")
                      <div class="disabled" style="margin-top: 30px;">
                        <input type="text"
                               data-provide="slider"
                               data-slider-min="1"
                               data-slider-max="5"
                               data-slider-step="1"
                               data-slider-value="{{App\Answer::getCollAnswers($q->id, $user->id, $e->id) ? App\Answer::getCollAnswers($q->id, $user->id, $e->id)->answer : '' }}"
                               data-slider-tooltip="always"
                               data-slider-ticks="[1, 2, 3, 4, 5]"
                               data-slider-ticks-labels='["1", "2", "3", "4", "5"]'
                               disabled
                        >
                      </div>
                      @elseif ($q->type == "rate")
                        @foreach($q->children as $child)
                          <div class="row margin-bottom">
                            <div class="col-md-2">
                              <input type="radio" name="answers[{{$q->id}}][ansr]" value="{{ $child->id }}" id="user-{{ $child->id }}" {{ App\Answer::getCollAnswers($q->id, $user->id, $e->id) && App\Answer::getCollAnswers($q->id, $user->id, $e->id)->answer == $child->id ? 'checked' : '' }} disabled> {{ $child->titre }}
                            </div>
                            <div class="col-md-10">
                              <label class="pull-right pointer" for="user-{{ $child->id }}">{{ json_decode($child->options)->label }}</label>
                            </div>
                          </div>
                        @endforeach
                      @endif
                  </div>
                @empty
                  <p class="help-block">Aucune question</p>
                @endforelse
              </div>
            </div>
          @endif
        @endforeach
      </div>
    </div>
    <div class="col-md-6 mentor-item">
      <span id="max_note" class="hidden">{{ App\Setting::get('max_note') }}</span>
      <h4 class="alert alert-info"> {{ App\User::getMentor($user->id)->name." ".App\User::getMentor($user->id)->last_name }} </h4>

      <form action="{{url('answers/store')}}" method="post" id="surveyForm">
        <input type="hidden" name="entretien_id" value="{{$e->id}}">
        <input type="hidden" name="mentor_id"
               value="{{App\User::getMentor($user->id) ? App\User::getMentor($user->id)->id : Auth::user()->id }}">
        <input type="hidden" name="user_id" value="{{$user->id}}">
        <input type="hidden" name="is_mentor" value="1">

        {{ csrf_field() }}
        <div class="panel-group">
          @php($gNote = 0)
          @php($c = 0)
          @foreach($groupes as $g)
            @php($c += 1)
            @if(count($g->questions)>0)
              <div class="panel panel-info">
                <div class="panel-heading">
                  {{ $g->name }}
                  @if ($g->notation_type == 'section')
                    @if(!App\Entretien::answeredMentor($e->id, $user->id,App\User::getMentor($user->id)->id))
                      @if(App\Evaluation::findOrFail($g->survey->evaluation_id)->title == 'Evaluations')
                        <input type="text" data-group-source="{{$g->id}}" class="notation inputNote" min="1"
                               max="{{App\Setting::get('max_note')}}" placeholder="Note"
                               value="{{App\Answer::getGrpNote($g->id, $user->id, $e->id) ? App\Answer::getGrpNote($g->id, $user->id, $e->id):''}}"
                               @if($g->notation_type == 'section' && App\Evaluation::findOrFail($g->survey->evaluation_id)->title == 'Evaluations') style="display: block;"
                               required @else style="display: none;" @endif>
                      @endif
                      @if(App\Answer::getGrpNote($g->id, $user->id, $e->id))
                        @php($gNote += App\Answer::getGrpNote($g->id, $user->id, $e->id))
                      @endif
                    @else
                      <span class="pull-right">Note : {{App\Answer::getGrpNote($g->id, $user->id, $e->id) ? App\Answer::getGrpNote($g->id, $user->id, $e->id):''}}
                        / {{App\Survey::countGroups($survey->id)}}</span>
                      @if(App\Answer::getGrpNote($g->id, $user->id, $e->id))
                        @php($gNote += App\Answer::getGrpNote($g->id, $user->id, $e->id))
                      @endif
                    @endif
                  @endif
                </div>
                <div class="panel-body">
                  @forelse($g->questions as $q)
                    <div class="form-group">
                      @if(in_array($q->type, ['text', 'textarea', 'radio']))
                        <input type="text" data-group-target="{{$g->id}}" name="answers[{{$q->id}}][note]"
                               placeholder="Note" class="notation inputNote" size="3" min="1"
                               max="{{App\Setting::get('max_note')}}"
                               value="{{ App\Answer::getMentorAnswers($q->id, $user->id, $e->id) ? App\Answer::getMentorAnswers($q->id, $user->id, $e->id)->note : ''}}"
                               @if($g->notation_type == 'item' && App\Evaluation::findOrFail($g->survey->evaluation_id)->title == 'Evaluations') style="display: block;"
                               required @endif>
                      @endif
                      @if($q->parent == null)
                        <label for="" class="questionTitle"><i class="fa fa-caret-right"></i>
                          {{$q->titre}}
                        </label>
                      @endif
                      @if($q->type == 'text')
                        <input type="{{$q->type}}" name="answers[{{$q->id}}][ansr]" class="form-control" required
                               value="{{ App\Answer::getMentorAnswers($q->id, $user->id, $e->id) ? App\Answer::getMentorAnswers($q->id, $user->id, $e->id)->mentor_answer : ''}}" {{ (App\Entretien::answeredMentor($e->id, $user->id,App\User::getMentor($user->id)->id)) == false ? '':'disabled' }}>
                      @elseif($q->type == 'textarea')
                        <textarea name="answers[{{$q->id}}][ansr]" class="form-control"
                                  required {{ (App\Entretien::answeredMentor($e->id, $user->id,App\User::getMentor($user->id)->id)) == false ? '':'disabled' }}>{{ App\Answer::getMentorAnswers($q->id, $user->id, $e->id) ? App\Answer::getMentorAnswers($q->id, $user->id, $e->id)->mentor_answer : ''}}</textarea>
                      @elseif($q->type == "checkbox")
                        <input type="text" data-group-target="{{$g->id}}" name="answers[{{$q->id}}][note]"
                               class="notation" min="1" max="{{App\Setting::get('max_note')}}"
                               value="{{ App\Answer::getMentorAnswers($q->id, $user->id, $e->id) ? App\Answer::getMentorAnswers($q->id, $user->id, $e->id)->note : ''}}"
                               style="display: {{$g->notation_type == 'item' && App\Evaluation::findOrFail($g->survey->evaluation_id)->title == 'Evaluations' ? 'block':'none'}}">
                        <p class="help-inline text-red checkboxError"><i class="fa fa-close"></i> Veuillez cocher au
                          moins un élement</p>
                        @foreach($q->children as $child)
                          <div class="survey-checkbox">
                            <input type="{{$q->type}}" name="answers[{{$q->id}}][ansr][]" id="{{$child->titre}}" value="{{$child->id}}" {{ App\Answer::getMentorAnswers($q->id, $user->id, $e->id) && in_array($child->id, json_decode(App\Answer::getMentorAnswers($q->id, $user->id, $e->id)->mentor_answer)) ? 'checked' : '' }} {{ (App\Entretien::answeredMentor($e->id, $user->id,App\User::getMentor($user->id)->id)) == false ? '':'disabled' }}>
                            <label for="{{$child->titre}}">{{ $child->titre }}</label>
                          </div>
                        @endforeach
                        <div class="clearfix"></div>
                      @elseif($q->type == "radio")
                        @foreach($q->children as $child)
                          <input type="{{$q->type}}" name="answers[{{$q->id}}][ansr]" id="{{$child->id}}" value="{{$child->id}}" required="" {{ App\Answer::getMentorAnswers($q->id, $user->id, $e->id) && $child->id == App\Answer::getMentorAnswers($q->id, $user->id, $e->id)->mentor_answer ? 'checked':'' }} {{ (App\Entretien::answeredMentor($e->id, $user->id,App\User::getMentor($user->id)->id)) == false ? '':'disabled' }}>
                          <label for="{{$child->id}}">{{ $child->titre }}</label>
                        @endforeach
                      @elseif($q->type == "slider")
                        <div class="" style="margin-top: 30px;">
                          <input type="text" required="" name="answers[{{$q->id}}][ansr]" data-provide="slider"
                                 data-slider-min="1"
                                 data-slider-max="5"
                                 data-slider-step="1"
                                 data-slider-value="{{ App\Answer::getMentorAnswers($q->id, $user->id, $e->id) ? App\Answer::getMentorAnswers($q->id, $user->id, $e->id)->mentor_answer : ''}}"
                                 data-slider-ticks="[1, 2, 3, 4, 5]"
                                 data-slider-ticks-labels='["1", "2", "3", "4", "5"]'
                                 data-slider-tooltip="always"
                          >
                        </div>
                      @elseif ($q->type == "rate")
                        @foreach($q->children as $child)
                          <div class="row margin-bottom">
                            <div class="col-md-2">
                              <input type="radio" name="answers[{{$q->id}}][ansr]" value="{{ $child->id }}" id="mentor-{{ $child->id }}" {{ App\Answer::getMentorAnswers($q->id, $user->id, $e->id) && App\Answer::getMentorAnswers($q->id, $user->id, $e->id)->mentor_answer == $child->id ? 'checked' : '' }}> {{ $child->titre }}
                            </div>
                            <div class="col-md-10">
                              <label class="pull-right pointer" for="mentor-{{ $child->id }}">{{ json_decode($child->options)->label }}</label>
                            </div>
                          </div>
                        @endforeach
                      @endif
                    </div>
                  @empty
                    <p class="help-block">Aucune question</p>
                  @endforelse
                </div>
              </div>
            @endif
          @endforeach
        </div>
        @if (Route::current()->getName() != 'entretien.apercu')
        <a href="{{url('/')}}" class="btn btn-default"><i class="fa fa-long-arrow-left"></i> Retour </a>
        @endif
        @if(!App\Entretien::answeredMentor($e->id, $user->id,App\User::getMentor($user->id)->id))
          <button type="submit" class="btn btn-success" id="submitAnswers"><i class="fa fa-check"></i> Enregistrer
          </button>
        @endif
        @if(App\Entretien::note($e->id, $user->id) > 0)
          <div class="callout callout-success" style="margin-top:15px">
            <p class="">
              <i class="fa fa-info-circle fa-2x"></i>
              <span class="content-callout h4"><b style="margin-right: 1em;">Note globale : {{App\Entretien::note($e->id, $user->id)}}</b>
                @foreach(App\Answer::NOTE_DEGREE as $key => $value)
                  <span class="fa fa-star {{$key <= App\Entretien::note($e->id, $user->id) ? 'checked':''}}" title="{{$value['title'].' ('.$value['ref'].')'}}" data-toggle="tooltip"></span>
                @endforeach
              </span>
            </p>
          </div>
        @endif
      </form>
    </div>
  @else
    <p class="alert alert-default">Aucune donnée disponible !</p>
  @endif
</div>

@section('javascript')
  <script>
    $(document).ready(function () {
      $('[data-group-source]').on('keyup', function () {
        $('[data-group-target="' + $(this).attr('data-group-source') + '"]').val($(this).val())
      })

      $('[data-group-source]').on('change', function () {
        $('[data-group-target="' + $(this).attr('data-group-source') + '"]').val($(this).val())
      })
      $('[data-group-source]').on('click', function () {
        $('[data-group-target="' + $(this).attr('data-group-source') + '"]').val($(this).val())
      })
    })
  </script>
@endsection