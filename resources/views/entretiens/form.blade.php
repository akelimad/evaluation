
<style>
  /* Steps */
  .step {
    position: relative;
    min-height: 1em;
    color: gray;
  }
  .step + .step {
    margin-top: 1.5em
  }
  .step > div:first-child {
    position: static;
    height: 0;
  }
  .step > div:not(:first-child) {
    margin-left: 1.5em;
    padding-left: 1em;
  }
  .step.active {
    color: #4285f4
  }
  .step.active .circle {
    background-color: #4285f4;
  }

  /* Circle */
  .circle {
    background: gray;
    position: relative;
    width: 22px;
    height: 22px;
    line-height: 22px !important;
    border-radius: 50%;
    color: #fff;
    text-align: center;
    box-shadow: 0 0 0 3px #fff;
  }
  .circle i {
    line-height: 22px !important;
  }

  /* Vertical Line */
  .circle:after {
    content: ' ';
    position: absolute;
    display: block;
    top: 1px;
    right: 50%;
    bottom: 1px;
    left: 50%;
    height: 100%;
    width: 1px;
    transform: scale(1, 2);
    transform-origin: 50% -100%;
    background-color: rgba(0, 0, 0, 0.25);
  }
  .step:last-child .circle:after {
    display: none
  }

  /* Stepper Titles */
  .title {
    line-height: 1.5em;
    font-weight: bold;
  }
  .caption {
    font-size: 0.8em;
  }
  .evals-wrapper, .carreers-wrapper, .objectifs-wrapper {
    display: none;
  }
  .select2-container {
    width: 100% !important;
  }
  #shedule-datetime-container {
    display: none;
  }
</style>

