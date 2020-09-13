<div class="content">
  <input type="hidden" name="id" value="{{ $permission->id }}">
  {{ csrf_field() }}
  <div class="row">
    <div class="col-md-12">
      <div class="form-group">
        <label for="name" class="control-label required">Nom</label>
        <input type="text" name="name" class="form-control" id="name" placeholder="" value="{{ $permission->display_name }}" chm-validate="required">
      </div>
    </div>
  </div>
  <div class="row">
    <div class="col-md-12">
      <div class="form-group">
        <label for="display_name" class="control-label">Nom d'affichage</label>
        <input type="text" name="display_name" class="form-control" id="display_name" placeholder="" value="{{ $permission->display_name }}">
      </div>
    </div>
  </div>

  <div class="row">
    <div class="col-md-12">
      <div class="form-group">
        <label for="description" class="control-label">Description</label>
        <textarea class="form-control" name="description" rows="2" placeholder="">{{ $permission->display_name }}</textarea>
      </div>
    </div>
  </div>

</div>
