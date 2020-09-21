@extends('layouts.app')
@section('title', 'Compétences')
@section('content')
  @php($isMentor = count(Auth::user()->children)>0 && $user->id != Auth::user()->id)
  <section class="content skills">
    <div class="row">
      <div class="col-md-12">
        @if(Session::has('success_update'))
          @include('partials.alerts.success', ['messages' => Session::get('success_update') ])
        @endif
        <div class="box box-primary card">
          <h3 class="mb40"> Liste des compétences pour : {{$e->titre}} - {{ $user->fullname() }}
          </h3>

          <div class="nav-tabs-custom">
            @include('partials.tabs')
            <div class="tab-content">
              @if ($skill)
                <form action="{{ url('skills/updateUserSkills') }}" method="post">
                  {{ csrf_field() }}
                  <input type="hidden" name="skill_id" value="{{ $skill->id }}">
                  <input type="hidden" name="entretien_id" value="{{ $e->id }}">
                  <input type="hidden" name="user_id" value="{{ $user->id }}">
                  <input type="hidden" name="mentor_id" value="{{ $user->parent->id }}">
                  <div class="row">
                    <div class="col-md-12 mt-20">
                      <p class="m-0 styled-title">Fiche métier : {{ $skill->title }}</p>
                    </div>
                  </div>
                  <div class="row">
                    <div class="col-md-6">
                      <div class="row mb-0">
                        <div class="col-md-12">
                          <h4 class="alert alert-info p-5">{{ $user->fullname() }}</h4>
                        </div>
                        <div class="col-md-12">
                          @forelse($skill->getSkillsTypes() as $type)
                            <div class="panel panel-default mb-30">
                              <div class="panel-heading">{{ $type['title'] }}</div>
                              <div class="panel-body">
                                @foreach($type['skills'] as $key => $skillItem)
                                <div class="row">
                                  <div class="col-md-6">
                                    <span class="">{{ $key + 1 }})</span>
                                    {{ $skillItem['title'] }}
                                  </div>
                                  <div class="col-md-6">
                                    <input type="text"
                                           class="slider"
                                           name="user_notes[skill_type_{{ $type['id'] }}][{{ $key }}]"
                                           data-provide="slider"
                                           data-slider-min="0"
                                           data-slider-max="10"
                                           data-slider-step="0.5"
                                           data-slider-value="{{ \App\Skill::getNote($e->id, $user->id, $user->parent->id, 'skill_type_'.$type['id'], $key, 'user') }}"
                                           data-slider-enabled="{{ Auth::user()->id == $user->id }}"
                                           data-slider-tooltip="always"
                                    >
                                  </div>
                                </div>
                                @endforeach
                              </div>
                            </div>
                          @empty
                            <p>Aucun type de compétence trouvé !</p>
                          @endforelse
                        </div>
                        <div class="col-md-12">
                          <label for="" class="control-label">Commentaires</label>
                          <textarea name="" id="" class="form-control" {{ $isMentor ? 'readonly':'' }}></textarea>
                        </div>
                      </div>
                    </div>
                    @if($isMentor)
                    <div class="col-md-6">
                      <div class="row mb-0">
                        <div class="col-md-12">
                          <h4 class="alert alert-info p-5">{{ $user->parent->fullname() }}</h4>
                        </div>
                        <div class="col-md-12">
                          @forelse($skill->getSkillsTypes() as $type)
                            <div class="panel panel-default mb-30">
                              <div class="panel-heading">{{ $type['title'] }}</div>
                              <div class="panel-body">
                                @foreach($type['skills'] as $key => $skillItem)
                                  <div class="row">
                                    <div class="col-md-6">
                                      {{ $skillItem['title'] }}
                                    </div>
                                    <div class="col-md-6">
                                      <input type="text"
                                             class="slider"
                                             name="mentor_notes[skill_type_{{ $type['id'] }}][{{ $key }}]"
                                             data-provide="slider"
                                             data-slider-min="0"
                                             data-slider-max="10"
                                             data-slider-step="0.5"
                                             data-slider-value="{{ \App\Skill::getNote($e->id, $user->id, $user->parent->id, 'skill_type_'.$type['id'], $key, 'mentor') }}"
                                             data-slider-enabled="true"
                                             data-slider-tooltip="always"
                                      >
                                    </div>
                                  </div>
                                @endforeach
                              </div>
                            </div>
                          @empty
                            <p>Aucun type de compétence trouvé !</p>
                          @endforelse
                        </div>
                        <div class="col-md-12">
                          <label for="" class="control-label">Commentaires</label>
                          <textarea name="" id="" class="form-control"></textarea>
                        </div>
                      </div>
                    </div>
                    @endif
                  </div>
                  <div class="row">
                    <div class="col-md-12">
                      @if(!App\Entretien::answered($e->id, $user->id) && Auth::user()->id == $user->id)
                        <button type="submit" class="btn btn-success"><i class="fa fa-check"></i> Enregistrer
                        </button>
                      @endif
                      @if(!App\Entretien::answeredMentor($e->id, $user->id, $user->parent->id) && Auth::user()->id != $user->id)
                        <button type="submit" class="btn btn-success"><i class="fa fa-check"></i> Enregistrer
                        </button>
                      @endif
                    </div>
                  </div>
                </form>
              @else
                @include('partials.alerts.info', ['messages' => "Aucun résultat trouvé" ])
              @endif
            </div>
          </div>

          @include('partials.submit-eval')

          <div class="callout callout-info">
            <p class="">
              <i class="fa fa-info-circle fa-2x"></i>
              <span class="content-callout">Cette page affiche Liste des compétences de la part du collaborateur: <b>{{ $user->name." ".$user->last_name }}</b> pour l'entretien: <b>{{ $e->titre }}</b> </span>
            </p>
          </div>
        </div>
      </div>
    </div>
  </section>
@endsection
