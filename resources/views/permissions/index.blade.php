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

            </div>
          </div>
          <div class="box-body">
            <form action="" method="post">
              {{ csrf_field() }}
              <table class="table table-hover table-striped">
                <thead>
                <tr>
                  <th>Permissions</th>
                  @php($roles = \App\Role::where('name', '<>', 'root')->get())
                  @foreach($roles as $role)
                    <th>{{ $role->name }}</th>
                  @endforeach
                </tr>
                </thead>
                <tbody>
                @foreach(\App\Permission::groupBy('section')->get() as $permissionSection)
                  <tr class="bg-aqua-gradient">
                    <td colspan="{{ $roles->count() + 1 }}">{{ $permissionSection->section }}</td>
                  </tr>
                  @foreach(\App\Permission::where('section', $permissionSection->section)->get() as $p)
                  <tr>
                    <td>{{ $p->display_name }}</td>
                    @foreach(\App\Role::where('name', '<>', 'root')->get() as $role)
                      <td>
                        <input type="checkbox" name="roles[{{ $role->id }}][]" value="{{ $p->id }}" {{ $role->name == 'ADMIN' ? 'disabled':'' }} {{ $role->hasPermission($p->name) ? 'checked':''  }}>
                      </td>
                    @endforeach
                  </tr>
                  @endforeach
                @endforeach
                </tbody>
              </table>

              <div class="actions">
                <button class="btn btn-success pull-right">Enregistrer les droits d'accès</button>
              </div>
            </form>
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
