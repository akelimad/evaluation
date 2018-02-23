
<div class="content">
    <input type="hidden" name="type" value="professionnel">
    <input type="hidden" name="id" value="{{isset($e->id) ? $e->id : null }}">
    {{ csrf_field() }}
    <div class="form-group">
        <div class="col-md-6">
            <label for="date" class="control-label">Date de l'entretien</label>
            <input type="text" name="date" class="form-control" id="datepicker" value="{{isset($e->date) ? Carbon\Carbon::parse($e->date)->format('d-m-Y') : null }}">
        </div>
        <div class="col-md-6">
            <label for="titre" class="control-label">Titre</label>
            <input type="text" name="titre" class="form-control" id="titre" value="{{isset($e->titre) ? $e->titre : null }}">
        </div>
    </div>
    <div class="form-group">
        <div class="col-md-6">
            <label for="motif" class="control-label">Motif</label>
            <select id="motif" name="motif" class="form-control">
                <option value="obligatoire">Entretien professionnel obligatoire</option>
                <option value="conge_parental">Reprise d'activité suite à un congé parental</option>
                <option value="conge_education">Reprise d'activité suite à un congé parental d'éducation</option>
                <option value="conge_adoption">Reprise d'activité suite à un congé d'adoption</option>
                <option value="conge_sabbatique">Reprise d'activité suite à un congé sabbatique</option>
                <option value="mobilite_volontaire">Reprise d'activité suite à une période de mobilité volontaire sécurisée</option>
                <option value="arret_maladie">Reprise d'activité suite à un arrêt longue maladie</option>
                <option value="mandat_syndical">Reprise d'activité suite à un mandat syndical</option>
                <option value="autre">Reprise d'activité suite à un autre cas</option>
            </select>
        </div>
        <div class="col-md-6">
            <label for="email" class="control-label">Fréquence</label>
            <select name="frequence" id="frequence" class="form-control">
                <option value="1">Tous les deux ans</option>
                <option value="2">Tous les six ans</option>
                <option value="3">Autre cas</option>
            </select>
        </div>
    </div>
    <div class="form-group">
        <div class="col-md-6">
            <label for="conclusion_mentor" class="control-label">Conclusion du mentor</label>
            <textarea name="conclusion_mentor" id="conclusion_mentor" class="form-control">{{isset($e->conclusion_mentor) ? $e->conclusion_mentor : '' }}</textarea>
        </div>
        <div class="col-md-6"> 
            <label for="conclusion_coll" class="control-label">Conclusion du collaborateur</label>
            <textarea name="conclusion_coll" id="conclusion_coll" class="form-control">{{isset($e->conclusion_coll) ? $e->conclusion_coll : '' }}</textarea>
        </div>
    </div>
    @if(empty($e->id))
    <div class="form-group">
        <div class="col-md-12">
            <label for="user_id" class="control-label">Collaborateur à evaluer</label>
            <select name="usersId[]" id="user_id" class="form-control select2" multiple="multiple" data-placeholder="select " style="width: 100%;">
                @foreach($users as $user)
                    <option value="{{ $user->id }}"> {{ $user->email }} </option>
                @endforeach
            </select>
        </div>
    </div>
    @endif
</div>
<script>
    $(function() {
        $('#datepicker, #datepicker1, #datepicker2').datepicker({
            autoclose: true,
            format: 'dd-mm-yyyy'
        })
        $('.select2').select2()
    })
</script>