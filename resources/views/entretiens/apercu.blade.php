
<div class="apercu">
  <div class="useful-actions mb-20">
    <a href="{{ route('entretien.download-pdf', ['eid' => $e->id, 'uid' => $user->id]) }}" class="btn btn-primary"><i class="fa fa-file-pdf-o"></i> Télécharger en PDF</a>
    <a href="javascript:void(0)" id="openAll" class="pull-right ml-20">Tout dérouler</a>
    <a href="javascript:void(0)" id="closeAll" class="pull-right">Tout enrouler</a>
    <div class="clearfix"></div>
  </div>
  @if($user->parent)
    <p class="help-block">Aperçu sur les informations partagées entre
      {{ $user->fullname() }} et
      {{ $user->parent ? $user->parent->name : $user->name }} {{ $user->parent ? $user->parent->last_name : $user->last_name }}
      lors de l'entretien : <b>{{ $e->titre }}</b>
    </p>
    <div class="panel-group" id="accordion">
      @if(in_array('Evaluation annuelle', $entreEvalsTitle))
        <div class="panel panel-default">
          <div class="panel-heading" role="tab" id="heading-evaluations">
            <h4 class="panel-title">
              <a role="button" data-toggle="collapse" data-parent="#accordion" href="#collapse-evaluations" aria-controls="collapse-evaluations" style="padding: 10px 15px;">
                <i class="more-less fa fa-chevron-right"></i>
                Entretien annuel
              </a>
            </h4>
          </div>
          <div id="collapse-evaluations" class="panel-collapse collapse in" role="tabpanel" aria-labelledby="heading-evaluations">
            <div class="panel-body">
              @php($surveyId = App\Evaluation::surveyId($e->id, 1))
              @php($survey = App\Survey::findOrFail($surveyId))
              @include('questions.survey2', ['groupes' => $survey->groupes, 'survey' => $survey, 'evaluator_id' => $eu->mentor_id])
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
                <i class="more-less fa fa-chevron-right"></i>
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
                @include('questions.survey2', ['groupes' => $survey->groupes, 'survey' => $survey, 'evaluator_id' => $eu->mentor_id])
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
                <i class="more-less fa fa-chevron-right"></i>
                Objectifs
              </a>
            </h4>
          </div>
          <div id="collapse-objectifs" class="panel-collapse collapse in" role="tabpanel" aria-labelledby="heading-objectifs">
            <div class="panel-body objectifs">
              <div class="box-body no-padding mb40">
                <ul class="nav nav-tabs">
                  <li class="active"><a data-toggle="tab" href="#personnel">Individuel</a></li>
                  <li><a data-toggle="tab" href="#team">Collectif</a></li>
                </ul>
                <div class="tab-content pt-30">
                  <div id="personnel" class="tab-pane fade in active">
                    @forelse($objectifsPersonnal as $key => $objectif)
                      <h3 class="styled-title mt-0">{{ $objectif->title }}</h3>
                      <canvas class="chart" id="personnelChart{{$key+1}}" style="max-height: 600px;"></canvas>
                    @empty
                      <p>Aucun résultat trouvé !</p>
                    @endforelse
                  </div>
                  <div id="team" class="tab-pane">
                    <div id="personnel" class="tab-pane fade in active">
                      @forelse($objectifsTeam as $key => $objectif)
                        <h3 class="styled-title mt-0">{{ $objectif->title }}</h3>
                        <canvas class="chart" id="teamChart{{$key+1}}" style="max-height: 600px;"></canvas>
                      @empty
                        <p>Aucun résultat trouvé !</p>
                      @endforelse
                    </div>
                  </div>
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
                <i class="more-less fa fa-chevron-right"></i>
                Formations
              </a>
            </h4>
          </div>
          <div id="collapse-formations" class="panel-collapse collapse in" role="tabpanel" aria-labelledby="heading-formations">
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
                <i class="more-less fa fa-chevron-right"></i>
                Compétences
              </a>
            </h4>
          </div>
          <div id="collapse-skills" class="panel-collapse collapse in" role="tabpanel" aria-labelledby="heading-skills">
            <div class="panel-body">
              @forelse($skill->getSkillsTypes() as $type)
                <div class="row">
                  <div class="col-md-12">
                    <h3 style="border-bottom: 2px solid gray;">{{ $type['title'] }} <span class="pull-right">{{ $skill->getSkillTypeNote($e->id, $user->id, $user->parent->id, "skill_type_".$type['id'], $type['id'], 'mentor') }}/10</span></h3>
                    <canvas class="chart" id="type_{{ $type['id'] }}_chart" style="max-height: 600px;"></canvas>
                  </div>
                </div>
              @empty
                <p>Aucun type de compétence trouvé !</p>
              @endforelse
            </div>
          </div>
        </div>
      @endif
      @if(in_array('Primes', $entreEvalsTitle))
        <div class="panel panel-default">
          <div class="panel-heading" role="tab" id="heading-salary">
            <h4 class="panel-title">
              <a role="button" data-toggle="collapse" data-parent="#accordion" href="#collapse-salary"
                 aria-controls="collapse-salary">
                <i class="more-less fa fa-chevron-right"></i>
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
                <i class="more-less fa fa-chevron-right"></i>
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
    $('#openAll').on('click', function () {
      $('.collapse').addClass('in');
    })
    $('#closeAll').on('click', function () {
      $('.collapse').removeClass('in');
    })

    $('.slider').bootstrapSlider()

    var pluginsOptions = [{
      beforeInit: function(chart) {
        chart.data.labels.forEach(function(e, i, a) {
          if (/brk/.test(e)) {
            a[i] = e.split(/brk/);
          }
        });
      }
    }]

    var radarOptions = {
      tooltips: {
        enabled: true,
        mode: 'label'
      },
      scale: {
        ticks: {
          beginAtZero: true
        }
      },
      legend: {
        display: true,
        position: 'top',
      },
    }

    @foreach($objectifsPersonnal as $key => $objectif)
    @php($collValues = isset(\App\Objectif_user::getValues($e->id, $user->id, $objectif->id)['collValues']) ? \App\Objectif_user::getValues($e->id, $user->id, $objectif->id)['collValues'] : [])
    @php($mentorValues = isset(\App\Objectif_user::getValues($e->id, $user->id, $objectif->id)['mentorValues']) ? \App\Objectif_user::getValues($e->id, $user->id, $objectif->id)['mentorValues'] : [])
    if (document.getElementById('personnelChart{{$key+1}}')) {
      let myChart{{$key+1}} = new Chart(document.getElementById('personnelChart{{$key+1}}'), {
        type: 'radar',
        data: {
          labels: [
              @if(!empty($objectif->getIndicators()))
                @foreach($objectif->getIndicators() as $key => $indicator)
                "{{ $indicator['title'] }}",
                @endforeach
              @endif
          ],
          datasets: [
            {
              label: "Collaborateur",
              borderColor: 'green',
              data: [
                  @if(!empty($collValues))
                    @foreach($collValues as $value) {{ $value }}, @endforeach
                  @endif
              ]
            },
            {
              label: "Manager",
              borderColor: 'red',
              data: [
                @if(!empty($mentorValues))
                  @foreach($mentorValues as $value) {{ $value }}, @endforeach
                @endif
              ]
            },
          ]
        },
        options: radarOptions,
      });
    }
    @endforeach

    @foreach($objectifsTeam as $key => $objectif)
    @php($teamValues = isset(\App\Objectif_user::getValues($e->id, $user->id, $objectif->id)['teamValues']) ? \App\Objectif_user::getValues($e->id, $user->id, $objectif->id)['teamValues'] : [])
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
              borderColor: 'orange',
              data: [@foreach($teamValues as $value) {{ $value }}, @endforeach]
            }
          ]
        },
        options: radarOptions,
      });
    }
    @endforeach

    @foreach($skill->getSkillsTypes() as $key => $type)
      @php($field = 'skill_type_'.$type['id'])
      if (document.getElementById('type_{{ $type['id'] }}_chart')) {
        let chart = new Chart(document.getElementById('type_{{ $type['id'] }}_chart'), {
          type: 'radar',
          data: {
            labels: [@foreach($skill->getDataAsArray($key) as $value) "{!! $value !!} " ,@endforeach],
            datasets: [
              {
                label: "Collaborateur",
                borderColor: 'green',
                data: [
                  @foreach(\App\Skill::getFieldNotes($e->id, $user->id, $user->parent->id, $field, 'user') as $note)
                  {{ $note }},
                  @endforeach
                ]
              },
              {
                label: "Manager",
                borderColor: 'red',
                data: [
                  @foreach(\App\Skill::getFieldNotes($e->id, $user->id, $user->parent->id, $field, 'mentor') as $note)
                  {{ $note }},
                  @endforeach
                ]
              },
            ]
          },
          options: radarOptions,
        });
      }
    @endforeach

  })
</script>

