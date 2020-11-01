<div class="content p-xxs-10">
  <input type="hidden" name="id" value="{{ isset($user) ? $user->id : null }}">
  {{ csrf_field() }}
  <div class="row">
    <div class="col-md-6">
      <label for="name" class="control-label required">Prénom</label>
      <input type="text" name="name" class="form-control" id="name" placeholder="Prénom" chm-validate="required|valid_name" value="{{ isset($user) ? $user->name : '' }}">
    </div>
    <div class="col-md-6">
      <label for="last_name" class="control-label required">Nom</label>
      <input type="text" name="last_name" class="form-control" id="last_name" placeholder="Nom" chm-validate="required|valid_name" value="{{ isset($user) ? $user->last_name : ''  }}">
    </div>
  </div>
  <div class="row">
    <div class="col-md-6">
      <label for="email" class="control-label required">Email</label>
      <input type="email" name="email" class="form-control" id="email" placeholder="info@email.com" chm-validate="required|valid_email" value="{{ isset($user) ? $user->email : ''  }}">
    </div>
    <div class="col-md-6">
      <label for="photo" class="control-label">Photo</label>
      <div class="input-group">
        <label class="input-group-btn">
          <span class="btn btn-primary">
            Parcourir <input type="file" name="avatar" style="display: none;" accept="image/*">
          </span>
        </label>
        <input type="text" id="avatar" class="form-control" readonly="">
      </div>
      @if($user->id != null && $user->avatar != null)
        <div class="logo" style="margin-top: 10px;">
          <a href="{{ App\User::avatar($user->id) }}" target="_blank" class="btn btn-info btn-xs btn-flat"><i
                class="fa fa-download"></i> Télécharger</a>
        </div>
      @endif
    </div>
  </div>
  <div class="row">
    <div class="col-md-6">
      <label for="password" class="control-label">Mot de passe</label>
      <input id="password" type="password" class="form-control" name="password" {{ isset($user) ? '':'required' }}>
    </div>
    <div class="col-md-6">
      <label for="password" class="control-label">Confirmation du mot de passe</label>
      <input id="password-confirm" type="password" class="form-control" name="password_confirmation" {{ isset($user) ? '':'required' }}>
    </div>
  </div>
  <div class="row">
    <div class="col-md-6">
      <label for="tel" class="control-label">Téléphone mobile</label>
      <input type="text" name="tel" class="form-control" id="tel" placeholder="ex: 0606060606" value="{{ isset($user) ? $user->tel : ''  }}" pattern="^((06)|(07))\s?\d{2}\s?\d{2}\s?\d{2}\s?\d{2}$">
    </div>
    <div class="col-md-6">
      <label for="password" class="control-label">Equipe</label>
      <select name="team_id" id="team_id" class="form-control">
        <option value=""></option>
        @foreach($teams as $team)
          <option value="{{ $team->id }}" {{ in_array($team->id, $userTeamsId) ? 'selected':'' }}>{{ $team->name }}</option>
        @endforeach
      </select>
    </div>
  </div>
  <div class="row">
    <div class="col-md-6">
      <label for="function" class="control-label">Fonction</label>
      <select name="function" id="function" class="form-control">
        <option value=""></option>
        @foreach($fonctions as $func)
          <option value="{{ $func->id }}" {{ (isset($user->function) && $user->function == $func->id) ? 'selected':'' }}>{{ $func->title }}</option>
        @endforeach
      </select>
    </div>
    <div class="col-md-6">
      <label for="service" class="control-label">Département</label>
      <select name="service" id="service" class="form-control">
        <option value=""></option>
        @foreach($departments as $dep)
          <option value="{{ $dep->id }}" {{ (isset($user->service) && $user->service == $dep->id) ? 'selected':'' }}>{{ $dep->title }}</option>
        @endforeach
      </select>
    </div>
  </div>
  <div class="row">
    <div class="col-md-6">
      <label for="password" class="control-label">Matricule</label>
      <input id="mle" type="text" class="form-control" name="mle" value="{{ isset($user) ? $user->mle : '' }}">
    </div>
    <div class="col-md-6">
      <label for="date_recruiting" class="control-label">Date de recrutement</label>
      <input id="date_recruiting" type="text" class="form-control datepicker" name="date_recruiting" value="{{ !empty($user->date_recruiting) ? $user->date_recruiting : '' }}" readonly>
    </div>
  </div>
  @role(['ROOT', 'ADMIN', 'RH'])
  @if(Auth::user()->hasRole('ADMIN') && Auth::user()->id != $user->id)
    <div class="row">
      <div class="col-md-6">
        <label for="role" class="control-label required">Rôle</label>
        <select name="roles[]" id="role" class="form-control select2" chm-validate="required"  multiple required @role(['COLLABORATEUR', 'MANAGER']) disabled @endrole>
          @foreach($roles as $role)
            <option value="{{$role->id}}" {{isset($roles_ids) && in_array($role->id, $roles_ids) ? 'selected':''}}> {{$role->name}} </option>
          @endforeach
        </select>
      </div>
      <div class="col-md-6">
        <label for="user_id" class="control-label">Manager</label>
        <select name="user_id" id="user_id" class="form-control select2" @role(['COLLABORATEUR', 'MANAGER']) disabled @endrole>
          <option value=""></option>
          @foreach($users as $u)
            <option value="{{$u->id}}" {{isset($user) && $u->id == $user->user_id ? 'selected':''}}> {{$u->name. " ".$u->last_name}} </option>
          @endforeach
        </select>
      </div>
      <div class="clearfix"></div>
    </div>
  @endif
  @endrole
</div>

<link rel="stylesheet" href="{{ asset('css/bootstrap-datepicker.min.css') }}">
<script src="{{asset('js/moment.min.js')}}"></script>
<script src="{{asset('js/bootstrap-datepicker.min.js')}}"></script>
<script src="{{asset('js/bootstrap-datepicker.fr.min.js')}}"></script>

<script>
  $(document).ready(function () {
    // Initialise select2
    $('.select2').select2()

    $('.datepicker').datepicker({
      endDate: new Date(),
      autoclose: true,
      format: 'dd/mm/yyyy',
      language: 'fr',
      todayHighlight: true,
    })

    // to show the choosen filename in input like: avatar.png
    $(document).on('change', ':file', function () {
      var input = $(this),
          numFiles = input.get(0).files ? input.get(0).files.length : 1,
          label = input.val().replace(/\\/g, '/').replace(/.*\//, '');
      input.trigger('fileselect', [numFiles, label]);
    });
    $(document).ready(function () {
      $(':file').on('fileselect', function (event, numFiles, label) {
        var input = $(this).parents('.input-group').find(':text'),
            log = numFiles > 1 ? numFiles + ' files selected' : label;
        if (input.length) {
          input.val(log);
        }
      });
    });
  })
</script>