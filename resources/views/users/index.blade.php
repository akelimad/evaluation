@extends('layouts.app')
@section('title', 'Utilisateurs')
@section('breadcrumb')
  <li>Utilisateurs</li>
@endsection
@section('content')
  <section class="content users">

    @include('users.search')

    <div class="row">
      <div class="col-md-12">
        <div class="box box-primary">
          <div class="box-header">
            <h3 class="box-title"><i class="fa fa-user"></i> Liste des utilisateurs <span class="badge badge-count">0</span></h3>
            <div class="box-tools mb40">
              <a
                  href="javascript:void(0)"
                  chm-modal="{{ route('user.form') }}"
                  chm-modal-options='{"form":{"attributes":{"id":"userForm","target-table":"[chm-table]"}}}'
                  class="btn bg-maroon"
              ><i class="fa fa-user-plus"></i>&nbsp;{{ "Ajouter" }}</a>

              <a href="{{ url('users/import') }}" class="btn btn-success"><i class="fa fa-upload"></i> Importer</a>
            </div>
          </div>
          <div class="box-body">
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
