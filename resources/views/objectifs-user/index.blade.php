@extends('layouts.app')
@section('style')
  <style>
    .objectifs-type .nav-tabs>li>a {
      padding: 10px 50px;
    }
    .objectifs-type .nav-tabs>li.active>a,
    .objectifs-type .nav-tabs>li.active>a:focus,
    .objectifs-type .nav-tabs>li.active>a:hover {
      border: none;
      border-bottom: 3px solid #3c8dbc;
      color: #3c8dbc;
    }
    table tbody tr td {
      padding: 15px 8px !important;
    }
  </style>
@endsection
@php($isMentor = count(Auth::user()->children)>0 && $user->id != Auth::user()->id)
@section('content')
  <section class="content objectifs">
    <div class="row">
      <div class="col-md-12">
        <div class="box box-primary card">
          <h3 class="mb40"> Liste des objectifs pour: {{$e->titre}} - {{ $user->name." ".$user->last_name }} </h3>

          <div class="nav-tabs-custom">
            @include('partials.tabs')
            <div class="tab-content">
              <div class="box-body no-padding mb40">
                <form action="{{ route('updateNoteObjectifs') }}" method="post">
                  <input type="hidden" name="entretien_id" value="{{$e->id}}">
                  <input type="hidden" name="user_id" value="{{$user->id}}">
                  {{ csrf_field() }}
                  <div class="row">
                    <div class="col-md-{{ !$isMentor ? '12':'6'  }} objectifs-type">
                      <h4 class="alert alert-info p-5">{{ $user->fullname() }}</h4>
                      <ul class="nav nav-tabs">
                        <li class="active"><a data-toggle="tab" href="#user-personnel">Individuel</a></li>
                        <li><a data-toggle="tab" href="#user-team">Collectif</a></li>
                      </ul>
                      <div class="tab-content pt-30">
                        <div id="user-personnel" class="tab-pane fade in active">
                          @forelse($objectifsPersonnal as $objectif)
                            <div class="item">
                              <p class="bg-gray p-5">
                                <b>Titre :</b> {{ $objectif->title }}
                                <span class="pull-right font-20">{{ \App\Objectif::getTotalNote($e->id, $user->id, $objectif->id) }} %</span>
                              </p>
                            </div>
                            <div class="item">
                              <p><b>Date d'échéance :</b> {{ date('d/m/Y', strtotime($objectif->deadline)) }}</p>
                            </div>
                            <div class="item">
                              <p class="mb-0"><b>Indicateurs :</b></p>
                              <table class="table">
                                <thead>
                                <tr>
                                  <th width="28%">Titre</th>
                                  <th width="12%" class="text-center"><span title="Objectif fixé" data-toggle="tooltip">O <i class="fa fa-question-circle"></i></span></th>
                                  <th width="40%" class="text-center">Réalisé</th>
                                  <th width="10%" class="text-center">En %</th>
                                  <th width="10%" class="text-center"><span title="Pondération en %" data-toggle="tooltip">P <i class="fa fa-question-circle"></i></span></th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($objectif->getIndicators() as $indicator)
                                  <tr>
                                    <td>{{ $indicator['title'] }}</td>
                                    <td class="text-center">{{ $indicator['fixed'] }}</td>
                                    <td>
                                      <input type="text"
                                             class="slider"
                                             name="objectifs[{{ $objectif->id }}][{{ $indicator['id'] }}][user_realized]"
                                             data-provide="slider"
                                             data-slider-min="0"
                                             data-slider-max="{{ $indicator['fixed'] * 2 }}"
                                             data-slider-step="1"
                                             data-slider-value="{{ \App\Objectif_user::getRealised($e->id, $user->id, $objectif->id, $indicator['id']) }}"
                                             data-slider-enabled="{{ $user->id == Auth::user()->id }}"
                                             data-slider-tooltip="always">
                                    </td>
                                    <td class="text-center">
                                      {{ round((\App\Objectif_user::getRealised($e->id, $user->id, $objectif->id, $indicator['id']) / $indicator['fixed']) * 100) }}
                                    </td>
                                    <td class="text-center">{{ $indicator['ponderation'] }}</td>
                                  </tr>
                                @endforeach
                                </tbody>
                              </table>
                            </div>
                          @empty
                            <tr>
                              <td>
                                @include('partials.alerts.info', ['messages' => "Aucun résultat trouvé" ])
                              </td>
                            </tr>
                          @endforelse
                        </div>
                        <div id="user-team" class="tab-pane fade">
                          @forelse($objectifsTeam as $objectif)
                            <div class="item">
                              <p class="bg-gray p-5"><b>Titre :</b>
                                {{ $objectif->title }}
                                <span class="pull-right font-20">0 %</span>
                              </p>
                            </div>
                            <div class="item">
                              <p><b>Collectif :</b> {{ $objectif->team > 0 ? \App\Team::find($objectif->team)->name : '---' }}</p>
                            </div>
                            <div class="item">
                              <p><b>Date d'échéance :</b> {{ date('d/m/Y', strtotime($objectif->deadline)) }}</p>
                            </div>
                            <div class="item">
                              <p class="mb-0"><b>Indicateurs :</b></p>
                              <table class="table">
                                <thead>
                                <tr>
                                  <th width="28%" class="text-center">Titre</th>
                                  <th width="12%" class="text-center"><span title="Objectif fixé" data-toggle="tooltip">O <i class="fa fa-question-circle"></i></span></th>
                                  <th width="48%" class="text-center">
                                    Réalisé <span title="Cette valeur ne peut être remplie que par les managers" data-toggle="tooltip"><i class="fa fa-question-circle font-16"></i></span>
                                  </th>
                                  <th width="12%" class="text-center"><span title="Pondération en %" data-toggle="tooltip">P <i class="fa fa-question-circle"></i></span></th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($objectif->getIndicators() as $indicator)
                                  <tr>
                                    <td>{{ $indicator['title'] }}</td>
                                    <td class="text-center">{{ $indicator['fixed'] }}</td>
                                    <td>
                                      <input type="text"
                                             class="slider"
                                             name=""
                                             data-provide="slider"
                                             data-slider-min="0"
                                             data-slider-max="{{ $indicator['fixed'] * 2 }}"
                                             data-slider-step="1"
                                             data-slider-value=""
                                             data-slider-tooltip="always"
                                             data-slider-enabled="false">
                                    </td>
                                    <td class="text-center">{{ $indicator['ponderation'] }}</td>
                                  </tr>
                                @endforeach
                                </tbody>
                              </table>
                            </div>
                          @empty
                            <tr>
                              <td>
                                @include('partials.alerts.info', ['messages' => "Aucun résultat trouvé" ])
                              </td>
                            </tr>
                          @endforelse
                        </div>
                      </div>
                    </div>
