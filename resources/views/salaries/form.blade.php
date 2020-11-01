<div class="content p-xxs-10">
  <input type="hidden" name="id" value="{{ isset($s->id) ? $s->id : null }}">
  <input type="hidden" name="eid" value="{{ isset($e->id) ? $e->id : null }}">
  <input type="hidden" name="uid" value="{{ isset($user->id) ? $user->id : null }}">
  {{ csrf_field() }}
  <div class="row">
    <div class="col-md-12">
      <div class="form-group">
        <label for="brut" class="control-label required">Brut</label>
        <input type="number" name="brut" class="form-control" id="brut" min="0" value="{{ isset($s->brut) ? $s->brut : ''}}" required="">
      </div>
    </div>
  </div>
  <div class="row">
    <div class="col-md-12">
      <div class="form-group">
        <label for="prime" class="control-label">Prime</label>
        <input type="number" name="prime" class="form-control" id="prime" min="0" value="{{ isset($s->prime) ? $s->prime : ''}}">
      </div>
    </div>
  </div>
  <div class="row">
    <div class="col-md-12">
      <div class="form-group">
        <label for="comment" class="control-label">Commentaire</label>
        <textarea name="comment" class="form-control" id="comment">{{isset($s->comment) ? $s->comment : ''}}</textarea>
      </div>
    </div>
  </div>
</div>