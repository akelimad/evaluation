
<div class="content">
    <input type="hidden" name="id" value="{{ isset($o->id) ? $o->id : null }}">
    <input type="hidden" name="e_id" value="{{ isset($e->e_id) ? $e->e_id : null }}">
    {{ csrf_field() }}
    <div class="form-group">
        <label for="titre" class="col-md-3 control-label">Titre de l'objectif</label>
        <div class="col-md-9">
            <input type="text" class="form-control" name="titre" id="titre" placeholder="" value="{{isset($o->titre) ? $o->titre :''}}" >
        </div>
    </div>
    <div class="form-group">
        <label for="description" class="col-md-3 control-label">Description</label>
        <div class="col-md-9">
            <textarea name="description" id="description" class="form-control">{{ isset($o->description) ? $o->description :''}}</textarea>
        </div>
    </div>
    <div class="form-group">
        <label for="methode" class="col-md-3 control-label">Moyen pour l'atteindre</label>
        <div class="col-md-9">
            <textarea name="methode" id="methode" class="form-control">{{ isset($o->methode) ? $o->methode :''}}</textarea>
        </div>
    </div>
    <div class="form-group">
        <label for="mesure" class="col-md-3 control-label">Mesure</label>
        <div class="col-md-9">
            <textarea name="mesure" id="mesure" class="form-control">{{ isset($o->mesure) ? $o->mesure :''}}</textarea>
        </div>
    </div>
    <div class="form-group">
        <label for="echeance" class="col-md-3 control-label">A réaliser avant la fin du trimestre</label>
        <div class="col-md-9">
            <input type="number" class="form-control" name="echeance" id="echeance" placeholder="" value="{{isset($o->echeance) ? $o->echeance :''}}" min="1" max="4" maxlength="1" pattern="\d*">
        </div>
    </div>
    <div class="form-group">
        <label for="statut" class="col-md-3 control-label">Statut</label>
        <div class="col-md-9">
            <select name="statut" id="statut" class="form-control">
                <option value="0" {{isset($o->statut) && $o->statut == "1" ? 'selected' :''}}> En attente de validation  </option>
                <option value="1" {{isset($o->statut) && $o->statut == "2" ? 'selected' :''}}> Accepté par le Mentor   </option>
                <option value="2" {{isset($o->statut) && $o->statut == "3" ? 'selected' :''}}> Objectif atteint   </option>
                <option value="3" {{isset($o->statut) && $o->statut == "4" ? 'selected' :''}}> Objectif non atteint   </option>
            </select>
        </div>
    </div>
</div>