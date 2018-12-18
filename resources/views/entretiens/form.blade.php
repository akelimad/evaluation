<div class="content">
    <input type="hidden" name="type" value="annuel">
    <input type="hidden" name="id" value="{{ $entretien->id }}">
    {{ csrf_field() }}
    <div class="form-group">
        <div class="col-md-6">
            <label for="date" class="control-label">Date de l'entretien <span class="asterisk">*</span></label>
            <input type="text" name="date" class="form-control" id="datepicker" placeholder="Choisir une date"
                   value="{{isset($entretien->date) ? Carbon\Carbon::parse($entretien->date)->format('d-m-Y') : null }}"
                   readonly="" required="">
        </div>
        <div class="col-md-6">
            <label for="date_limit" class="control-label">Date de clôture <span class="asterisk">*</span></label>
            <input type="text" name="date_limit" class="form-control" id="datepicker" placeholder="Choisir une date"
                   value="{{isset($entretien->date_limit) ? Carbon\Carbon::parse($entretien->date_limit)->format('d-m-Y') : null }}"
                   readonly="" required="">
        </div>
    </div>
    <div class="form-group">
        <div class="col-md-12">
            <label for="titre" class="control-label">Titre <span class="asterisk">*</span></label>
            <input type="text" name="titre" class="form-control" id="titre" placeholder=""
                   value="{{isset($entretien->titre) ? $entretien->titre : null }}" required="">
        </div>
    </div>
    <div class="form-group">
        <div class="col-md-12">
            <label for="users_id" class="control-label">Collaborateur à evaluer <span class="asterisk">*</span></label>
            <select name="usersId[]" id="users_id" class="form-control select2" multiple
                    data-placeholder="select" style="width: 100%;" required>
                @foreach($users as $user)
                    <option value="{{ $user->id }}" {{ in_array($user->id, $e_users) ? 'selected':null}}> {{ $user->name." ".$user->last_name }} </option>
                @endforeach
            </select>
            <input type="checkbox" id="check-all"> <label for="check-all">Tout selectionner</label>
        </div>
    </div>
</div>

<script>
$(function () {
    $('#datepicker, #datepicker1, #datepicker2').datepicker({
      startDate: new Date(),
      autoclose: true,
      format: 'dd-mm-yyyy',
      language: 'fr',
      todayHighlight: true,
    })
    $('.select2').select2()

    $("#check-all").change(function(){
      if($("#check-all").is(':checked') ){
          $(".select2 > option").prop("selected","selected");
          $(".select2").trigger("change");
      }else{
          $(".select2 > option").removeAttr("selected");
          $(".select2").trigger("change");
      }
    });

})
</script>