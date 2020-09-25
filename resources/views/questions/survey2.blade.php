<style>
  .panel .form-group {
    padding-bottom: 10px;
    border-bottom: 1px solid #ececec;
  }
  .slider.slider-horizontal {
    width: 100% !important;
  }
  .array-qst-note {
    background: #e6d3b0 !important;
  }
  .pointer {
    cursor: pointer;
  }
  .array-table tbody tr td label.table-radio-item {
    cursor: pointer;
    display: block;
    margin: 0;
    height: 100%;
  }

  .rating > input { display: none; }
  .rating > label:before {
    margin: 5px;
    font-size: 1.25em;
    font-family: FontAwesome;
    display: inline-block;
    content: "\f005";
  }

  .rating > .half:before {
    content: "\f089";
    position: absolute;
  }

  .rating > label {
    color: #ddd;
    float: right;
  }

  /***** CSS Magic to Highlight Stars on Hover *****/

  .rating > input:checked ~ label, /* show gold star when clicked */
  .rating:not(:checked) > label:hover, /* hover current star */
  .rating:not(:checked) > label:hover ~ label { color: #FFD700;  } /* hover previous stars in list */

  .rating > input:checked + label:hover, /* hover current star when changing rating */
  .rating > input:checked ~ label:hover,
  .rating > label:hover ~ input:checked ~ label, /* lighten current selection */
  .rating > input:checked ~ label:hover ~ label { color: #FFED85;  }



</style>
<div class="row evaluation-survey">
  @if(!empty($groupes))
    {{--<div class="col-md-12">--}}
      {{--@include('partials.alerts.info', ['messages' => "1 : Insuffisant (I) | 2 : En dessous des attentes (ED) | 3 : En ligne avec les attentes (EL) | 4: Au-dessus des attentes (AD) | 5 : Remarquable (R)" ])--}}
    {{--</div>--}}
    <div class="col-md-6">
      <h4 class="alert alert-info">Auto-évaluation de : {{ $user->name." ".$user->last_name }} </h4>

      <div class="panel-group">
        @foreach($groupes as $g)
          @if(count($g->questions)>0)
            <div class="panel panel-info mb-20">
              <div class="panel-heading">{{ $g->name }}</div>
              <div class="panel-body">
                <div class="row mb-0">
                  @forelse($g->questions as $q)
                    <div class="col-md-12 mb-30">
                      <div class="form-group">
                        @if($q->parent == null)
                          <label for="" class="questionTitle"><i class="fa fa-caret-right"></i> {{$q->titre}}</label>
                        @endif
                        @if($q->type == 'text')
                          <input type="{{$q->type}}" class="form-control" value="{{App\Answer::getCollAnswers($q->id, $user->id, $e->id) ? App\Answer::getCollAnswers($q->id, $user->id, $e->id)->answer : '' }}" disabled>
                        @elseif($q->type == 'textarea')
                          <textarea class="form-control" disabled>{{App\Answer::getCollAnswers($q->id, $user->id, $e->id) ? App\Answer::getCollAnswers($q->id, $user->id, $e->id)->answer :''}}</textarea>
                        @elseif($q->type == "checkbox")
                          @foreach($q->children as $child)
                            <div class="survey-checkbox">
                              <input type="{{$q->type}}" value="{{$child->id}}" {{App\Answer::getCollAnswers($q->id, $user->id, $e->id) && in_array($child->id, json_decode(App\Answer::getCollAnswers($q->id, $user->id, $e->id)->answer)) ? 'checked' : '' }} disabled>
                              <label>{{ $child->titre }}</label>
                            </div>
                          @endforeach
                          <div class="clearfix"></div>
                        @elseif($q->type == "radio")
                          @foreach($q->children as $child)
                            <div class="choice-item">
                              <input type="{{$q->type}}" id="{{$child->id}}" value="{{$child->id}}" {{App\Answer::getCollAnswers($q->id, $user->id, $e->id) && $child->id == App\Answer::getCollAnswers($q->id, $user->id, $e->id)->answer ? 'checked' : '' }} disabled>
                              <label for="{{$child->id}}" class="d-inline-block">{{ $child->titre }}</label>
                            </div>
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
                              <div class="col-md-1">
                                <input type="radio" name="answers[{{$q->id}}][ansr]" value="{{ $child->id }}" id="user-{{ $child->id }}" {{ App\Answer::getCollAnswers($q->id, $user->id, $e->id) && App\Answer::getCollAnswers($q->id, $user->id, $e->id)->answer == $child->id ? 'checked' : '' }} disabled> {{ $child->titre }}
                              </div>
                              <div class="col-md-11">
                                @php($options = json_decode($child->options, true))
                                <label class="pull-right pointer" for="user-{{ $child->id }}">
                                  {{ isset($options['label']) ? $options['label'] : 'vide' }}
                                </label>
                              </div>
                            </div>
                          @endforeach
                        @elseif ($q->type == "select")
                          <div class="row">
                            <div class="col-md-6">
                              <select name="answers[{{$q->id}}][ansr]" id="" class="form-control" disabled>
                                <option value=""></option>
                                @foreach($q->children as $child)
                                  <option value="{{ $child->id }}" {{ App\Answer::getCollAnswers($q->id, $user->id, $e->id) && App\Answer::getCollAnswers($q->id, $user->id, $e->id)->answer == $child->id ? 'selected' : '' }}>{{ $child->titre }}</option>
                                @endforeach
                              </select>
                            </div>
                          </div>
                        @elseif($q->type == "array")
                          @php($answersColumns = json_decode($q->options, true))
                          @php($answersColumns = isset($answersColumns['answers']) ? $answersColumns['answers'] : [])
                          @php($positivesAnswers = 0)
                          @php($options = json_decode($q->options, true))
                          @php($showNote = isset($options['show_global_note']) ? 1 : 0)
                          @if (!empty($answersColumns))
                            <div class="table-responsive">
                              <table class="table table-hover array-table">
                                <thead>
                                <tr>
                                  <th width="20%"></th>
                                  @foreach($answersColumns as $key => $answer)
                                    @if ($answer['id'] > 0)
                                      @php($positivesAnswers ++)
                                    @endif
                                    <th class="text-center">
                                      <label for="">{{ $answer['id'] != 'n/a' && $showNote ? $answer['id'] . ' = ' : ''  }} {{ $answer['value'] }}</label>
                                    </th>
                                  @endforeach
                                </tr>
                                </thead>
                                <tbody>
                                @php($sum = $countItems =0)
                                @foreach($q->children as $child)
                                  @php($answerObj = App\Answer::getCollAnswers($child->id, $user->id, $e->id))
                                  @if ($answerObj && $answerObj->answer > 0)
                                    @php($countItems ++)
                                    @php($sum = $sum + $answerObj->answer)
                                  @endif
                                  <tr>
                                    <td>{{ $child->titre }}</td>
                                    @foreach($answersColumns as $key => $answer)
                                      <td class="text-center" title="{{ $answer['value'] }}">
                                        <label for="item_{{ $child->id }}_{{ $answer['id'] }}" class="table-radio-item">
                                          <input type="radio" name="answers[{{ $child->id }}][ansr]" id="item_{{ $child->id }}_{{ $answer['id'] }}" value="{{ $answer['id'] }}" {{ $answerObj && $answerObj->answer == $answer['id'] ? 'checked' : '' }} disabled>
                                        </label>
                                      </td>
                                    @endforeach
                                  </tr>
                                @endforeach
                                @php($options = json_decode($q->options, true))
                                @php($showNote = isset($options['show_global_note']) ? 1 : 0)
                                @if ($showNote == 1)
                                  <tr class="array-qst-note">
                                    <td colspan="2">Note globale obtenue par le collaborateur</td>
                                    <td colspan="{{ count($answersColumns) }}"><span class="pull-right">{{  $countItems > 0 ? round($sum/$countItems) : 0 }} / {{ $positivesAnswers }}</span></td>
                                  </tr>
                                @endif
                                </tbody>
                              </table>
                            </div>
                          @else
                            <p class="help-block">Impossible de trouver les réponses de cette question</p>
                          @endif
                        @endif
                      </div>
                    </div>
                  @empty
                    <p class="help-block">Aucune question</p>
                  @endforelse
                </div>
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
          @php($maxNote = App\Setting::get('max_note'))
          @foreach($groupes as $g)
            @php($c += 1)
            @if(count($g->questions)>0)
              @php($evalModel = App\Evaluation::find($g->survey->evaluation_id))
              <div class="panel panel-info mb-20">
                <div class="panel-heading">
                  {{ $g->name }}
                  @if ($g->ponderation > 0)
                    <span class="pull-right">Note : {{ \App\Answer::getGrpNote($g->id, $user->id, $e->id) }}</span>
                  @endif
                </div>
                <div class="panel-body">
                  <div class="row mb-0">
                    @forelse($g->questions as $q)
                      <div class="col-md-12 mb-30">
                        <div class="form-group">
                          @if($q->parent == null)
                            <div class="row mb-0">
                              <div class="col-md-{{ $q->ponderation > 0 ? '6' : '12' }}">
                                <label for="" class="questionTitle"><i class="fa fa-caret-right"></i>
                                  {{$q->titre}}
                                </label>
                              </div>
                              @if($q->ponderation > 0)
                                @php($mentorAnswer = App\Answer::getMentorAnswers($q->id, $user->id, $e->id))
                                <div class="col-md-6">
                                  @if(Route::currentRouteName() == 'entretien.apercu')
                                    <span>Note : {{ $mentorAnswer ? $mentorAnswer->note : ''}}/{{ App\Setting::get('max_note') }}</span>
                                  @else
                                    <span class="ml-5 pull-right">/ {{ $maxNote }}</span>
                                    <input type="number" data-group-target="{{$g->id}}" name="answers[{{$q->id}}][note]"
                                           placeholder="Note" class="ml-5 notation inputNote pull-right" size="5" min="0"
                                           max="{{ $maxNote }}"
                                           step="0.5"
                                           value="{{ $mentorAnswer ? $mentorAnswer->note : ''}}" required>
                                    <label class="pull-right">Note :</label>
                                  @endif
                                </div>
                              @endif
                            </div>
                          @endif

                          @if($q->type == 'text')
                            <input type="{{$q->type}}" name="answers[{{$q->id}}][ansr]" class="form-control" required
                                   value="{{ App\Answer::getMentorAnswers($q->id, $user->id, $e->id) ? App\Answer::getMentorAnswers($q->id, $user->id, $e->id)->mentor_answer : ''}}" {{ (App\Entretien::answeredMentor($e->id, $user->id,App\User::getMentor($user->id)->id)) == false ? '':'disabled' }}>
                          @elseif($q->type == 'textarea')
                            <textarea name="answers[{{$q->id}}][ansr]" class="form-control"
                                      required {{ (App\Entretien::answeredMentor($e->id, $user->id,App\User::getMentor($user->id)->id)) == false ? '':'disabled' }}>{{ App\Answer::getMentorAnswers($q->id, $user->id, $e->id) ? App\Answer::getMentorAnswers($q->id, $user->id, $e->id)->mentor_answer : ''}}</textarea>
                          @elseif($q->type == "checkbox")
                            <input type="text" data-group-target="{{$g->id}}" name="answers[{{$q->id}}][note]"
                                   class="notation" min="1" max="{{ $maxNote }}"
                                   value="{{ App\Answer::getMentorAnswers($q->id, $user->id, $e->id) ? App\Answer::getMentorAnswers($q->id, $user->id, $e->id)->note : ''}}"
                                   style="display: {{$g->notation_type == 'item' && App\Evaluation::find($g->survey->evaluation_id)->title == 'Evaluation annuelle' ? 'block':'none'}}">
                            <p class="help-inline text-red checkboxError"><i class="fa fa-close"></i> Veuillez cocher au moins un élement</p>
                            @foreach($q->children as $child)
                              <div class="survey-checkbox">
                                <input type="{{$q->type}}" name="answers[{{$q->id}}][ansr][]" id="{{$child->titre}}" value="{{$child->id}}" {{ App\Answer::getMentorAnswers($q->id, $user->id, $e->id) && in_array($child->id, json_decode(App\Answer::getMentorAnswers($q->id, $user->id, $e->id)->mentor_answer)) ? 'checked' : '' }} {{ (App\Entretien::answeredMentor($e->id, $user->id,App\User::getMentor($user->id)->id)) == false ? '':'disabled' }}>
                                <label for="{{$child->titre}}">{{ $child->titre }}</label>
                              </div>
                            @endforeach
                            <div class="clearfix"></div>
                          @elseif($q->type == "radio")
                            @foreach($q->children as $child)
                              <div class="choice-item">
                                <input type="{{$q->type}}" name="answers[{{$q->id}}][ansr]" id="{{$child->id}}" value="{{$child->id}}" required="" {{ App\Answer::getMentorAnswers($q->id, $user->id, $e->id) && $child->id == App\Answer::getMentorAnswers($q->id, $user->id, $e->id)->mentor_answer ? 'checked':'' }} {{ (App\Entretien::answeredMentor($e->id, $user->id,App\User::getMentor($user->id)->id)) == false ? '':'disabled' }}>
                                <label for="{{$child->id}}" class="d-inline-block">{{ $child->titre }}</label>
                              </div>
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
                          @elseif ($q->type == "select")
                            <div class="row">
                              <div class="col-md-6">
                                <select name="answers[{{$q->id}}][ansr]" id="" class="form-control">
                                  <option value=""></option>
                                  @foreach($q->children as $child)
                                    <option value="{{ $child->id }}" {{ App\Answer::getMentorAnswers($q->id, $user->id, $e->id) && App\Answer::getMentorAnswers($q->id, $user->id, $e->id)->mentor_answer == $child->id ? 'selected' : '' }}>{{ $child->titre }}</option>
                                  @endforeach
                                </select>
                              </div>
                            </div>
                          @elseif ($q->type == "array")
                            @php($answersColumns = json_decode($q->options, true))
                            @php($answersColumns = isset($answersColumns['answers']) ? $answersColumns['answers'] : [])
                            @php($positivesAnswers = 0)
                            @php($options = json_decode($q->options, true))
                            @php($showNote = isset($options['show_global_note']) ? 1 : 0)
                            @if (!empty($answersColumns))
                              <div class="table-responsive">
                                <table class="table table-hover array-table">
                                  <thead>
                                  <tr>
                                    <th width="20%"></th>
                                    @foreach($answersColumns as $key => $answer)
                                      @if ($answer['id'] > 0)
                                        @php($positivesAnswers ++)
                                      @endif
                                      <th class="text-center">
                                        <label for="">{{ $answer['id'] != 'n/a' && $showNote ? $answer['id'] . ' = ' : ''  }} {{ $answer['value'] }}</label>
                                      </th>
                                    @endforeach
                                  </tr>
                                  </thead>
                                  <tbody>
                                  @php($sum = $countItems =0)
                                  @foreach($q->children as $child)
                                    @php($answerObj = App\Answer::getMentorAnswers($child->id, $user->id, $e->id))
                                    @if ($answerObj && $answerObj->mentor_answer > 0)
                                      @php($countItems ++)
                                      @php($sum = $sum + $answerObj->mentor_answer)
                                    @endif
                                    <tr>
                                      <td>{{ $child->titre }}</td>
                                      @foreach($answersColumns as $key => $answer)
                                        <td class="text-center" title="{{ $answer['value'] }}">
                                          <label for="m_item_{{ $child->id }}_{{ $answer['id'] }}" class="table-radio-item">
                                            <input type="radio" name="answers[{{ $child->id }}][ansr]" id="m_item_{{ $child->id }}_{{ $answer['id'] }}" value="{{ $answer['id'] }}" {{ $answerObj && $answerObj->mentor_answer == $answer['id'] ? 'checked' : '' }} required>
                                          </label>
                                        </td>
                                      @endforeach
                                    </tr>
                                  @endforeach
                                  @php($options = json_decode($q->options, true))
                                  @php($showNote = isset($options['show_global_note']) ? 1 : 0)
                                  @if ($showNote == 1)
                                    <tr class="array-qst-note">
                                      <td colspan="2">Note globale :</td>
                                      <td colspan="{{ count($answersColumns) }}"><span class="pull-right">{{  $countItems > 0 ? round($sum/$countItems) : 0 }} / {{ $positivesAnswers }}</span></td>
                                    </tr>
                                  @endif
                                  </tbody>
                                </table>
                              </div>
                            @else
                              <p class="help-block">Impossible de trouver les réponses de cette question</p>
                            @endif
                          @endif
                        </div>
                      </div>
                    @empty
                      <p class="help-block">Aucune question</p>
                    @endforelse
                  </div>
                </div>
              </div>
            @endif
          @endforeach
        </div>
        @if (Route::current()->getName() != 'entretien.apercu')
        <a href="{{url('/')}}" class="btn btn-default"><i class="fa fa-long-arrow-left"></i> Retour </a>
        @endif
        @if(!App\Entretien::answeredMentor($e->id, $user->id,App\User::getMentor($user->id)->id) && Route::current()->getName() != 'entretien.apercu')
          <button type="submit" class="btn btn-success" id="submitAnswers"><i class="fa fa-check"></i> Enregistrer
          </button>
        @endif
      </form>
    </div>

    <div class="col-md-12 mt-30">
      <div class="alert alert-info">
        @php($totalNote = \App\Answer::getTotalNote($survey->id, $user->id, $e->id))
        @php($rateNote = ($totalNote * 10) / 100)
        @php($note = App\Helpers\Base::cutNum($rateNote, 1))
        <div class="row mb-0">
          <div class="col-md-4 mt-5">
            <span class=""><b style="margin-right: 1em;">Note globale : {{ $note * 10 }}/100</b></span>
          </div>
          <div class="col-md-8">
            <div class="rating pull-left">
              @foreach([10, 9.5, 9, 8.5, 8, 7.5, 7, 6.5, 6, 5.5, 5, 4.5, 4, 3.5, 3, 2.5, 2, 1.5, 1, 0.5] as $value)
                <input type="radio" id="{{$survey->id}}_star{{ $value }}" name="{{$survey->id}}_rating" value="{{ $value }}" {{ $note >= $value && $note < $value + 0.5 ? 'checked':'' }} /><label class= "{{ is_int($value) ? 'full' : 'half' }} mb-0" for="{{$survey->id}}_star{{ $value }}" title="{{ $value }}"></label>
              @endforeach
            </div>
          </div>
        </div>
      </div>
    </div>
  @else
    <p class="alert alert-default">Aucune donnée disponible !</p>
  @endif
</div>

@section('javascript')
  <script>

  </script>
@endsection