@extends('layouts.app')
@section('title', 'Départements')
@section('breadcrumb')
  <li>{{ __("Paramétrages") }}</li>
  <li>{{ __("Champs éditables") }}</li>
@endsection
@section('content')
  <section class="content p-sm-10 setting">
    <div class="row">
      <div class="col-md-3">
        <div class="card">
          <ul class="list-group mb-0">
            @foreach(App\Setting::$models as $model)
              <li class="list-group-item {{ $model['active'] == $active ? 'active':'' }}"><a href="{{ url($model['route']) }}"><i class="{{ $model['icon'] }}"></i> {{ $model['label'] }}</a>
              </li>
            @endforeach
          </ul>
        </div>
      </div>
      <div class="col-md-9">
        <div class="title-section mb-20">
          <h3 class="mt-0">
            {{ __("Départements") }} <span class="badge badge-count">0</span>
            <div class="pull-md-right pull-sm-right">
              <a
                  href="javascript:void(0)"
                  chm-modal="{{ route('department.form') }}"
                  chm-modal-options='{"form":{"attributes":{"id":"departmentForm","target-table":"[chm-table]"}}}'
                  class="btn bg-maroon"
              ><i class="fa fa-plus"></i>&nbsp;{{ "Ajouter" }}</a>
            </div>
          </h3>
        </div>
        <div class="box p-0">
          <div class="box-body p-0">
            <div chm-table="{{ route('departments.table') }}"
                 chm-table-options='{"with_ajax": true}'
                 chm-table-params='{{ json_encode(request()->query->all()) }}'
                 id="DepartmentsTableContainer"
            ></div>
          </div>
        </div>
      </div>
    </div>
  </section>
@endsection
