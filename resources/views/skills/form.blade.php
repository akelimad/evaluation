
<div class="content">
    <input type="hidden" name="id" value="{{ isset($s->id) ? $s->id : null }}">
    {{ csrf_field() }}
    <div class="form-group">
        <label for="axe" class="control-label">Axe <span class="asterisk">*</span></label>
        <input type="text" class="form-control" name="axe" id="axe" placeholder="" value="{{isset($s->axe) ? $s->axe :''}}" required="">
    </div>
    <div class="form-group">
        <label for="famille" class="control-label">Famille <span class="asterisk">*</span></label>
        <input type="text" class="form-control" name="famille" id="famille" placeholder="" value="{{isset($s->famille) ? $s->famille :''}}" required="">
    </div>
    <div class="form-group"> 
        <label for="categorie" class="control-label">Catégorie <span class="asterisk">*</span></label>
        <input type="text" class="form-control" name="categorie" id="categorie" placeholder="" value="{{isset($s->categorie) ? $s->categorie :''}}" required="">
    </div>
    <div class="form-group">
        <label for="competence" class="control-label">Compétence <span class="asterisk">*</span></label>
        <input type="text" class="form-control" name="competence" id="competence" placeholder="" value="{{isset($s->competence) ? $s->competence :''}}" required=""> 
    </div>
</div>
                            