<form method="POST" action="" id="entretienForm" role="form" class="allInputsFormValidation form-vertical" onsubmit="return chmEntretien.store(event)" novalidate>
  <div class="content p-30">
    <input type="hidden" name="type" value="annuel">
    <input type="hidden" name="id" value="{{ $entretien->id }}">
    {{ csrf_field() }}
    <div class="row">
      <div class="col-md-3">
        <div class="step active" data-step="1">
          <div>
            <div class="circle"><i class="fa fa-check"></i></div>
          </div>
          <div>
            <div class="title">Nom</div>
          </div>
        </div>
        <div class="step" data-step="2">
          <div>
            <div class="circle"><i class="fa fa-circle"></i></div>
          </div>
          <div>
            <div class="title">Type</div>
          </div>
        </div>
        <div class="step" data-step="3">
          <div>
            <div class="circle"><i class="fa fa-circle"></i></div>
          </div>
          <div>
            <div class="title">Evaluations</div>
          </div>
        </div>
        <div class="step" data-step="4">
          <div>
            <div class="circle"><i class="fa fa-circle"></i></div>
          </div>
          <div>
            <div class="title">Participants</div>
          </div>
        </div>
        <div class="step" data-step="5">
          <div>
            <div class="circle"><i class="fa fa-circle"></i></div>
          </div>
          <div>
            <div class="title">Dates</div>
          </div>
        </div>
        <div class="step" data-step="6">
          <div>
            <div class="circle"><i class="fa fa-circle"></i></div>
          </div>
          <div>
            <div class="title">Récapitulatif</div>
          </div>
        </div>
      </div>
      <div class="col-md-9">
        <div class="step-content" data-step="1">
          <div class="row">
            <div class="col-md-12">
              <label for="titre" class="control-label required">Titre</label>
              <input type="text" name="titre" class="form-control" id="titre" placeholder="" value="{{isset($entretien->titre) ? $entretien->titre : null }}" chm-validate="required">
            </div>
          </div>
          <div class="actions">
            <button type="button" class="btn btn-primary next pull-right">Continuer <i class="fa fa-long-arrow-right"></i></button>
          </div>
        </div>
        <div class="step-content" data-step="2" style="display: none;">
          <div class="row">
            <div class="col-md-12">
              <label for="titre" class="control-label required">Type</label>
              <select name="model" id="model" class="form-control" chm-validate="required">
                <option value=""></option>
                @foreach(\App\Modele::all() as $model)
                  <option value="{{ $model->id }}" data-ref="{{ $model->ref }}" {{ $entretien->model_id == $model->id ? 'selected':'' }}>{{ $model->title }}</option>
                @endforeach
              </select>
              <div class="feedback-360-options mt-15">
                <div class="form-check mb-15">
                  <input type="checkbox" class="feedback360-options-cb" id="anonym" name="options[]" value="anonym" chm-validate="required" {{ in_array('anonym', $entretien->getOptions()) ? 'checked':'' }}> <label for="anonym" class="font-14 mb-0"><b>Réponses anonymes</b></label>
                  <span class="text-muted font-12 d-block">Si cette option est activée, le nom des collègues n'est pas affiché sur l'écran des résultats</span>
                </div>

                <div class="form-check">
                  <input type="checkbox" class="feedback360-options-cb" id="block" name="options[]" value="hide_results" chm-validate="required" {{ in_array('hide_results', $entretien->getOptions()) ? 'checked':'' }}> <label for="block" class="font-14 mb-0"><b>Bloquer le partage</b></label>
                  <span class="text-muted font-12 d-block">Si cette option est activée, il ne sera pas possible aux évalués de voir les résultats. seuls le responsable de l'évaluation et les admins auront accès aux résultats</span>
                </div>
              </div>
            </div>
          </div>
          <div class="actions">
            <button type="button" class="btn btn-default previous pull-left"><i class="fa fa-long-arrow-left"></i> Retour</button>
            <button type="button" class="btn btn-primary next pull-right">Continuer <i class="fa fa-long-arrow-right"></i></button>
          </div>
        </div>
        <div class="step-content" data-step="3" style="display: none;">
          <div class="row">
            <div class="col-md-12">
              <label for="titre" class="control-label required mb-10">Choisissez le contenu d'évaluation</label>
              <div class="eval-items-container mt-5">
                @foreach($evaluations as $evaluation)
                  @php($itemsId = \App\Entretien_evaluation::getItemsId($entretien->id, $evaluation->id))
                  <div class="form-check">
                    <input type="checkbox" name="items[{{$evaluation->id}}][]" class="eval-item-checkbox form-check-input" id="eval-{{ $evaluation->id }}" value="0" chm-validate="required" {{ in_array($evaluation->id, $entretienEvalIds) ? 'checked':'' }}>
                    <label class="form-check-label" id="eval-{{$evaluation->id}}-label" for="eval-{{ $evaluation->id }}">{{ $evaluation->title }}</label>
                  </div>

                  @if ($evaluation->title == "Evaluation annuelle")
                    <div class="evals-wrapper mb-10">
                      <select name="items[{{$evaluation->id}}][object_id][]" id="entretien" class="form-control">
                        <option value="">Veuillez sélectionner</option>
                        @foreach(App\Survey::getAll()->where('evaluation_id', 1)->orWhere('evaluation_id', 0)->get() as $s)
                          <option value="{{ $s->id }}" data-model-ref="{{ $s->getModele() ? $s->getModele()->ref : '---' }}" {{ in_array($s->id, $itemsId) ? 'selected':'' }}>{{ $s->title }}</option>
                        @endforeach
                      </select>
                    </div>
                  @endif

                  @if ($evaluation->title == "Carrières")
                    <div class="carreers-wrapper mb-10">
                      <select name="items[{{$evaluation->id}}][object_id][]" id="carreer" class="form-control">
                        <option value="">Veuillez sélectionner</option>
                        @foreach(App\Survey::getAll()->where('evaluation_id', 2)->orWhere('evaluation_id', 0)->get() as $s)
                          <option value="{{ $s->id }}" data-model-ref="{{ $s->getModele() ? $s->getModele()->ref : '---' }}" {{ in_array($s->id, $itemsId) ? 'selected':'' }}>{{ $s->title }}</option>
                        @endforeach
                      </select>
                    </div>
                  @endif

                  @if ($evaluation->title == "Objectifs")
                    <div class="objectifs-wrapper mb-10 w-100">
                      <div class="row">
                        <div class="col-md-12">
                          <select name="items[{{$evaluation->id}}][object_id][]" id="objectif" class="form-control select2" multiple>
                            @foreach(App\EntretienObjectif::getAll()->get() as $s)
                              <option value="{{ $s->id }}" {{ in_array($s->id, $itemsId) ? 'selected':'' }}>{{ $s->title }}</option>
                            @endforeach
                          </select>
                        </div>
                      </div>
                    </div>
                  @endif
                @endforeach
              </div>
            </div>
          </div>
          <div class="actions">
            <button type="button" class="btn btn-default previous pull-left"><i class="fa fa-long-arrow-left"></i> Retour</button>
            <button type="button" class="btn btn-primary next pull-right">Continuer <i class="fa fa-long-arrow-right"></i></button>
          </div>
        </div>
        <div class="step-content" data-step="4" style="display: none;">
          <div class="row">
            <div class="col-md-12 mb-15">
              <div class="mb-20" id="fb360-user-to-evaluate">
                <label for="fb30_userid_to_evaluate" class="control-label required">{{ __("Choisissez l'évalué") }}</label>
                <select name="fb30_userid_to_evaluate" id="fb30_userid_to_evaluate" class="form-control select2" data-placeholder="select" style="width: 100%;" chm-validate="required">
                  @foreach($users as $user)
                    <option title="{{ $user->email }}" value="{{ $user->id }}" {{ in_array($user->id, $entretien->users->pluck('id')->toArray()) ? 'selected':''}}>{{ $user->fullname() }}</option>
                  @endforeach
                </select>
              </div>
              <div class="entretien-annuel-users">
                <label for="" class="control-label required" style="position: relative">{{ __("Choisissez les évaluateurs") }}</label>
                <div class="separator mb-10">
                  <label for="" class="">Sélectionnez les équipes :</label>
                  <select name="teamsIdToEvaluate[]" id="teams_id_to_evaluate" class="form-control select2" multiple data-placeholder="select" style="width: 100%;">
                    @foreach(\App\Team::getAll()->get() as $team)
                      <option data-id="{{ $team->id }}" id="team-{{ $team->id }}" data-usersid="{{ json_encode($team->users->pluck('id')) }}" value="{{ $team->id }}">{{ $team->name . " (". count($team->users) .")" }}</option>
                    @endforeach
                  </select>
                </div>
                <div class="separator">
                  <label for="" class="mb-0">{{ __("Ou des utilisateurs :") }} <input type="checkbox" id="selectAllUsers"> <label for="selectAllUsers" class="d-inline-block">Tout sélectionner</label></label>
                  <select name="usersIdToEvaluates[]" id="users_id_to_evaluate" class="form-control select2" multiple data-placeholder="select" style="width: 100%;" chm-validate="required">
                    @foreach($users as $user)
                      <option title="{{ $user->email }}" value="{{ $user->id }}" {{ in_array($user->id, $e_users) ? 'selected':null}}>{{ $user->fullname() }}</option>
                    @endforeach
                  </select>
                </div>
              </div>
            </div>
          </div>
          <div class="actions">
            <button type="button" class="btn btn-default previous pull-left"><i class="fa fa-long-arrow-left"></i> Retour</button>
            <button type="button" class="btn btn-primary next pull-right">Continuer <i class="fa fa-long-arrow-right"></i></button>
          </div>
        </div>
        <div class="step-content" data-step="5" style="display: none;">
          <div class="row">
            <div class="col-md-12">
              <label for="date" class="control-label required">Date limite pour l'auto-évaluation</label>
              <input type="text" name="date" id="interview-startdate" class="form-control datepicker" placeholder="Choisir une date" value="{{isset($entretien->date) ? Carbon\Carbon::parse($entretien->date)->format('d-m-Y') : null }}" chm-validate="required" readonly="" required="">
            </div>
          </div>
          <div class="row">
            <div class="col-md-12">
              <label for="date_limit" id="date_limit_label" class="control-label required">Date limite pour l'évaluation manager</label>
              <input type="text" name="date_limit" id="interview-enddate" class="form-control datepicker" placeholder="Choisir une date" value="{{isset($entretien->date_limit) ? Carbon\Carbon::parse($entretien->date_limit)->format('d-m-Y') : null }}" chm-validate="required" readonly="" required="">
            </div>
          </div>
          <div class="row">
            <div class="col-md-6">
              @php($campaign = \App\Campaign::get($entretien->id))
              <label for="" class="control-label required">Date de lancement</label>
              <select name="shedule_type" id="shedule_type" class="form-control" chm-validate="required">
                <option value=""></option>
                <option value="now" {{ ($campaign && $campaign->shedule_type == 'now') || !$campaign && $entretien->id > 0 ? 'selected':'' }}>Immédiat</option>
                <option value="sheduled" {{ $campaign && $campaign->shedule_type == 'sheduled' ? 'selected':'' }}>Programmé</option>
              </select>
            </div>
            <div class="col-md-6" id="shedule-datetime-container">
              <div class="form-group">
                <label for="" class="control-label">Choisissez la date et l'heure</label>
                <input type="datetime-local" name="sheduled_at" id="sheduled_at" min="{{ date('Y-m-d') }}T{{ date('H:i') }}" class="form-control" value="{{ $campaign ? date('Y-m-d', strtotime($campaign->sheduled_at)).'T'.date('H:i', strtotime($campaign->sheduled_at)) : '' }}">
              </div>
            </div>
          </div>
          <div class="row">
            <div class="col-md-6">
              <div class="form-group">
                <label for="" class="control-label required">{{ __("Fréquence des relances auto") }}</label>
                <select name="freq_reminder" id="freq_reminder" class="form-control" chm-validate="required" >
                  <option value=""></option>
                  <option value="every_day" {{ $entretien->freq_reminder == 'every_day' ? 'selected':'' }}>{{ __("Chaque jour") }}</option>
                  <option value="every_2days" {{ $entretien->freq_reminder == 'every_2days' ? 'selected':'' }}>{{ __("Chaque 2 jours") }}</option>
                  <option value="every_3days" {{ $entretien->freq_reminder == 'every_3days' ? 'selected':'' }}>{{ __("Chaque 3 jours") }}</option>
                  <option value="every_week" {{ $entretien->freq_reminder == 'every_week' ? 'selected':'' }}>{{ __("Chaque semaine") }}</option>
                  <option value="every_2weeks" {{ $entretien->freq_reminder == 'every_2weeks' ? 'selected':'' }}>{{ __("Chaque 2 semaines") }}</option>
                  <option value="every_month" {{ $entretien->freq_reminder == 'every_month' ? 'selected':'' }}>{{ __("Chaque mois") }}</option>
                </select>
              </div>
            </div>
          </div>
          <div class="actions">
            <button type="button" class="btn btn-default previous pull-left"><i class="fa fa-long-arrow-left"></i> Retour</button>
            <button type="button" class="btn btn-primary next pull-right">Continuer <i class="fa fa-long-arrow-right"></i></button>
          </div>
        </div>
        <div class="step-content" data-step="6" style="display: none;">
          <div class="summary">
            <p><b>Récapitulatif</b></p>
            <table class="table table-bordered table-striped">
              <tr>
                <td width="50%"><b>Titre</b></td><td id="titre-td"></td>
              </tr>
              <tr>
                <td><b>Type</b></td><td id="model-td"></td>
              </tr>
              <tr>
                <td><b>Participants</b></td><td id="participants-td"></td>
              </tr>
              <tr>
                <td><b>Date limite pour l'auto-évaluation</b></td><td id="interview-startdate-td"></td>
              </tr>
              <tr>
                <td id="date_limit_td"></td><td id="interview-enddate-td"></td>
              </tr>
            </table>
          </div>
          <div class="actions">
            <button type="submit" class="btn btn-primary btn-block submit">{{ $entretien->id > 0 ? 'Mettre à jour' : 'Lancer la campagne' }}</button>
            <p class="mt-10">NB: Un email sera immédiatement envoyé aux collaborateurs sélectionnés, et leurs managers</p>
            <button type="button" class="btn btn-default previous pull-left"><i class="fa fa-long-arrow-left"></i> Retour</button>
          </div>
        </div>
      </div>
    </div>
  </div>
