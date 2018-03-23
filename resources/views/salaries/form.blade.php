
<div class="content">
    <input type="hidden" name="id" value="{{ isset($s->id) ? $s->id : null }}">
    <input type="hidden" name="eid" value="{{ isset($e->id) ? $e->id : null }}">
    <input type="hidden" name="uid" value="{{ isset($user->id) ? $user->id : null }}">
    {{ csrf_field() }}
    <div class="form-group">
        <label for="brut" class="control-label">Brut <span class="asterisk">*</span></label>
        <input type="number" name="brut" class="form-control" id="brut" min="0" value="{{ isset($s->brut) ? $s->brut : ''}}" required="">
    </div>
    <div class="form-group">
        <label for="prime" class="control-label">Prime</label>
        <input type="number" name="prime" class="form-control" id="prime" min="0" value="{{ isset($s->prime) ? $s->prime : ''}}">
    </div>
    <div class="form-group">
        <label for="comment" class="control-label">Commentaire</label>
        <textarea name="comment" class="form-control" id="comment">{{isset($s->comment) ? $s->comment : ''}}</textarea>
    </div>
</div>