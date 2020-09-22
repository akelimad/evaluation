@extends('layouts.app')
@section('title', 'Salaires')
@section('content')
  <section class="content evaluations">
    <div class="row">
      <div class="col-md-12">
        <div class="box box-primary card">
          <h3 class="mb40">Liste des primes pour: {{ $e->titre }} - {{ $user->fullname() }}</h2>
            <div class="nav-tabs-custom">
              @include('partials.tabs')
              <div class="tab-content">
                <div class="col-md-12 mb-20">
                  {{ request()->query->set('eid', $e->id) }}
                  {{ request()->query->set('uid', $user->id) }}
                  <div chm-table="{{ route('primes.table') }}"
                       chm-table-options='{"with_ajax": true}'
                       chm-table-params='{{ json_encode(request()->query->all()) }}'
                       id="PrimesTableContainer"
                  ></div>
                </div>

                <div class="mt-20">
                  <a href="{{url('/')}}" class="btn btn-default"><i class="fa fa-long-arrow-left"></i> Retour </a>
                  @if($user->id != Auth::user()->id && !App\Entretien::answeredMentor($e->id, $user->id, $user->parent->id))
                    <a onclick="return chmSalary.create({eid: {{$e->id}} , uid: {{$user->id}} })" data-id="{{$e->id}}"
                       class="btn btn-success"><i class="fa fa-plus"></i> Ajouter une prime</a>
                  @endif
                </div>
              </div>
            </div>

            @include('partials.submit-eval')

            <div class="callout callout-info">
              <p class="">
                <i class="fa fa-info-circle fa-2x"></i>
                <span class="content-callout">Cette page affiche Liste des primes de la part du collaborateur: <b>{{ $user->name." ".$user->last_name }}</b> pour l'entretien: <b>{{ $e->titre }}</b> </span>
              </p>
            </div>
        </div>
      </div>
    </div>
  </section>
@endsection
  