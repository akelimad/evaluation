<div class="content">
  <input type="hidden" name="id" value="{{ isset($o->id) ? $o->id : null }}">
  {{ csrf_field() }}
  <div class="row">
    <div class="col-md-12">
      <div class="form-group">
        <label for="title" class="control-label required">Titre</label>
        <input type="text" name="title" id="title" class="form-control" value="{{ isset($o->title) ? $o->title :''}}" chm-validate="required">
      </div>
    </div>
  </div>
  <div class="row">
    <div class="col-md-12">
      <div class="form-group">
        <label for="description" class="control-label">Description</label>
        <textarea name="description" id="description" class="form-control">{{ isset($o->description) ? $o->description :''}}</textarea>
      </div>
    </div>
  </div>
</div>