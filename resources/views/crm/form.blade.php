
<div class="content">
    <input type="hidden" name="id" value="{{ isset($user->id) ? $user->id : null }}">
    {{ csrf_field() }}
    <div class="form-group">
        <div class="col-md-6">
            <label for="first_name" class="control-label">Prénom de la personne de contact<span class="asterisk">*</span></label>
            <input type="text" name="first_name" class="form-control" id="first_name" placeholder="" required="" value="{{ isset($user->first_name) ? $user->first_name : ''  }}" >
        </div>
        <div class="col-md-6">
            <label for="last_name" class="control-label">Nom de la personne de contact<span class="asterisk">*</span></label>
            <input type="text" name="last_name" class="form-control" id="last_name" placeholder=""  value="{{ isset($user->last_name) ? $user->last_name : '' }}" required>
        </div>
    </div>
    <div class="form-group">
        <div class="col-md-6">
            <label for="name" class="control-label">Nom de la société<span class="asterisk">*</span></label>
            <input type="text" name="name" class="form-control" id="name" placeholder=""  value="{{ isset($user->name) ? $user->name : '' }}" required>
        </div>
        <div class="col-md-6">
            <label for="email" class="control-label">Email<span class="asterisk">*</span></label>
            <input type="email" name="email" class="form-control" id="email" placeholder="info@contact.com" required="" value="{{ isset($user->email) ? $user->email : ''  }}">
        </div>
    </div>
    <div class="form-group">
        <div class="col-md-6"> 
            <label for="password" class="control-label">Mot de passe<span class="asterisk">*</span></label>
            <input id="password" type="password" class="form-control" name="password" {{ isset($user->id) ? '':'required' }}>
        </div>
        <div class="col-md-6"> 
            <label for="password" class="control-label">Confirmation du mot de passe<span class="asterisk">*</span></label>
            <input id="password-confirm" type="password" class="form-control" name="password_confirmation" {{ isset($user->id) ? '':'required' }}>
        </div>
    </div>
    <div class="form-group">
        <div class="col-md-6"> 
            <label for="photo" class="control-label">Logo<span class="asterisk">*</span></label>
            <div class="input-group">
                <label class="input-group-btn">
                    <span class="btn btn-primary">
                        Parcourir <input type="file" name="logo" style="display: none;" accept="image/*">
                    </span>
                </label>
                <input type="text" id="logo" class="form-control" readonly="">
            </div>
            @if(isset($user->id) && $user->logo != '' && App\User::logo($user->id) != '')
                <div class="logo" style="margin-top: 10px;">
                    <a href="{{ App\User::logo($user->id) }}" target="_blank" class="btn btn-info btn-xs btn-flat"><i class="fa fa-download"></i> Télécharger</a>
                    <bouton onclick="return Crm.removeLogo({id: {{ $user->id }} })" class="btn btn-danger btn-xs btn-flat"><i class="fa fa-trash"></i> Supprimer</bouton>
                </div>
            @endif
        </div>
        <div class="col-md-6">
            <label for="role" class="control-label">Rôle <span class="asterisk">*</span></label>
            <select name="roles[]" id="role" class="form-control" required>
                @foreach($roles as $role)
                    <option value="{{$role->id}}" {{isset($roles_ids) && in_array($role->id, $roles_ids) ? 'selected':''}}> {{$role->name}} </option>
                @endforeach
            </select>
        </div>
        <div class="clearfix"></div>
    </div>

</div>

<script>
    $(function(){
        // to show the choosen filename in input like: logo.png
        $(document).on('change', ':file', function() {
        var input = $(this),
            numFiles = input.get(0).files ? input.get(0).files.length : 1,
            label = input.val().replace(/\\/g, '/').replace(/.*\//, '');
            input.trigger('fileselect', [numFiles, label]);
        });
        $(document).ready( function() {
            $(':file').on('fileselect', function(event, numFiles, label) {
                var input = $(this).parents('.input-group').find(':text'),
                log = numFiles > 1 ? numFiles + ' files selected' : label;
                if( input.length ) {
                    input.val(log);
                }
            });
        });
    })
</script>