
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
    line-height: 22px;
    border-radius: 50%;
    color: #fff;
    text-align: center;
    box-shadow: 0 0 0 3px #fff;
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
</style>

<form method="POST" action="" id="entretienForm" role="form" class="allInputsFormValidation form-vertical" onsubmit="return chmEntretien.store(event)">
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
            <div class="title">Modèle</div>
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
              <label for="titre" class="control-label required">Modèle</label>
              <select name="model" id="model" class="form-control" chm-validate="required">
                <option value=""></option>
                <option value="Entretien annuel" {{ $entretien->model == 'Entretien annuel' ? 'selected':'' }}>Entretien annuel</option>
              </select>
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
              <label for="titre" class="control-label required mb-10">Choisissez le type d'évaluation</label>
              <div class="eval-items-container mt-5">
                @foreach($evaluations as $evaluation)
                  <div class="form-check">
                    <input type="checkbox" name="items[{{$evaluation->id}}][]" class="eval-item-checkbox form-check-input" id="eval-{{ $evaluation->id }}" value="0" chm-validate="required" {{ in_array($evaluation->id, $entretienEvalIds) ? 'checked':'' }}>
                    <label class="form-check-label" for="eval-{{ $evaluation->id }}">{{ $evaluation->title }}</label>
                  </div>
                  @if ($evaluation->title == "Entretien annuel")
                    <div class="evals-wrapper mb-10">
                      <select name="items[{{$evaluation->id}}][object_id][]" id="entretien" class="form-control">
                        <option value="">Veuillez sélectionner</option>
                        @foreach(App\Survey::getAll()->where('evaluation_id', 1)->get() as $s)
                          <option value="{{ $s->id }}" {{ in_array($s->id, $entretienEvalSurveyIds) ? 'selected':'' }}>{{ $s->title }}</option>
                        @endforeach
                      </select>
                      <p class=""><a href="/config/surveys" target="_blank">Ajouter un nouveau ?</a></p>
                    </div>
                  @endif
                  @if ($evaluation->title == "Carrières")
                    <div class="carreers-wrapper mb-10">
                      <select name="items[{{$evaluation->id}}][object_id][]" id="carreer" class="form-control">
                        <option value="">Veuillez sélectionner</option>
                        @foreach(App\Survey::getAll()->where('evaluation_id', 2)->get() as $s)
                          <option value="{{ $s->id }}" {{ in_array($s->id, $entretienEvalSurveyIds) ? 'selected':'' }}>{{ $s->title }}</option>
                        @endforeach
                      </select>
                      <p class=""><a href="/config/surveys" target="_blank">Ajouter un nouveau ?</a></p>
                    </div>
                  @endif
                  @if ($evaluation->title == "Objectifs")
                    <div class="objectifs-wrapper mb-10 w-100">
                      <div class="row">
                        <div class="col-md-12">
                          <select name="items[{{$evaluation->id}}][object_id][]" id="objectif" class="form-control select2" multiple>
                            @foreach(App\EntretienObjectif::getAll()->get() as $s)
                              <option value="{{ $s->id }}" {{ in_array($s->id, $entretienEvalSurveyIds) ? 'selected':'' }}>{{ $s->title }}</option>
                            @endforeach
                          </select>
                          <p class=""><a href="/config/entretienObjectif" target="_blank">Ajouter un nouveau ?</a></p>
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
            <div class="col-md-12">
              <label for="users_id" class="control-label required">Participants</label>
              <select name="usersId[]" id="users_id" class="form-control select2" multiple data-placeholder="select" style="width: 100%;" chm-validate="required">
                @foreach($users as $user)
                  <option value="{{ $user->id }}" {{ in_array($user->id, $e_users) ? 'selected':null}}> {{ $user->name." ".$user->last_name }} </option>
                @endforeach
              </select>
              <div class="form-check">
                <input type="checkbox" id="check-all"> <label for="check-all">Tout sélectionner</label>
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
              <label for="date_limit" class="control-label required">Date limite pour l'évaluation manager</label>
              <input type="text" name="date_limit" id="interview-enddate" class="form-control datepicker" placeholder="Choisir une date" value="{{isset($entretien->date_limit) ? Carbon\Carbon::parse($entretien->date_limit)->format('d-m-Y') : null }}" chm-validate="required" readonly="" required="">
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
                <td><b>Modèle</b></td><td id="model-td"></td>
              </tr>
              <tr>
                <td><b>Participants</b></td><td id="participants-td"></td>
              </tr>
              <tr>
                <td><b>Date limite pour l'auto-évaluation</b></td><td id="interview-startdate-td"></td>
              </tr>
              <tr>
                <td><b>Date limite pour l'évaluation manager</b></td><td id="interview-enddate-td"></td>
              </tr>
            </table>
          </div>
          <div class="actions">
            <button type="submit" class="btn btn-primary btn-block submit">{{ $entretien->id > 0 ? 'Mettre à jour' : 'Lancer la campagne' }}</button>
            <p class="mt-10">NB: Un email sera immédiatement envoyé aux collaborateurs sélectionnés</p>
            <button type="button" class="btn btn-default previous pull-left"><i class="fa fa-long-arrow-left"></i> Retour</button>
          </div>
        </div>
      </div>
    </div>
  </div>
