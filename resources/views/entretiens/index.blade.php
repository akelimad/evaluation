@extends('layouts.app')
@section('title', 'Campagnes')
@section('breadcrumb')
  <li>Campagnes</li>
@endsection

@section('style')
  @parent
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
@endsection

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
          <a href="{{ route('entretiens', ['status' => \App\Entretien::CURRENT_STATUS]) }}" class="{{ request()->get('status', \App\Entretien::CURRENT_STATUS) == \App\Entretien::CURRENT_STATUS ? 'active':'' }}"> <i class="fa fa-spinner"></i> {{ \App\Entretien::CURRENT_STATUS }}</a>

          <a href="{{ route('entretiens', ['status' => \App\Entretien::FINISHED_STATUS]) }}" class="{{ request()->get('status') == \App\Entretien::FINISHED_STATUS ? 'active':'' }}"> <i class="fa fa-check-circle"></i> {{ \App\Entretien::FINISHED_STATUS }}</a>

          <a href="{{ route('entretiens', ['status' => \App\Entretien::EXPIRED_STATUS]) }}" class="{{ request()->get('status') == \App\Entretien::EXPIRED_STATUS ? 'active':'' }}"> <i class="fa fa-calendar-times-o"></i> {{ \App\Entretien::EXPIRED_STATUS }}</a>

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
                <h3 class="box-title" title="{{ $e->titre }}" data-toggle="tooltip"><a href="{{ route('entretien.show', ['id' => $e->id]) }}">{{ str_limit($e->titre, 20) }}</a></h3>
                <span class="label label-{{ $e->isCurrent() ? 'success':'danger' }} pull-right pl-10 pr-10 p-5 font-14">{{ $e->getStatus() }}</span>
              </div>
              <div class="box-body">
                <p><b>Date limite pour l'auto-évaluation :</b> <span class="pull-right">{{Carbon\Carbon::parse($e->date)->format('d/m/Y')}}</span></p>
                <p><b>Date limite pour l'évaluation {{ $e->model == "Feedback 360" ? " des collègues":"manager" }} :</b> <span class="pull-right">{{Carbon\Carbon::parse($e->date_limit)->format('d/m/Y')}}</span></p>
                <p><b>Nombre de collaborateurs impliqués :</b> <span class="badge pull-right">{{ $e->users->count() }}</span></p>
              </div>
              <div class="box-footer">
                <div class="dropdown">
                  <button class="btn btn-info dropdown-toggle m-0" type="button" id="questionTypes" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">Actions <span class="caret"></span>
                  </button>
                  <ul class="dropdown-menu" aria-labelledby="questionTypes">
                    <li><a href="{{ route('entretien.show', ['id' => $e->id]) }}"><i class="fa fa-eye"></i> {{ __("Consulter") }}</a></li>
                    <li><a href="{{ route('entretien.clone', ['id' => $e->id]) }}"><i class="fa fa-clone"></i> {{ __("Copier") }}</a></li>
                    <li><a href="javascript:void(0)" onclick="return chmModal.confirm('', 'Supprimer l\'entretien ?', 'Etes-vous sur de vouloir supprimer cet entretien ?','chmEntretien.delete', {eid: {{ $e->id }} }, {width: 450})" class="delete"><i class="fa fa-trash"></i> {{ __("Supprimer") }}</a></li>
                  </ul>
                </div>
              </div>
            </div>
          </div>
        @endforeach
      </div>
      @if(!request()->get('status', false))
        {{ request()->query->set('status', \App\Entretien::CURRENT_STATUS) }}
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