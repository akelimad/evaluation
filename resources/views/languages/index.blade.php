@extends('layouts.app')
@section('title', 'Langues')
@section('breadcrumb')
  <li>{{ __("Langues") }}</li>
@endsection
@section('content')

  <section class="content languages">

    <div class="row mb-0">
      <div class="col-md-6">
        <h3 class="box-title"><i class="fa fa-flag"></i> {{ __("Langues") }} <span class="badge badge-count">0</span></h3>
      </div>
      <div class="col-md-6">
        <div class="pull-right">
          <a
              href="javascript:void(0)"
              chm-modal="{{ route('languages.form') }}"
              chm-modal-options='{"form":{"attributes":{"id":"languageForm","target-table":"[chm-table]"}}}'
              class="btn bg-maroon"
          ><i class="fa fa-flag"></i>&nbsp;{{ __("Ajouter") }}</a>
        </div>
      </div>
    </div>

    <div class="row mb-0">
      <div class="col-md-12">
        <div class="box p-0">
          <div class="box-body p-0">
            <div chm-table="{{ route('languages.table') }}"
                 chm-table-options='{"with_ajax": true}'
                 chm-table-params='{{ json_encode(request()->query->all()) }}'
                 id="LanguagesTableContainer"
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

    })
  </script>
@endsection