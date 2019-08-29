@extends('layouts.app')

@section('content')
  <section class="content objectifs">
    <div class="row">
      <div class="col-md-12">
        <div class="box box-primary card">
          <h3 class="mb40"> Liste des objectifs pour: {{$e->titre}} - {{ $user->name." ".$user->last_name }} </h3>

          <div class="nav-tabs-custom">
            @include('partials.tabs')
            <div class="tab-content">
              @if(!empty($objectifs))
                <div class="box-body no-padding mb40">
                  <div class="row">
                    <div class="{{ $user->id != Auth::user()->id ? 'col-md-6':'col-md-12' }}">
                      @if ($user->id != Auth::user()->id)
                        <h4 class="alert alert-info p-5">{{ $user->name." ".$user->last_name }}</h4>
                      @endif
                      <div class="table-responsive">
                        <form action="{{url('objectifs/updateNoteObjectifs')}}">
                          <input type="hidden" name="entretien_id" value="{{$e->id}}">
                          <input type="hidden" name="user_id" value="{{$user->id}}">
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
                                  <td>Sous total (%)</td>
                                  <td colspan="3">
                                    <span class="badge badge-success pull-right userSubTotalObjectif userSubTotalObjectif-{{ $objectif->id }}" id="userSubTotalSubObjectif-{{ $sub->id }}" data-ponderation="{{ $sub->ponderation }}">0</span>
                                  </td>
                                </tr>
                              @endif
                              @if (count($sub->children) <= 0)
                                <tr style="background: #cae5f1;">
                                  <td>Sous total (%)</td>
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
                          @if(!App\Entretien_user::userHasSubmitedEval($e->id, $user->id))
                            <button type="submit" class="btn btn-success pull-right"><i class="fa fa-check"></i> Sauvegarder
                            </button>
                          @endif
                        </form>
                      </div>
                    </div>
                    @if ($user->id != Auth::user()->id)
                      <div class="col-md-6">
                        <h4 class="alert alert-info p-5">{{ $user->parent->name." ".$user->parent->last_name }}</h4>
                        <div class="table-responsive">
                          <form action="{{url('objectifs/updateNoteObjectifs')}}">
                            <input type="hidden" name="entretien_id" value="{{$e->id}}">
                            <input type="hidden" name="user_id" value="{{$user->id}}">
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
                                      <td>Sous total (%)</td>
                                      <td colspan="3">
                                        <span class="badge badge-success pull-right mentorSubTotalObjectif mentorSubTotalObjectif-{{ $objectif->id }}" id="mentorSubTotalSubObjectif-{{ $sub->id }}" data-ponderation="{{ $sub->ponderation }}">0</span>
                                      </td>
                                    </tr>
                                  @endif
                                  @if (count($sub->children) <= 0)
                                    <tr style="background: #cae5f1;">
                                      <td>Sous total (%)</td>
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
                            @if(!App\Entretien_user::mentorHasSubmitedEval($e->id, $user->id, $user->parent->id))
                              <button type="submit" class="btn btn-success pull-right"><i class="fa fa-check"></i> Sauvegarder
                              </button>
                            @endif
                          </form>
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
@endsection


