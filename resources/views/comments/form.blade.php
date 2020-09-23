<div class="content commentsForm">
  <input type="hidden" name="id" value="{{ isset($comment->id) ? $comment->id : null }}">
  <input type="hidden" name="eid" value="{{ isset($e->id) ? $e->id : null }}">
  <input type="hidden" name="uid" value="{{ isset($user->id) ? $user->id : null }}">
  {{ csrf_field() }}
  <div class="row">
    @php($isMentor = $user->id != Auth::user()->id)
    <div class="col-md-12">
      <label class="control-label required">Commentaire</label>
      <textarea class="form-control" name="comment" chm-validate="required" style="height: 200px;min-height: 0">{{ $isMentor ? $comment->mentorComment : $comment->userComment }}</textarea>
    </div>
  </div>
</div>
