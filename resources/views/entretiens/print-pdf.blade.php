<style>
  .text-center {
    text-align: center;
  }
  .table {
    width: 100%;
    max-width: 100%;
    border-spacing: 0;
    border-collapse: collapse;
  }
  .table tr td,
  .table tr th {
    padding: 8px;
    vertical-align: middle;
    border: 1px solid #ddd;
  }
  .row {
    width: 100%;
    margin-right: -15px;
    margin-left: -15px;
  }
  .col-md-2 {
    width: 16.66666667%;
  }
  .col-md-6 {
    width: 50%;
  }
  .col-md-10 {
     width: 83.33333333%;
  }
  .col-md-12 {
    width: 100%;
  }
  .col-md-2, .col-md-6, .col-md-10, .col-md-12 {
    float: left;
  }
  .section-title {
    background-color: #0b8ccd;
    color: white;
    width: 100%;
    font-size: 18px;
    padding: 5px;
    font-weight: bold;
  }
  .mt-20 {
    margin-top: 20px;
  }
  .mb-20 {
    margin-bottom: 20px;
  }
  .clearfix {
    clear: both;
  }
  .q-choice {
    border: 1px solid slategray;
    padding: 10px;
  }
  .groupTitle {
    padding: 10px;
    background: lightslategray;
    margin-bottom: 20px;
  }
  .questionTitle {
    margin-bottom: 20px;
    font-weight: 600;
  }
  .pull-right {
    float: right;
  }
  tr.sous-total {
    background-color: darkseagreen;
  }
  tr.total {
    background-color: greenyellow;
  }
  .pl-30 {
    padding-left: 30px !important;
  }
  .sousTotal {
    background: #e4cece;
  }
  .total {
    background: #f39c12;
  }
  .array-qst-note {
    background: #e6d3b0 !important;
  }
</style>
{{-- ****************** Header ********************** --}}
<h2 class="text-center">Formulaire d'Evaluation Annuelle des Performances	</h2>
<table class="table">
  <tbody>
    <tr>
      <td>Date de l'entretien</td>
      <td>{{ Carbon\Carbon::parse($e->date)->format('d/m/Y')}}</td>
    </tr>
    <tr>
      <td>Pour l'année</td>
      <td>{{ Carbon\Carbon::parse(date('Y'))->format('Y')}}</td>
    </tr>
    <tr>
      <td colspan="2"></td>
    </tr>
    <tr>
      <td>Nom et prénom</td>
      <td>{{ $user->last_name . ' ' .$user->name }}</td>
    </tr>
    <tr>
      <td>Matricule</td>
      <td>{{ $user->mle }}</td>
    </tr>
    <tr>
      <td>Direction</td>
      <td>{{ $user->service }}</td>
    </tr>
    <tr>
      <td>Fonction exercée</td>
      <td>{{ $user->service }}</td>
    </tr>
    <tr>
      <td>Nom et fonction de l'évaluateur</td>
      <td>{{ $user->parent->last_name }}, {{ is_numeric($user->parent->function) ? App\Fonction::find($user->parent->function)->title : '---' }}</td>
    </tr>
    <tr>
      <td>Date d'embauche dans la société</td>
      <td>{{ !is_null($user->date_recruiting) ? Carbon\Carbon::parse($user->date_recruiting)->format('d/m/Y') : '---' }}</td>
    </tr>
  </tbody>
