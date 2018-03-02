
<div class="content">
    <input type="hidden" name="id" value="{{ isset($g->id) ? $g->id : null }}">
    {{ csrf_field() }}
    <div class="form-group">
        <label for="name" class="col-md-2 control-label">Nom</label>
        <div class="col-md-10">
            <input type="text" name="name" id="name" class="form-control" value="{{isset($g->name) ? $g->name :''}}">
        </div>
    </div>
    <div class="form-group">
        <label for="description" class="col-md-2 control-label">Description</label>
        <div class="col-md-10">
            <textarea name="description" id="description" class="form-control">{{ isset($g->description) ? $g->description :''}}</textarea>
        </div>
    </div>
</div>