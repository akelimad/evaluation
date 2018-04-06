
<div class="content">
    <input type="hidden" name="id" value="{{ isset($g->id) ? $g->id : null }}">
    <input type="hidden" name="sid" value="{{ isset($sid) ? $sid : null }}">
    {{ csrf_field() }}
    <div class="form-group">
        <label for="name" class="control-label">Nom</label>
        <input type="text" name="name" id="name" class="form-control" value="{{isset($g->name) ? $g->name :''}}" required="">
    </div>
    <div class="form-group">
        <label for="description" class="control-label">Description</label>
        <textarea name="description" id="description" class="form-control">{{ isset($g->description) ? $g->description :''}}</textarea>
    </div>
</div>