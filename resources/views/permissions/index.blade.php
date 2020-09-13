@extends('layouts.app')
@section('title', 'Rôles')
@section('breadcrumb')
  <li>Permissions</li>
@endsection
@section('content')
  <section class="content">
    <div class="row">
      <div class="col-md-12">
        <div class="box box-primary">
          <div class="box-header">
            <h3 class="box-title"><i class="fa fa-lock"></i> Liste des permissions <span class="badge badge-count">0</span></h3>

            <div class="box-tools mb40">
              <a
                  href="javascript:void(0)"
                  chm-modal="{{ route('permission.form') }}"
                  chm-modal-options='{"form":{"attributes":{"id":"permissionForm","target-table":"[chm-table]"}}}'
                  class="btn bg-maroon"
              ><i class="fa fa-user-secret"></i>&nbsp;{{ "Ajouter" }}</a>

            </div>
          </div>
          <div class="box-body">
            <table class="table table-hover table-striped">
              <thead>
                <tr>
                  <th>Permissions</th>
                  @foreach(\App\Role::where('name', '<>', 'root')->get() as $role)
                  <th>{{ $role->name }}</th>
                  @endforeach
                </tr>
              </thead>
              <tbody>
                @foreach(\App\Permission::all() as $p)
                <tr>
                  <td>{{ $p->name }}</td>
                  @foreach(\App\Role::where('name', '<>', 'root')->get() as $role)
                    <td>
                      <input type="checkbox" {{ $role->name == 'ADMIN' ? 'checked disabled':'' }}>
                    </td>
                  @endforeach
                </tr>
                @endforeach
              </tbody>
            </table>
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
