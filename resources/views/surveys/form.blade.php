
<div class="content">
    <input type="hidden" name="id" value="{{ isset($s->id) ? $s->id : null }}">
    {{ csrf_field() }}
    <div class="form-group">
        <label for="title" class="col-md-2 control-label">Titre</label>
        <div class="col-md-10">
            <input type="text" name="title" id="title" class="form-control" value="{{ isset($s->title) ? $s->title :''}}">
        </div>
    </div>
    <div class="form-group">
        <label for="description" class="col-md-2 control-label">Description</label>
        <div class="col-md-10">
            <textarea name="description" id="description" class="form-control">{{ isset($s->description) ? $s->description :''}}</textarea>
        </div>
    </div>
</div>