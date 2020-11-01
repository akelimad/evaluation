@extends('layouts.app')
@section('title', 'Questionnaires')
@section('breadcrumb')
  <li>{{ __("Paramétrages") }}</li>
  <li>{{ __("Questionnaires") }}</li>
@endsection
@section('content')
  <section class="content p-sm-10">
    <div class="row">
      <div class="col-md-12">
        <div class="title-section mb-20">
          <h3 class="mt-0">
            <i class="fa fa-pencil"></i> {{ __("Questionnaires") }} <span class="badge badge-count">0</span>
            <div class="pull-md-right pull-sm-right">
              <a href="{{ route('survey.form') }}" class="btn bg-maroon" title="" data-toggle="tooltip"> <i class="fa fa-plus"></i> Ajouter </a>
            </div>
          </h3>
        </div>
      </div>
      <div class="col-md-12">
        <div class="box p-0">
          <div class="box-body p-0">
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