</table>
{{-- ****************** Infos de l'évaluation ********** --}}
<div class="entretien-infos">
  <h3 class="text-center" style="color: #0b8ccd;font-weight: 600">{{ $e->titre }}</h3>
  <div class="col-md-12">La période évaluée commence le {{ !is_null($e->start_periode) ? Carbon\Carbon::parse($e->start_periode)->format('d/m/Y') : '---' }} et se termine le {{ !is_null($e->end_periode) ? Carbon\Carbon::parse($e->end_periode)->format('d/m/Y') : '---' }}</div>
</div>
{{-- ****************** Evaluations ********************** --}}
<div class="mt-20"><p class="section-title">{{ $survey->title }}</p></div>
<div class="evaluation-survey">
  @if(!empty($survey->groupes))
    <div class="mentor-item">
      <div class="panel-group">
        @php($gNote = 0)
        @php($c = 0)
        @foreach($survey->groupes as $g)
          @php($c += 1)
          @if(count($g->questions)>0)
            <div class="panel panel-info">
              <div class="panel-heading">
                <div class="groupTitle">{{ $g->name }}</div>
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
                    @if($q->parent == null)
                      <div class="questionTitle"><i class="fa fa-caret-right"></i>
                        {{$q->titre}}
                      </div>
                    @endif
                    @if($q->type == 'text')
                      <input type="{{$q->type}}" name="answers[{{$q->id}}][ansr]" class="form-control" required
                             value="{{ App\Answer::getMentorAnswers($q->id, $user->id, $e->id) ? App\Answer::getMentorAnswers($q->id, $user->id, $e->id)->mentor_answer : ''}}" {{ (App\Entretien::answeredMentor($e->id, $user->id,App\User::getMentor($user->id)->id)) == false ? '':'disabled' }}>
                    @elseif($q->type == 'textarea')
                      <p style="padding: 10px; border: 1px solid darkgray;">{!! App\Answer::getMentorAnswers($q->id, $user->id, $e->id) ? nl2br(App\Answer::getMentorAnswers($q->id, $user->id, $e->id)->mentor_answer) : '' !!}</p>
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
                        <span>{{ App\Answer::getMentorAnswers($q->id, $user->id, $e->id) ? App\Answer::getMentorAnswers($q->id, $user->id, $e->id)->mentor_answer : ''}}</span>
                      </div>
                    @elseif ($q->type == "rate")
                      @foreach($q->children as $child)
                        <div class="q-choice">
                          <div class="col-md-2">
                            <input type="radio" name="answers[{{$q->id}}][ansr]" value="{{ $child->id }}" id="mentor-{{ $child->id }}" {{ App\Answer::getMentorAnswers($q->id, $user->id, $e->id) && App\Answer::getMentorAnswers($q->id, $user->id, $e->id)->mentor_answer == $child->id ? 'checked' : '' }}> {{ $child->titre }}
                          </div>
                          <div class="col-md-10">
                            {{ json_decode($child->options)->label }}
                          </div>
                          <div class="clearfix"></div>
                        </div>
                      @endforeach
                    @elseif ($q->type == "array")
                      @php($answersColumns = json_decode($q->options, true))
                      @php($answersColumns = isset($answersColumns['answers']) ? $answersColumns['answers'] : [])
                      @php($positivesAnswers = 0)
                      @php($options = json_decode($q->options, true))
                      @php($showNote = isset($options['show_global_note']) ? 1 : 0)
                      @if (!empty($answersColumns))
                        <div class="table-responsive mb-20">
                          <table class="table table-hover array-table">
                            <thead>
                            <tr>
                              <th width="20%"></th>
                              @foreach($answersColumns as $key => $answer)
                                @if ($answer['id'] > 0)
                                  @php($positivesAnswers ++)
                                @endif
                                <th class="text-center" style="font-size: 11px;">
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
                                <td style="font-size: 11px;">
                                  {{ $child->titre }}
                                </td>
                                @foreach($answersColumns as $key => $answer)
                                  <td class="text-center">
                                    <span>{{ $answerObj && $answerObj->mentor_answer == $answer['id'] ? '&#88;' : '' }}</span>
                                  </td>
                                @endforeach
                              </tr>
                            @endforeach
                            @if ($showNote == 1)
                              <tr class="array-qst-note">
                                <td colspan="3">Note globale obtenue par le mentor</td>
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
                @empty
                  <p class="help-block">Aucune question</p>
                @endforelse
              </div>
            </div>
          @endif
        @endforeach
      </div>
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
    </div>
  @endif
</div>

{{-- ****************** Objectifs ********************** --}}
@if(count($objectifs) > 0)
  <div class="mt-20"><p class="section-title">Objectifs</p></div>
  <div class="box-body">
    <h4 class="alert alert-info p-5">Mentor : {{ $user->parent->name." ".$user->parent->last_name }}</h4>
    <div class="table-responsive">
      <table class="table table-hover">
        <tr>
          <th width="20%">Critères d'évaluation</th>
          <th>Notation (%)</th>
          <th>Apréciation</th>
          <th class="text-center">Pondération (%)</th>
        </tr>
        @foreach($objectifs as $objectif)
          <tr>
            <td colspan="4" class="objectifTitle text-center">
              {{ $objectif->title }}
            </td>
          </tr>
          @foreach($objectif->children as $sub)
            <tr class=" {{ count($sub->children) <= 0 ? 'objectifRow' : '' }}" id="{{ count($sub->children) <= 0 ? 'mentorObjectifRow-' . $sub->id : '' }}" data-mentorobjrow="{{ $sub->id }}">
              <td style="max-width: 6%">{{ $sub->title }}</td>
              <td class="criteres text-center slider-note">
                @if (count($sub->children) <= 0)
                  <span>{{ App\Objectif::getObjectif($e->id, $user, $user->parent, $sub->id)->mentorNote }}</span>
                @endif
              </td>
              <td>
                @if (count($sub->children) <= 0)
                  <p>{{ App\Objectif::getObjectif($e->id, $user, $user->parent, $sub->id)->mentorAppreciation }}</p>
                @endif
              </td>
              <td class="text-center">
                <span class="ponderation">{{ $sub->ponderation }}</span>
              </td>
            </tr>
            @if (count($sub->children) > 0)
              @foreach($sub->children as $subObj)
                <tr class="subObjectifRow">
                  <td class="pl-30">&#8211; {{ $subObj->title }}</td>
                  <td class="criteres text-center slider-note">
                    <span>{{ App\Objectif::getObjectif($e->id, $user, $user->parent, $subObj->id)->mentorNote }}</span>
                  </td>
                  <td>
                    <p>{{ App\Objectif::getObjectif($e->id, $user, $user->parent, $subObj->id)->mentorAppreciation }}</p>
                  </td>
                  <td><span class="ponderation">{{ $subObj->ponderation }}</span></td>
                </tr>
              @endforeach
              <tr style="background: #cae5f1;">
                <td>Sous total</td>
                <td colspan="3">
                  <span class="badge badge-success pull-right">{{ App\Objectif::getObjSubTotal($e, $user, $user->parent, 'mentor', $sub->id) }}</span>
                </td>
              </tr>
            @endif
            @if (count($sub->children) <= 0)
              <tr style="background: #cae5f1;">
                <td>Sous total</td>
                <td colspan="3">
                  <span class="badge badge-success pull-right">{{ App\Objectif::getObjSubTotal($e, $user, $user->parent, 'mentor', $sub->id) }}</span>
                </td>
                </td>
              </tr>
            @endif
          @endforeach
          @if (!empty($objectif->extra_fields))
            @foreach (json_decode($objectif->extra_fields) as $key => $field)
              <tr>
                <td>
                  {{ $field->label }}
                </td>
                <td colspan="3">
                  @if ($field->type == 'text')
                    <p>{{ App\Objectif::getExtraFieldData($e->id, $user, $user->prent, $sub->id, $key) }}</p>
                  @elseif ($field->type == 'textarea')
                    <p>{{ App\Objectif::getExtraFieldData($e->id, $user, $user->parent, $sub->id, $key) }}</p>
                  @endif
                </td>
              </tr>
            @endforeach
          @endif
          <tr class="sousTotal">
            <td colspan="3">
              <span>Sous total de la section (%)</span>
            </td>
            <td>
              <span class="badge badge-success pull-right">{{ App\Objectif::getSectionSubTotal($e, $user, $user->parent, 'mentor', $objectif->id) }}</span>
            </td>
          </tr>
        @endforeach
        <tr class="total">
          <td colspan="3" valign="middle">
            <span>TOTAL DE L'ÉVALUATION (%)</span>
          </td>
          <td valign="middle">
            <span class="btn-default pull-right badge">{{ App\Objectif::getTotalNote($e, $user, $user->parent, 'mentor') }}</span>
          </td>
        </tr>
      </table>
    </div>
  </div>
@endif

{{-- ****************** Commentaires ********************** --}}
<div class="mt-20"><p class="section-title">Emargement et commentaires</p></div>
<table class="table mt-20">
  <tbody>
    <tr>
      <td width="20%">Nom du manager :</td>
      <td>{{ $user->parent->last_name }}</td>
    </tr>
    <tr>
      <td width="20%">Commentaire du manager :</td>
      <td>{{ isset($comment->mentorComment) ? $comment->mentorComment : '' }}</td>
    </tr>
    <tr>
      <td width="20%">Signature du manager :</td>
      <td>{{ $user->parent->name . ' ' .$user->parent->last_name }}</td>
    </tr>
    <tr>
      <td width="20%">Date de la revue :</td>
      <td>{{ isset($comment->mentor_updated_at) ? Carbon\Carbon::parse($comment->mentor_updated_at)->format('d/m/Y') : '' }}</td>
    </tr>
  </tbody>
</table>
<table class="table mt-20">
  <tbody>
  <tr>
    <td width="20%">Nom du collaborateur :</td>
    <td>{{ $user->last_name }}</td>
  </tr>
  <tr>
    <td width="20%">Commentaire du collaborateur :</td>
    <td>{{ isset($comment->userComment) ? $comment->userComment : '' }}</td>
  </tr>
  <tr>
    <td width="20%">Signature du collaborateur :</td>
    <td>{{ $user->name . ' ' .$user->last_name }}</td>
  </tr>
  <tr>
    <td width="20%">Date de la revue :</td>
    <td>{{ isset($comment->created_at) ? Carbon\Carbon::parse($comment->created_at)->format('d/m/Y') : '' }}</td>
  </tr>
  </tbody>
</table>