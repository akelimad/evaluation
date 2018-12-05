<div class="content">
    <input type="hidden" name="id" value="{{ isset($action) ? $action->id : null }}">
    {{ csrf_field() }}
    <div class="form-group">
        <label for="slug" class="control-label">slug <span class="asterisk">*</span></label>
        <input type="text" class="form-control" name="slug" placeholder="" required="" value="{{ isset($action) ? $action->slug : '' }}" {{ isset($action) ? 'readonly':'' }}>
    </div>
    <div class="form-group">
        <label for="name" class="control-label">Nom <span class="asterisk">*</span></label>
        <input type="text" class="form-control" name="name" placeholder="" required="" value="{{ isset($action) ? $action->name : ''}}">
    </div>
    <div class="form-group">
        <label for="type" class="control-label">Type <span class="asterisk">*</span></label>
        <select name="type" id="type" class="form-control">
            <option value="0" {{ isset($action) && $action->type == 0 ? 'selected' : ''}}>Manuel</option>
            <option value="1" {{ isset($action) && $action->type == 1 ? 'selected' : ''}}>Automatique</option>
        </select>
    </div>
</div>