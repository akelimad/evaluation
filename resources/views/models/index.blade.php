@extends('layouts.app')
@section('title', 'Modèles')
@section('breadcrumb')
  <li>Paramétrage</li>
  <li>Modèles</li>
@endsection
@section('content')
  <section class="content p-sm-10 setting">
    <div class="row">
      <div class="col-md-12">
        <div class="title-section mb-20">
          <h3 class="mt-0">
            <i class="fa fa-list"></i> {{ __("Modèles d'évaluations") }} <span class="badge badge-count">0</span>
            <div class="pull-md-right pull-sm-right">
              <a
                  href="javascript:void(0)"
                  chm-modal="{{ route('model.form') }}"
                  chm-modal-options='{"form":{"attributes":{"id":"modelForm","target-table":"[chm-table]"}}}'
                  class="btn bg-maroon"
              ><i class="fa fa-plus"></i>&nbsp;{{ "Ajouter" }}</a>
            </div>
          </h3>
        </div>
      </div>
      <div class="col-md-12">
        <div class="box p-0">
          <div class="box-body p-0">
            <div chm-table="{{ route('models.table') }}"
                 chm-table-options='{"with_ajax": true}'
                 chm-table-params='{{ json_encode(request()->query->all()) }}'
                 id="ModelesTableContainer"
            ></div>
          </div>
        </div>
      </div>
    </div>
  </section>
@endsection
