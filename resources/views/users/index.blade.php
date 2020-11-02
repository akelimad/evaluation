@extends('layouts.app')
@section('title', 'Utilisateurs')
@section('breadcrumb')
  <li>{{ __("Paramétrages") }}</li>
  <li>{{ __("Utilisateurs") }}</li>
@endsection
@section('content')
  <section class="content p-sm-10 users">

    <div class="row mb-0">
      <div class="col-md-6">
        <h3 class="box-title"><i class="fa fa-users"></i> {{ __("Utilisateurs") }} <span class="badge badge-count">0</span></h3>
      </div>
      <div class="col-md-6 mb-sm-20">
        <div class="pull-md-right pull-sm-right">
          <a
              href="javascript:void(0)"
              chm-modal="{{ route('user.form') }}"
              chm-modal-options='{"form":{"attributes":{"id":"userForm","target-table":"[chm-table]"}}}'
              class="btn bg-maroon mb-sm-10"
          ><i class="fa fa-user-plus"></i>&nbsp;{{ "Ajouter" }}</a>

          <a href="{{ url('users/import') }}" class="btn btn-success mb-sm-10"><i class="fa fa-upload"></i> {{ __("Importer") }}</a>

          <a href="{{ url('users/export') }}" class="btn bg-olive-active mb-sm-10"><i class="fa fa-download"></i> {{ __("Exporter") }}</a>
        </div>
      </div>
    </div>

    @include('users.search')

    <div class="row mb-0">
      <div class="col-md-12">
        <div class="box p-0">
          <div class="box-body p-0">
            <div chm-table="{{ route('users.table') }}"
                 chm-table-options='{"with_ajax": true}'
                 chm-table-params='{{ json_encode(request()->query->all()) }}'
                 id="UsersTableContainer"
            ></div>
          </div>
        </div>
      </div>
    </div>
  </section>
@endsection
