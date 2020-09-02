<div class="content">
  <input type="hidden" name="id" value="{{ $skill->id > 0 ? $skill->id : null }}">
  {{ csrf_field() }}
  <div class="row">
    <div class="col-md-12">
      <div class="form-group">
        <label for="" class="control-label required">Titre</label>
        <input type="text" name="title" id="title" class="form-control" chm-validate="required" value="{{ $skill->title }}">
      </div>
    </div>
  </div>
  <div class="row">
    <div class="col-md-6">
      <label for="entretien" class="control-label required">Fonction</label>
      <select name="function_id" id="function_id" class="form-control" chm-validate="required">
        <option value=""></option>
        @foreach(App\Fonction::getAll()->get() as $function)
          <option value="{{ $function->id }}" {{ $skill->function_id == $function->id ? 'selected':'' }}>{{ $function->title }}</option>
        @endforeach
      </select>
    </div>
  </div>
  <div class="row">
    <div class="col-md-12">
      <div class="form-group">
        <label for="" class="control-label">Description</label>
        <textarea name="description" id="description" class="form-control">{{ $skill->description }}</textarea>
      </div>
    </div>
  </div>
  <div class="row">
    <div class="col-md-12">
      <h3 class="styled-title">Compétences</h3>
    </div>
    <div class="col-md-12 mb-20">
      <div class="form-group">
        <label for="" class="control-label required">Savoir</label>
        <input type="text" name="savoir" id="savoir" class="form-control tagsinput" data-role="tagsinput" chm-validate="required" value="{{ $skill->getDataAsStr('savoir') }}" placeholder="Ajouter ...">
      </div>
    </div>
    <div class="col-md-12 mb-20">
      <div class="form-group">
        <label for="" class="control-label required">Savoir-faire</label>
        <input type="text" name="savoir_faire" id="savoir_faire" class="form-control tagsinput" chm-validate="required" value="{{ $skill->getDataAsStr('savoir_faire') }}" placeholder="Ajouter ...">
      </div>
    </div>
    <div class="col-md-12">
      <div class="form-group">
        <label for="" class="control-label required">Savoir-être</label>
        <input type="text" name="savoir_etre" id="savoir_etre" class="form-control tagsinput" chm-validate="required" value="{{ $skill->getDataAsStr('savoir_etre') }}" placeholder="Ajouter ...">
      </div>
    </div>
  </div>
  <div class="row">
    <div class="col-md-12">
      <h3 class="styled-title">Mobilité professionnelle</h3>
    </div>
    <div class="col-md-12">
      <div class="form-group">
        <label for="" class="control-label required">Mobilité professionnelle</label>
        <input type="text" name="mobilite_pro" id="mobilite_pro" class="form-control tagsinput" chm-validate="required" value="{{ $skill->mobilite_pro }}" placeholder="Ajouter ...">
      </div>
    </div>
  </div>
</div>

<link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-tagsinput/0.8.0/bootstrap-tagsinput.css" rel="stylesheet">
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-tagsinput/0.8.0/bootstrap-tagsinput.min.js"></script>
<script>
  $(document).ready(function () {
    setTimeout(function () {
      $('.tagsinput').tagsinput()
    }, 500)
  })
</script>