
<div class="content">
    <input type="hidden" name="id" value="{{ isset($r->id) ? $r->id : null }}">
    <input type="hidden" name="e_id" value="{{ isset($e->e_id) ? $e->e_id : null }}">
    {{ csrf_field() }}
    <div class="form-group">
        <label for="type" class="col-md-2 control-label">Type</label>
        <div class="col-md-10">
            <select name="type" id="type" class="form-control">
                <option value="option1"> Option 1 </option>
                <option value="option1"> Option 1 </option>
                <option value="option1"> Option 1 </option>
                <option value="option1"> Option 1 </option>
                <option value="option1"> Option 1 </option>
            </select>
        </div>
    </div>
    <div class="form-group">
        <label for="amount" class="col-md-2 control-label">Montant</label>
        <div class="col-md-10">
            <input type="number" name="amount" class="form-control" id="amount" min="0" value="{{ isset($r->amount) ? $r->amount : ''}}">
        </div>
    </div>
    <div class="form-group">
        <label for="reason" class="col-md-2 control-label">Raison</label>
        <div class="col-md-10">
            <textarea name="reason" class="form-control" id="reason">{{isset($r->reason) ? $r->reason : ''}}</textarea>
        </div>
    </div>
    
</div>