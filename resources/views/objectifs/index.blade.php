@extends('layouts.app')
@section('breadcrumb')
  <li>Objectifs</li>
@endsection
@section('content')
  <section class="content users">
    <div class="row">
      <div class="col-md-12">
        <div class="box box-primary">
          <div class="box-header">
            <h3 class="box-title">Liste des objectifs <span class="badge badge-count">0</span></h3>
            <div class="box-tools mb40">
              <a href="{{ route('objectif.form') }}" class="btn bg-maroon"><i class="fa fa-plus"></i>&nbsp;{{ "Ajouter" }}</a>
            </div>
          </div>
          <div class="box-body">
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
  