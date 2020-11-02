@extends('layouts.app')
@section('title', 'Sociétés')
@section('breadcrumb')
  <li>Utilisateurs</li>
@endsection
@section('content')
  <section class="content p-sm-10 users">

    <div class="row mb-0">
      <div class="col-sm-6">
        <h3 class="box-title"><i class="fa fa-industry"></i> Liste des sociétés <span class="badge badge-count">0</span></h3>
      </div>
      <div class="col-sm-6 mb-sm-20">
        <div class="pull-md-right pull-sm-right">
          <a
              href="javascript:void(0)"
              chm-modal="{{ route('company.form') }}"
              chm-modal-options='{"form":{"attributes":{"id":"userForm","target-table":"[chm-table]"}}}'
              class="btn bg-maroon"
          ><i class="fa fa-industry"></i>&nbsp;{{ "Ajouter" }}</a>
        </div>
      </div>
    </div>

    @include('companies.search')

    <div class="row mb-0">
      <div class="col-md-12">
        <div class="box p-0">
          <div class="box-body p-0">
            <div chm-table="{{ route('companies.table') }}"
                 chm-table-options='{"with_ajax": true}'
                 chm-table-params='{{ json_encode(request()->query->all()) }}'
                 id="CompaniesTableContainer"
            ></div>
          </div>
        </div>
      </div>
    </div>
  </section>
@endsection
