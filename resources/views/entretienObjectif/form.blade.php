
<div class="content">
    <input type="hidden" name="id" value="{{ isset($o->id) ? $o->id : null }}">
    {{ csrf_field() }}
    <div class="form-group">
        <label for="title" class="col-md-2 control-label">Titre</label>
        <div class="col-md-10">
            <input type="text" name="title" id="title" class="form-control" value="{{ isset($o->title) ? $o->title :''}}" required="">
        </div>
    </div>
    <div class="form-group">
        <label for="description" class="col-md-2 control-label">Description</label>
        <div class="col-md-10">
            <textarea name="description" id="description" class="form-control">{{ isset($o->description) ? $o->description :''}}</textarea>
        </div>
    </div>
</div>