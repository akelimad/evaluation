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
      <label for="titre" class="control-label">Période d'évaluation de</label>
      <input type="text" name="start_periode" class="form-control datepicker" placeholder="Choisir une date" value="{{isset($entretien->start_periode) ? Carbon\Carbon::parse($entretien->start_periode)->format('d-m-Y') : null }}" readonly="" required="">
    </div>
    <div class="col-md-6">
      <label for="titre" class="control-label">à</label>
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
</div>

<script>
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

  })
</script>