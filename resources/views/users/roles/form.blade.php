
<div class="content">
    <input type="hidden" name="id" value="{{ isset($role->id) ? $role->id : null }}">
    {{ csrf_field() }}
    <div class="form-group">
        <label for="name" class="col-md-2 control-label">Nom <span class="asterisk">*</span></label>
        <div class="col-md-10">
            <input type="text" name="name" class="form-control" id="name" placeholder="eg. admin, Rh, ...." value="{{ isset($role->display_name) ? $role->name: '' }}" required="" {{isset($role->id) ? 'readonly': ''}}>
        </div>
        <div class="clearfix"></div>
    </div>
    <div class="form-group">
        <label for="display_name" class="col-md-2 control-label">Le nom affiché</label>
        <div class="col-md-10">
            <input type="text" name="display_name" class="form-control" id="display_name" placeholder="eg. role admin" value="{{ isset($role->display_name) ? $role->display_name: '' }}">
        </div>
        <div class="clearfix"></div>
    </div>
    <div class="form-group">
        <label for="description" class="col-md-2 control-label">Description</label>
        <div class="col-md-10">
            <textarea class="form-control" name="description" rows="3" placeholder="Description ....">{{ isset($role->display_name) ? $role->description: '' }}</textarea>
        </div>
        <div class="clearfix"></div>
    </div>
</div>
  