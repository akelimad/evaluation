
<div class="content">
    <input type="hidden" name="id" value="{{ isset($s->id) ? $s->id : null }}">
    <input type="hidden" name="e_id" value="{{ isset($e->e_id) ? $e->e_id : null }}">
    {{ csrf_field() }}
    <div class="form-group">
        <label for="domaine" class="col-md-2 control-label">Domaine de compétence</label>
        <div class="col-md-10">
            <input type="text" class="form-control" name="domaine" id="domaine" placeholder="" value="{{isset($s->domaine) ? $s->domaine :''}}" >
        </div>
    </div>
    <div class="form-group">
        <label for="titre" class="col-md-2 control-label">Nom de la compétence</label>
        <div class="col-md-10">
            <input type="text" class="form-control" name="titre" id="titre" placeholder="" value="{{isset($s->titre) ? $s->titre :''}}">
        </div>
    </div>
    <div class="form-group">
        <label for="niveau" class="col-md-2 control-label">Niveau</label>
        <div class="col-md-10">
            <select name="niveau" id="niveau" class="form-control">
                <option value=""> === select ===  </option>
                <option value="1" {{isset($s->niveau) && $s->niveau == "1" ? 'selected' :''}}> Connaissance de base  </option>
                <option value="2" {{isset($s->niveau) && $s->niveau == "2" ? 'selected' :''}}> Maîtrise   </option>
                <option value="3" {{isset($s->niveau) && $s->niveau == "3" ? 'selected' :''}}> Maîtrise avancée   </option>
                <option value="4" {{isset($s->niveau) && $s->niveau == "4" ? 'selected' :''}}> Expert   </option>
                <option value="5" {{isset($s->niveau) && $s->niveau == "5" ? 'selected' :''}}> Expert avancé  </option>
            </select>
        </div>
    </div>
    <div class="form-group">
        <label for="commentaire" class="col-md-2 control-label">Commentaire</label>
        <div class="col-md-10">
            <textarea name="commentaire" id="commentaire" class="form-control">{{ isset($s->commentaire) ? $s->commentaire :''}}</textarea>
        </div>
    </div>
    <div class="form-group">
        <label for="commentaire" class="col-md-2 control-label">Envie de transmettre</label>
        <div class="col-md-10">
            <label class="toggle-check">
                <input type="checkbox" name="transmit" class="toggle-check-input" {{ isset($s->transmit) && $s->transmit == 1 ? 'checked' :''}}/>
                <span class="toggle-check-text"></span>
            </label>
        </div>
    </div>
</div>
                            