@extends('layouts.app')
@section('title', 'Rôles')
@section('breadcrumb')
  <li>Permissions</li>
@endsection
@section('content')
  <section class="content">
    <div class="row">
      <div class="col-md-12">
        <div class="title-section mb-20">
          <h3 class="mt-0">
            <i class="fa fa-list"></i> {{ __("Permissions") }} <span class="badge badge-count">0</span>
          </h3>
        </div>
      </div>
      <div class="col-md-12">
        <div class="box p-10">
          <div class="box-body p-10">
            <form action="" method="post" chm-form>
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
