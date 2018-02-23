
<div class="content">
    <input type="hidden" name="type" value="annuel">
    <input type="hidden" name="id" value="{{isset($e->id) ? $e->id : null }}">
    {{ csrf_field() }}
    <div class="form-group">
        <div class="col-md-6">
            <label for="date" class="control-label">Date de l'entretien</label>
            <input type="text" name="date" class="form-control" id="datepicker" placeholder="" value="{{isset($e->date) ? Carbon\Carbon::parse($e->date)->format('d-m-Y') : null }}">
        </div>
        <div class="col-md-6">
            <label for="date_limit" class="control-label">Date limite</label>
            <input type="text" name="date_limit" class="form-control" id="datepicker" placeholder="" value="{{isset($e->date_limit) ? Carbon\Carbon::parse($e->date_limit)->format('d-m-Y') : null }}">
        </div>
    </div>
    <div class="form-group">
        <div class="col-md-12">
            <label for="titre" class="control-label">Titre</label>
            <input type="text" name="titre" class="form-control" id="titre" placeholder="" value="{{isset($e->titre) ? $e->titre : null }}">
        </div>
    </div>
    <div class="form-group">
        @if(empty($e->id))
        <div class="col-md-12">
            <label for="user_id" class="control-label">Collaborateur à evaluer</label>
            <select name="usersId[]" id="user_id" class="form-control select2" multiple="multiple" data-placeholder="select " style="width: 100%;">
                @foreach($users as $user)
                    <option value="{{ $user->id }}" {{isset($e->user_id) && $e->user_id == $user->id ? 'selected' : null }} > {{ $user->email }} </option>
                @endforeach
            </select>
        </div>
        @endif
    </div>
</div>
  
<script>
    $(function() {
        $('#datepicker, #datepicker1, #datepicker2').datepicker({
            autoclose: true,
            format: 'dd-mm-yyyy'
        })
    })
    $('.select2').select2()
</script>