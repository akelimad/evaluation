
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
              @if(!empty($objectifs))
                <div class="box-body no-padding mb40">
                  <div class="row">
                    <div class="{{ $user->id != Auth::user()->id ? 'col-md-6':'col-md-12' }}">
                      @if ($user->id != Auth::user()->id)
                        <h4 class="alert alert-info p-5">{{ $user->name." ".$user->last_name }}</h4>
                      @endif
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
                              <tr class=" {{ count($sub->children) <= 0 ? 'objectifRow' : '' }}" id="{{ count($sub->children) <= 0 ? 'userObjectifRow-' . $sub->id : '' }}" data-userobjrow="{{ $sub->id }}">
                                <td style="max-width: 6%">{{ $sub->title }}</td>
                                <td class="criteres text-center slider-note {{$user->id != Auth::user()->id ? 'disabled':''}}">
                                  @if (count($sub->children) <= 0)
                                    <input type="text" class="slider userNote userObjSection-{{ $sub->id }}"
                                           data-objectif="{{ $sub->id }}" data-section="{{ $objectif->id }}" required=""
                                           name="objectifs[{{$objectif->id}}][{{$sub->id}}][userNote]" data-provide="slider"
                                           data-slider-min="0" data-slider-max="200" data-slider-step="1"
                                           data-slider-value="{{App\Objectif::getObjectif($e->id, $user, null, $sub->id) ? App\Objectif::getObjectif($e->id, $user, null, $sub->id)->userNote : '0' }}"
                                           data-slider-tooltip="always">
                                    <input type="hidden" name="objectifs[{{$objectif->id}}][{{$sub->id}}][realise]"
                                           value="">
                                  @endif
                                </td>
                                <td>
                                  @if (count($sub->children) <= 0)
                                    <input type="text" name="objectifs[{{$objectif->id}}][{{$sub->id}}][userAppr]"
                                           class="form-control"
                                           value="{{App\Objectif::getObjectif($e->id, $user, null, $sub->id) ? App\Objectif::getObjectif($e->id, $user, null, $sub->id)->userAppreciation : '' }}"
                                           placeholder="Révision de l'objectif ..."
                                           title="Révision de l'objectif (optionnel) + date de la révision"
                                           data-toggle="tooltip">
                                  @endif
                                </td>
                                <td class="text-center">
                                  <span class="ponderation">{{ $sub->ponderation }}</span>
                                </td>
                              </tr>
                              @if (count($sub->children) > 0)
                                @foreach($sub->children as $subObj)
                                  <tr class="subObjectifRow">
                                    <td class="pl-30"><i class="fa fa-minus"></i> {{ $subObj->title }}</td>
                                    <td class="{{$user->id != Auth::user()->id ? 'disabled':''}}">
                                      <input type="text" class="slider userNote userSubObjSection-{{ $sub->id }}" data-objectif="{{ $sub->id }}" data-section="{{ $objectif->id }}" required="" name="objectifs[{{$objectif->id}}][{{$subObj->id}}][userNote]" data-provide="slider"
                                             data-slider-min="0"
                                             data-slider-max="200"
                                             data-slider-step="1"
                                             data-slider-value="{{App\Objectif::getObjectif($e->id, $user, null, $subObj->id) ? App\Objectif::getObjectif($e->id, $user, null, $subObj->id)->userNote : '0' }}"
                                             data-slider-tooltip="always">
                                    </td>
                                    <td>
                                      <input type="text" name="objectifs[{{$objectif->id}}][{{$subObj->id}}][userAppr]"
                                             class="form-control"
                                             value="{{App\Objectif::getObjectif($e->id, $user, null, $subObj->id) ? App\Objectif::getObjectif($e->id, $user, null, $subObj->id)->userAppreciation : '' }}"
                                             placeholder="Révision de l'objectif ..."
                                             title="Révision de l'objectif (optionnel) + date de la révision"
                                             data-toggle="tooltip">
                                    </td>
                                    <td><span class="ponderation">{{ $subObj->ponderation }}</span></td>
                                  </tr>
                                @endforeach
                                <tr style="background: #cae5f1;">
                                  <td>Sous total</td>
                                  <td colspan="3">
                                    <span class="badge badge-success pull-right userSubTotalObjectif userSubTotalObjectif-{{ $objectif->id }}" id="userSubTotalSubObjectif-{{ $sub->id }}" data-ponderation="{{ $sub->ponderation }}">0</span>
                                  </td>
                                </tr>
                              @endif
                              @if (count($sub->children) <= 0)
                                <tr style="background: #cae5f1;">
                                  <td>Sous total</td>
                                  <td colspan="3">
                                    <span class="badge badge-success pull-right userSubTotalObjectif userSubTotalObjectif-{{ $objectif->id }}" id="userSubTotalObjectif-{{ $sub->id }}" data-ponderation="{{ $sub->ponderation }}">0</span>
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
                                  <td colspan="4">
                                    @if ($field->type == 'text')
                                      <input type="text" name="user_extra_fields_data[{{$key}}]" class="form-control" value="{{ App\Objectif::getExtraFieldData($e->id, $user, null, $sub->id, $key) }}">
                                    @elseif ($field->type == 'textarea')
                                      <textarea name="user_extra_fields_data[{{$key}}]" class="form-control">{{ App\Objectif::getExtraFieldData($e->id, $user, null, $sub->id, $key) }}</textarea>
                                    @endif
                                  </td>
                                </tr>
                              @endforeach
                            @endif
                            <tr>
                              <td colspan="4" class="sousTotal">
                                <span>Sous total de la section (%)</span>
                                <span class="badge badge-success pull-right userSubTotalSection" id="userSubTotalSection-{{$objectif->id}}">0</span>
                              </td>
                            </tr>
                          @endforeach
                          <tr>
                            <td colspan="4" class="btn-warning" valign="middle">
                              <span>TOTAL DE L'ÉVALUATION (%)</span>
                              <span class="btn-default pull-right badge userTotalNote">0</span>
                            </td>
                          </tr>
                        </table>
                      </div>
                    </div>
                    @if ($user->id != Auth::user()->id)
                      <div class="col-md-6">
                        <h4 class="alert alert-info p-5">{{ $user->parent->name." ".$user->parent->last_name }}</h4>
                        <div class="table-responsive">
                          <table class="table table-hover">
                            <tr>
                              <th width="20%">Critères d'évaluation</th>
                              <th>Notation (%)</th>
                              <th>Apréciation</th>
                              <th class="text-center">Pondération (%)</th>
                            </tr>
                            @foreach($objectifs as $objectif)
                              <input type="hidden" name="parentObjectif[]" value="{{$objectif->id}}">
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
                                      <input type="text" class="slider mentorNote mentorObjSection-{{ $sub->id }}" data-objectif="{{ $sub->id }}" data-section="{{ $objectif->id }}" name="objectifs[{{$objectif->id}}][{{$sub->id}}][mentorNote]" data-provide="slider"
                                             data-slider-min="0"
                                             data-slider-max="200"
                                             data-slider-step="1"
                                             data-slider-value="{{App\Objectif::getObjectif($e->id, $user, $user->parent, $sub->id) ? App\Objectif::getObjectif($e->id, $user, $user->parent, $sub->id)->mentorNote : '0' }}"
                                             data-slider-tooltip="always">
                                    @endif
                                  </td>
                                  <td>
                                    @if (count($sub->children) <= 0)
                                      <input type="text" name="objectifs[{{$objectif->id}}][{{$sub->id}}][mentorAppr]"
                                             class="form-control"
                                             value="{{App\Objectif::getObjectif($e->id, $user, $user->parent, $sub->id) ? App\Objectif::getObjectif($e->id, $user, $user->parent, $sub->id)->mentorAppreciation : '' }}"
                                             placeholder="Révision de l'objectif ..."
                                             title="Révision de l'objectif (optionnel) + date de la révision"
                                             data-toggle="tooltip">
                                    @endif
                                  </td>
                                  <td class="text-center">
                                    <span class="ponderation">{{ $sub->ponderation }}</span>
                                  </td>
                                </tr>
                                @if (count($sub->children) > 0)
                                  @foreach($sub->children as $subObj)
                                    <tr class="subObjectifRow">
                                      <td class="pl-30"><i class="fa fa-minus"></i> {{ $subObj->title }}</td>
                                      <td class="criteres text-center slider-note">
                                        <input type="text" class="slider mentorNote mentorSubObjSection-{{ $sub->id }}"
                                               data-objectif="{{ $sub->id }}" data-section="{{ $objectif->id }}"
                                               name="objectifs[{{$objectif->id}}][{{$subObj->id}}][mentorNote]"
                                               data-provide="slider" data-slider-min="0" data-slider-max="200"
                                               data-slider-step="1"
                                               data-slider-value="{{App\Objectif::getObjectif($e->id, $user, $user->parent, $subObj->id) ? App\Objectif::getObjectif($e->id, $user, $user->parent, $subObj->id)->mentorNote : '0' }}"
                                               data-slider-tooltip="always">
                                      </td>
                                      <td>
                                        <input type="text" name="objectifs[{{$objectif->id}}][{{$subObj->id}}][mentorAppr]"
                                               class="form-control"
                                               value="{{App\Objectif::getObjectif($e->id, $user, $user->parent, $subObj->id) ? App\Objectif::getObjectif($e->id, $user, $user->parent, $subObj->id)->mentorAppreciation : '' }}"
                                               placeholder="Révision de l'objectif ..."
                                               title="Révision de l'objectif (optionnel) + date de la révision"
                                               data-toggle="tooltip">
                                      </td>
                                      <td><span class="ponderation">{{ $subObj->ponderation }}</span></td>
                                    </tr>
                                  @endforeach
                                  <tr style="background: #cae5f1;">
                                    <td>Sous total</td>
                                    <td colspan="3">
                                      <span class="badge badge-success pull-right mentorSubTotalObjectif mentorSubTotalObjectif-{{ $objectif->id }}" id="mentorSubTotalSubObjectif-{{ $sub->id }}" data-ponderation="{{ $sub->ponderation }}">0</span>
                                    </td>
                                  </tr>
                                @endif
                                @if (count($sub->children) <= 0)
                                  <tr style="background: #cae5f1;">
                                    <td>Sous total</td>
                                    <td colspan="3">
                                      <span class="badge badge-success pull-right mentorSubTotalObjectif mentorSubTotalObjectif-{{ $objectif->id }}" id="mentorSubTotalObjectif-{{ $sub->id }}" data-ponderation="{{ $sub->ponderation }}">0</span>
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
                                    <td colspan="4">
                                      @if ($field->type == 'text')
                                        <input type="text" name="mentor_extra_fields_data[{{$key}}]" class="form-control" value="{{ App\Objectif::getExtraFieldData($e->id, $user, $user->parent, $sub->id, $key, false) }}">
                                      @elseif ($field->type == 'textarea')
                                        <textarea name="mentor_extra_fields_data[{{$key}}]" class="form-control">{{ App\Objectif::getExtraFieldData($e->id, $user, $user->parent, $sub->id, $key, false) }}</textarea>
                                      @endif
                                    </td>
                                  </tr>
                                @endforeach
                              @endif
                              <tr>
                                <td colspan="3" class="sousTotal">
                                  <span>Sous total de la section (%)</span>
                                </td>
                                <td colspan="3" class="sousTotal">
                                  <span class="badge badge-success pull-right mentorSubTotalSection" id="mentorSubTotalSection-{{$objectif->id}}">0</span>
                                </td>
                              </tr>
                            @endforeach
                            <tr>
                              <td colspan="3" class="btn-warning"
                                  valign="middle">
                                <span>TOTAL DE L'ÉVALUATION (%)</span>
                              </td>
                              <td colspan="3" class="btn-warning" valign="middle">
                                <span class="btn-default pull-right badge mentorTotalNote">0</span>
                              </td>
                            </tr>
                          </table>
                        </div>
                      </div>
                    @endif
                  </div>
                </div>
              @else
                @include('partials.alerts.info', ['messages' => "Aucun résultat trouvé" ])
              @endif
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

    // Calculate sub total & total in real-time
    var userTypes
    @if ($user->id == Auth::user()->id)
      userTypes = ['user']
    @else
      userTypes = ['user', 'mentor']
    @endif
    $.each(userTypes, function (i, userType) {
      $('.slider').on('change', function (ev) {
        var objectifId = $(this).data('objectif')
        var sectionId = $(this).data('section')
        var subObjectifs = $('.'+ userType +'SubObjSection-' + objectifId)
        var total = sectionTotal = SubTotalObjectif = SubTotalSubObjectif = note = ponderation = objPonderation = 0

        // calculate sub total of objectif that have own sub objectifs
        $.each(subObjectifs, function (i, el) {
          note = $(el).closest('.subObjectifRow').find('.' + userType + 'Note').val()
          ponderation = $(el).closest('.subObjectifRow').find('.ponderation').text()
          SubTotalSubObjectif += parseInt(note) * parseInt(ponderation)
        })
        objPonderation = $('[data-'+ userType +'objrow="'+ objectifId +'"]').find('.ponderation').text()
        objPonderation = parseInt(objPonderation) / 100
        SubTotalSubObjectif = Math.round((SubTotalSubObjectif / 100) * objPonderation)
        $('#'+ userType +'SubTotalSubObjectif-' + objectifId).text(SubTotalSubObjectif)

        // calculate sub total of objectif that have'nt sub objectifs
        $.each($('#' + userType + 'ObjectifRow-' + objectifId), function (i, el) {
          note = $(el).closest('.objectifRow').find('.' + userType + 'Note').val()
          ponderation = $(el).closest('.objectifRow').find('.ponderation').text()
          SubTotalObjectif += parseInt(note) * (parseInt(ponderation) / 100)
        })
        SubTotalObjectif = Math.round(SubTotalObjectif)
        $('#'+ userType +'SubTotalObjectif-' + objectifId).text(SubTotalObjectif)

        // calculate sub total of section
        var countObjs = 0
        $.each($('.'+ userType +'SubTotalObjectif-' + sectionId), function (i, el) {
          countObjs += 1
          sectionTotal += parseInt($(el).text())
        })
        sectionTotal = sectionTotal / countObjs
        $('#' + userType + 'SubTotalSection-' + sectionId).text(Math.round(sectionTotal))

        // calculate total note of evaluation
        var countSections = 0
        $.each($('.' + userType + 'SubTotalSection'), function (i, el) {
          countSections += 1
          total += parseInt($(el).text())
        })
        total = total / countSections
        $('.' + userType + 'TotalNote').text(Math.round(total))
      })
    })
    $('.slider').trigger('change')
  })
</script>