<!-- *************************************************************************************** !-->
                    @if($isMentor)
                      <div class="col-md-6 objectifs-type">
                      <h4 class="alert alert-info p-5">{{ $user->parent->fullname() }}</h4>
                      <ul class="nav nav-tabs">
                        <li class="active"><a data-toggle="tab" href="#mentor-personnel">Individuel</a></li>
                        <li><a data-toggle="tab" href="#mentor-team">Collectif</a></li>
                      </ul>
                      <div class="tab-content pt-30">
                        <div id="mentor-personnel" class="tab-pane fade in active">
                          @forelse($objectifsPersonnal as $objectif)
                            <div class="item">
                              <p class="bg-gray p-5">
                                <b>Titre :</b> {{ $objectif->title }}
                                <span class="pull-right font-20">{{ \App\Objectif::getTotalNote($e->id, $user->id, $objectif->id, 'mentor_personnal') }} %</span>
                              </p>
                            </div>
                            <div class="item">
                              <p><b>Date d'échéance :</b> {{ date('d/m/Y', strtotime($objectif->deadline)) }}</p>
                            </div>
                            <div class="item">
                              <p class="mb-0"><b>Indicateurs :</b></p>
                              <table class="table">
                                <thead>
                                <tr>
                                  <th width="80%" class="text-center">Réalisé</th>
                                  <th width="20%" class="text-center">En %</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($objectif->getIndicators() as $indicator)
                                  <tr>
                                    <td>
                                      <input type="text"
                                             class="slider"
                                             name="objectifs[{{ $objectif->id }}][{{ $indicator['id'] }}][mentor_personnal_realized]"
                                             data-provide="slider"
                                             data-slider-min="0"
                                             data-slider-max="{{ $indicator['fixed'] * 2 }}"
                                             data-slider-step="1"
                                             data-slider-value="{{ \App\Objectif_user::getRealised($e->id, $user->id, $objectif->id, $indicator['id'], 'mentor_personnal') }}"
                                             data-slider-tooltip="always">
                                    </td>
                                    <td class="text-center">
                                      {{ round((\App\Objectif_user::getRealised($e->id, $user->id, $objectif->id, $indicator['id'], 'mentor') / $indicator['fixed']) * 100) }}
                                    </td>
                                  </tr>
                                @endforeach
                                </tbody>
                              </table>
                            </div>
                          @empty
                            <tr>
                              <td>
                                @include('partials.alerts.info', ['messages' => "Aucun résultat trouvé" ])
                              </td>
                            </tr>
                          @endforelse
                        </div>
                        <div id="mentor-team" class="tab-pane fade">
                          @forelse($objectifsTeam as $objectif)
                            <div class="item">
                              <p class="bg-gray p-5"><b>Titre :</b>
                                {{ $objectif->title }}
                                <span class="pull-right font-20">{{ \App\Objectif::getTotalNote($e->id, $user->id, $objectif->id, 'mentor_team') }} %</span>
                              </p>
                            </div>
                            <div class="item">
                              <p><b>Collectif :</b> {{ $objectif->team > 0 ? \App\Team::find($objectif->team)->name : '---' }}</p>
                            </div>
                            <div class="item">
                              <p><b>Date d'échéance :</b> {{ date('d/m/Y', strtotime($objectif->deadline)) }}</p>
                            </div>
                            <div class="item">
                              <p class="mb-0"><b>Indicateurs :</b></p>
                              <table class="table">
                                <thead>
                                <tr>
                                  <th width="90%" class="text-center">Réalisé <span title="Cette valeur ne peut être remplie que par les managers" data-toggle="tooltip"><i class="fa fa-question-circle font-16"></i></span>
                                  </th>
                                  <th width="10%" class="text-center"><span title="Pondération en %" data-toggle="tooltip">P <i class="fa fa-question-circle"></i></span></th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($objectif->getIndicators() as $indicator)
                                  <tr>
                                    <td>
                                      <input type="text"
                                             class="slider"
                                             name="objectifs[{{ $objectif->id }}][{{ $indicator['id'] }}][mentor_team_realized]"
                                             data-provide="slider"
                                             data-slider-min="0"
                                             data-slider-max="{{ $indicator['fixed'] * 2 }}"
                                             data-slider-step="1"
                                             data-slider-value="{{ \App\Objectif_user::getRealised($e->id, $user->id, $objectif->id, $indicator['id'], 'mentor_team') }}"
                                             data-slider-tooltip="always"
                                             data-slider-enabled="true">
                                    </td>
                                    <td class="text-center">{{ $indicator['ponderation'] }}</td>
                                  </tr>
                                @endforeach
                                </tbody>
                              </table>
                            </div>
                          @empty
                            <tr>
                              <td>
                                @include('partials.alerts.info', ['messages' => "Aucun résultat trouvé" ])
                              </td>
                            </tr>
                          @endforelse
                        </div>
                      </div>
                    </div>
                    @endif
                  </div>
                  @if(!App\Entretien::answered($e->id, Auth::user()->id))
                  <div class="row">
                    <div class="col-md-12">
                      <div class="save-action mt-20">
                        <button type="submit" class="btn btn-success" > <i class="fa fa-check"></i> Enregistrer tout</button>
                        <div class="clearfix"></div>
                      </div>
                    </div>
                  </div>
                  @endif
                </form>
              </div>
            </div>
          </div>

          @include('partials.submit-eval')

          <div class="callout callout-info">
            <p class="">
              <i class="fa fa-info-circle fa-2x"></i>
              <span class="content-callout">Cette page affiche la liste des objectifs du collaborateur: <b>{{ $user->name." ".$user->last_name }}</b> dans le cadre de l'entretien : <b>{{ $e->titre }}</b> </span>
            </p>
          </div>
        </div>
      </div>
    </div>
  </section>
@endsection

@section('javascript')
  <script>
    $(document).ready(function () {

    })
  </script>
@endsection



