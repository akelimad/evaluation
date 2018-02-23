
<div class="content">
    <input type="hidden" name="id" value="{{ isset($d->id) ? $d->id : null }}">
    <input type="hidden" name="e_id" value="{{ isset($e->e_id) ? $e->e_id : null }}">
    {{ csrf_field() }}
    <div class="form-group">
        <label for="titre" class="col-md-2 control-label">Titre</label>
        <div class="col-md-10">
            <input type="text" class="form-control" name="titre" id="titre" placeholder="" value="{{isset($d->titre) ? $d->titre :''}}">
        </div>
    </div>
    <div class="form-group">
        <label for="apropos" class="col-md-2 control-label">A propos</label>
        <div class="col-md-10">
            <textarea name="apropos" id="apropos" class="form-control">{{ isset($d->apropos) ? $d->apropos :''}}</textarea>
        </div>
    </div>
    <div class="form-group">
        <label for="fichier" class="col-md-2 control-label">Choisissez le document</label>
        <div class="col-md-10">
            <input type="file" name="fichier" id="fichier">
            @if(!empty($d->fichier))
                <p class="showFile"><a href="{{asset('documents/'.$d->fichier)}}" target="_blank" class="form-control"><i class="fa fa-download"></i> Télécharger le document </a></p>
            @endif
        </div>
    </div>
</div>