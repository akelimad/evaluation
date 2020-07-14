@extends('layouts.app')
@section('title', 'Entretiens')
@section('breadcrumb')
  <li>Entretiens</li>
@endsection
@section('content')
  <section class="content entretiens-list">
    <div class="row">
      <div class="col-md-12">
        @foreach (['danger', 'warning', 'success', 'info'] as $key)
          @if(Session::has($key))
            @include('partials.alerts.'.$key, ['messages' => Session::get($key) ])
          @endif
        @endforeach
        <div class="box box-primary">
          <div class="box-header">
            <h3 class="box-title">Liste des entretiens <span class="badge">{{$entretiens->total()}}</span></h3>

            <div class="box-tools">
              <a href="javascript:void(0)" onclick="return chmEntretien.form({})" class="btn bg-maroon"
                 data-toggle="tooltip" title="Créer un entretien"> <i class="fa fa-plus"></i> Ajouter</a>
            </div>
          </div>
        </div>
      </div>
      <div class="clearfix"></div>
    </div>
    @if(count($entretiens)>0)
      <div class="row">
        @foreach($entretiens as $e)
          <div class="col-sm-4">
            <div class="box box-primary">
              <div class="box-header with-border">
                <h3 class="box-title" title="{{ $e->titre }}" data-toggle="tooltip">{{ str_limit($e->titre, 20) }}</h3>
                <span class="label label-success pull-right pl-10 pr-10 p-10 font-18">Actif</span>
              </div>
              <div class="box-body">
                <p><b>Date de l'entretien :</b> <span class="pull-right">{{Carbon\Carbon::parse($e->date)->format('d/m/Y')}}</span></p>
                <p><b>Date de clôture :</b> <span class="pull-right">{{Carbon\Carbon::parse($e->date_limit)->format('d/m/Y')}}</span></p>
              </div>
              <div class="box-footer">
                <a href="{{ route('entretien.show', ['id' => $e->id]) }}" class="btn btn-primary pull-right"><i class="fa fa-gear"></i> Gérer la campagne</a>
              </div>
            </div>
          </div>
        @endforeach
      </div>
    @else
      @include('partials.alerts.info', ['messages' => "Aucune donnée trouvée ... !!" ])
    @endif
  </section>
@endsection

@section('javascript')
  <script>
    $(document).ready(function () {
      if (chmUrl.getParam('deleted') == 1) {
        window.location.href = 'entretiens/index'
      }
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