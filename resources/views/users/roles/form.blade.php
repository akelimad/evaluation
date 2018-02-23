
<div class="content">
    <input type="hidden" name="id" value="{{ isset($r->id) ? $r->id : null }}">
    {{ csrf_field() }}
    <div class="form-group">
        <label for="name" class="col-md-2 control-label">Nom</label>
        <div class="col-md-10">
            <input type="text" name="name" class="form-control" id="name" placeholder="eg. admin, Rh, ....">
        </div>
        <div class="clearfix"></div>
    </div>
    <div class="form-group">
        <label for="display_name" class="col-md-2 control-label">Le nom affiché</label>
        <div class="col-md-10">
            <input type="text" name="display_name" class="form-control" id="display_name" placeholder="eg. role admin">
        </div>
        <div class="clearfix"></div>
    </div>
    <div class="form-group">
        <label for="description" class="col-md-2 control-label">Description</label>
        <div class="col-md-10">
            <textarea class="form-control" name="description" rows="3" placeholder="Description ...."></textarea>
        </div>
        <div class="clearfix"></div>
    </div>
    <div class="form-group">
        <label class="control-label col-md-2">Permissions</label>
        <div class="col-md-10">
            <div class="row">
            @foreach($permissions as $p)
                <div class="col-sm-3 checkbox">
                    <label>
                        <input type="checkbox" value="{{$p->id}}" name="permissions[]" >{{$p->name}}
                    </label>
                </div>
            @endforeach
            </div>
        </div>
        <div class="clearfix"></div>
    </div>
</div>
  