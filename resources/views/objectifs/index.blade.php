@extends('layouts.app')
@section('breadcrumb')
  <li>Objectifs</li>
@endsection
@section('content')
  <section class="content users">
    <div class="row">
      <div class="col-md-12">
        <div class="title-section mb-20">
          <h3 class="mt-0">
            <i class="fa fa-signal"></i> {{ __("Objectifs") }} <span class="badge badge-count">0</span>
            <div class="pull-right">
              <a href="{{ route('objectif.form') }}" class="btn bg-maroon"><i class="fa fa-plus"></i>&nbsp;{{ "Ajouter" }}</a>
            </div>
          </h3>
        </div>
      </div>
      <div class="col-md-12">
        <div class="box p-0">
          <div class="box-body p-0">
            <div chm-table="{{ route('objectifs.table') }}"
                 chm-table-options='{"with_ajax": true}'
                 chm-table-params='{{ json_encode(request()->query->all()) }}'
                 id="ObjectifsTableContainer"
            ></div>
          </div>
        </div>
      </div>
    </div>
  </section>
@endsection
  