
@if(!App\Entretien::answered($e->id, $user->id) && Auth::user()->id == $user->id)
  <div class="submit mb-20 alert alert-warning">
    <buton onclick="return chmModal.confirm('', 'Soumettre ?', 'Attention !! Vous n’aurez plus la possibilité de modifier votre évaluation. Êtes-vous sûr de vouloir soumettre ?','chmEntretien.submission', {eid: {{$e->id}}, user: {{$user->id}}}, {width: 450, btnlabel: 'Soumettre'})" class="btn btn-danger pull-right"><i class="fa fa-lock"></i> Soumettre</buton>
    <p>En cliquant sur le button "Soumettre", vous n'aurez plus le droit de compléter et/ou de modifier votre évaluation</p>
    <p>NB: Le button "Soumettre" n'enregistre pas les données</p>
  </div>
@endif

@if(!App\Entretien::answeredMentor($e->id, $user->id, $evaluator_id) && Auth::user()->id != $user->id)
  <div class="submit mb-20 alert alert-warning">
    <buton onclick="return chmModal.confirm('', 'Soumettre ?', 'Attention !! Vous n’aurez plus la possibilité de modifier votre évaluation. Êtes-vous sûr de vouloir soumettre ?','chmEntretien.submission', {eid: {{$e->id}}, user: {{$user->id}}}, {width: 450, btnlabel: 'Soumettre'})" class="btn btn-danger pull-right"><i class="fa fa-lock"></i> Soumettre</buton>
    <p>En cliquant sur le button "Soumettre", vous n'aurez plus le droit de compléter et/ou de modifier votre évaluation</p>
    <p>NB: Le button "Soumettre" n'enregistre pas les données</p>
  </div>
@endif