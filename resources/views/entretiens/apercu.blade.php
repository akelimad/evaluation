
<div class="apercu">
  @if($user->parent)
    <p class="help-block">Aperçu sur les informations partagées entre
      {{ $user->name." ".$user->last_name }} et
      {{ $user->parent ? $user->parent->name : $user->name }} {{ $user->parent ? $user->parent->last_name : $user->last_name }}
      sur l'entretien : {{ $e->titre }}
    </p>
    <div class="panel-group" id="accordion" role="tablist" aria-multiselectable="true">
      @if(in_array('Entretien annuel', $entreEvalsTitle))
        <div class="panel panel-default">
          <div class="panel-heading" role="tab" id="heading-evaluations">
            <h4 class="panel-title">
              <a role="button" data-toggle="collapse" data-parent="#accordion" href="#collapse-evaluations"
                 aria-controls="collapse-evaluations" style="padding: 10px 15px;">
                <i class="more-less fa fa-angle-right"></i>
                Entretien annuel
              </a>
            </h4>
          </div>
          <div id="collapse-evaluations" class="panel-collapse collapse in" role="tabpanel"
               aria-labelledby="heading-evaluations">
            <div class="panel-body">
              @php($surveyId = App\Evaluation::surveyId($e->id, 1))
              @php($survey = App\Survey::findOrFail($surveyId))
              @include('questions.survey2', ['groupes' => $survey->groupes])
            </div>
          </div>
        </div>
      @endif
      @if(in_array('Carrières', $entreEvalsTitle))
        <div class="panel panel-default">
          <div class="panel-heading" role="tab" id="heading-carrieres">
            <h4 class="panel-title">
              <a role="button" data-toggle="collapse" data-parent="#accordion" href="#collapse-carrieres"
                 aria-controls="collapse-carrieres">
                <i class="more-less fa fa-angle-right"></i>
                Carrières
              </a>
            </h4>
          </div>
          <div id="collapse-carrieres" class="panel-collapse collapse in" role="tabpanel"
               aria-labelledby="heading-carrieres">
            <div class="panel-body">
              <div class="panel-body">
                @php($surveyId = App\Evaluation::surveyId($e->id, 2))
                @php($survey = App\Survey::findOrFail($surveyId))
                <div class="row">
                  @if(count($survey->groupes)>0)
                    <div class="col-md-6">
                      <h4 class="alert alert-info"> {{ $user->name." ".$user->last_name }} </h4>

                      <div class="panel-group">
                        @foreach($survey->groupes as $g)
                          @if(count($g->questions)>0)
                            <div class="panel panel-info">
                              <div class="panel-heading">{{ $g->name }}</div>
                              <div class="panel-body">
                                @forelse($g->questions as $q)
                                  <div class="form-group">
                                    @if($q->parent == null)
                                      <label for="" class="questionTitle help-block text-blue"><i
                                            class="fa fa-caret-right"></i> {{$q->titre}}</label>
                                    @endif
                                    @if($q->type == 'text')
                                      <div class="text-background">
                                        {{App\Answer::getCollAnswers($q->id, $user->id, $e->id) ? App\Answer::getCollAnswers($q->id, $user->id, $e->id)->answer : '' }}
                                      </div>
                                    @elseif($q->type == 'textarea')
                                      <div class="text-background">
                                        {{App\Answer::getCollAnswers($q->id, $user->id, $e->id) ? App\Answer::getCollAnswers($q->id, $user->id, $e->id)->answer :''}}
                                      </div>
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
                                    @endif
                                  </div>
                                @empty
                                  <p class="help-block"> Aucune question </p>
                                @endforelse
                              </div>
                            </div>
                          @endif
                        @endforeach
                      </div>
                    </div>
                    <div class="col-md-6">
                      <h4 class="alert alert-info"> {{ App\User::getMentor($user->id)->name." ".App\User::getMentor($user->id)->last_name }} </h4>

                      <div class="panel-group">
                        @foreach($survey->groupes as $g)
                          @if(count($g->questions)>0)
                            <div class="panel panel-info">
                              <div class="panel-heading">{{ $g->name }}
                              </div>
                              <div class="panel-body">
                                @forelse($g->questions as $q)
                                  <div class="form-group">
                                    @if($q->parent == null)
                                      <label for="" class="questionTitle help-block text-blue"><i
                                            class="fa fa-caret-right"></i> {{$q->titre}}</label>
                                    @endif
                                    @if($q->type == 'text')
                                      <div class="text-background">
                                        {{ App\Answer::getMentorAnswers($q->id, $user->id, $e->id) ? App\Answer::getMentorAnswers($q->id, $user->id, $e->id)->mentor_answer : ''}}
                                      </div>
                                    @elseif($q->type == 'textarea')
                                      <div class="text-background">
                                        {{ App\Answer::getMentorAnswers($q->id, $user->id, $e->id) ? App\Answer::getMentorAnswers($q->id, $user->id, $e->id)->mentor_answer : ''}}
                                      </div>
                                    @elseif($q->type == "checkbox")
                                      <p class="help-inline text-red checkboxError"><i class="fa fa-close"></i> Veuillez
                                        cocher au moins un élement</p>
                                      @foreach($q->children as $child)
                                        <div class="survey-checkbox">
                                          <input type="{{$q->type}}" name="answers[{{$q->id}}][]" id="{{$child->titre}}"
                                                 value="{{$child->id}}" {{ App\Answer::getMentorAnswers($q->id, $user->id, $e->id) && in_array($child->id, json_decode(App\Answer::getMentorAnswers($q->id, $user->id, $e->id)->mentor_answer)) ? 'checked' : '' }} {{ (App\Entretien::answeredMentor($e->id, $user->id,App\User::getMentor($user->id)->id)) == false ? '':'disabled' }}>
                                          <label for="{{$child->titre}}">{{ $child->titre }}</label>
                                        </div>
                                      @endforeach
                                      <div class="clearfix"></div>
                                    @elseif($q->type == "radio")
                                      @foreach($q->children as $child)
                                        <input type="{{$q->type}}" name="answers[{{$q->id}}]" id="{{$child->id}}"
                                               value="{{$child->id}}"
                                               required="" {{ App\Answer::getMentorAnswers($q->id, $user->id, $e->id) && $child->id == App\Answer::getMentorAnswers($q->id, $user->id, $e->id)->mentor_answer ? 'checked':'' }} {{ (App\Entretien::answeredMentor($e->id, $user->id,App\User::getMentor($user->id)->id)) == false ? '':'disabled' }}>
                                        <label for="{{$child->id}}">{{ $child->titre }}</label>
                                      @endforeach
                                    @endif
                                  </div>

                                @empty
                                  <p class="help-block"> Aucune question </p>
                                @endforelse
                              </div>
                            </div>
                          @endif
                        @endforeach
                      </div>
                    </div>
                  @else
                    <p class="alert alert-default">Aucune donnée disponible !</p>
                  @endif
                </div>
              </div>
            </div>
          </div>
        </div>
      @endif
      @if(in_array('Objectifs', $entreEvalsTitle))
        <div class="panel panel-default">
          <div class="panel-heading" role="tab" id="heading-objectifs">
            <h4 class="panel-title">
              <a role="button" data-toggle="collapse" data-parent="#accordion" href="#collapse-objectifs"
                 aria-controls="collapse-objectifs">
                <i class="more-less fa fa-angle-right"></i>
                Objectifs
              </a>
            </h4>
          </div>
          <div id="collapse-objectifs" class="panel-collapse collapse in" role="tabpanel"
               aria-labelledby="heading-objectifs">
            <div class="panel-body objectifs">
              <div class="box-body no-padding mb40">
                <ul class="nav nav-tabs">
                  <li class="active"><a data-toggle="tab" href="#personnel">Personnel</a></li>
                  <li><a data-toggle="tab" href="#team">Equipe</a></li>
                </ul>
                <div class="tab-content pt-30">
                  <div id="personnel" class="tab-pane fade in active">
                    @forelse($objectifsPersonnal as $key => $objectif)
                      <h3 class="bg-gray p-5 mt-0">{{ $objectif->title }}</h3>
                      <canvas class="chart" id="personnelChart{{$key+1}}" style="max-height: 600px;"></canvas>
                    @empty
                      <p>Aucun résultat trouvé !</p>
                    @endforelse
                  </div>
                  <div id="team" class="tab-pane">
                    <div id="personnel" class="tab-pane fade in active">
                      @forelse($objectifsTeam as $key => $objectif)
                        <h3 class="bg-gray p-5 mt-0">{{ $objectif->title }}</h3>
                        <canvas class="chart" id="teamChart{{$key+1}}" style="max-height: 600px;"></canvas>
                      @empty
                        <p>Aucun résultat trouvé !</p>
                      @endforelse
                  </div>
                </div>
            </div>
          </div>
        </div>
      @endif
      @if(in_array('Formations', $entreEvalsTitle))
        <div class="panel panel-default">
          <div class="panel-heading" role="tab" id="heading-formations">
            <h4 class="panel-title">
              <a role="button" data-toggle="collapse" data-parent="#accordion" href="#collapse-formations"
                 aria-controls="collapse-formations">
                <i class="more-less fa fa-angle-right"></i>
                Formations
              </a>
            </h4>
          </div>
          <div id="collapse-formations" class="panel-collapse collapse in" role="tabpanel"
               aria-labelledby="heading-formations">
            <div class="panel-body">
              <p class="help-block">
                Liste des formations souhaitées de la part de {{ $user->name." ".$user->last_name }} acceptées
                par {{ $user->parent ? $user->parent->name : $user->name  }} {{ $user->parent ? $user->parent->last_name : $user->last_name }}
              </p>
              @if(count($formations)>0)
                <table class="table table-striped">
                  <thead>
                  <tr>
                    <th>Date</th>
                    <th>Exercice</th>
                    <th>Formation</th>
                    <th>Date d'acceptation</th>
                  </tr>
                  </thead>
                  <tbody>
                  @foreach($formations as $f)
                    <tr>
                      <td> {{ Carbon\Carbon::parse($f->date)->format('d/m/Y')}} </td>
                      <td> {{ $f->exercice }} </td>
                      <td> {{ $f->title }} </td>
                      <td> {{ Carbon\Carbon::parse($f->updated_at)->format('d/m/Y')}} </td>
                    </tr>
                  @endforeach
                  </tbody>
                </table>
              @else
                @include('partials.alerts.info', ['messages' => "Aucune donnée trouvée ... !!" ])
              @endif
            </div>
          </div>
        </div>
      @endif
      @if(in_array('Compétences', $entreEvalsTitle))
        <div class="panel panel-default">
          <div class="panel-heading" role="tab" id="heading-skills">
            <h4 class="panel-title">
              <a role="button" data-toggle="collapse" data-parent="#accordion" href="#collapse-skills"
                 aria-controls="collapse-skills">
                <i class="more-less fa fa-angle-right"></i>
                Compétences
              </a>
            </h4>
          </div>
          <div id="collapse-skills" class="panel-collapse collapse in" role="tabpanel" aria-labelledby="heading-skills">
            <div class="panel-body">
              <table class="table table-hover">
                <tr>
                  <th>Axe</th>
                  <th>Famille</th>
                  <th>Catégorie</th>
                  <th>Compétence</th>
                  <th>Objectif</th>
                  <th>N+1</th>
                  <th>Ecart</th>
                </tr>
                @php($totalObjectif = 0)
                @php($totalNplus1 = 0)
                @php($totalEcart = 0)
                @foreach($skills as $skill)
                  <tr>
                    <td> {{ $skill->axe ? $skill->axe : '---' }}</td>
                    <td> {{ $skill->famille ? $skill->famille : '---' }} </td>
                    <td> {{ $skill->categorie ? $skill->categorie : '---' }} </td>
                    <td> {{ $skill->competence ? $skill->competence : '---' }} </td>
                    <td class="text-center">
                      {{ App\Skill::getSkill($skill->id, $user->id, $e->id) ? App\Skill::getSkill($skill->id, $user->id, $e->id)->objectif : '---' }}
                      @php($totalObjectif += App\Skill::getSkill($skill->id, $user->id, $e->id) ? App\Skill::getSkill($skill->id, $user->id, $e->id)->objectif : 0)
                    </td>
                    <td class="text-center">
                      {{ App\Skill::getSkill($skill->id, $user->id, $e->id) ? App\Skill::getSkill($skill->id, $user->id, $e->id)->nplus1 : '---' }}
                      @php($totalNplus1 += App\Skill::getSkill($skill->id, $user->id, $e->id) ? App\Skill::getSkill($skill->id, $user->id, $e->id)->nplus1 : 0)
                    </td>
                    <td class="text-center">
                      {{ App\Skill::getSkill($skill->id, $user->id, $e->id) ? App\Skill::getSkill($skill->id, $user->id, $e->id)->ecart : '---' }}
                      @php($totalEcart += App\Skill::getSkill($skill->id, $user->id, $e->id) ? App\Skill::getSkill($skill->id, $user->id, $e->id)->ecart : 0)
                    </td>
                  </tr>
                @endforeach
                <tr>
                  <td colspan="4">
                    Totaux des compétences :
                  </td>
                  <td class="text-center"><span class="badge">{{$totalObjectif}}</span></td>
                  <td class="text-center"><span class="badge">{{$totalNplus1}}</span></td>
                  <td class="text-center"><span class="badge">{{$totalEcart}}</span></td>
                </tr>
              </table>
            </div>
          </div>
        </div>
      @endif
      @if(in_array('Salaires', $entreEvalsTitle))
        <div class="panel panel-default">
          <div class="panel-heading" role="tab" id="heading-salary">
            <h4 class="panel-title">
              <a role="button" data-toggle="collapse" data-parent="#accordion" href="#collapse-salary"
                 aria-controls="collapse-salary">
                <i class="more-less fa fa-angle-right"></i>
                Salaires
              </a>
            </h4>
          </div>
          <div id="collapse-salary" class="panel-collapse collapse in" role="tabpanel" aria-labelledby="heading-salary">
            <div class="panel-body">
              @if(count($salaries)>0)
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
                    @foreach($salaries as $s)
                      <tr>
                        <td> {{ Carbon\Carbon::parse($s->created_at)->format('d/m/Y') }} </td>
                        <td> {{ $s->brut or '---' }} </td>
                        <td> {{ $s->prime or '---' }} </td>
                        <td> {{ $s->comment ? $s->comment : '---' }} </td>
                      </tr>
                    @endforeach
                    </tbody>
                  </table>
                </div>
              @else
                @include('partials.alerts.info', ['messages' => "Aucune donnée trouvée ... !!" ])
              @endif
            </div>
          </div>
        </div>
      @endif
      @if(in_array('Commentaires', $entreEvalsTitle))
        <div class="panel panel-default">
          <div class="panel-heading" role="tab" id="heading-comments">
            <h4 class="panel-title">
              <a role="button" data-toggle="collapse" data-parent="#accordion" href="#collapse-comments"
                 aria-controls="collapse-comments">
                <i class="more-less fa fa-angle-right"></i>
                Commentaires
              </a>
            </h4>
          </div>
          <div id="collapse-comments" class="panel-collapse collapse in" role="tabpanel"
               aria-labelledby="heading-comments">
            <div class="panel-body">
              @if($comment)
                <div class="direct-chat-messages" style="height: auto;">
                  <div class="col-md-6">
                    <h5 class="alert alert-info p-5 mt-0">Commentaire du collaborateur : {{ $user->name." ".$user->last_name }}</h5>
                    <div class="direct-chat-msg mb20">
                      <div class="direct-chat-info clearfix">
                        <span class="direct-chat-name pull-left">{{ $user->name." ".$user->last_name }}</span>
                        <span
                            class="direct-chat-timestamp pull-right">{{ Carbon\Carbon::parse($comment->created_at)->format('d/m/Y à H:i')}}</span>
                      </div>
                      <img class="direct-chat-img" src="{{ App\User::avatar($user->id) }}" alt="message user image">

                      <div class="direct-chat-text">
                        {{ $comment->userComment or '---' }}
                      </div>
                    </div>
                  </div>
                  <div class="col-md-6">
                    <h5 class="alert alert-info p-5 mt-0">Commentaire du mentor : {{ $user->parent->name." ".$user->parent->last_name }}</h5>
                    <div class="direct-chat-msg right">
                      <div class="direct-chat-info clearfix">
                        <span class="direct-chat-name pull-right">{{ $user->parent->name." ".$user->parent->last_name }}</span>
                        <span class="direct-chat-timestamp pull-left">{{ $comment->mentor_updated_at != null ? Carbon\Carbon::parse($comment->mentor_updated_at)->format('d/m/Y à H:i') : '' }}</span>
                      </div>
                      <img class="direct-chat-img" src="{{ App\User::avatar($user->parent->id) }}"
                           alt="message user image">

                      <div class="direct-chat-text">
                        {{ $comment->mentorComment or '---' }}
                      </div>
                    </div>
                  </div>
                </div>
              @else
                @include('partials.alerts.info', ['messages' => "Aucune donnée trouvée ... !!" ])
              @endif
            </div>
          </div>
        </div>
      @endif
    </div>
  @else
    @include('partials.alerts.info', ['messages' => "l'utlisateur ".$user->name." ".$user->last_name." n'a pas de mentor" ])
  @endif
