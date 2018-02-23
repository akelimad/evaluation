
<div class="content">
    <input type="hidden" name="id" value="{{ isset($c->id) ? $c->id : null }}">
    <input type="hidden" name="e_id" value="{{ isset($e->e_id) ? $e->e_id : null }}">
    {{ csrf_field() }}
    <div class="form-group">
        <label for="is_task" class="col-md-2 control-label">Tâche ?</label>
        <div class="col-md-10">
            <label class="toggle-check">
                <input type="checkbox" name="is_task" class="toggle-check-input" {{ isset($c->is_task) && $c->is_task == 1 ? 'checked' :''}}/>
                <span class="toggle-check-text"></span>
            </label>
        </div>
    </div>
    <div class="form-group">
        <label for="destinataire" class="col-md-2 control-label">Destinataire</label>
        <div class="col-md-10">
            <select name="destinataire" id="destinataire" class="form-control">
                <option value="Collaborateur">Collaborateur</option>
                <option value="RH">RH</option>
            </select>
        </div>
    </div>
    <div class="form-group">
        <label for="fichier" class="col-md-2 control-label">Echéance</label>
        <div class="col-md-10">
            <input type="text" name="echeance" id="datepicker" class="form-control" readonly="true" value="{{isset($c->echeance) ? Carbon\Carbon::parse($c->echeance)->format('d-m-Y') : null }}">
        </div>
    </div>
    <div class="form-group">
        <label for="is_done" class="col-md-2 control-label">Terminé ?</label>
        <div class="col-md-10">
            <label class="toggle-check">
                <input type="checkbox" name="is_done" class="toggle-check-input" {{ isset($c->is_done) && $c->is_done == 1 ? 'checked' :''}}/>
                <span class="toggle-check-text"></span>
            </label>
        </div>
    </div>
    <div class="form-group">
        <label for="comment" class="col-md-2 control-label">Commentaire</label>
        <div class="col-md-10">
            <textarea name="comment" id="comment" class="form-control">{{ isset($c->comment) ? $c->comment :''}}</textarea>
        </div>
    </div>

</div>
<script>
    $(function() {
        $('#datepicker').datepicker({
            autoclose: true,
            format: 'dd-mm-yyyy'
        })
    })
</script>