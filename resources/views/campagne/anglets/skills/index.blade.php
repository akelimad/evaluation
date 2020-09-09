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
                          <h3 class="border-bottom">Savoir</h3>
                          @foreach($skill->getDataAsArray('savoir') as $key => $item)
                            <div class="row">
                              <div class="col-md-6">
                                <p>{{ $item }}</p>
                              </div>
                              <div class="col-md-6">
                                <input type="text"
                                       class="slider"
                                       name="user_notes[savoir][{{ $key }}]"
                                       data-provide="slider"
                                       data-slider-min="0"
                                       data-slider-max="10"
                                       data-slider-step="0.5"
                                       data-slider-value="{{ \App\Skill::getNote($e->id, $user->id, $user->parent->id, 'savoir', $key, 'user') }}"
                                       data-slider-enabled="true"
                                       data-slider-tooltip="always"
                                >
                              </div>
                            </div>
                          @endforeach

                          <h3 class="border-bottom">Savoir-faire</h3>
                          @foreach($skill->getDataAsArray('savoir_faire') as $key => $item)
                            <div class="row">
                              <div class="col-md-6">
                                <p>{{ $item }}</p>
                              </div>
                              <div class="col-md-6">
                                <input type="text"
                                       class="slider"
                                       name="user_notes[savoir_faire][{{ $key }}]"
                                       data-provide="slider"
                                       data-slider-min="0"
                                       data-slider-max="10"
                                       data-slider-step="0.5"
                                       data-slider-value="{{ \App\Skill::getNote($e->id, $user->id, $user->parent->id, 'savoir_faire', $key, 'user') }}"
                                       data-slider-enabled="true"
                                       data-slider-tooltip="always"
                                >
                              </div>
                            </div>
                          @endforeach

                          <h3 class="border-bottom">Savoir-être</h3>
                          @foreach($skill->getDataAsArray('savoir_etre') as $key => $item)
                            <div class="row">
                              <div class="col-md-6">
                                <p>{{ $item }}</p>
                              </div>
                              <div class="col-md-6">
                                <input type="text"
                                       class="slider"
                                       name="user_notes[savoir_etre][{{ $key }}]"
                                       data-provide="slider"
                                       data-slider-min="0"
                                       data-slider-max="10"
                                       data-slider-step="0.5"
                                       data-slider-value="{{ \App\Skill::getNote($e->id, $user->id, $user->parent->id, 'savoir_faire', $key, 'user') }}"
                                       data-slider-enabled="true"
                                       data-slider-tooltip="always"
                                >
                              </div>
                            </div>
                          @endforeach
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
                          <h3 class="border-bottom">Savoir</h3>
                          @foreach($skill->getDataAsArray('savoir') as $key => $item)
                            <div class="row">
                              <div class="col-md-6">
                                <p>{{ $item }}</p>
                              </div>
                              <div class="col-md-6">
                                <input type="text"
                                       class="slider"
                                       name="mentor_notes[savoir][{{ $key }}]"
                                       data-provide="slider"
                                       data-slider-min="0"
                                       data-slider-max="10"
                                       data-slider-step="0.5"
                                       data-slider-value="{{ \App\Skill::getNote($e->id, $user->id, $user->parent->id, 'savoir', $key, 'mentor') }}"
                                       data-slider-enabled="true"
                                       data-slider-tooltip="always"
                                >
                              </div>
                            </div>
                          @endforeach

                          <h3 class="border-bottom">Savoir-faire</h3>
                          @foreach($skill->getDataAsArray('savoir_faire') as $key => $item)
                            <div class="row">
                              <div class="col-md-6">
                                <p>{{ $item }}</p>
                              </div>
                              <div class="col-md-6">
                                <input type="text"
                                       class="slider"
                                       name="mentor_notes[savoir_faire][{{ $key }}]"
                                       data-provide="slider"
                                       data-slider-min="0"
                                       data-slider-max="10"
                                       data-slider-step="0.5"
                                       data-slider-value="{{ \App\Skill::getNote($e->id, $user->id, $user->parent->id, 'savoir_faire', $key, 'mentor') }}"
                                       data-slider-enabled="true"
                                       data-slider-tooltip="always"
                                >
                              </div>
                            </div>
                          @endforeach

                          <h3 class="border-bottom">Savoir-être</h3>
                          @foreach($skill->getDataAsArray('savoir_etre') as $key => $item)
                            <div class="row">
                              <div class="col-md-6">
                                <p>{{ $item }}</p>
                              </div>
                              <div class="col-md-6">
                                <input type="text"
                                       class="slider"
                                       name="mentor_notes[savoir_etre][{{ $key }}]"
                                       data-provide="slider"
                                       data-slider-min="0"
                                       data-slider-max="10"
                                       data-slider-step="0.5"
                                       data-slider-value="{{ \App\Skill::getNote($e->id, $user->id, $user->parent->id, 'savoir_faire', $key, 'mentor') }}"
                                       data-slider-enabled="true"
                                       data-slider-tooltip="always"
                                >
                              </div>
                            </div>
                          @endforeach
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