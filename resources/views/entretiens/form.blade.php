
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
    width: 1.5em;
    height: 1.5em;
    line-height: 1.5em;
    border-radius: 100%;
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
</style>

<form method="POST" action="" id="entretienForm" role="form" class="allInputsFormValidation form-horizontal" onsubmit="return chmEntretien.store(event)">
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
          <div class="form-group">
            <div class="col-md-12">
              <label for="titre" class="control-label required">Titre</label>
              <input type="text" name="titre" class="form-control" id="titre" placeholder="" value="{{isset($entretien->titre) ? $entretien->titre : null }}" chm-validate="required">
            </div>
          </div>
          <div class="actions">
            <button type="button" class="btn btn-primary next pull-right">Continuer</button>
          </div>
        </div>

        <div class="step-content" data-step="2" style="display: none;">
          <div class="form-group">
            <div class="col-md-12">
              <label for="titre" class="control-label required">Modèle</label>
              <select name="model" id="model" class="form-control">
                <option value=""></option>
                <option value="Entretien annuel">Entretien annuel</option>
              </select>
            </div>
          </div>
          <div class="actions">
            <button type="button" class="btn btn-default previous pull-left">Retour</button>
            <button type="button" class="btn btn-primary next pull-right">Continuer</button>
          </div>
        </div>

        <div class="step-content" data-step="3" style="display: none;">
          <div class="form-group">
            <div class="col-md-12">
              <label for="titre" class="control-label required mb-10">Items</label>
              <button type="button" class="btn btn-info btn-xs ml-20 mb-0" onclick="return sellectAll(this)"><i class="fa fa-check-square"></i> Tout sélectionner</button>
              <button type="button" class="btn btn-danger btn-xs ml-10 mb-0" onclick="return desellectAll(this)"><i class="fa fa-square"></i> Tout désélectionner</button>
              <div class="eval-items-container mt-5">
                @foreach($evaluations as $evaluation)
                  <div class="form-check">
                    <input type="checkbox" name="evaluationsId[]" class="form-check-input" id="eval-{{ $evaluation->id }}" value="{{ $evaluation->id }}">
                    <label class="form-check-label" for="eval-{{ $evaluation->id }}">{{ $evaluation->title }}</label>
                  </div>
                @endforeach
              </div>
            </div>
          </div>
          <div class="actions">
            <button type="button" class="btn btn-default previous pull-left">Retour</button>
            <button type="button" class="btn btn-primary next pull-right">Continuer</button>
          </div>
        </div>

        <div class="step-content" data-step="4" style="display: none;">
          <div class="form-group">
            <div class="col-md-12">
              <label for="users_id" class="control-label required">Participants</label>
              <select name="usersId[]" id="users_id" class="form-control select2" multiple data-placeholder="select" style="width: 100%;" >
                @foreach($users as $user)
                  <option value="{{ $user->id }}" {{ in_array($user->id, $e_users) ? 'selected':null}}> {{ $user->name." ".$user->last_name }} </option>
                @endforeach
              </select>
              <input type="checkbox" id="check-all"> <label for="check-all">Tout sélectionner</label>
            </div>
          </div>
          <div class="actions">
            <button type="button" class="btn btn-default previous pull-left">Retour</button>
            <button type="button" class="btn btn-primary next pull-right">Continuer</button>
          </div>
        </div>

        <div class="step-content" data-step="5" style="display: none;">
          <div class="form-group">
            <div class="col-md-12">
              <div class="form-group">
                <div class="col-md-6">
                  <label for="date" class="control-label required">Date de l'entretien</label>
                  <input type="text" name="date" id="interview-startdate" class="form-control datepicker" placeholder="Choisir une date" value="{{isset($entretien->date) ? Carbon\Carbon::parse($entretien->date)->format('d-m-Y') : null }}" readonly="" required="">
                </div>
                <div class="col-md-6">
                  <label for="date_limit" class="control-label required">Date de clôture</label>
                  <input type="text" name="date_limit" id="interview-enddate" class="form-control datepicker" placeholder="Choisir une date" value="{{isset($entretien->date_limit) ? Carbon\Carbon::parse($entretien->date_limit)->format('d-m-Y') : null }}" readonly="" required="">
                </div>
              </div>
            </div>
          </div>
          <div class="actions">
            <button type="button" class="btn btn-default previous pull-left">Retour</button>
            <button type="button" class="btn btn-primary next pull-right">Continuer</button>
          </div>
        </div>

        <div class="step-content" data-step="6" style="display: none;">
          <div class="summary">
            <p><b>Récapitulatif</b></p>
            <table class="table table-bordered table-striped">
              <tr>
                <td class="" width="50%">Titre</td><td id="titre-td"></td>
              </tr>
              <tr>
                <td class="">Modèle</td><td id="model-td"></td>
              </tr>
              <tr>
                <td class="">Participants</td><td id="participants-td"></td>
              </tr>
              <tr>
                <td class="">Date de l'entretien</td><td id="interview-startdate-td"></td>
              </tr>
              <tr>
                <td class="">Date de clôture</td><td id="interview-enddate-td"></td>
              </tr>
            </table>
          </div>
          <div class="actions">
            <button type="submit" class="btn btn-primary btn-block submit">Lancer la compagne</button>
            <p class="mt-10">NB: un email sera immédiatement envoyé aux collaborateurs sélectionnés</p>
            <button type="button" class="btn btn-default previous pull-left">Retour</button>
          </div>
        </div>

      </div>

