@extends('layouts.app')
@section('title', 'Rôles')
@section('breadcrumb')
  <li>Rôles</li>
@endsection
@section('content')
  <section class="content">
    <div class="row">
      <div class="col-md-12">
        <div class="box box-primary">
          <div class="box-header">
            <h3 class="box-title"><i class="fa fa-user-secret"></i> Liste des rôles <span class="badge badge-count">0</span></h3>
            <div class="box-tools mb40">
              <a
                  href="javascript:void(0)"
                  chm-modal="{{ route('role.form') }}"
                  chm-modal-options='{"form":{"attributes":{"id":"roleForm","target-table":"[chm-table]"}}}'
                  class="btn bg-maroon"
              ><i class="fa fa-user-secret"></i>&nbsp;{{ "Ajouter" }}</a>

            </div>
          </div>
          <div class="box-body">
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
