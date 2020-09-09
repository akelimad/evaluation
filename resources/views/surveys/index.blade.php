@extends('layouts.app')
@section('title', 'Questionnaires')
@section('breadcrumb')
  <li>Questionnaires</li>
@endsection
@section('content')
  <section class="content users">
    <div class="row">
      <div class="col-md-12">
        <div class="box box-primary">
          <div class="box-header">
            <h3 class="box-title"><i class="fa fa-pencil"></i> Liste des questionnaires <span class="badge badge-count">0</span></h3>
            <div class="box-tools mb40">
              <a href="{{ route('survey.form') }}" class="btn bg-maroon" title="" data-toggle="tooltip"> <i class="fa fa-plus"></i> Ajouter </a>
            </div>
          </div>
          <div class="box-body">
            <div chm-table="{{ route('surveys.table') }}"
                 chm-table-options='{"with_ajax": true}'
                 chm-table-params='{{ json_encode(request()->query->all()) }}'
                 id="SurveysTableContainer"
            ></div>
          </div>
        </div>
      </div>
    </div>
  </section>
@endsection