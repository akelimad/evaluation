@extends('layouts.app')
@section('title', 'Utilisateurs')
@section('breadcrumb')
  <li>Utilisateurs</li>
@endsection
@section('content')
  <section class="content users">

    <div class="row mb-0">
      <div class="col-md-6">
        <h3 class="box-title"><i class="fa fa-user"></i> Liste des utilisateurs <span class="badge badge-count">0</span></h3>
      </div>
      <div class="col-md-6">
        <div class="pull-right">
          <a
              href="javascript:void(0)"
              chm-modal="{{ route('user.form') }}"
              chm-modal-options='{"form":{"attributes":{"id":"userForm","target-table":"[chm-table]"}}}'
              class="btn bg-maroon"
          ><i class="fa fa-user-plus"></i>&nbsp;{{ "Ajouter" }}</a>

          <a href="{{ url('users/import') }}" class="btn btn-success"><i class="fa fa-upload"></i> Importer</a>
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
