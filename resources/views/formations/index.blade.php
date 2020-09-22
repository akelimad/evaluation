@extends('layouts.app')
@section('title', 'Formations')
@section('content')
  <section class="content evaluations">
    <div class="row">
      <div class="col-md-12">
        <div class="box box-primary card">
          <h3 class="mb40"> Liste des formations : {{ $e->titre }} - {{ $user->fullname() }} </h3>
          <div class="nav-tabs-custom">
            @include('partials.tabs')
            <div class="tab-content">
              <div class="row">
                <div class="col-md-12 mb-0">
                  <h3 class="styled-title">Formations demandées</h3>
                </div>
              </div>
              <div class="col-md-12 mb-20">
                {{ request()->query->set('eid', $e->id) }}
                {{ request()->query->set('uid', $user->id) }}
                <div chm-table="{{ route('formations.table') }}"
                     chm-table-options='{"with_ajax": true}'
                     chm-table-params='{{ json_encode(request()->query->all()) }}'
                     id="FormationsTableContainer"
                ></div>
              </div>
              @if(!App\Entretien::answered($e->id, $user->id) && $user->id == Auth::user()->id)
                <div class="row">
                  <div class="col-md-12">
                    <a
                        href="javascript:void(0)"
                        chm-modal="{{ route('formation.add', ['e_id' => $e->id]) }}"
                        chm-modal-options='{"form":{"attributes":{"id":"formationForm","target-table":"[chm-table]"}}}'
                        class="btn btn-primary"
                    ><i class="fa fa-plus"></i>&nbsp;{{ "Demander une formation" }}</a>
                  </div>
                </div>
              @endif
            </div>
          </div>

          @include('partials.submit-eval')

          <div class="callout callout-info">
            <p class="">
              <i class="fa fa-info-circle fa-2x"></i>
              <span class="content-callout">Cette page affiche Liste des formations demandées de la part du collaborateur: <b>{{ $user->name." ".$user->last_name }}</b> pour l'entretien: <b>{{ $e->titre }}</b> </span>
            </p>
          </div>
        </div>
      </div>
    </div>
  </section>
@endsection

@section('javascript')
  @parent
  <link rel="stylesheet" href="{{ asset('css/bootstrap-datepicker.min.css') }}">
  <script src="{{asset('js/moment.min.js')}}"></script>
  <script src="{{asset('js/bootstrap-datepicker.min.js')}}"></script>
  <script src="{{asset('js/bootstrap-datepicker.fr.min.js')}}"></script>
@endsection