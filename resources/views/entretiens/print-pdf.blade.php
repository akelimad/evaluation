<style>
  .text-center {
    text-align: center;
  }
  .table {
    width: 100%;
    max-width: 100%;
    border-spacing: 0;
    border-collapse: collapse;
  }
  .table tr td,
  .table tr th {
    padding: 8px;
    vertical-align: middle;
    border: 1px solid #ddd;
  }
  .row {
    width: 100%;
    margin-right: -15px;
    margin-left: -15px;
  }
  .col-md-2 {
    width: 16.66666667%;
  }
  .col-md-6 {
    width: 50%;
  }
  .col-md-10 {
     width: 83.33333333%;
  }
  .col-md-12 {
    width: 100%;
  }
  .col-md-2, .col-md-6, .col-md-10, .col-md-12 {
    float: left;
  }
  .section-title {
    background-color: #0b8ccd;
    color: white;
    width: 100%;
    font-size: 18px;
    padding: 5px;
    font-weight: bold;
  }
  .mt-20 {
    margin-top: 20px;
  }
  .mb-20 {
    margin-bottom: 20px;
  }
  .clearfix {
    clear: both;
  }
  .q-choice {
    border: 1px solid slategray;
    padding: 10px;
  }
  .groupTitle {
    padding: 10px;
    background: lightslategray;
    margin-bottom: 20px;
  }
  .questionTitle {
    margin-bottom: 20px;
    font-weight: 600;
  }
  .pull-right {
    float: right;
  }
  tr.sous-total {
    background-color: darkseagreen;
  }
  tr.total {
    background-color: greenyellow;
  }
  .pl-30 {
    padding-left: 30px !important;
  }
  .sousTotal {
    background: #e4cece;
  }
  .total {
    background: #f39c12;
  }
  .array-qst-note {
    background: #e6d3b0 !important;
  }
</style>
{{-- ****************** Header ********************** --}}
<h2 class="text-center">Formulaire d'Evaluation Annuelle des Performances	</h2>
<table class="table">
  <tbody>
    <tr>
      <td>Date de l'entretien</td>
      <td>{{ Carbon\Carbon::parse($e->date)->format('d/m/Y')}}</td>
    </tr>
    <tr>
      <td>Pour l'année</td>
      <td>{{ Carbon\Carbon::parse(date('Y'))->format('Y')}}</td>
    </tr>
    <tr>
      <td colspan="2"></td>
    </tr>
    <tr>
      <td>Nom et prénom</td>
      <td>{{ $user->last_name . ' ' .$user->name }}</td>
    </tr>
    <tr>
      <td>Matricule</td>
      <td>{{ $user->mle }}</td>
    </tr>
    <tr>
      <td>Direction</td>
      <td>{{ $user->service }}</td>
    </tr>
    <tr>
      <td>Fonction exercée</td>
      <td>{{ $user->service }}</td>
    </tr>
    <tr>
      <td>Nom et fonction de l'évaluateur</td>
      <td>{{ $user->parent->last_name }}, {{ is_numeric($user->parent->function) ? App\Fonction::find($user->parent->function)->title : '---' }}</td>
    </tr>
    <tr>
      <td>Date d'embauche dans la société</td>
      <td>{{ !is_null($user->date_recruiting) ? Carbon\Carbon::parse($user->date_recruiting)->format('d/m/Y') : '---' }}</td>
    </tr>
  </tbody>
</table>
<div class="entretien-infos">
  <h3 class="text-center" style="color: #0b8ccd;font-weight: 600">{{ $e->titre }}</h3>
</div>

@if(in_array('Evaluation annuelle', $entreEvalsTitle))
  <div class="mt-20"><p class="section-title">Evaluation annuelle</p></div>
  <h3>En construction ...</h3>
@endif

@if(in_array('Carrières', $entreEvalsTitle))
  <div class="mt-20"><p class="section-title">Carrières</p></div>
  <h3>En construction ...</h3>
@endif

@if(in_array('Objectifs', $entreEvalsTitle))
  <div class="mt-20"><p class="section-title">Objectifs</p></div>
  <h3>En construction ...</h3>
@endif

@if(in_array('Formations', $entreEvalsTitle))
  <div class="mt-20"><p class="section-title">Formations</p></div>
  <h3>En construction ...</h3>
@endif

@if(in_array('Compétences', $entreEvalsTitle))
  <div class="mt-20"><p class="section-title">Compétences</p></div>
  <h3>En construction ...</h3>
@endif

@if(in_array('Primes', $entreEvalsTitle))
  <div class="mt-20"><p class="section-title">Primes</p></div>
  <h3>En construction ...</h3>
@endif

@if(in_array('Commentaires', $entreEvalsTitle))
  <div class="mt-20"><p class="section-title">Commentaires</p></div>
  <table class="table mt-20">
    <tbody>
    <tr>
      <td width="20%">Nom du manager :</td>
      <td>{{ $user->parent->last_name }}</td>
    </tr>
    <tr>
      <td width="20%">Commentaire du manager :</td>
      <td>{{ isset($comment->mentorComment) ? $comment->mentorComment : '' }}</td>
    </tr>
    <tr>
      <td width="20%">Signature du manager :</td>
      <td>{{ $user->parent->name . ' ' .$user->parent->last_name }}</td>
    </tr>
    <tr>
      <td width="20%">Date de la revue :</td>
      <td>{{ isset($comment->mentor_updated_at) ? Carbon\Carbon::parse($comment->mentor_updated_at)->format('d/m/Y') : '' }}</td>
    </tr>
    </tbody>
  </table>
  <table class="table mt-20">
    <tbody>
    <tr>
      <td width="20%">Nom du collaborateur :</td>
      <td>{{ $user->last_name }}</td>
    </tr>
    <tr>
      <td width="20%">Commentaire du collaborateur :</td>
      <td>{{ isset($comment->userComment) ? $comment->userComment : '' }}</td>
    </tr>
    <tr>
      <td width="20%">Signature du collaborateur :</td>
      <td>{{ $user->name . ' ' .$user->last_name }}</td>
    </tr>
    <tr>
      <td width="20%">Date du commentaire :</td>
      <td>{{ isset($comment->created_at) ? date('d/m/Y', strtotime($comment->created_at)) : '---' }}</td>
    </tr>
    </tbody>
  </table>
@endif
