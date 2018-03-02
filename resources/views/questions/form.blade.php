
<div class="content">
    <input type="hidden" name="id" value="{{ isset($g->id) ? $g->id : null }}">
    <input type="hidden" name="groupe_id" value="{{$gid}}">
    {{ csrf_field() }}
    <div class="form-group">
        <label for="titre" class="col-md-2 control-label">Titre</label>
        <div class="col-md-10">
            <input type="text" name="titre" id="titre" class="form-control" value="{{isset($g->titre) ? $g->titre :''}}">
        </div>
    </div>
    <div class="form-group">
        <label for="type" class="col-md-2 control-label">Type</label>
        <div class="col-md-10">
            <select name="type" id="type" class="form-control">
                <option value="text" > Text  </option>
                <option value="textarea" > Textarea   </option>
                <option value="checkbox" > Case à cocher  </option>
                <option value="radio"> Radio button   </option>
            </select>
        </div>
    </div>

</div>