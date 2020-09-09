@extends('layouts.app')
@section('title', 'Départements')
@section('breadcrumb')
  <li>Paramétrage</li>
  <li>Départements</li>
@endsection
@section('content')
  <section class="content setting">
    <div class="row">
      <div class="col-md-3">
        <div class="box box-primary">
          <div class="box-body">
            <ul class="list-group">
              @foreach(App\Setting::$models as $model)
                <li class="list-group-item {{ $model['active'] == $active ? 'active':'' }}"><a href="{{ url($model['route']) }}"><i class="{{ $model['icon'] }}"></i> {{ $model['label'] }}</a>
                </li>
              @endforeach
            </ul>
          </div>
        </div>
      </div>
      <div class="col-md-9">
        <div class="box box-primary">
          <div class="box-header">
            <h3 class="box-title">Liste des départements <span class="badge badge-count"></span></h3>
            <div class="box-tools mb40">
              <a
                  href="javascript:void(0)"
                  chm-modal="{{ route('department.form') }}"
                  chm-modal-options='{"form":{"attributes":{"id":"departmentForm","target-table":"[chm-table]"}}}'
                  class="btn bg-maroon"
              ><i class="fa fa-plus"></i>&nbsp;{{ "Ajouter" }}</a>
            </div>
          </div>
          <div class="box-body">
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
