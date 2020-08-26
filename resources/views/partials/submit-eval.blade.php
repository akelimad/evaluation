<div class="submit mb-20">
  @if(!App\Entretien::answered($e->id, $user->id) && Auth::user()->id == $user->id)
    <buton onclick="return chmModal.confirm('', 'Soumettre ?', 'Attention !! Vous n’aurez plus la possibilité de modifier votre évaluation. Êtes-vous sûr de vouloir soumettre ?','chmEntretien.submission', {eid: {{$e->id}}, user: {{$user->id}}}, {width: 450, btnlabel: 'Soumettre'})" class="btn btn-danger pull-right"><i class="fa fa-check"></i> Soumettre</buton>
  @endif
  @if(!App\Entretien::answeredMentor($e->id, $user->id, $user->parent->id) && Auth::user()->id != $user->id)
    <buton onclick="return chmModal.confirm('', 'Soumettre ?', 'Attention !! Vous n’aurez plus la possibilité de modifier votre évaluation. Êtes-vous sûr de vouloir soumettre ?','chmEntretien.submission', {eid: {{$e->id}}, user: {{$user->id}}}, {width: 450, btnlabel: 'Soumettre'})" class="btn btn-danger pull-right"><i class="fa fa-check"></i> Soumettre</buton>
  @endif
</div>