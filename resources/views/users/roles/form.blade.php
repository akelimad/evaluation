<div class="content">
  <input type="hidden" name="id" value="{{ isset($role->id) ? $role->id : null }}">
  {{ csrf_field() }}
  <div class="row">
    <div class="col-md-12">
      <div class="form-group">
        <label for="name" class="control-label required">Nom</label>
        <input type="text" name="name" class="form-control" id="name" placeholder="eg. admin, Rh, ...." value="{{ isset($role->display_name) ? $role->name: '' }}" required="" {{isset($role->id) ? 'readonly': ''}}>
      </div>
    </div>
  </div>
  <div class="row">
    <div class="col-md-12">
      <div class="form-group">
        <label for="display_name" class="control-label">Nom d'affichage</label>
        <input type="text" name="display_name" class="form-control" id="display_name" placeholder="eg. role admin" value="{{ isset($role->display_name) ? $role->display_name: '' }}">
      </div>
    </div>
  </div>

  <div class="row">
    <div class="col-md-12">
      <div class="form-group">
        <label for="description" class="control-label">Description</label>
        <textarea class="form-control" name="description" rows="2" placeholder="Description ....">{{ isset($role->display_name) ? $role->description: '' }}</textarea>
      </div>
    </div>
  </div>

</div>
  