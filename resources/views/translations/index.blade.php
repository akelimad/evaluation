@extends('layouts.app')
@section('title', 'Questionnaires')
@section('breadcrumb')
  <li>{{ __("Traduction de l'interface") }}</li>
@endsection
@section('content')

  <section class="content translations users">
    <div class="row">
      <div class="col-md-12 mb-10">
        @include('partials/alerts/info', [
        'messages' => __("Le button 'Scanner' sert à scanner toutes les phrases trouvées dans le code et les insérer dans la BDD")
        ])
      </div>
      <div class="col-md-12">
        @include('partials/alerts/info', [
        'messages' => __("Le button 'Publier' sert à exporter juste les phrases traduites dans des fichiers pour plus d'optimisation. Veuillez cliquer sur ce button pour que les traductions soient prises en compte")
        ])
      </div>
    </div>
    <div class="row mb-0">
      <div class="col-md-6">
        <h3 class="box-title"><i class="fa fa-language"></i> {{ __("Messages") }} <span class="badge badge-count">0</span></h3>
      </div>
      <div class="col-md-6">
        <div class="pull-md-right pull-sm-right">
          <form class="form-find d-inline-block" method="POST" action="" role="form">
            <button type="submit" class="btn btn-info"><i class="fa fa-search"></i> Scanner</button>
          </form>

          <form class="form-publish d-inline-block" method="POST" action="" role="form">
            <button type="submit" class="btn btn-success"><i class="fa fa-check-circle"></i> Publier</button>
          </form>
        </div>
      </div>
    </div>

    @include('translations.search')

    <div class="row mb-0">
      <div class="col-md-12">
        <div class="box p-0">
          <div class="box-body p-0">
            <div chm-table="{{ route('interface.translations.table') }}"
                 chm-table-options='{"with_ajax": true}'
                 chm-table-params='{{ json_encode(request()->query->all()) }}'
                 id="TranslationsTableContainer"
            ></div>
          </div>
        </div>
      </div>
    </div>
  </section>
@endsection

@section('javascript')
  @parent
  <script>
    $(document).ready(function($) {

      $('.form-find').on('submit', function (e) {
        e.preventDefault()
        window.chmModal.show({
          type: 'post',
          url: "{{ action('\Barryvdh\TranslationManager\Controller@postFind') }}",
          data: {
            "_token": $('input[name="_token"]').val(),
          }
        }, {
          message: '<i class="fa fa-circle-o-notch fa-spin"></i>&nbsp;' + "{{ __("Scanne en cours ...") }}",
          onSuccess: (response) => {
            if ('status' in response && response.status === 'ok') {
              window.chmTable.refresh('#TranslationsTableContainer')
              swal({
                type: 'success',
                text: "{{ __("Le scanne est terminé avec succès") }}"
              })
            }
          }
        })
      })

      $('.form-publish').on('submit', function (e) {
        e.preventDefault()
        window.chmModal.show({
          type: 'post',
          url: "{{ action('\Barryvdh\TranslationManager\Controller@postPublish', '_json') }}",
          data: {
            "_token": $('input[name="_token"]').val(),
          }
        }, {
          message: '<i class="fa fa-circle-o-notch fa-spin"></i>&nbsp;' + "{{ __("Publication en cours ...") }}",
          onSuccess: (response) => {
            if ('status' in response && response.status === 'ok') {
              swal({
                type: 'success',
                text: "{{ __("La publication est terminée avec succès") }}",
                showCancelButton: true,
                cancelButtonText: "{{ __("Fermer") }}",
                confirmButtonText: "{{ __("Actualiser") }}",
              }).then(function () {
                window.location.reload()
              }).catch(swal.noop);
            }
          }
        })
      })

    })
  </script>
@endsection