</form>

<link rel="stylesheet" href="{{ asset('css/bootstrap-datepicker.min.css') }}">
<script src="{{asset('js/moment.min.js')}}"></script>
<script src="{{asset('js/bootstrap-datepicker.min.js')}}"></script>
<script src="{{asset('js/bootstrap-datepicker.fr.min.js')}}"></script>

<script>

  var sellectAll = function (target) {
    $('.form-check').find('[type="checkbox"]').prop('checked', true)
  }
  var desellectAll = function (target) {
    $('.form-check').find('[type="checkbox"]').prop('checked', false)
  }

  function getdata () {
    var titre = $('#titre').val()
    var model = $('#model :selected').text()
    var countParticipants = $('#users_id_to_evaluate :selected').length
    var interview_sartdate = $('#interview-startdate').val()
    var interview_enddate = $('#interview-enddate').val()

    $('#titre-td').html(titre)
    $('#model-td').html(model)
    $('#participants-td').html(countParticipants)
    $('#interview-startdate-td').html(interview_sartdate)
    $('#interview-enddate-td').html(interview_enddate)
  }

  function  showHideEvalsErrorBlock() {
    var countChecked = $('.eval-item-checkbox:checked').length
    if (countChecked == 0) {
      chmForm.showErrorBlock('.eval-items-container', "Veuillez choisir au moins un élément")
    } else {
      $('.eval-items-container').removeClass('chm-has-error').next('.chm-error-block').remove()
    }
  }

  function showHideFeedback360ErrorBlock () {
    var countChecked = $('.feedback360-options-cb:checked').length
    if (countChecked == 0) {
      chmForm.showErrorBlock('.feedback-360-options', "Veuillez choisir au moins un élément")
    } else {
      $('.feedback-360-options').removeClass('chm-has-error').next('.chm-error-block').remove()
    }
  }

  function ArrayDiff (a1, a2) {
    var a = [], diff = [];
    for (var i = 0; i < a1.length; i++) {
      a[a1[i]] = true;
    }
    for (var i = 0; i < a2.length; i++) {
      if (a[a2[i]]) {
        delete a[a2[i]];
      } else {
        a[a2[i]] = true;
      }
    }
    for (var k in a) {
      diff.push(k);
    }
    return diff;
  }

  var usersId = []
  $(document).ready(function () {
    $('.datepicker').datepicker({
      startDate: new Date(),
      autoclose: true,
      format: 'dd-mm-yyyy',
      language: 'fr',
      todayHighlight: true,
    })
    $('.select2').select2()

    $("#selectAllUsers").on('change', function(){
      if($(this).is(':checked')){
        $('#users_id_to_evaluate').select2('destroy').find('option').prop('selected', 'selected').end().select2();
      }else{
        $('#users_id_to_evaluate').select2('destroy').find('option').prop('selected', false).end().select2();
      }
    });

    $('.eval-item-checkbox').on('change', function() {
      showHideEvalsErrorBlock()

      var labelText = $(this).next('label').text()

      if ($.inArray(labelText, ['Evaluation annuelle', 'Feedback 360']) !== -1 && $(this).is(':checked')) {
        $('.evals-wrapper').show()
        chmForm.setRule($('select#entretien'), 'required')
      } else if ($.inArray(labelText, ['Evaluation annuelle', 'Feedback 360']) !== -1 && !$(this).is(':checked')) {
        $('.evals-wrapper').hide()
        chmForm.setRule($('select#entretien'), 'required', false)
      }
      if (labelText == 'Carrières' && $(this).is(':checked')) {
        $('.carreers-wrapper').show()
        chmForm.setRule($('select#carreer'), 'required')
      } else if (labelText == 'Carrières' && !$(this).is(':checked')) {
        $('.carreers-wrapper').hide()
        chmForm.setRule($('select#carreer'), 'required', false)
      }
      if (labelText == 'Objectifs' && $(this).is(':checked')) {
        $('.objectifs-wrapper').show()
        chmForm.setRule($('select#objectif'), 'required')
      } else if (labelText == 'Objectifs' && !$(this).is(':checked')) {
        $('.objectifs-wrapper').hide()
        chmForm.setRule($('select#objectif'), 'required', false)
      }
    })
    @if($entretien->id > 0)
      $('.eval-item-checkbox').trigger('change')
    @endif

    $('.feedback360-options-cb').on('change', function() {
      showHideFeedback360ErrorBlock()
    })

    $(document).on('click', 'button.next', function (e) {
      var $container = $(this).closest('.step-content')
      var stepNbr = $container.attr('data-step')
      var $step = $('.step[data-step="'+ stepNbr +'"]')

      var isValid = true
      $(this).closest('.step-content').find('.form-control').each(function () {
        if (!chmForm.isValid(this)) {
          isValid = false;
        }
      })
      if (stepNbr == 2) {
        if ($('select#model option:selected').data('ref') == 'FB360') {
          showHideFeedback360ErrorBlock()
        }
        $('.form-check-input').each(function (index, element) {
          var itemLabel = $(element).closest('.form-check').find('label').text()
          var labelText = ""
          if ($('select#model').find(':selected').data('ref') == "FB360") {
            labelText = "Date limite pour les évaluateurs"
            if (itemLabel != 'Feedback 360') {
              $(element).closest('.form-check').hide()
            } else {
              $(element).closest('.form-check').show()
            }
            $('#users_id').select2({
              maximumSelectionLength: 1
            })
          } else {
            labelText = "Date limite pour l'évaluation manager"
            $(element).closest('.form-check').show()
            $('#users_id').select2({
              maximumSelectionLength: -1 // no limit
            })
          }
          $('#date_limit_label').text(labelText)
          $('#date_limit_td').html('<b>'+labelText+'</b>')
        })
        var countChecked = $('.feedback360-options-cb:checked').length
        if (countChecked == 0 && $('select#model').val() == "Feedback 360") {
          isValid = false;
        }
      }
      if (stepNbr == 3) {
        showHideEvalsErrorBlock()
        var countChecked = $('.eval-item-checkbox:checked').length
        if (countChecked == 0) {
          isValid = false;
        }
        // check if selected surveyid in evaluation != carreer
        if ($('#entretien').val() != '' && $('#entretien').val() == $('#carreer').val()) {
          chmForm.showErrorBlock('#carreer', "Vous ne pouvez séléctionner le même questionnaire pour 2 sections")
          isValid = false;
        }
      }

      if (stepNbr == 5) {
        var start_date = $("#interview-startdate").datepicker('getDate');
        var end_date = $("#interview-enddate").datepicker('getDate');
        if (end_date <= start_date) {
          chmForm.showErrorBlock($("#interview-enddate"), "La date limite de l'évaluateur doit être une date supérieure à la date limite de l'évalué")
          isValid = false;
        }
      }
      if (!isValid) {
        return false
      }

      getdata()

      $step.removeClass('active').next().addClass('active')
      if (!$step.next().find('.circle i').hasClass('fa-check')) {
        $step.next().find('.circle i').toggleClass('fa-check fa-circle')
      }
      $container.hide().next('.step-content').show()
    })

    $(document).on('click','button.previous', function (e) {
      var $container = $(this).closest('.step-content')
      var stepNbr = $container.attr('data-step')
      var $step = $('.step[data-step="'+ stepNbr +'"]')
      $step.removeClass('active').prev().addClass('active')
      $container.hide().prev('.step-content').show()
    })

    $('select#model').on('change', function () {
      $('select#entretien option, select#carreer option').not(':first').hide()
      var val = $(this).val()
      var ref = $(this).find(':selected').data('ref')
      if (ref == "FB360") {
        $('.feedback').show()
        $('.entretien-annuel-users > label').text('{{ __("Choisissez les évaluateurs") }}')
        $('#eval-1-label').html('Feedback 360')
        $('.feedback-360-options').show()
        $('#fb360-user-to-evaluate').show()
        $('select#entretien option, select#carreer option').filter(function () {
          return $(this).data('model-ref') == "FB360"
        }).show()
        $('.evals-wrapper').hide()
        $('.carreers-wrapper').hide()
        $('.objectifs-wrapper').hide()
      } else {
        $('.feedback').hide()
        $('.entretien-annuel-users > label').text('{{ __("Choisissez les évalués") }}')
        $('#eval-1-label').html('Evaluation annuelle')
        $('.feedback-360-options').hide()
        $('.feedback-360-options').find(':checkbox').prop('checked', false)
        $('#fb360-user-to-evaluate').hide()
        $('select#entretien option, select#carreer option').filter(function () {
          return $(this).data('model-ref') == "ENT"
        }).show()
      }
    })
    $('select#model').trigger('change')

    $('#teams_id_to_evaluate').on('change', function (e) {
      var unselectedIds = $(this).data('users-id');
      var selectedIds = $('select#participants_id').val()
      var id = $(this).data('id')
      if ($(this).is(':checked')) {
        $.ajax({
          url : 'config/teams/'+ id +'/get-users',
          type: 'GET',
          dataType : 'json',
          data: {id: id},
          success : function(response) {
            response.forEach(function (user) {
              usersId.push(user.id)
              var newOption = new Option(user.name, user.id, false, false);
              $('select#participants_id').append(newOption).trigger('change');
            })
            $('select#participants_id').val(usersId)
          }
        });
      } else {
        $.each(unselectedIds, function (key, id) {
          if ($.inArray(""+id+"", selectedIds) !== -1) {
            $("select#participants_id option[value='"+id+"']").remove();
          }
        })
      }
    })
    $('select#teams_id_to_evaluate').on('select2:selecting', function (event) {
      var selectedTeamId = event.params.args.data.id
      var alreadySelected = $('#users_id_to_evaluate').val()
      var teamUsersId = $('select#teams_id_to_evaluate option[value="'+ selectedTeamId +'"]').data('usersid')
      var newAllUsersId = alreadySelected.concat(teamUsersId)
      $('select#users_id_to_evaluate').val(newAllUsersId).trigger('change')
    })

    $('select#teams_id_to_evaluate').on('select2:unselecting', function(event) {
      var teamIdToRemove = event.params.args.data.id
      var allUsersId = $('#users_id_to_evaluate').val()
      var usersIdToRemove = $('select#teams_id_to_evaluate option[value="'+ teamIdToRemove +'"]').data('usersid')
      var diff = ArrayDiff(usersIdToRemove, allUsersId)
      $('select#users_id_to_evaluate').val(diff).trigger('change')
    })

    $('select#shedule_type').on('change', function () {
      var val = $(this).val()
      if (val == 'sheduled') {
        $('#shedule-datetime-container').show()
        chmForm.setRule($('#sheduled_at'), 'required')
      } else {
        $('#shedule-datetime-container').hide()
        chmForm.setRule($('#sheduled_at'), 'required', false)
      }
    })
    $('select#shedule_type').trigger('change')

  })
</script>