<div class="content">
  <input type="hidden" name="type" value="annuel">
  <input type="hidden" name="id" value="{{ $entretien->id }}">
  {{ csrf_field() }}
  <div class="form-group">
    <div class="col-md-6">
      <label for="date" class="control-label required">Date de l'entretien</label>
      <input type="text" name="date" class="form-control datepicker" placeholder="Choisir une date" value="{{isset($entretien->date) ? Carbon\Carbon::parse($entretien->date)->format('d-m-Y') : null }}" readonly="" required="">
    </div>
    <div class="col-md-6">
      <label for="date_limit" class="control-label required">Date de clôture</label>
      <input type="text" name="date_limit" class="form-control datepicker" placeholder="Choisir une date" value="{{isset($entretien->date_limit) ? Carbon\Carbon::parse($entretien->date_limit)->format('d-m-Y') : null }}" readonly="" required="">
    </div>
  </div>
  <div class="form-group">
    <div class="col-md-12">
      <label for="titre" class="control-label required">Titre</label>
      <input type="text" name="titre" class="form-control" id="titre" placeholder="" value="{{isset($entretien->titre) ? $entretien->titre : null }}" required="" chm-validate="required">
    </div>
  </div>
  <div class="form-group">
    <div class="col-md-6">
      <label for="titre" class="control-label">Période d’entretien du</label>
      <input type="text" name="start_periode" class="form-control datepicker" placeholder="Choisir une date" value="{{isset($entretien->start_periode) ? Carbon\Carbon::parse($entretien->start_periode)->format('d-m-Y') : null }}" readonly="" required="">
    </div>
    <div class="col-md-6">
      <label for="titre" class="control-label">au</label>
      <input type="text" name="end_periode" class="form-control datepicker" placeholder="Choisir une date" value="{{isset($entretien->end_periode) ? Carbon\Carbon::parse($entretien->end_periode)->format('d-m-Y') : null }}" readonly="" required="">
    </div>
  </div>
  <div class="form-group">
    <div class="col-md-12">
      <label for="users_id" class="control-label required">Collaborateur à évaluer</label>
      <select name="usersId[]" id="users_id" class="form-control select2" multiple data-placeholder="select" style="width: 100%;" required>
        @foreach($users as $user)
          <option value="{{ $user->id }}" {{ in_array($user->id, $e_users) ? 'selected':null}}> {{ $user->name." ".$user->last_name }} </option>
        @endforeach
      </select>
      <input type="checkbox" id="check-all"> <label for="check-all">Tout sélectionner</label>
    </div>
  </div>
</form>

<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bs-stepper/dist/css/bs-stepper.min.css">
<script src="https://cdn.jsdelivr.net/npm/bs-stepper/dist/js/bs-stepper.min.js"></script>

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
    $('#participants-td').html(countParticipants + " participants")
    $('#interview-startdate-td').html(interview_sartdate)
    $('#interview-enddate-td').html(interview_enddate)
  }

  $(function () {
    $('.datepicker').datepicker({
      //startDate: new Date(),
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

    $(document).on('click', 'button.next', function (e) {
      getdata()
      var $container = $(this).closest('.step-content')
      var stepNbr = $container.attr('data-step')
      var $step = $('.step[data-step="'+ stepNbr +'"]')
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