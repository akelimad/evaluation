<div class="modeleForm">
  <input type="hidden" name="id" value="{{ $model->id }}">
  {{ csrf_field() }}
  <div class="row">
    <div class="col-md-12">
      <div class="form-group">
        <label for="" class="control-label required">Nom</label>
        <input type="text" name="name" id="name" class="form-control" value="{{ $model->name }}" chm-validate="required|alpha_numeric">
      </div>
    </div>
  </div>
</div>
