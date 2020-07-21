@extends('layouts.app')
@section('title', 'Entretiens')
@section('breadcrumb')
  <li>Campagnes</li>
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
        <a href="{{ route('entretiens', ['status' => \App\Entretien::ACTIF_STATUS]) }}" class="btn {{ request()->get('status', \App\Entretien::ACTIF_STATUS) == \App\Entretien::ACTIF_STATUS ? 'bg-gray-active':'' }}"> <i class="fa fa-spinner"></i> {{ \App\Entretien::ACTIF_STATUS }}</a>
        <a href="{{ route('entretiens', ['status' => \App\Entretien::FINISHED_STATUS]) }}" class="btn {{ request()->get('status') == \App\Entretien::FINISHED_STATUS ? 'bg-gray-active':'' }}"> <i class="fa fa-calendar-times-o"></i> {{ \App\Entretien::FINISHED_STATUS }}</a>
        <a href="{{ route('entretiens', ['status' => 'all']) }}" class="btn {{ request()->get('status') == 'all' ? 'bg-gray-active':'' }}"> <i class="fa fa-list"></i> Tout</a>
      </div>
    </div>

    @if(count($results)>0)
      <div class="row">
        @foreach($results as $e)
          <div class="col-sm-4">
            <div class="box box-primary box-hover pt-5">
              <div class="box-header with-border">
                <h3 class="box-title" title="{{ $e->titre }}" data-toggle="tooltip">{{ str_limit($e->titre, 20) }}</h3>
                <span class="label label-{{ $e->isActif() ? 'success':'danger' }} pull-right pl-10 pr-10 p-5 font-14">{{ $e->getStatus() }}</span>
              </div>
              <div class="box-body">
                <p><b>Date limite pour l'auto-évaluation :</b> <span class="pull-right">{{Carbon\Carbon::parse($e->date)->format('d/m/Y')}}</span></p>
                <p><b>Date limite pour l'évaluation manager :</b> <span class="pull-right">{{Carbon\Carbon::parse($e->date_limit)->format('d/m/Y')}}</span></p>
                <p><b>Nombre de collaborateurs impliqués :</b> <span class="badge pull-right">{{ $e->users->count() }}</span></p>
              </div>
              <div class="box-footer">
                <a href="{{ route('entretien.show', ['id' => $e->id]) }}" class="btn btn-primary pull-right"><i class="fa fa-gear"></i> Gérer la campagne</a>
              </div>
            </div>
          </div>
        @endforeach
      </div>
      @include('partials.pagination')
    @else
      @include('partials.alerts.info', ['messages' => "Aucune donnée trouvée ... !!" ])
    @endif
  </section>
@endsection

@section('javascript')
  <script>
    $(document).ready(function () {
      $('.table-responsive').on('show.bs.dropdown', function () {
        $('.table-responsive').css('overflow', 'inherit')
      })
      $('.table-responsive').on('hide.bs.dropdown', function () {
        $('.table-responsive').css('overflow', 'auto')
      })

      $('.checkbox-eval').change(function () {
        if (this.checked) {
          $(this).closest('td').find('select').prop('required', true)
        } else {
          $(this).closest('td').find('select').prop('required', false)
        }
      })
      $('.checkbox-eval').change()
    })
  </script>
@endsection