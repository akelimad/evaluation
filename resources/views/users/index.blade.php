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
            <h3 class="box-title"><i class="glyphicon glyphicon-user"></i> Liste des utilisateurs <span class="badge">{{$results->total()}}</span></h3>
            <div class="box-tools mb40">
              <a onclick="return chmUser.form({})" class="btn bg-maroon"> <i class="fa fa-user-plus"></i> Ajouter </a>
              <a href="{{ url('users/import') }}" class="btn btn-success"><i class="fa fa-upload"></i> Importer</a>
            </div>
          </div>
          <div class="box-body mb20">
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
