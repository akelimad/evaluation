@extends('layouts.app')
@section('title', 'Rôles')
@section('breadcrumb')
  <li>Rôles</li>
@endsection
@section('content')
  <section class="content">
    <div class="row">
      <div class="col-md-12">
        <div class="title-section mb-20">
          <h3 class="mt-0">
            <i class="fa fa-user-secret"></i> {{ __("Roles") }} <span class="badge badge-count">0</span>
            <div class="pull-right">
              <a
                  href="javascript:void(0)"
                  chm-modal="{{ route('role.form') }}"
                  chm-modal-options='{"form":{"attributes":{"id":"roleForm","target-table":"[chm-table]"}}}'
                  class="btn bg-maroon"
              ><i class="fa fa-user-secret"></i>&nbsp;{{ "Ajouter" }}</a>
            </div>
          </h3>
        </div>
      </div>
      <div class="col-md-12">
        <div class="box p-0">
          <div class="box-body p-0">
            <div chm-table="{{ route('roles.table') }}"
                 chm-table-options='{"with_ajax": true}'
                 chm-table-params='{{ json_encode(request()->query->all()) }}'
                 id="RolesTableContainer"
            ></div>
          </div>
        </div>
      </div>
    </div>
  </section>
@endsection

@section('javascript')
  @parent
  <script>
    $(document).ready(function () {
      window.chmTable.refresh('#RolesTableContainer')
    })
  </script>
@endsection
