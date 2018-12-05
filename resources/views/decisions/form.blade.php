
<div class="content">
    <input type="hidden" name="id" value="{{ isset($d->id) ? $d->id : null }}">
    <input type="hidden" name="e_id" value="{{ isset($e->e_id) ? $e->e_id : null }}">
    {{ csrf_field() }}
    <div class="form-group">
        <label for="titre" class="col-md-2 control-label">Titre</label>
        <div class="col-md-10">
            <textarea name="titre" id="titre" class="form-control">{{ isset($d->titre) ? $d->titre :''}}</textarea>
        </div>
    </div>
    <div class="form-group">
        <label for="moyen" class="col-md-2 control-label">Moyen pour y arriver</label>
        <div class="col-md-10">
            <textarea name="moyen" id="moyen" class="form-control">{{ isset($d->moyen) ? $d->moyen :''}}</textarea>
        </div>
    </div>
    <div class="form-group">
        <label for="delay" class="col-md-2 control-label">Délais</label>
        <div class="col-md-10">
            <select id="delay" name="delay" class="form-control">
                <option value="A determiner">A determiner</option>
                <option value="Trimestre 1">Premier trimestre</option>
                <option value="Trimestre 2">Second trimestre</option>
                <option value="Trimestre 3">Troisième trimestre</option>
                <option value="Trimestre 4">Quatrième trimestre</option>
                <option value="Annee" selected="selected">Dans l'année</option>
                <option value="Dans les 2 ans">Dans les 2 ans</option>
                <option value="Urgent">Urgent</option>
            </select>
        </div>
    </div>
    <div class="form-group">
        <label for="results" class="col-md-2 control-label">Résultats attendus</label>
        <div class="col-md-10">
            <textarea name="results" id="results" class="form-control">{{ isset($d->results) ? $d->results :''}}</textarea>
        </div>
    </div>
    
</div>