<table>
  <thead>
    <tr>
      <th>{{ __("Prénom") }}</th>
      <th>{{ __("Nom") }}</th>
      <th>{{ __("Email") }}</th>
      <th>{{ __("Téléphone") }}</th>
      <th>{{ __("Manager") }}</th>
      <th>{{ __("Rôles") }}</th>
      <th>{{ __("Date de recrutement") }}</th>
      <th>{{ __("Matricule") }}</th>
      <th>{{ __("Fonction") }}</th>
      <th>{{ __("Département") }}</th>
      <th>{{ __("Equipes") }}</th>
      <th>{{ __("Création du compte") }}</th>
    </tr>
  </thead>
  <tbody>
    @foreach($users as $user)
      @php($function = App\Fonction::find($user->function))
      @php($dept = App\Department::find($user->service))
      <tr>
        <td>{{ $user->name }}</td>
        <td>{{ $user->last_name }}</td>
        <td>{{ $user->email }}</td>
        <td>{{ $user->tel }}</td>
        <td>{{ $user->parent != null ? $user->parent->fullname() : '' }}</td>
        <td>
          @foreach($user->roles as $key => $role)
            {{ $role->name }} {{ $key+1 == $user->roles->count() ? '' : ',' }}
          @endforeach
        </td>
        <td>{{ !empty($user->date_recruiting) ? $user->date_recruiting : '' }}</td>
        <td>{{ $user->mle }}</td>
        <td>{{ $function ? $function->title : '' }}</td>
        <td>{{ $dept ? $dept->title : '' }}</td>
        <td>
          @foreach($user->teams as $key => $team)
            {{ $team->name }} {{ $key+1 == $user->teams->count() ? '' : ',' }}
          @endforeach
        </td>
        <td>{{ Carbon\Carbon::parse($user->created_at)->format('d/m/Y')}}</td>
      </tr>
    @endforeach
  </tbody>
</table>