</div>

<script>
  $(document).ready(function () {
    function toggleIcon(e) {
      $(e.target).prev('.panel-heading').find(".more-less").toggleClass('fa-angle-right fa-angle-down');
    }
    $('.panel-group').on('hidden.bs.collapse', toggleIcon);
    $('.panel-group').on('shown.bs.collapse', toggleIcon);
    $('.slider').bootstrapSlider()

    var radarOptions = {
      legend: {
        display: false,
        position: 'top',
      },
      scale: {
        ticks: {
          beginAtZero: true
        }
      }
    }

    @foreach($objectifsPersonnal as $key => $objectif)
    @php($collValues = \App\Objectif_user::getValues($e->id, $user->id, $objectif->id)['collValues'])
    @php($mentorValues = \App\Objectif_user::getValues($e->id, $user->id, $objectif->id)['mentorValues'])
    if (document.getElementById('personnelChart{{$key+1}}')) {
      let myChart{{$key+1}} = new Chart(document.getElementById('personnelChart{{$key+1}}'), {
        type: 'radar',
        data: {
          labels: [
            @foreach($objectif->getIndicators() as $key => $indicator)
            "{{ $indicator['title'] }}",
            @endforeach
          ],
          datasets: [
            {
              label: "",
              borderColor: 'green',
              data: [@foreach($collValues as $value) {{ $value }}, @endforeach]
            },
            {
              label: "",
              borderColor: 'red',
              data: [@foreach($mentorValues as $value) {{ $value }}, @endforeach]
            },
          ]
        },
        options: radarOptions,
      });
    }
    @endforeach

    @foreach($objectifsTeam as $key => $objectif)
    @php($teamValues = \App\Objectif_user::getValues($e->id, $user->id, $objectif->id)['teamValues'])
    if (document.getElementById('teamChart{{$key+1}}')) {
      let myChart{{$key+1}} = new Chart(document.getElementById('teamChart{{$key+1}}'), {
        type: 'radar',
        data: {
          labels: [
            @foreach($objectif->getIndicators() as $key => $indicator)
            "{{ $indicator['title'] }}",
            @endforeach
          ],
          datasets: [
            {
              label: "",
              borderColor: 'green',
              data: [@foreach($teamValues as $value) {{ $value }}, @endforeach]
            }
          ]
        },
        options: radarOptions,
      });
    }
    @endforeach


  })
</script>

