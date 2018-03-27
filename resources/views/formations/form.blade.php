
<div class="content">
    <input type="hidden" name="id" value="{{ isset($f->id) ? $f->id : null }}">
    <input type="hidden" name="e_id" value="{{ isset($e->e_id) ? $e->e_id : null }}">
    {{ csrf_field() }}
    <div class="form-group">
        <label for="date" class="control-label">Date <span class="asterisk">*</span></label>
        <input type="text" class="form-control" name="date" id="datepicker" placeholder="Ex: 01/01/2018" value="{{isset($f->date) ? Carbon\Carbon::parse($f->date)->format('d-m-Y') :''}}" readonly="" required="">
    </div>
    <div class="form-group">
        <label for="exercice" class="control-label">Exercice <span class="asterisk">*</span></label>
        <select name="exercice" id="exercice" class="form-control" required="">
            @for ($i = date('Y') ; $i <= date('Y') + 6 ; $i++)
            <option value="{{ $i }}" {{ isset($f->exercice) && $f->exercice == $i ? 'selected' :'' }}> {{ $i }} </option>
            @endfor               
        </select>
    </div>
    <div class="form-group">
        <label for="title" class="control-label">Titre de la formation demandée <span class="asterisk">*</span></label>
        <input type="text" class="form-control" name="title" id="title" placeholder="Ex: Formation IA" value="{{isset($f->title) ? $f->title :''}}" required="">
    </div> 
</div>
<script>
    $(function() {
        $('#datepicker').datepicker({
            autoclose: true,
            format: 'dd-mm-yyyy',
            language: 'fr',
            startDate: new Date()
        })
    })
</script>