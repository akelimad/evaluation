@extends('layouts.app')
@section('title', __("Niveaux d'expériences"))
@section('breadcrumb')
  <li>{{ __("Paramétrage") }}</li>
  <li>{{ __("Niveaux d'expériences") }}</li>
@endsection
@section('content')
  <section class="content p-sm-10 setting">
    <div class="row">
      <div class="col-md-12">
        <div class="title-section mb-20">
          <h3 class="mt-0">
            <i class="fa fa-briefcase"></i> {{ __("Niveaux d'expériences") }} <span class="badge badge-count">0</span>
            <div class="pull-md-right pull-sm-right">
              <a
                  href="javascript:void(0)"
                  chm-modal="{{ route('experience-levels.form') }}"
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
            <div chm-table="{{ route('experience-levels.table') }}"
                 chm-table-options='{"with_ajax": true}'
                 chm-table-params='{{ json_encode(request()->query->all()) }}'
                 id="ExperienceLevelsTableContainer"
            ></div>
          </div>
        </div>
      </div>
    </div>
  </section>
@endsection
