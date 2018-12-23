
<div class="content">
    <input type="hidden" name="id" value="{{ isset($s->id) ? $s->id : null }}">
    {{ csrf_field() }}
    <div class="form-group">
        <label for="title" class="col-md-2 control-label">Titre</label>
        <div class="col-md-10">
            <input type="text" name="title" id="title" class="form-control" value="{{ isset($s->title) ? $s->title :''}}" required="">
        </div>
    </div>
    <div class="form-group">
        <label for="description" class="col-md-2 control-label">Description</label>
        <div class="col-md-10">
            <textarea name="description" id="description" class="form-control">{{ isset($s->description) ? $s->description :''}}</textarea>
        </div>
    </div>
    <div class="form-group">
        <label for="description" class="col-md-2 control-label">Type</label>
        <div class="col-md-10">
            <select name="type" class="form-control">
                <option value="0" {{ isset($s->type) && $s->type == 0 ? 'selected':''}}>Standard</option>
                <option value="1" {{ isset($s->type) && $s->type == 1 ? 'selected':''}}>Personnalisé</option>
            </select>
        </div>
    </div>
</div>