</form>

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
    var countParticipants = $('#users_id :selected').length
    var interview_sartdate = $('#interview-startdate').val()
    var interview_enddate = $('#interview-enddate').val()

    $('#titre-td').html(titre)
    $('#model-td').html(model)
    $('#participants-td').html(countParticipants)
    $('#interview-startdate-td').html(interview_sartdate)
    $('#interview-enddate-td').html(interview_enddate)
  }

  function  showHideCountryErrorBlock() {
    var countChecked = $('.eval-item-checkbox:checked').length
    if (countChecked == 0) {
      chmForm.showErrorBlock('.eval-items-container', "Veuillez choisir au moins un élément")
    } else {
      $('.eval-items-container').removeClass('chm-has-error').next('.chm-error-block').remove()
    }
  }

  $(function () {
    $('.datepicker').datepicker({
      startDate: new Date(),
      autoclose: true,
      format: 'dd-mm-yyyy',
      language: 'fr',
      todayHighlight: true,
    })
    $('.select2').select2()

    $("#check-all").change(function () {
      if ($("#check-all").is(':checked')) {
        $(".select2 > option").prop("selected", "selected");
        $(".select2").trigger("change");
      } else {
        $(".select2 > option").removeAttr("selected");
        $(".select2").trigger("change");
      }
    });

    $('.eval-item-checkbox').on('change', function() {
      showHideCountryErrorBlock()

      if ($(this).next('label').text() == 'Entretien annuel' && $(this).is(':checked')) {
        $('.evals-wrapper').show()
        chmForm.setRule($('select#entretien'), 'required')
      } else if ($(this).next('label').text() == 'Entretien annuel' && !$(this).is(':checked')) {
        $('.evals-wrapper').hide()
        chmForm.setRule($('select#entretien'), 'required', false)
      }
      if ($(this).next('label').text() == 'Carrières' && $(this).is(':checked')) {
        $('.carreers-wrapper').show()
        chmForm.setRule($('select#carreer'), 'required')
      } else if ($(this).next('label').text() == 'Carrières' && !$(this).is(':checked')) {
        $('.carreers-wrapper').hide()
        chmForm.setRule($('select#carreer'), 'required', false)
      }
      if ($(this).next('label').text() == 'Objectifs' && $(this).is(':checked')) {
        $('.objectifs-wrapper').show()
        chmForm.setRule($('select#objectif'), 'required')
      } else if ($(this).next('label').text() == 'Objectifs' && !$(this).is(':checked')) {
        $('.objectifs-wrapper').hide()
        chmForm.setRule($('select#objectif'), 'required', false)
      }
    })
    @if($entretien->id > 0)
      $('.eval-item-checkbox').trigger('change')
    @endif

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
      if (stepNbr == 3) {
        showHideCountryErrorBlock()
        var countChecked = $('.eval-item-checkbox:checked').length
        if (countChecked == 0) {
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

  })
</script>