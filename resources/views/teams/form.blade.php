<div class="content">
  <input type="hidden" name="id" value="{{ isset($team->id) ? $team->id : null }}">
  {{ csrf_field() }}
  <div class="form-group">
    <label for="name" class="control-label">Nom <span class="asterisk">*</span></label>
    <input type="text" name="name" class="form-control" id="name" placeholder="eg. dev, ...." value="{{ isset($team->name) ? $team->name: '' }}" required="">
  </div>
  <div class="form-group">
    <label for="description" class="control-label">Description</label>
      <textarea class="form-control" name="description" rows="3" placeholder="Description ....">{{ isset($team->description) ? $team->description: '' }}</textarea>
  </div>
</div>
  