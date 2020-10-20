@extends('layouts.app')
@section('title', 'Evaluations')
@section('content')
  <section class="content evaluations">
    <div class="row">
      <div class="col-md-12">
        <div class="box box-primary card">
          <h3 class="mt-0 mb40"> Remplir votre évaluation pour : {{ $e->titre }}</h3>

          <div class="nav-tabs-custom">
            @include('partials.tabs')
            <div class="tab-content">
              <div class="box-body">
                @if(count(Auth::user()->children)>0 && $user->id != Auth::user()->id || $evaluator_id == Auth::user()->id)
                  @include('questions/survey2', ['evaluator_id' => $evaluator_id])
                @endif
                @if($user->id == Auth::user()->id )
                  @include('questions/survey', ['evaluator_id' => $evaluator_id])
                @endif
              </div>
            </div>
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
