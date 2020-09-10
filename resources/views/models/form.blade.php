<div class="modeleForm">
  <input type="hidden" name="id" value="{{ $model->id }}">
  {{ csrf_field() }}
  <div class="row">
    <div class="col-md-12 mb-20">
      <div class="form-group">
        <label for="" class="control-label required">Réf</label>
        <input type="text" name="ref" id="ref" class="form-control" value="{{ $model->ref }}" chm-validate="required|alpha_numeric" {{ $model->id > 0 ? 'readonly':'' }}>
      </div>
    </div>

    <div class="col-md-12">
      <div class="form-group">
        <label for="" class="control-label required">Titre</label>
        <input type="text" name="title" id="title" class="form-control" value="{{ $model->title }}" chm-validate="required|alpha_numeric">
      </div>
    </div>
  </div>
</div>
