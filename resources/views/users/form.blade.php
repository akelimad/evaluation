
<div class="content">
    <input type="hidden" name="id" value="{{ isset($user) ? $user->id : null }}">
    {{ csrf_field() }}
    <div class="form-group">
        <div class="col-md-6">
            <label for="name" class="control-label">Prénom <span class="asterisk">*</span></label>
            <input type="text" name="name" class="form-control" id="name" placeholder="Prénom" required="" value="{{ isset($user) ? $user->name : '' }}">
        </div>
        <div class="col-md-6">
            <label for="last_name" class="control-label">Nom <span class="asterisk">*</span></label>
            <input type="text" name="last_name" class="form-control" id="last_name" placeholder="Nom" required="" value="{{ isset($user) ? $user->last_name : ''  }}" >
        </div>
    </div>
    <div class="form-group">
        <div class="col-md-12">
            <label for="email" class="control-label">Email <span class="asterisk">*</span></label>
            <input type="email" name="email" class="form-control" id="email" placeholder="info@email.com" required="" value="{{ isset($user) ? $user->email : ''  }}">
        </div>
    </div>
    <div class="form-group">
        <div class="col-md-6"> 
            <label for="password" class="control-label">Mot de passe <span class="asterisk">*</span></label>
            <input id="password" type="password" class="form-control" name="password" {{ isset($user) ? '':'required' }}>
        </div>
        <div class="col-md-6"> 
            <label for="password" class="control-label">Confirmez-le <span class="asterisk">*</span></label>
            <input id="password-confirm" type="password" class="form-control" name="password_confirmation" {{ isset($user) ? '':'required' }}>
        </div>
    </div>
    <div class="form-group">
        <div class="col-md-6">
            <label for="adress" class="control-label">Adresse</label>
            <input type="text" name="address" class="form-control" id="adress" placeholder="Adresse" value="{{ isset($user) ? $user->address : '' }}">
        </div>
        <div class="col-md-6"> 
            <label for="society" class="control-label">Société</label>
            <input id="society" type="text" class="form-control" name="society" placeholder="Société" value="{{ isset($user) ? $user->society : ''  }}">
        </div>
    </div>
    <div class="form-group">
        <div class="col-md-6">
            <label for="zip_code" class="control-label">Code postale</label>
            <input type="number" name="zip_code" min="0" class="form-control" id="zip_code" placeholder="Code postale" value="{{ isset($user) ? $user->zip_code : ''  }}">
        </div>
        <div class="col-md-3"> 
            <label for="ville" class="control-label">Ville</label>
            <input id="ville" type="text" class="form-control" name="city" placeholder="Ville" value="{{ isset($user) ? $user->city : ''  }}">
        </div>
        <div class="col-md-3"> 
            <label for="pays" class="control-label">Pays</label>
            <input id="pays" type="text" class="form-control" name="country" placeholder="Pays" value="{{ isset($user) ? $user->country : ''  }}">
        </div>
    </div>
    <div class="form-group">
        <div class="col-md-6">
            <label for="tel" class="control-label">Téléphone mobile</label>
            <input type="text" name="tel" class="form-control" id="tel" placeholder="ex: 0606060606" value="{{ isset($user) ? $user->tel : ''  }}" pattern="^((06)|(07))\s?\d{2}\s?\d{2}\s?\d{2}\s?\d{2}$">
        </div>
        <div class="col-md-6"> 
            <label for="fix" class="control-label">Téléphone fix</label>
            <input id="fix" type="text" class="form-control" name="fix" placeholder="ex: 0505050505" value="{{ isset($user) ? $user->fix : ''  }}" pattern="^((05))\s?\d{2}\s?\d{2}\s?\d{2}\s?\d{2}$">
        </div>
    </div>
    <div class="form-group">
        <div class="col-md-6">
            <label for="about" class="control-label">A propos de moi </label>
            <textarea name="about" id="about" class="form-control">{{ isset($user) ? $user->about : '' }}</textarea>
        </div>
        <div class="col-md-6">
            <label for="qualification" class="control-label">Qualification </label>
            <textarea name="qualification" id="qualification" class="form-control">{{ isset($user) ? $user->qualification : '' }}</textarea>
        </div>
    </div>
    <div class="form-group">
        <div class="col-md-6">
            <label for="function" class="control-label">Fonction </label>
            <input id="function" type="text" class="form-control" name="function" placeholder="Fonction" value="{{ isset($user) ? $user->function : '' }}">
        </div>
        <div class="col-md-6"> 
            <label for="service" class="control-label">Service</label>
            <input id="service" type="text" class="form-control" name="service" placeholder="Service" value="{{ isset($user) ? $user->service : '' }}">
        </div>
    </div>
    <div class="form-group">
        <div class="col-md-6"> 
            <label for="photo" class="control-label">Photo </label>
            <div class="input-group">
                <label class="input-group-btn">
                    <span class="btn btn-primary">
                        Parcourir <input type="file" name="avatar" style="display: none;" accept="image/*">
                    </span>
                </label>
                <input type="text" id="avatar" class="form-control" readonly="">
            </div>
            @if(isset($user))
            <img src="{{ asset('avatars/'.$user->avatar) }}" alt="" width="100" height="100">
            @endif
        </div>
        <div class="col-md-6">
            <label for="salary" class="control-label">Salaire </label>
            <input id="salary" type="number" min="0" class="form-control" name="salary" placeholder="Salaire" value="{{ isset($user) ? $user->salary : '' }}">
        </div>
        <div class="clearfix"></div>
    </div>
    <div class="form-group">
        <div class="col-md-6">
            <label for="role" class="control-label">Rôle <span class="asterisk">*</span></label>
            <select name="roles[]" id="role" class="form-control" multiple="" required="" @role(['COLLABORATEUR', 'MENTOR']) disabled @endrole>
                @foreach($roles as $role)
                    <option value="{{$role->id}}" {{isset($roles_ids) && in_array($role->id, $roles_ids) ? 'selected':''}}> {{$role->name}} </option>
                @endforeach
            </select>
        </div>
        <div class="col-md-6">
            <label for="user_id" class="control-label">Mentor </label>
            <select name="user_id" id="user_id" class="form-control" @role(['COLLABORATEUR', 'MENTOR']) disabled @endrole>
                <option value="">=== Select ===</option>
                @foreach($users as $u)
                    <option value="{{$u->id}}" {{isset($user) && $u->id == $user->user_id ? 'selected':''}}> {{$u->name. " ".$u->last_name}} </option>
                @endforeach
            </select>
        </div>
        <div class="clearfix"></div>
    </div>
</div>

<script>
    $(function(){
        // to show the choosen filename in input like: avatar.png
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