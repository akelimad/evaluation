<div class="row">
  <input type="hidden" name="id" value="{{ $language->id }}">
  {{ csrf_field() }}
  <div class="col-md-12 mb-20">
    <div class="form-group">
      <label for="" class="control-label required">Nom</label>
      <input type="text" name="name" class="form-control" chm-validate="required" value="{{ $language->name }}">
    </div>
  </div>
  <div class="col-md-12 mb-20">
    <div class="form-group">
      <label for="" class="control-label required">Code ISO</label>
      <input type="text" name="iso_code" class="form-control" chm-validate="required" value="{{ $language->iso_code }}">
    </div>
  </div>
  <div class="col-md-12">
    <div class="form-group">
      <label for="" class="control-label required">Direction</label>
      <label for="ltr"><input type="radio" chm-validate="required" name="direction" value="ltr" id="ltr" {{ ($language->id > 0 && $language->direction == 'ltr') || is_null($language->id) ? 'checked':'' }}> {{ __("De gauche à droite") }}</label>
      <label for="rtl"><input type="radio" chm-validate="required" name="direction" value="rtl" id="rtl" {{ $language->id > 0 && $language->direction == 'rtl' ? 'checked':'' }}> {{ __("De droite à gauche") }}</label>
    </div>
  </div>
</div>