
<div class="content">
    <input type="hidden" name="id" value="{{ isset($user) ? $user->id : null }}">
    {{ csrf_field() }}
    <div class="form-group">
        <div class="col-md-6">
            <label for="name" class="control-label">Prénom</label>
            <input type="text" name="name" class="form-control" id="name" placeholder="Prénom" required="" value="{{ isset($user) ? $user->name : '' }}">
        </div>
        <div class="col-md-6">
            <label for="last_name" class="control-label">Nom</label>
            <input type="text" name="last_name" class="form-control" id="last_name" placeholder="Nom" required="" value="{{ isset($user) ? $user->last_name : ''  }}" >
        </div>
    </div>
    <div class="form-group">
        <div class="col-md-12">
            <label for="email" class="control-label">Email</label>
            <input type="email" name="email" class="form-control" id="email" placeholder="info@email.com" required="" value="{{ isset($user) ? $user->email : ''  }}">
        </div>
    </div>
    <div class="form-group">
        <div class="col-md-6"> 
            <label for="password" class="control-label">Mot de passe</label>
            <input id="password" type="password" class="form-control" name="password" {{ isset($user) ? '':'required' }}>
        </div>
        <div class="col-md-6"> 
            <label for="password" class="control-label">Confirmez-le</label>
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
            <input type="text" name="zip_code" class="form-control" id="zip_code" placeholder="Code postale" value="{{ isset($user) ? $user->zip_code : ''  }}">
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
            <input type="text" name="tel" class="form-control" id="tel" placeholder="0606060606" value="{{ isset($user) ? $user->tel : ''  }}">
        </div>
        <div class="col-md-6"> 
            <label for="fix" class="control-label">Téléphone fix</label>
            <input id="fix" type="text" class="form-control" name="fix" placeholder="0505050505" value="{{ isset($user) ? $user->fix : ''  }}">
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
            <label for="avatar" class="control-label">Photo</label>
            <input type="file" name="avatar" id="avatar" class="form-control">
            @if(isset($user))
            <img src="{{ asset('avatars/'.$user->avatar) }}" alt="" width="100" height="100">
            @endif
        </div>
        <div class="col-md-6">
            <label for="salary" class="control-label">Salaire </label>
            <input id="salary" type="number" class="form-control" name="salary" placeholder="Salaire" value="{{ isset($user) ? $user->salary : '' }}">
        </div>
    </div>
    <div class="form-group">
        <div class="col-md-6">
            <label for="role" class="control-label">Rôle </label>
            <select name="roles[]" id="role" class="form-control" multiple="" required="">
                @foreach($roles as $role)
                    <option value="{{$role->id}}" {{isset($roles_ids) && in_array($role->id, $roles_ids) ? 'selected':''}}> {{$role->name}} </option>
                @endforeach
            </select>
        </div>
        <div class="col-md-6">
            <label for="user_id" class="control-label">Mentor </label>
            <select name="user_id" id="user_id" class="form-control" {{ isset($user) ? '':'required' }}>
                <option value="">=== Select ===</option>
                @foreach($users as $u)
                    <option value="{{$u->id}}" {{isset($user) && $u->id == $user->user_id ? 'selected':''}}> {{$u->email}} </option>
                @endforeach
            </select>
            <label for="tel" class="control-label">Statut </label>
            <label class="toggle-check" style="display: block;">
                <input type="checkbox" name="status" class="toggle-check-input" {{ isset($user) && $user->status ==1 ? 'checked':'' }}/>
                <span class="toggle-check-text"></span>
            </label>
        </div>
    </div>
</div>