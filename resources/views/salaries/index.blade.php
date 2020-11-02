@extends('layouts.app')
@section('title', 'Salaires')
@section('content')
  <section class="content salaries p-sm-10">
    <div class="row">
      <div class="col-md-12">
        <div class="box box-primary card">
          <h3 class="mt-0 mb40">Liste des primes pour: {{ $e->titre }} - {{ $user->fullname() }}</h2>
            <div class="nav-tabs-custom">
              @include('partials.tabs')
              <div class="tab-content p-20 p-sm-0">
                <h3 class="mb-20 mt-5">{{ __("Primes attribuées") }}
                  @if($user->id != Auth::user()->id && !App\Entretien::answeredMentor($e->id, $user->id, $user->parent->id))
                    <a
                        href="javascript:void(0)"
                        chm-modal="{{ route('prime.add', ['eid' => $e->id, 'uid' => $user->id]) }}"
                        chm-modal-options='{"form":{"attributes":{"id":"primeForm","target-table":"[chm-table]"}}}'
                        class="btn btn-success pull-right"
                    ><i class="fa fa-plus"></i>&nbsp;{{ "Ajouter une prime" }}</a>
                  @endif
                </h3>
                {{ request()->query->set('eid', $e->id) }}
                {{ request()->query->set('uid', $user->id) }}
                <div chm-table="{{ route('primes.table') }}"
                     chm-table-options='{"with_ajax": true}'
                     chm-table-params='{{ json_encode(request()->query->all()) }}'
                     id="PrimesTableContainer"
                ></div>

                <div class="mt-20 p-20 bg-gray">
                  <a href="{{ route('anglets.competences', ['eid' => $e->id, 'uid' => $user->id]) }}" class="btn btn-default"><i class="fa fa-long-arrow-left"></i> {{ __("Précédent") }}</a>
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
  