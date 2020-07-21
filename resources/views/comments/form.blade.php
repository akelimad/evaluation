<div class="content commentsForm">
  <input type="hidden" name="id" value="{{ isset($c->id) ? $c->id : null }}">
  <input type="hidden" name="eid" value="{{ isset($e->id) ? $e->id : null }}">
  <input type="hidden" name="uid" value="{{ isset($user->id) ? $user->id : null }}">
  {{ csrf_field() }}
  <div class="row">
    <div class="col-md-12">
      <label class="control-label required">Commentaire</label>
      <textarea class="form-control" name="comment" chm-validate="required" style="height: 200px;min-height: 0">{{ $comment or '' }}</textarea>
    </div>
  </div>
</div>