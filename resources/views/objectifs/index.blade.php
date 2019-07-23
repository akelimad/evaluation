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
                <div class="box-body table-responsive no-padding mb40">
                  <form action="{{url('objectifs/updateNoteObjectifs')}}">
                    <input type="hidden" name="entretien_id" value="{{$e->id}}">
                    <input type="hidden" name="user_id" value="{{$user->id}}">
                    <table class="table table-hover table-striped">
                      @if($user->id != Auth::user()->id)
                        <tr class="userMentorInfoRow">
                          <td colspan="3" class="objectifTitle {{ $user->id != Auth::user()->id ? 'separate':'' }}">
                            {{ $user->name." ".$user->last_name }}
                          </td>
                          <td colspan="4" class="objectifTitle">
                            {{ $user->parent->name." ".$user->parent->last_name }}
                          </td>
                        </tr>
                      @endif
                      <tr>
                        <th width="20%">Critères d'évaluation</th>
                        <th>Notation (%)</th>
                        <th class="{{ $user->id != Auth::user()->id ? 'separate':'' }}">Apréciation</th>
                        <th class="text-center">Pondération (%)</th>
                        <th title="Objectif N+1">Obj N+1</th>
                        @if($user->id != Auth::user()->id)
                          <th width="15%">Notation (%)</th>
                          <th>Appreciation</th>
                        @endif
                      </tr>
                      @php($c = 0)
                      @php($userTotal = 0)
                      @php($mentorTotal = 0)
                      @foreach($objectifs as $objectif)
                        @php($c += 1)
                        <input type="hidden" name="parentObjectif[]" value="{{$objectif->id}}">
                        <tr>
                          <td colspan="7" class="objectifTitle text-center">
                            {{ $objectif->title }}
                          </td>
                        </tr>
                        @php($usersousTotal = 0)
                        @php($mentorsousTotal = 0)
                        @php($sumPonderation = 0)
                        @foreach($objectif->children as $sub)

                          @php( $sumPonderation += $sub->ponderation )
                          @if($user->id == Auth::user()->id )
                            @if(App\Objectif::getObjectif($e->id,$user->id, $sub->id))
                              @php( $usersousTotal += App\Objectif::getObjectif($e->id,$user->id, $sub->id)->userNote * $sub->ponderation )
                            @endif
                          @else
                            @if(App\Objectif::getObjectif($e->id,$user->id, $sub->id))
                              @php( $usersousTotal += App\Objectif::getObjectif($e->id,$user->id, $sub->id)->userNote * $sub->ponderation )
                            @endif
                            @if(App\Objectif::getObjectif($e->id,$user->id, $sub->id))
                              @php( $mentorsousTotal += App\Objectif::getObjectif($e->id,$user->id, $sub->id)->mentorNote * $sub->ponderation )
                            @endif
                          @endif

                          <tr class="objectifRow">
                            <td style="max-width: 6%">{{ $sub->title }}</td>
                            <td class="criteres text-center slider-note {{$user->id != Auth::user()->id ? 'disabled':''}}">
                              @if(!App\Objectif::getNmoins1Note($sub->id, $e->id) || (App\Objectif::getNmoins1Note($sub->id, $e->id) == true && App\Objectif::getNmoins1Note($sub->id, $e->id)->objNplus1 == 0 ) )
                                <input type="text" class="slider userNote userObjSection-{{ $objectif->id }}"
                                       data-section="{{ $objectif->id }}" required=""
                                       name="objectifs[{{$objectif->id}}][{{$sub->id}}][userNote]" data-provide="slider"
                                       data-slider-min="0" data-slider-max="200" data-slider-step="1"
                                       data-slider-value="{{App\Objectif::getObjectif($e->id,$user->id, $sub->id) ? App\Objectif::getObjectif($e->id,$user->id, $sub->id)->userNote : '0' }}"
                                       data-slider-tooltip="always">
                                <input type="hidden" name="objectifs[{{$objectif->id}}][{{$sub->id}}][realise]"
                                       value="">
                              @else
                                <input type="hidden" name="objectifs[{{$objectif->id}}][{{$sub->id}}][userNote]"
                                       value="">
                                <table class="table table-bordered table-sub-objectif">
                                  <tr>
                                    <td>N-1</td>
                                    <td>Realisé</td>
                                    <td>Ecart</td>
                                    <td>N+1</td>
                                  </tr>
                                  <tr>
                                    <td>
                                      <span class="nMoins1-{{$sub->id}}"> {{App\Objectif::getNmoins1Note($sub->id, $e->id) ? App\Objectif::getNmoins1Note($sub->id, $e->id)->userNote : ''}} </span>
                                    </td>
                                    <td>
                                      <input type="number" min="0" max="200" class="text-center realise"
                                             name="objectifs[{{$objectif->id}}][{{$sub->id}}][realise]"
                                             data-id="{{$sub->id}}"
                                             value="{{App\Objectif::getRealise($sub->id, $e->id) ? App\Objectif::getRealise($sub->id, $e->id)->realise : ''}}">
                                    </td>
                                    <td>
                                      <input type="number" min="0" max="200" class="ecart-{{$sub->id}} text-center"
                                             name="objectifs[{{$objectif->id}}][{{$sub->id}}][ecart]" readonly=""
                                             value="{{App\Objectif::getRealise($sub->id, $e->id) ? App\Objectif::getRealise($sub->id, $e->id)->ecart : ''}}">
                                    </td>
                                    <td></td>
                                  </tr>
                                </table>
                              @endif
                            </td>
                            <td class="{{ $user->id != Auth::user()->id ? 'separate':'' }}">
                              <input type="text" name="objectifs[{{$objectif->id}}][{{$sub->id}}][userAppr]"
                                     class="form-control"
                                     value="{{App\Objectif::getObjectif($e->id,$user->id, $sub->id) ? App\Objectif::getObjectif($e->id,$user->id, $sub->id)->userAppreciation : '' }}"
                                     placeholder="Révision de l'objectif ..."
                                     title="Révision de l'objectif (optionnel) + date de la révision"
                                     data-toggle="tooltip" {{ $user->id != Auth::user()->id ? 'disabled':'' }}>
                            </td>
                            <td class="text-center">
                              <span class="ponderation">{{ $sub->ponderation }}</span>
                            </td>
                            <td class="">
                              <input type="checkbox"
                                     name="objectifs[{{$objectif->id}}][{{$sub->id}}][objNplus1]" {{isset(App\Objectif::getObjectif($e->id,$user->id, $sub->id)->objNplus1) && App\Objectif::getObjectif($e->id,$user->id, $sub->id)->objNplus1 == 1 ? 'checked':''}}>
                            </td>
                            @if($user->id != Auth::user()->id)
                              <td class="slider" style="width: 100%; line-height: 60px;">
                                <input type="text" class="slider mentorNote mentorObjSection-{{ $objectif->id }}"
                                       data-section="{{ $objectif->id }}"
                                       name="objectifs[{{$objectif->id}}][{{$sub->id}}][mentorNote]"
                                       data-provide="slider" data-slider-min="0" data-slider-max="200"
                                       data-slider-step="1"
                                       data-slider-value="{{App\Objectif::getObjectif($e->id,$user->id, $sub->id) ? App\Objectif::getObjectif($e->id,$user->id, $sub->id)->mentorNote : '0' }}"
                                       data-slider-tooltip="always">
                              </td>
                              <td>
                                <input type="text" name="objectifs[{{$objectif->id}}][{{$sub->id}}][mentorAppr]"
                                       class="form-control"
                                       value="{{App\Objectif::getObjectif($e->id,$user->id, $sub->id) ? App\Objectif::getObjectif($e->id,$user->id, $sub->id)->mentorAppreciation : '' }}"
                                       placeholder="Révision de l'objectif ..."
                                       title="Révision de l'objectif (optionnel) + date de la révision"
                                       data-toggle="tooltip">
                              </td>
                            @else
                              <input type="hidden" name="objectifs[{{$objectif->id}}][{{$sub->id}}][mentorNote]"
                                     value="">
                              <input type="hidden" name="objectifs[{{$objectif->id}}][{{$sub->id}}][mentorAppreciation]"
                                     value="">
                            @endif
                          </tr>
                        @endforeach
                        @if (!empty($objectif->extra_fields))
                          @foreach (json_decode($objectif->extra_fields) as $key => $field)
                          <tr>
                            <td>
                              {{ $field->label }}
                            </td>
                            @if($user->id == Auth::user()->id)
                              <td colspan="4">
                                @if ($field->type == 'text')
                                  <input type="text" name="user_extra_fields_data[{{$key}}]" class="form-control" value="{{ App\Objectif::getExtraFieldData($e->id, $user->id, $sub->id, $key) }}">
                                @elseif ($field->type == 'textarea')
                                  <textarea name="user_extra_fields_data[{{$key}}]" class="form-control">{{ App\Objectif::getExtraFieldData($e->id,$user->id, $sub->id, $key) }}</textarea>
                                @endif
                              </td>
                            @else
                              <td colspan="2" class="separate">
                                @if ($field->type == 'text')
                                  <input type="text" name="user_extra_fields_data[{{$key}}]" class="form-control" value="{{ App\Objectif::getExtraFieldData($e->id, $user->id, $sub->id, $key) }}">
                                @elseif ($field->type == 'textarea')
                                  <textarea name="user_extra_fields_data[{{$key}}]" class="form-control">{{ App\Objectif::getExtraFieldData($e->id,$user->id, $sub->id, $key) }}</textarea>
                                @endif
                              </td>
                              <td colspan="4">
                                @if ($field->type == 'text')
                                  <input type="text" name="mentor_extra_fields_data[{{$key}}]" class="form-control" value="{{ App\Objectif::getExtraFieldData($e->id, $user->id, $sub->id, $key, false) }}">
                                @elseif ($field->type == 'textarea')
                                  <textarea name="mentor_extra_fields_data[{{$key}}]" class="form-control">{{ App\Objectif::getExtraFieldData($e->id,$user->id, $sub->id, $key, false) }}</textarea>
                                @endif
                              </td>
                            @endif
                          </tr>
                          @endforeach
                        @endif
                        <tr>
                          @if($user->id == Auth::user()->id)
                            <td colspan="7" class="sousTotal">
                              <span>Sous-total (%)</span>
                              <span class="badge badge-success pull-right userSubTotalSection" id="userSubTotalSection-{{$objectif->id}}">{{ round($usersousTotal/$sumPonderation) }}</span>
                            </td>
                          @else
                            <td colspan="3" class="sousTotal {{ $user->id != Auth::user()->id ? 'separate':'' }}">
                              <span>Sous-total (%)</span>
                              <span
                                  class="badge badge-success pull-right userSubTotalSection" id="userSubTotalSection-{{$objectif->id}}">{{ round($usersousTotal/$sumPonderation) }}</span>
                            </td>
                            <td colspan="4" class="sousTotal">
                              <span
                                  class="badge badge-success pull-right mentorSubTotalSection" id="mentorSubTotalSection-{{$objectif->id}}">{{ round($mentorsousTotal/$sumPonderation) }}</span>
                            </td>
                          @endif
                        </tr>
                        @php( $userTotal += App\Objectif::cutNum($usersousTotal/$sumPonderation))
                        @php( $mentorTotal += App\Objectif::cutNum($mentorsousTotal/$sumPonderation))
                      @endforeach
                      <tr>
                        @if($user->id == Auth::user()->id)
                          <td colspan="7" class="btn-warning" valign="middle">
                            <span>TOTAL DE L'ÉVALUATION (%)</span>
                            <span class="btn-default pull-right badge totalNote">
                              {{ round($userTotal/$c) }}
                            </span>
                          </td>
                        @else
                          <td colspan="3" class="btn-warning {{ $user->id != Auth::user()->id ? 'separate':'' }}"
                              valign="middle">
                            <span>TOTAL DE L'ÉVALUATION (%)</span>
                            <span class="btn-default pull-right badge">
                              {{ round($userTotal/$c) }}
                            </span>
                          </td>
                          <td colspan="4" class="btn-warning" valign="middle">
                            <span class="btn-default pull-right badge totalNote">
                              {{ round($mentorTotal/$c) }}
                            </span>
                          </td>
                        @endif
                      </tr>
                    </table>
                    @if($user->id == Auth::user()->id && !App\Entretien_user::userHasSubmitedEval($e->id, $user->id))
                      <button type="submit" class="btn btn-success pull-right"><i class="fa fa-check"></i> Sauvegarder
                      </button>
                    @endif
                    @if($user->id != Auth::user()->id && !App\Entretien_user::mentorHasSubmitedEval($e->id, $user->id, $user->parent->id))
                      <button type="submit" class="btn btn-success pull-right"><i class="fa fa-check"></i> Sauvegarder
                      </button>
                    @endif

                  </form>
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
        var userType
        @if ($user->id == Auth::user()->id)
          userType = 'user'
        @else
          userType = 'mentor'
        @endif
      $('.slider').on('change', function (ev) {
          var sectionId = $(this).data('section')
          var sectionObjectifs = $('.'+ userType +'ObjSection-' + sectionId)
          var total = sectionsTotal = note = ponderation = 0
          $.each(sectionObjectifs, function (i, el) {
            note = $(el).closest('.objectifRow').find('.' + userType + 'Note').val()
            ponderation = $(el).closest('.objectifRow').find('.ponderation').text()
            sectionsTotal += parseInt(note) * parseInt(ponderation)
            console.log('sous total : ' + sectionsTotal)
          })
          sectionsTotal = sectionsTotal / 100
          sectionsTotal = Math.round(sectionsTotal)
          $('#'+ userType +'SubTotalSection-' + sectionId).text(sectionsTotal)
          var countSect = 0
          $.each($('.'+ userType +'SubTotalSection'), function (i, el) {
            countSect += 1
            total += parseInt($(el).text())
          })
          total = total / countSect
          $('.totalNote').text(Math.round(total))
        })
      })
    </script>
  @endsection


