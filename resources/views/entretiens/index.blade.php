@extends('layouts.app')
@section('title', 'Campagnes')
@section('breadcrumb')
  <li>Campagnes</li>
@endsection
<style>
  .entretiens-status {
    border-bottom: 1px solid #b7b4b4;
  }
  .entretiens-status a {
    transition: all .2s ease-in-out;
    padding: 12px 30px;
    display: inline-block;
    border-bottom: 3px solid transparent;
    color: black;
  }
  .entretiens-status a.active,
  .entretiens-status a:focus,
  .entretiens-status a:hover {
    border-bottom: 3px solid #337ab7;
    color: #337ab7;
  }
</style>
@section('content')
  <section class="content entretiens-list">
    <div class="row mb-30">
      <div class="col-md-8 col-sm-8">
        <h2 class="pageName m-0"><i class="fa fa-comments-o"></i> Campagnes <span class="badge">{{$results->total()}}</span></h2>
      </div>
      <div class="col-md-4 col-sm-4">
        <div class="pull-right">
          <a href="javascript:void(0)" onclick="return chmEntretien.form({})" class="btn bg-aqua-active"><i class="fa fa-plus"></i> Nouvelle campagne</a>
        </div>
      </div>
    </div>

    <div class="row mb-30">
      <div class="col-md-12">
        <div class="entretiens-status">
          <a href="{{ route('entretiens', ['status' => \App\Entretien::ACTIF_STATUS]) }}" class="{{ request()->get('status', \App\Entretien::ACTIF_STATUS) == \App\Entretien::ACTIF_STATUS ? 'active':'' }}"> <i class="fa fa-spinner"></i> {{ \App\Entretien::ACTIF_STATUS }}</a>
          <a href="{{ route('entretiens', ['status' => \App\Entretien::FINISHED_STATUS]) }}" class="{{ request()->get('status') == \App\Entretien::FINISHED_STATUS ? 'active':'' }}"> <i class="fa fa-calendar-times-o"></i> {{ \App\Entretien::FINISHED_STATUS }}</a>
          <a href="{{ route('entretiens', ['status' => 'all']) }}" class="{{ request()->get('status') == 'all' ? 'active':'' }}"> <i class="fa fa-list"></i> Tout</a>
        </div>
      </div>
    </div>

    @if(count($results)>0)
      <div class="row">
        @foreach($results as $e)
          <div class="col-sm-4">
            <div class="box box-default box-hover pt-5">
              <div class="box-header with-border">
                <h3 class="box-title" title="{{ $e->titre }}" data-toggle="tooltip">{{ str_limit($e->titre, 20) }}</h3>
                <span class="label label-{{ $e->isActif() ? 'success':'danger' }} pull-right pl-10 pr-10 p-5 font-14">{{ $e->getStatus() }}</span>
              </div>
              <div class="box-body">
                <p><b>Date limite pour l'auto-évaluation :</b> <span class="pull-right">{{Carbon\Carbon::parse($e->date)->format('d/m/Y')}}</span></p>
                <p><b>Date limite pour l'évaluation {{ $e->model == "Feedback 360" ? " des collègues":"manager" }} :</b> <span class="pull-right">{{Carbon\Carbon::parse($e->date_limit)->format('d/m/Y')}}</span></p>
                <p><b>Nombre de collaborateurs impliqués :</b> <span class="badge pull-right">{{ $e->users->count() }}</span></p>
              </div>
              <div class="box-footer text-center">
                <a href="{{ route('entretien.show', ['id' => $e->id]) }}" class="btn btn-primary"><i class="fa fa-gear"></i> Gérer la campagne</a>
              </div>
            </div>
          </div>
        @endforeach
      </div>
      @if(!request()->get('status', false))
        {{ request()->query->set('status', 'Actif') }}
      @endif
      @include('partials.pagination')
    @else
      @include('partials.alerts.info', ['messages' => "Aucune donnée trouvée ... !!" ])
    @endif
  </section>
@endsection

@section('javascript')
  @parent
  <script>
    $(document).ready(function () {

    })
  </script>
@endsection