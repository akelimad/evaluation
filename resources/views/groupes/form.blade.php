
<div class="content">
    <input type="hidden" name="id" value="{{ isset($g->id) ? $g->id : null }}">
    <input type="hidden" name="sid" value="{{ isset($sid) ? $sid : null }}">
    {{ csrf_field() }}
    <div class="form-group">
        <label for="name" class="control-label">Type de questions</label>
        <input type="text" name="name" id="name" class="form-control" value="{{isset($g->name) ? $g->name :''}}" required="">
    </div>
    <div class="form-group">
        <label for="description" class="control-label">Description</label>
        <textarea name="description" id="description" class="form-control">{{ isset($g->description) ? $g->description :''}}</textarea>
    </div>
    @php($survey = App\Survey::find($sid))
    @if(App\Evaluation::find($survey->evaluation_id)->title == 'Evaluations')
    <div class="form-group">
        <label for="notation_type" class="control-label">Notation par</label>
        <select name="notation_type" id="notation_type" class="form-control">
            <option value=""></option>
            <option value="section" {{ isset($g->notation_type) && $g->notation_type == 'section' ? 'selected':''}}>Section</option>
            <option value="item" {{ isset($g->notation_type) && $g->notation_type == 'item' ? 'selected':''}}>Item</option>
        </select>
    </div>
    @endif
</div>