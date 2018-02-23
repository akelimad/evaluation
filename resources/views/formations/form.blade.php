
<div class="content">
    <input type="hidden" name="id" value="{{ isset($f->id) ? $f->id : null }}">
    <input type="hidden" name="e_id" value="{{ isset($e->e_id) ? $e->e_id : null }}">
    {{ csrf_field() }}
    <div class="form-group">
        <label for="titre" class="col-md-3 control-label">Titre de la formation demandée</label>
        <div class="col-md-9">
            <input type="text" class="form-control" name="titre" id="titre" placeholder="" value="{{isset($f->titre) ? $f->titre :''}}" >
        </div>
    </div>
    <div class="form-group">
        <label for="perspective" class="col-md-3 control-label">Perspective attendue</label>
        <div class="col-md-9">
            <input type="text" class="form-control" name="perspective" id="perspective" placeholder="" value="{{isset($f->perspective) ? $f->perspective :''}}" >
        </div>
    </div>
    <div class="form-group">
        <label for="date" class="col-md-3 control-label">Date</label>
        <div class="col-md-9">
            <input type="text" class="form-control" name="date" id="datepicker" placeholder="" value="{{isset($f->date) ? Carbon\Carbon::parse($f->date)->format('d-m-Y') :''}}" >
        </div>
    </div>
    <div class="form-group">
        <label for="commentaire" class="col-md-3 control-label">Envie de transmettre</label>
        <div class="col-md-9">
            <label class="toggle-check">
                <input type="checkbox" name="transmit" class="toggle-check-input" {{ isset($f->transmit) && $f->transmit == 1 ? 'checked' :''}}/>
                <span class="toggle-check-text"></span>
            </label>
        </div>
    </div>   
</div>
<script>
    $(function() {
        $('#datepicker').datepicker({
            autoclose: true,
            format: 'dd-mm-yyyy',
            language: 'fr'
        })
    })
</script>