<div class="content">
  <input type="hidden" name="id" value="{{ isset($f->id) ? $f->id : null }}">
  <input type="hidden" name="e_id" value="{{ isset($e->e_id) ? $e->e_id : null }}">
  {{ csrf_field() }}
  <div class="row">
    <div class="col-md-12">
      <label for="date" class="control-label">Date <span class="asterisk">*</span></label>
      <input type="text" class="form-control" name="date" id="datepicker" placeholder="" value="{{isset($f->date) ? Carbon\Carbon::parse($f->date)->format('d-m-Y') :''}}" readonly="" required="">
    </div>
  </div>
  <div class="row">
    <div class="col-md-12">
      <label for="exercice" class="control-label">Exercice<span class="asterisk">*</span></label>
      <select name="exercice" id="exercice" class="form-control" required="">
        @for ($i = date('Y') ; $i <= date('Y') + 6 ; $i++)
          <option value="{{ $i }}" {{ isset($f->exercice) && $f->exercice == $i ? 'selected' :'' }}> {{ $i }} </option>
        @endfor
      </select>
    </div>
  </div>
  <div class="row">
    <div class="col-md-12">
      <label for="title" class="control-label">Titre de la formation demandée<span class="asterisk">*</span></label>
      <input type="text" class="form-control" name="title" id="title" placeholder="" value="{{isset($f->title) ? $f->title :''}}" required="">
    </div>
  </div>
  <div class="row">
    <div class="col-md-12">
      <label for="coll_comment" class="control-label">Description</label>
      <textarea name="coll_comment" id="coll_comment" class="form-control">{{isset($f->coll_comment) ? $f->coll_comment :''}}</textarea>
    </div>
  </div>
  @if ($user && Auth::user()->id != $user->id)
  <div class="row">
    <div class="col-md-12">
      <label for="status" class="control-label">Status</label>
      <select name="status" id="status" class="form-control">
        <option value=""></option>
        @foreach(\App\Formation::STATUS as $key => $value)
          <option value="{{ $key }}" {{ $f->status == $key ? 'selected':'' }}>{{ $value }}</option>
        @endforeach
      </select>
    </div>
  </div>
  @endif
</div>
<script>
  $(function () {
    $('#datepicker').datepicker({
      autoclose: true,
      format: 'dd-mm-yyyy',
      language: 'fr',
      startDate: new Date()
    })
  })
</script>