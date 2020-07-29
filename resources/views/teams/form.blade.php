<div class="content">
  <input type="hidden" name="id" value="{{ isset($team->id) ? $team->id : null }}">
  {{ csrf_field() }}
  <div class="row">
    <div class="col-md-12">
      <label for="name" class="control-label">Nom <span class="asterisk">*</span></label>
      <input type="text" name="name" class="form-control" id="name" placeholder="eg. dev, ...." value="{{ isset($team->name) ? $team->name: '' }}" required="">
    </div>
  </div>
  <div class="row">
    <div class="col-md-12">
      <label for="description" class="control-label">Description</label>
      <textarea class="form-control" name="description" rows="3" placeholder="Description ....">{{ isset($team->description) ? $team->description: '' }}</textarea>
    </div>
  </div>
  <div class="row">
    <div class="col-md-12">
      <label for="users_id" class="control-label">Collaborateurs</label>
      <select name="usersId[]" id="users_id" class="form-control select2 w-100" multiple data-placeholder="Choisir ...">
        @foreach($collaborators as $user)
          <option value="{{ $user->id }}" {{ in_array($user->id, $teamUsers) ? 'selected':''}}> {{ $user->name." ".$user->last_name }} </option>
        @endforeach
      </select>
    </div>
  </div>
</div>

<script>
  $(document).ready(function () {
    $('.select2').select2()
  })
</script>