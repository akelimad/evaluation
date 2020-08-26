@extends('layouts.app')
@section('title', 'Carrière')
@section('content')
  <section class="content evaluations">
    <div class="row">
      <div class="col-md-12">
        <div class="box box-primary card">
          <h3 class="mb40"> Remplir votre évaluation pour : {{ $e->titre }} - {{ $user->name." ".$user->last_name }}</h3>
          <div class="nav-tabs-custom">
            @include('partials.tabs')
            <div class="tab-content">
              <div class="box-body mb40">
                @if(count(Auth::user()->children)>0 && $user->id != Auth::user()->id)
                  @include('questions/survey2')
                @endif
                @if($user->id == Auth::user()->id)
                  @include('questions/survey')
                @endif
              </div>
            </div>
          </div>

          <div class="submit">
            @if(!App\Entretien::answered($e->id, $user->id) && Auth::user()->id == $user->id)
              <buton onclick="return chmModal.confirm('', 'Soumettre ?', 'Attention !! Vous n’aurez plus la possibilité de modifier votre évaluation. Êtes-vous sûr de vouloir soumettre ?','chmEntretien.submission', {eid: {{$e->id}}, user: {{$user->id}}}, {width: 450, btnlabel: 'Soumettre'})" class="btn btn-danger"><i class="fa fa-check"></i> Soumettre</buton>
            @endif
            @if(!App\Entretien::answeredMentor($e->id, $user->id, $user->parent->id) && Auth::user()->id != $user->id)
              <buton onclick="return chmModal.confirm('', 'Soumettre ?', 'Attention !! Vous n’aurez plus la possibilité de modifier votre évaluation. Êtes-vous sûr de vouloir soumettre ?','chmEntretien.submission', {eid: {{$e->id}}, user: {{$user->id}}}, {width: 450, btnlabel: 'Soumettre'})" class="btn btn-danger"><i class="fa fa-check"></i> Soumettre</buton>
            @endif
          </div>

          @include('partials.submit-eval')

          <div class="callout callout-info">
            <p class="">
              <i class="fa fa-info-circle fa-2x"></i>
              <span class="content-callout">Cette page affiche l'évaluation de la part du collaborateur: <b>{{ $user->name." ".$user->last_name }}</b> pour l'entretien: <b>{{ $e->titre }}</b> </span>
            </p>
          </div>
        </div>
      </div>
    </div>
  </section>
@endsection
  