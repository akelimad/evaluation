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
    vertical-align: top;
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
  .bordered {
    border: 1px solid #ddd;
    padding: 10px;
  }
  .text-blue {
    color: #0b8ccd;
    font-weight: 700;
  }
  .underline {
    text-decoration: underline;
  }
  .w-100 {
    width: 100%;
    display: block;
  }
  .mb-0 {
    margin-bottom: 0;
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
<div class="entretien-infos">
  <h3 class="text-center" style="color: #0b8ccd;font-weight: 600">{{ $e->titre }}</h3>
</div>

@if(in_array('Evaluation annuelle', $entreEvalsTitle))
  @php($surveyId = App\Evaluation::surveyId($e->id, 1))
  @php($survey = App\Survey::findOrFail($surveyId))
  <div class="mt-20"><p class="section-title">Evaluation annuelle</p></div>
  <table class="table">
    <thead>
      <tr>
        <th width="50%">Auto-évaluation de {{ $user->fullname() }}</th>
        <th width="50%">{{ $user->parent->fullname() }}</th>
      </tr>
    </thead>
    <tbody>
      @foreach($survey->groupes as $groupe)
        <tr>
          <td>
            <div class="panel-group">
              <div class="panel panel-info mb-20">
                <div class="panel-heading text-blue underline">{{ $groupe->name }}</div>
                <div class="panel-body">
                  @foreach($groupe->questions as $q)
                    @php($collAnswer = App\Answer::getCollAnswers($q->id, $user->id, $e->id))
                    <div class="question-box">
                      <p><b>{{ $q->titre }} :</b></p>
                      @if (in_array($q->type, ['text', 'textarea']))
                        <p class="bordered">{{$collAnswer ? $collAnswer->answer : '' }}</p>
                      @elseif (in_array($q->type, ['select', 'radio']))
                        @if (!empty($q->children))
                          @foreach($q->children as $child)
                            <p class="{{ $collAnswer && $collAnswer->answer == $child->id ? 'text-blue':''  }}">{{ $child->titre }}</p>
                          @endforeach
                        @endif
                      @elseif($q->type == 'checkbox')
                        @if (!empty($q->children))
                          @foreach($q->children as $child)
                            <p class="{{ $collAnswer && in_array($child->id, json_decode($collAnswer->answer)) ? 'text-blue':''  }}">{{ $child->titre }}</p>
                          @endforeach
                        @endif
                      @endif
                    </div>
                  @endforeach
                </div>
              </div>
            </div>
          </td>
          <td>
            <div class="panel-group">
              <div class="panel panel-info mb-20">
                <div class="panel-heading text-blue underline">{{ $groupe->name }}</div>
                <div class="panel-body">
                  @foreach($groupe->questions as $q)
                    @php($mentorAnswer = App\Answer::getMentorAnswers($q->id, $user->id, $e->id))
                    <div class="question-box">
                      <p><b>{{ $q->titre }} :</b></p>
                      @if (in_array($q->type, ['text', 'textarea']))
                        <p class="bordered">{{$mentorAnswer ? $mentorAnswer->mentor_answer : '' }}</p>
                      @elseif (in_array($q->type, ['select', 'radio']))
                        @if (!empty($q->children))
                          @foreach($q->children as $child)
                            <p class="{{ $mentorAnswer && $mentorAnswer->mentor_answer == $child->id ? 'text-blue':''  }}">{{ $child->titre }}</p>
                          @endforeach
                        @endif
                      @elseif($q->type == 'checkbox')
                        @if (!empty($q->children))
                          @foreach($q->children as $child)
                            <p class="{{ $mentorAnswer &&  in_array($child->id, json_decode($mentorAnswer->mentor_answer)) ? 'text-blue':''  }}">{{ $child->titre }}</p>
                          @endforeach
                        @endif
                      @endif
                    </div>
                  @endforeach
                </div>
              </div>
            </div>
          </td>
        </tr>
      @endforeach
    </tbody>
  </table>
@endif

@if(in_array('Carrières', $entreEvalsTitle))
  @php($surveyId = App\Evaluation::surveyId($e->id, 2))
  @php($survey = App\Survey::findOrFail($surveyId))
  <div class="mt-20"><p class="section-title">Carrières</p></div>
  <table class="table">
    <thead>
    <tr>
      <th width="50%">Auto-évaluation de {{ $user->fullname() }}</th>
      <th width="50%">{{ $user->parent->fullname() }}</th>
    </tr>
    </thead>
    <tbody>
    @foreach($survey->groupes as $groupe)
      <tr>
        <td>
          <div class="panel-group">
            <div class="panel panel-info mb-20">
              <div class="panel-heading text-blue underline">{{ $groupe->name }}</div>
              <div class="panel-body">
                @foreach($groupe->questions as $q)
                  @php($collAnswer = App\Answer::getCollAnswers($q->id, $user->id, $e->id))
                  <div class="question-box">
                    <p><b>{{ $q->titre }} :</b></p>
                    @if (in_array($q->type, ['text', 'textarea']))
                      <p class="bordered">{{$collAnswer ? $collAnswer->answer : '' }}</p>
                    @elseif (in_array($q->type, ['select', 'radio']))
                      @if (!empty($q->children))
                        @foreach($q->children as $child)
                          <p class="{{ $collAnswer && $collAnswer->answer == $child->id ? 'text-blue':''  }}">{{ $child->titre }}</p>
                        @endforeach
                      @endif
                    @elseif($q->type == 'checkbox')
                      @if (!empty($q->children))
                        @foreach($q->children as $child)
                          <p class="{{ $collAnswer &&  in_array($child->id, json_decode($collAnswer->answer)) ? 'text-blue':''  }}">{{ $child->titre }}</p>
                        @endforeach
                      @endif
                    @endif
                  </div>
                @endforeach
              </div>
            </div>
          </div>
        </td>
        <td>
          <div class="panel-group">
            <div class="panel panel-info mb-20">
              <div class="panel-heading text-blue underline">{{ $groupe->name }}</div>
              <div class="panel-body">
                @foreach($groupe->questions as $q)
                  @php($mentorAnswer = App\Answer::getMentorAnswers($q->id, $user->id, $e->id))
                  <div class="question-box">
                    <p><b>{{ $q->titre }} :</b></p>
                    @if (in_array($q->type, ['text', 'textarea']))
                      <p class="bordered">{{$mentorAnswer ? $mentorAnswer->mentor_answer : '' }}</p>
                    @elseif (in_array($q->type, ['select', 'radio']))
                      @if (!empty($q->children))
                        @foreach($q->children as $child)
                          <p class="{{ $mentorAnswer && $mentorAnswer->mentor_answer == $child->id ? 'text-blue':''  }}">{{ $child->titre }}</p>
                        @endforeach
                      @endif
                    @elseif($q->type == 'checkbox')
                      @if (!empty($q->children))
                        @foreach($q->children as $child)
                          <p class="{{ $mentorAnswer &&  in_array($child->id, json_decode($mentorAnswer->mentor_answer)) ? 'text-blue':''  }}">{{ $child->titre }}</p>
                        @endforeach
                      @endif
                    @endif
                  </div>
                @endforeach
              </div>
            </div>
          </div>
        </td>
      </tr>
    @endforeach
    </tbody>
  </table>
@endif

@if(in_array('Objectifs', $entreEvalsTitle))
  <div class="mt-20"><p class="section-title">Objectifs</p></div>
  <div class="personnalObjectif">
    <p class="" style="background: #f1f0f0;">Individuels</p>
    @forelse($objectifsPersonnal as $key => $objectif)
      <p class="text-blue underline">{{ $objectif->title }}</p>
      <img src="https://quickchart.io/chart?c={{ $chartData[$objectif->id] }}" style="max-width: 100%"/>
    @empty
      <p>Aucune donnée trouvée ... !!</p>
    @endforelse
  </div>

  <div class="personnalObjectif">
    <p class="" style="background: #f1f0f0;">Collectifs</p>
    @forelse($objectifsTeam as $key => $objectif)
      <p class="text-blue underline">{{ $objectif->title }}</p>
      <img src="https://quickchart.io/chart?c={{ $chartData[$objectif->id] }}" style="max-width: 100%"/>
    @empty
      <p>Aucune donnée trouvée ... !!</p>
    @endforelse
  </div>
@endif

@if(in_array('Formations', $entreEvalsTitle))
  <div class="mt-20"><p class="section-title">Formations</p></div>
  @if(count($formations) > 0)
    <table class="table table-striped">
      <thead>
      <tr>
        <th>Date</th>
        <th>Exercice</th>
        <th>Formation</th>
        <th>Date d'acceptation</th>
        <th>Statut</th>
      </tr>
      </thead>
      <tbody>
      @foreach($formations as $f)
        <tr>
          <td>{{ Carbon\Carbon::parse($f->date)->format('d/m/Y')}}</td>
          <td>{{ $f->exercice }}</td>
          <td>{{ $f->title }}</td>
          <td>{{ Carbon\Carbon::parse($f->updated_at)->format('d/m/Y') }}</td>
          <td>
            @if($f->status == 0)En attente
            @elseif($f->status == 1)Refusé
            @elseif($f->status == 2)Accepté
            @endif
          </td>
        </tr>
      @endforeach
      </tbody>
    </table>
  @else
    <p>Aucune donnée trouvée ... !!</p>
  @endif
@endif

@if(in_array('Compétences', $entreEvalsTitle))
  <div class="mt-20"><p class="section-title">Compétences</p></div>
  <p>Fiche métier : {{ $skill->title }}</p>
  @forelse($skill->getSkillsTypes() as $key => $type)
    <div class="item-box">
      <p style="background: #f1f0f0;">{!! $type['title'] or '---' !!} : <span>{{ $skill->getSkillTypeNote($e->id, $user->id, $user->parent->id, "skill_type_".$type['id'], $type['id'], 'mentor') }}/10</span></p>
      <img src="https://quickchart.io/chart?c={{ $chartData['skill_type_'. $key] }}" style="max-width: 100%"/>
    </div>
  @empty
    <p>Aucun type de compétence trouvé !</p>
  @endforelse
@endif

@if(in_array('Primes', $entreEvalsTitle))
  <div class="mt-20"><p class="section-title">Primes</p></div>
  @if(count($primes) > 0)
    <div class="box-body table-responsive no-padding mb40">
      <table class="table table-hover table-striped text-center">
        <thead>
        <tr>
          <th>Date</th>
          <th>Brut</th>
          <th>Prime</th>
          <th>Commentaire</th>
        </tr>
        </thead>
        <tbody>
        @foreach($primes as $s)
          <tr>
            <td>{{ Carbon\Carbon::parse($s->created_at)->format('d/m/Y') }}</td>
            <td>{{ $s->brut or '---' }}</td>
            <td>{{ $s->prime or '---' }}</td>
            <td>{{ $s->comment ? $s->comment : '---' }}</td>
          </tr>
        @endforeach
        </tbody>
      </table>
    </div>
  @else
    <p>Aucune donnée trouvée ... !!</p>
  @endif
@endif

@if(in_array('Commentaires', $entreEvalsTitle))
  <div class="mt-20"><p class="section-title">Commentaires</p></div>
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
      <td width="20%">Date du commentaire :</td>
      <td>{{ isset($comment->created_at) ? date('d/m/Y', strtotime($comment->created_at)) : '---' }}</td>
    </tr>
    </tbody>
  </table>
@endif
